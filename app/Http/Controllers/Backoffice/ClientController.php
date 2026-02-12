<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backoffice\Client\ClientStoreRequest;
use App\Http\Requests\Backoffice\Client\ClientUpdateRequest;
use App\Models\Client;
use App\Models\Agency;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    /**
     * Display a listing of the clients.
     */
    public function index()
    {
        $query = Client::with(['agency']);

        // 🔎 SEARCH
        if (request()->filled('search')) {
            $search = request('search');

            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"])
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('cin_number', 'like', "%{$search}%")
                  ->orWhere('passport_number', 'like', "%{$search}%")
                  ->orWhere('driving_license_number', 'like', "%{$search}%")
                  ->orWhereHas('agency', function ($sub) use ($search) {
                      $sub->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // 🏢 FILTER BY AGENCY
        if (request()->filled('agency_id')) {
            $query->where('agency_id', request('agency_id'));
        }

        // 📌 FILTER BY STATUS
        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        // 🔤 SORT
        if (request('sort') === 'az') {
            $query->orderBy('first_name', 'asc');
        } elseif (request('sort') === 'za') {
            $query->orderBy('first_name', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $clients = $query->paginate(15)->withQueryString();
        $agencies = Agency::all();

        return view('backoffice.clients.index', compact('clients', 'agencies'));
    }

    /**
     * Show the form for creating a new client.
     */
    public function create()
    {
        $agencies = Agency::all();

        return view('backoffice.clients.create', compact('agencies'));
    }

    /**
     * Store a newly created client in storage.
     */
    public function store(ClientStoreRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            // Extract avatar file from validated data
            $avatar = $validated['avatar'] ?? null;
            unset($validated['avatar']);

            // Set default values
            $validated['status'] = $validated['status'] ?? 'active';
            $validated['rating_average'] = null;
            $validated['rating_count'] = 0;

            // Create client with non-file fields
            $client = Client::create($validated);

            // Attach avatar to media collection if provided
            if ($avatar) {
                $client->addMediaFromRequest('avatar')
                    ->toMediaCollection('client_avatar');
            }

            DB::commit();

            return redirect()
                ->route('backoffice.clients.index')
                ->with('success', 'Client créé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erreur lors de la création: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified client.
     */
    public function show(Client $client)
    {
        $client->load(['agency']);

        return view('backoffice.clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit(Client $client)
    {
        $client->load(['agency']);
        $agencies = Agency::all();

        return view('backoffice.clients.edit', compact('client', 'agencies'));
    }

    /**
     * Update the specified client in storage.
     */
    public function update(ClientUpdateRequest $request, Client $client)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            // Extract avatar file from validated data
            $avatar = $validated['avatar'] ?? null;
            unset($validated['avatar']);
            unset($validated['remove_avatar']);

            // Update non-file fields
            $client->update($validated);

            // Handle avatar removal
            if ($request->boolean('remove_avatar')) {
                $client->clearMediaCollection('client_avatar');
            }

            // Handle avatar update
            if ($avatar) {
                $client->clearMediaCollection('client_avatar');
                $client->addMediaFromRequest('avatar')
                    ->toMediaCollection('client_avatar');
            }

            DB::commit();

            return redirect()
                ->route('backoffice.clients.index')
                ->with('success', 'Client mis à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified client from storage.
     */
    public function destroy(Client $client)
    {
        try {
            DB::beginTransaction();

            // Media Library will automatically delete associated media on model deletion
            $client->delete();

            DB::commit();

            return redirect()
                ->route('backoffice.clients.index')
                ->with('success', 'Client supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Agency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    /**
     * Display a listing of the clients.
     */
    public function index()
    {
        $clients = Client::with(['agency'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

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
    public function store(Request $request)
    {
        $validated = $request->validate([
            'agency_id' => 'required|exists:agencies,id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'nullable|email|max:150|unique:clients,email',
            'phone' => 'required|string|max:50',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'nationality' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'cin_number' => 'nullable|string|max:50',
            'cin_valid_until' => 'nullable|date',
            'passport_number' => 'nullable|string|max:50',
            'passport_issue_date' => 'nullable|date',
            'driving_license_number' => 'nullable|string|max:50',
            'driving_license_issue_date' => 'nullable|date',
            'status' => 'nullable|in:active,inactive,blacklisted',
            'notes' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // Handle avatar upload
            if ($request->hasFile('avatar')) {
                $path = $request->file('avatar')->store('clients/avatars', 'public');
                $validated['avatar'] = $path;
            }

            // Set default values
            $validated['status'] = $validated['status'] ?? 'active';
            $validated['rating_average'] = null;
            $validated['rating_count'] = 0;

            $client = Client::create($validated);

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
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'agency_id' => 'required|exists:agencies,id',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'nullable|email|max:150|unique:clients,email,' . $client->id,
            'phone' => 'required|string|max:50',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'nationality' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'cin_number' => 'nullable|string|max:50',
            'cin_valid_until' => 'nullable|date',
            'passport_number' => 'nullable|string|max:50',
            'passport_issue_date' => 'nullable|date',
            'driving_license_number' => 'nullable|string|max:50',
            'driving_license_issue_date' => 'nullable|date',
            'status' => 'nullable|in:active,inactive,blacklisted',
            'notes' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_avatar' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            // Handle avatar removal
            if ($request->boolean('remove_avatar') && $client->avatar) {
                Storage::disk('public')->delete($client->avatar);
                $validated['avatar'] = null;
            }

            // Handle new avatar upload
            if ($request->hasFile('avatar')) {
                if ($client->avatar) {
                    Storage::disk('public')->delete($client->avatar);
                }
                $path = $request->file('avatar')->store('clients/avatars', 'public');
                $validated['avatar'] = $path;
            }

            $client->update($validated);

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

            // Delete avatar file
            if ($client->avatar) {
                Storage::disk('public')->delete($client->avatar);
            }

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
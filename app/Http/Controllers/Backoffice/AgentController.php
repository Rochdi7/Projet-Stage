<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Agency;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AgentController extends Controller
{
    /**
     * Display a listing of the agents.
     */
    public function index()
    {
        $agents = Agent::with(['agency', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $agencies = Agency::all();
        $users = User::all();

        return view('backoffice.agents.index', compact('agents', 'agencies', 'users'));
    }

    /**
     * Show the form for creating a new agent.
     */
    public function create()
    {
        $agencies = Agency::all();
        $users = User::all();

        return view('backoffice.agents.create', compact('agencies', 'users'));
    }

    /**
     * Store a newly created agent in storage.
     */
public function store(Request $request)
{
    $validated = $request->validate([
        'agency_id' => 'required|exists:agencies,id',
        'user_id' => 'nullable|exists:users,id',
        'full_name' => 'required|string|max:150',
        'email' => 'nullable|email|max:150|unique:agents,email',
        'phone' => 'nullable|string|max:50',
        'notes' => 'nullable|string',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    try {
        DB::beginTransaction();

        // ✅ Handle avatar upload - SIMPLE STORAGE
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('agents/avatars', 'public');
            $validated['avatar'] = $path;
        }

        $agent = Agent::create($validated);

        DB::commit();

        return redirect()
            ->route('backoffice.agents.index')
            ->with('success', 'Agent créé avec succès.');

    } catch (\Exception $e) {
        DB::rollBack();
        
        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Erreur lors de la création: ' . $e->getMessage());
    }
}


    /**
     * Display the specified agent.
     */
    public function show(Agent $agent)
    {
        $agent->load(['agency', 'user']);

        return view('backoffice.agents.show', compact('agent'));
    }

    /**
     * Show the form for editing the specified agent.
     */
    public function edit(Agent $agent)
    {
        $agent->load(['agency', 'user']);
        $agencies = Agency::all();
        $users = User::all();

        return view('backoffice.agents.edit', compact('agent', 'agencies', 'users'));
    }

    /**
     * Update the specified agent in storage.
     */
public function update(Request $request, Agent $agent)
{
    $validated = $request->validate([
        'agency_id' => 'required|exists:agencies,id',
        'user_id' => 'nullable|exists:users,id',
        'full_name' => 'required|string|max:150',
        'email' => 'nullable|email|max:150|unique:agents,email,' . $agent->id,
        'phone' => 'nullable|string|max:50',
        'notes' => 'nullable|string',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'remove_avatar' => 'nullable|boolean',
    ]);

    try {
        DB::beginTransaction();

        // ✅ Handle avatar removal
        if ($request->boolean('remove_avatar') && $agent->avatar) {
            Storage::disk('public')->delete($agent->avatar);
            $validated['avatar'] = null;
        }

        // ✅ Handle new avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($agent->avatar) {
                Storage::disk('public')->delete($agent->avatar);
            }
            // Upload new avatar
            $path = $request->file('avatar')->store('agents/avatars', 'public');
            $validated['avatar'] = $path;
        }

        $agent->update($validated);

        DB::commit();

        return redirect()
            ->route('backoffice.agents.index')
            ->with('success', 'Agent mis à jour avec succès.');

    } catch (\Exception $e) {
        DB::rollBack();
        
        return redirect()
            ->back()
            ->withInput()
            ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
    }
}

    /**
     * Remove the specified agent from storage.
     */
    public function destroy(Agent $agent)
    {
        try {
            DB::beginTransaction();

            // Delete avatar file
            if ($agent->avatar) {
                Storage::disk('public')->delete($agent->avatar);
            }

            $agent->delete();

            DB::commit();

            return redirect()
                ->route('backoffice.agents.index')
                ->with('success', 'Agent supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
        }
    }
}
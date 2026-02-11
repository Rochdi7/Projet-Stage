<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backoffice\Agent\AgentStoreRequest;
use App\Http\Requests\Backoffice\Agent\AgentUpdateRequest;
use App\Models\Agent;
use App\Models\Agency;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
    public function store(AgentStoreRequest $request)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            // Extract avatar file from validated data
            $avatar = $validated['avatar'] ?? null;
            unset($validated['avatar']);

            // Create agent with non-file fields
            $agent = Agent::create($validated);

            // Attach avatar to media collection if provided
            if ($avatar) {
                $agent->addMediaFromRequest('avatar')
                    ->toMediaCollection('agent_avatar');
            }

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
    public function update(AgentUpdateRequest $request, Agent $agent)
    {
        $validated = $request->validated();

        try {
            DB::beginTransaction();

            // Extract avatar file from validated data
            $avatar = $validated['avatar'] ?? null;
            unset($validated['avatar']);
            unset($validated['remove_avatar']);

            // Update non-file fields
            $agent->update($validated);

            // Handle avatar removal
            if ($request->boolean('remove_avatar')) {
                $agent->clearMediaCollection('agent_avatar');
            }

            // Handle avatar update
            if ($avatar) {
                $agent->clearMediaCollection('agent_avatar');
                $agent->addMediaFromRequest('avatar')
                    ->toMediaCollection('agent_avatar');
            }

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

            // Media Library will automatically delete associated media on model deletion
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

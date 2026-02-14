<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backoffice\Agency\AgencyStoreRequest;
use App\Http\Requests\Backoffice\Agency\AgencyUpdateRequest;
use App\Models\Agency;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AgencyController extends Controller
{
    use AuthorizesRequests;

    public function index(): View
    {
        $this->authorize('viewAny', Agency::class);

        $query = Agency::query();

        // SEARCH
        if (request()->filled('search')) {
            $query->where('name', 'like', '%' . request('search') . '%');
        }

        // FAKE STATUS LOGIC (NO DATABASE COLUMN)
        if (request()->has('status')) {

            if (request('status') == 0) {
                // Inactive → force empty result
                $query->whereRaw('1 = 0');
            }

            // If status = 1 → do nothing (show all as active)
        }

        // SORT
        if (request('sort') === 'az') {
            $query->orderBy('name', 'asc');
        } elseif (request('sort') === 'za') {
            $query->orderBy('name', 'desc');
        } else {
            $query->latest();
        }

        $agencies = $query->paginate(15)->withQueryString();

        return view('backoffice.agencies.index', compact('agencies'));
    }


    public function create(): View
    {
        $this->authorize('create', Agency::class);

        return view('backoffice.agencies.create');
    }

    public function store(AgencyStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Agency::class);

        $agency = Agency::create($request->validated());

        return redirect()
            ->route('backoffice.agencies.index')
            ->with('toast', [
                'title'   => 'Created',
                'message' => "L’agence « {$agency->name} » a été créée avec succès.",
                'dot'     => '#198754', // green
                'delay'   => 3500,
                'time'    => 'now',
            ]);
    }

    public function show(Agency $agency): View
    {
        $this->authorize('view', $agency);

        return view('backoffice.agencies.show', compact('agency'));
    }

    public function edit(Agency $agency): View
    {
        $this->authorize('update', $agency);

        return view('backoffice.agencies.edit', compact('agency'));
    }

    public function update(AgencyUpdateRequest $request, Agency $agency): RedirectResponse
    {
        $this->authorize('update', $agency);

        $agency->update($request->validated());

        return redirect()
            ->route('backoffice.agencies.index')
            ->with('toast', [
                'title'   => 'Updated',
                'message' => "L’agence « {$agency->name} » a été modifiée avec succès.",
                'dot'     => '#0d6efd', // blue
                'delay'   => 3500,
                'time'    => 'now',
            ]);
    }

    public function destroy(Agency $agency): RedirectResponse
    {
        $this->authorize('delete', $agency);

        $name = $agency->name;
        $agency->delete();

        return redirect()
            ->route('backoffice.agencies.index')
            ->with('toast', [
                'title'   => 'Deleted',
                'message' => "L'agence « {$name} » a été supprimée avec succès.",
                'dot'     => '#dc3545', // red
                'delay'   => 3500,
                'time'    => 'now',
            ]);
    }

    public function profile(Agency $agency): View
    {
        $this->authorize('view', $agency);

        return view('Backoffice.profile.profile-setting', compact('agency'));
    }
}

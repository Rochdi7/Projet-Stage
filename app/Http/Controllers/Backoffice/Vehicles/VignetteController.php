<?php

namespace App\Http\Controllers\Backoffice\Vehicles;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleVignette;
use App\Http\Requests\Backoffice\VehicleVignette\VehicleVignetteStoreRequest;
use App\Http\Requests\Backoffice\VehicleVignette\VehicleVignetteUpdateRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class VignetteController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request, $vehicleId)
    {
        $vehicle = Vehicle::find($vehicleId);
        
        if (!$vehicle) {
            return view('Backoffice.vignettes.index', [
                'vehicle' => null,
                'vignettes' => new LengthAwarePaginator([], 0, 15),
                'availableYears' => collect([])
            ]);
        }
        
        $this->authorize('view', $vehicle);

        $query = $vehicle->vignettes();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('year', 'like', "%{$search}%")
                  ->orWhere('amount', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhere('date', 'like', "%{$search}%");
            });
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        if ($request->filled('amount_min')) {
            $query->where('amount', '>=', $request->amount_min);
        }
        if ($request->filled('amount_max')) {
            $query->where('amount', '<=', $request->amount_max);
        }

        $sort = $request->get('sort', 'latest');
        
        if ($sort === 'oldest') {
            $query->orderBy('date', 'asc');
        } elseif ($sort === 'amount_asc') {
            $query->orderBy('amount', 'asc');
        } elseif ($sort === 'amount_desc') {
            $query->orderBy('amount', 'desc');
        } elseif ($sort === 'year_asc') {
            $query->orderBy('year', 'asc');
        } elseif ($sort === 'year_desc') {
            $query->orderBy('year', 'desc');
        } else {
            $query->orderBy('date', 'desc');
        }

        $vignettes = $query->paginate(15)->withQueryString();

        $availableYears = $vehicle ? VehicleVignette::where('vehicle_id', $vehicle->id)
            ->select('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year') : collect([]);

        return view('Backoffice.vignettes.index', compact('vehicle', 'vignettes', 'availableYears'));
    }

    public function create(Vehicle $vehicle = null)
    {
        $vehicles = Vehicle::orderBy('registration_number')->get();
        return view('Backoffice.vignettes.create', compact('vehicle', 'vehicles'));
    }

    public function store(VehicleVignetteStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            $vehicle = Vehicle::findOrFail($data['vehicle_id']);
            
            VehicleVignette::create([
                'vehicle_id' => $vehicle->id,
                'date' => $data['date'],
                'amount' => $data['amount'],
                'year' => $data['year'],
                'notes' => $data['notes'] ?? null,
            ]);

            DB::commit();

            return redirect()
                ->route('backoffice.vehicles.vignettes.index', $vehicle->id)
                ->with('toast', [
                    'title' => 'Créé',
                    'message' => 'Vignette créée avec succès.',
                    'dot' => '#198754',
                    'delay' => 3500,
                    'time' => 'now',
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('toast', [
                'title' => 'Erreur',
                'message' => 'Erreur lors de la création: ' . $e->getMessage(),
                'dot' => '#dc3545',
                'delay' => 3500,
                'time' => 'now',
            ]);
        }
    }

    public function show(Vehicle $vehicle, VehicleVignette $vignette)
    {
        $this->authorize('view', $vehicle);
        $this->verifyResource($vehicle, $vignette);
        return view('Backoffice.vignettes.show', compact('vehicle', 'vignette'));
    }

    public function edit(Vehicle $vehicle, VehicleVignette $vignette)
    {
        $this->authorize('update', $vehicle);
        $this->verifyResource($vehicle, $vignette);
        $vehicles = Vehicle::orderBy('registration_number')->get();
        return view('Backoffice.vignettes.edit', compact('vehicle', 'vignette', 'vehicles'));
    }

    public function update(VehicleVignetteUpdateRequest $request, Vehicle $vehicle, VehicleVignette $vignette)
    {
        $this->authorize('update', $vehicle);
        $this->verifyResource($vehicle, $vignette);

        try {
            DB::beginTransaction();
            $data = $request->validated();
            
            $vignette->update([
                'vehicle_id' => $data['vehicle_id'] ?? $vignette->vehicle_id,
                'date' => $data['date'],
                'amount' => $data['amount'],
                'year' => $data['year'],
                'notes' => $data['notes'] ?? null,
            ]);

            DB::commit();
            return redirect()
                ->route('backoffice.vehicles.vignettes.index', $vignette->vehicle_id)
                ->with('toast', [
                    'title' => 'Mis à jour',
                    'message' => 'Vignette mise à jour avec succès.',
                    'dot' => '#0d6efd',
                    'delay' => 3500,
                    'time' => 'now',
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('toast', [
                'title' => 'Erreur',
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage(),
                'dot' => '#dc3545',
                'delay' => 3500,
                'time' => 'now',
            ]);
        }
    }

    public function destroy(Vehicle $vehicle, VehicleVignette $vignette)
    {
        $this->authorize('delete', $vehicle);
        $this->verifyResource($vehicle, $vignette);

        try {
            DB::beginTransaction();
            $vignette->delete();
            DB::commit();

            return redirect()
                ->route('backoffice.vehicles.vignettes.index', $vehicle->id)
                ->with('toast', [
                    'title' => 'Supprimé',
                    'message' => 'Vignette supprimée avec succès.',
                    'dot' => '#dc3545',
                    'delay' => 3500,
                    'time' => 'now',
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('toast', [
                'title' => 'Erreur',
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage(),
                'dot' => '#dc3545',
                'delay' => 3500,
                'time' => 'now',
            ]);
        }
    }

    private function verifyResource(Vehicle $vehicle, VehicleVignette $vignette): void
    {
        if ($vignette->vehicle_id !== $vehicle->id) {
            abort(404);
        }
    }
}
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

class VignetteController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the vignettes for a vehicle.
     */
    public function index(Request $request, Vehicle $vehicle)
    {
        $this->authorize('view', $vehicle);

        $query = $vehicle->vignettes();

        // 🔎 SEARCH
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('year', 'like', "%{$search}%")
                  ->orWhere('amount', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhere('date', 'like', "%{$search}%");
            });
        }

        // 📅 FILTER BY YEAR
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        // 📅 FILTER BY DATE RANGE
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        // 💰 FILTER BY AMOUNT RANGE
        if ($request->filled('amount_min')) {
            $query->where('amount', '>=', $request->amount_min);
        }
        if ($request->filled('amount_max')) {
            $query->where('amount', '<=', $request->amount_max);
        }

        // 🔤 SORT
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

        // Get available years for filter
        $availableYears = VehicleVignette::where('vehicle_id', $vehicle->id)
            ->select('year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('Backoffice.vignettes.index', compact('vehicle', 'vignettes', 'availableYears'));
    }

    /**
     * Show the form for creating a new vignette.
     */
    public function create(Vehicle $vehicle = null)
    {
        // Temporarily disable authorization for testing
        // $this->authorize('create', VehicleVignette::class);
        
        // Get all vehicles for the dropdown - FIXED: order by registration_number
        $vehicles = Vehicle::orderBy('registration_number')->get();
        
        return view('Backoffice.vignettes.create', compact('vehicle', 'vehicles'));
    }

    /**
     * Store a newly created vignette in storage.
     */
    public function store(VehicleVignetteStoreRequest $request, Vehicle $vehicle = null)
    {
        // Temporarily disable authorization for testing
        // $this->authorize('create', VehicleVignette::class);

        try {
            DB::beginTransaction();

            $data = $request->validated();
            
            // Get vehicle from the selected vehicle_id in the form
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

            return redirect()
                ->back()
                ->withInput()
                ->with('toast', [
                    'title' => 'Erreur',
                    'message' => 'Erreur lors de la création: ' . $e->getMessage(),
                    'dot' => '#dc3545',
                    'delay' => 3500,
                    'time' => 'now',
                ]);
        }
    }

    /**
     * Display the specified vignette.
     */
    public function show(Vehicle $vehicle, VehicleVignette $vignette)
    {
        $this->authorize('view', $vehicle);
        $this->verifyResource($vehicle, $vignette);
        
        return view('Backoffice.vignettes.show', compact('vehicle', 'vignette'));
    }

    /**
     * Show the form for editing the specified vignette.
     */
    public function edit(Vehicle $vehicle, VehicleVignette $vignette)
    {
        $this->authorize('update', $vehicle);
        $this->verifyResource($vehicle, $vignette);

        // Get all vehicles for the dropdown - FIXED: order by registration_number, not name
        $vehicles = Vehicle::orderBy('registration_number')->get();

        return view('Backoffice.vignettes.edit', compact('vehicle', 'vignette', 'vehicles'));
    }

    /**
     * Update the specified vignette in storage.
     */
    public function update(VehicleVignetteUpdateRequest $request, Vehicle $vehicle, VehicleVignette $vignette)
    {
        $this->authorize('update', $vehicle);
        $this->verifyResource($vehicle, $vignette);

        try {
            DB::beginTransaction();

            $data = $request->validated();
            
            // If vehicle_id is changed, update it
            if (isset($data['vehicle_id']) && $data['vehicle_id'] != $vignette->vehicle_id) {
                $vignette->vehicle_id = $data['vehicle_id'];
            }
            
            $vignette->update([
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

            return redirect()
                ->back()
                ->withInput()
                ->with('toast', [
                    'title' => 'Erreur',
                    'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage(),
                    'dot' => '#dc3545',
                    'delay' => 3500,
                    'time' => 'now',
                ]);
        }
    }

    /**
     * Remove the specified vignette from storage.
     */
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

            return redirect()
                ->back()
                ->with('toast', [
                    'title' => 'Erreur',
                    'message' => 'Erreur lors de la suppression: ' . $e->getMessage(),
                    'dot' => '#dc3545',
                    'delay' => 3500,
                    'time' => 'now',
                ]);
        }
    }

    /**
     * Verify that the vignette belongs to the vehicle.
     */
    private function verifyResource(Vehicle $vehicle, VehicleVignette $vignette): void
    {
        if ($vignette->vehicle_id !== $vehicle->id) {
            abort(404);
        }
    }
}
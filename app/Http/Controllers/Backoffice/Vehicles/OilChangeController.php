<?php

namespace App\Http\Controllers\Backoffice\Vehicles;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleOilChange;
use App\Http\Requests\Backoffice\VehicleOilChange\VehicleOilChangeStoreRequest;
use App\Http\Requests\Backoffice\VehicleOilChange\VehicleOilChangeUpdateRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class OilChangeController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request, $vehicleId)
    {
        $vehicle = Vehicle::find($vehicleId);
        
        if (!$vehicle) {
            return view('Backoffice.oil-changes.index', [
                'vehicle' => null,
                'oilChanges' => new LengthAwarePaginator([], 0, 15),
                'availableMechanics' => collect([])
            ]);
        }
        
        $this->authorize('view', $vehicle);

        $query = $vehicle->oilChanges();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('mechanic_name', 'like', "%{$search}%")
                  ->orWhere('mileage', 'like', "%{$search}%")
                  ->orWhere('next_mileage', 'like', "%{$search}%")
                  ->orWhere('amount', 'like', "%{$search}%")
                  ->orWhere('observations', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        if ($request->filled('mechanic')) {
            $query->where('mechanic_name', 'like', "%{$request->mechanic}%");
        }

        if ($request->filled('mileage_min')) {
            $query->where('mileage', '>=', $request->mileage_min);
        }
        if ($request->filled('mileage_max')) {
            $query->where('mileage', '<=', $request->mileage_max);
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
        } elseif ($sort === 'mileage_asc') {
            $query->orderBy('mileage', 'asc');
        } elseif ($sort === 'mileage_desc') {
            $query->orderBy('mileage', 'desc');
        } elseif ($sort === 'amount_asc') {
            $query->orderBy('amount', 'asc');
        } elseif ($sort === 'amount_desc') {
            $query->orderBy('amount', 'desc');
        } else {
            $query->orderBy('date', 'desc');
        }

        $oilChanges = $query->paginate(15)->withQueryString();

        $availableMechanics = $vehicle ? VehicleOilChange::where('vehicle_id', $vehicle->id)
            ->whereNotNull('mechanic_name')
            ->select('mechanic_name')
            ->distinct()
            ->orderBy('mechanic_name')
            ->pluck('mechanic_name') : collect([]);

        return view('Backoffice.oil-changes.index', compact('vehicle', 'oilChanges', 'availableMechanics'));
    }

    public function create(Vehicle $vehicle = null)
    {
        $vehicles = Vehicle::orderBy('registration_number')->get();
        return view('Backoffice.oil-changes.create', compact('vehicle', 'vehicles'));
    }

    public function store(VehicleOilChangeStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $vehicle = Vehicle::findOrFail($data['vehicle_id']);
            
            VehicleOilChange::create([
                'vehicle_id' => $vehicle->id,
                'date' => $data['date'],
                'amount' => $data['amount'],
                'mileage' => $data['mileage'],
                'next_mileage' => $data['next_mileage'],
                'mechanic_name' => $data['mechanic_name'] ?? null,
                'observations' => $data['observations'] ?? null,
            ]);

            DB::commit();
            return redirect()->route('backoffice.vehicles.oil-changes.index', $vehicle->id)
                ->with('toast', ['title' => 'Créé', 'message' => 'Vidange créée avec succès.', 'dot' => '#198754', 'delay' => 3500, 'time' => 'now']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('toast', [
                'title' => 'Erreur', 'message' => 'Erreur lors de la création: ' . $e->getMessage(),
                'dot' => '#dc3545', 'delay' => 3500, 'time' => 'now'
            ]);
        }
    }

    public function show(Vehicle $vehicle, VehicleOilChange $oilChange)
    {
        $this->authorize('view', $vehicle);
        $this->verifyResource($vehicle, $oilChange);
        return view('Backoffice.oil-changes.show', compact('vehicle', 'oilChange'));
    }

    public function edit(Vehicle $vehicle, VehicleOilChange $oilChange)
    {
        $this->authorize('update', $vehicle);
        $this->verifyResource($vehicle, $oilChange);
        $vehicles = Vehicle::orderBy('registration_number')->get();
        return view('Backoffice.oil-changes.edit', compact('vehicle', 'oilChange', 'vehicles'));
    }

    public function update(VehicleOilChangeUpdateRequest $request, Vehicle $vehicle, VehicleOilChange $oilChange)
    {
        $this->authorize('update', $vehicle);
        $this->verifyResource($vehicle, $oilChange);

        try {
            DB::beginTransaction();
            $data = $request->validated();
            
            $oilChange->update([
                'vehicle_id' => $data['vehicle_id'],
                'date' => $data['date'],
                'amount' => $data['amount'],
                'mileage' => $data['mileage'],
                'next_mileage' => $data['next_mileage'],
                'mechanic_name' => $data['mechanic_name'] ?? null,
                'observations' => $data['observations'] ?? null,
            ]);

            DB::commit();
            return redirect()->route('backoffice.vehicles.oil-changes.index', $oilChange->vehicle_id)
                ->with('toast', ['title' => 'Mis à jour', 'message' => 'Vidange mise à jour avec succès.', 'dot' => '#0d6efd', 'delay' => 3500, 'time' => 'now']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('toast', [
                'title' => 'Erreur', 'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage(),
                'dot' => '#dc3545', 'delay' => 3500, 'time' => 'now'
            ]);
        }
    }

    public function destroy(Vehicle $vehicle, VehicleOilChange $oilChange)
    {
        $this->authorize('delete', $vehicle);
        $this->verifyResource($vehicle, $oilChange);

        try {
            DB::beginTransaction();
            $oilChange->delete();
            DB::commit();
            return redirect()->route('backoffice.vehicles.oil-changes.index', $vehicle->id)
                ->with('toast', ['title' => 'Supprimé', 'message' => 'Vidange supprimée avec succès.', 'dot' => '#dc3545', 'delay' => 3500, 'time' => 'now']);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('toast', [
                'title' => 'Erreur', 'message' => 'Erreur lors de la suppression: ' . $e->getMessage(),
                'dot' => '#dc3545', 'delay' => 3500, 'time' => 'now'
            ]);
        }
    }

    private function verifyResource(Vehicle $vehicle, VehicleOilChange $oilChange): void
    {
        if ($oilChange->vehicle_id !== $vehicle->id) abort(404);
    }
}
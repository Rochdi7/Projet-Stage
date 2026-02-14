<?php

namespace App\Http\Controllers\Backoffice\Vehicles;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleControl;
use App\Models\Agency;
use App\Models\User;
use App\Http\Requests\Backoffice\VehicleControl\VehicleControlStoreRequest;
use App\Http\Requests\Backoffice\VehicleControl\VehicleControlUpdateRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class ControlController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the controls.
     */
    public function index(Request $request, $vehicleId)
    {
        // ============ GLOBAL VIEW - ALL VEHICLES ============
        if ($vehicleId === 'all') {
            $query = VehicleControl::with(['vehicle', 'agency', 'performer']);
            
            // 🔎 SEARCH
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('control_number', 'like', "%{$search}%")
                      ->orWhere('start_mileage', 'like', "%{$search}%")
                      ->orWhere('end_mileage', 'like', "%{$search}%")
                      ->orWhere('notes', 'like', "%{$search}%")
                      ->orWhereHas('vehicle', function ($sub) use ($search) {
                          $sub->where('registration_number', 'like', "%{$search}%");
                      })
                      ->orWhereHas('agency', function ($sub) use ($search) {
                          $sub->where('name', 'like', "%{$search}%");
                      });
                });
            }

            // 🔤 FILTER BY STATUS
            if ($request->filled('status')) {
                if ($request->status === 'completed') {
                    $query->whereNotNull('end_mileage');
                } elseif ($request->status === 'in_progress') {
                    $query->whereNull('end_mileage');
                }
            }

            // 🔤 SORT
            $sort = $request->get('sort', 'latest');
            if ($sort === 'oldest') {
                $query->orderBy('created_at', 'asc');
            } elseif ($sort === 'mileage_asc') {
                $query->orderBy('start_mileage', 'asc');
            } elseif ($sort === 'mileage_desc') {
                $query->orderBy('start_mileage', 'desc');
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $controls = $query->paginate(15)->withQueryString();

            return view('Backoffice.controls.index', [
                'vehicle' => null,
                'controls' => $controls,
                'isGlobalView' => true
            ]);
        }
        
        // ============ SINGLE VEHICLE VIEW ============
        $vehicle = Vehicle::findOrFail($vehicleId);
        $this->authorize('view', $vehicle);

        $query = $vehicle->controls()->with(['agency', 'performer']);

        // 🔎 SEARCH
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('control_number', 'like', "%{$search}%")
                  ->orWhere('start_mileage', 'like', "%{$search}%")
                  ->orWhere('end_mileage', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        // 🔤 FILTER BY STATUS
        if ($request->filled('status')) {
            if ($request->status === 'completed') {
                $query->whereNotNull('end_mileage');
            } elseif ($request->status === 'in_progress') {
                $query->whereNull('end_mileage');
            }
        }

        // 🔤 SORT
        $sort = $request->get('sort', 'latest');
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sort === 'mileage_asc') {
            $query->orderBy('start_mileage', 'asc');
        } elseif ($sort === 'mileage_desc') {
            $query->orderBy('start_mileage', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $controls = $query->paginate(15)->withQueryString();

        return view('Backoffice.controls.index', compact('vehicle', 'controls'));
    }

    /**
     * Global index for all controls.
     */
    public function globalIndex(Request $request)
    {
        return $this->index($request, 'all');
    }

    /**
     * Show the form for creating a new control.
     */
    public function create(Request $request, Vehicle $vehicle = null)
    {
        $agencies = Agency::orderBy('name')->get();
        $vehicles = Vehicle::orderBy('registration_number')->get();
        $users = User::orderBy('name')->get(); // REMOVED role filter
        
        return view('Backoffice.controls.create', compact('vehicle', 'agencies', 'vehicles', 'users'));
    }

    /**
     * Store a newly created control.
     */
    public function store(VehicleControlStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            
            $data = $request->validated();
            
            VehicleControl::create($data);

            DB::commit();
            
            $vehicleId = $data['vehicle_id'];
            
            return redirect()->route('backoffice.vehicles.controls.index', $vehicleId)
                ->with('toast', [
                    'title' => 'Créé', 
                    'message' => 'Contrôle créé avec succès.', 
                    'dot' => '#198754', 
                    'delay' => 3500, 
                    'time' => 'now'
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('toast', [
                'title' => 'Erreur', 
                'message' => 'Erreur lors de la création: ' . $e->getMessage(),
                'dot' => '#dc3545', 
                'delay' => 3500, 
                'time' => 'now'
            ]);
        }
    }

    /**
     * Display the specified control.
     */
    public function show($vehicleId, VehicleControl $control)
    {
        $vehicle = Vehicle::findOrFail($vehicleId);
        
        if ($control->vehicle_id !== $vehicle->id) {
            abort(404);
        }
        
        $this->authorize('view', $vehicle);
        
        $control->load(['agency', 'performer', 'items']);
        
        return view('Backoffice.controls.show', compact('vehicle', 'control'));
    }

    /**
     * Show the form for editing the specified control.
     */
    public function edit($vehicleId, VehicleControl $control)
    {
        $vehicle = Vehicle::findOrFail($vehicleId);
        
        if ($control->vehicle_id !== $vehicle->id) {
            abort(404);
        }
        
        $this->authorize('update', $vehicle);
        
        $agencies = Agency::orderBy('name')->get();
        $vehicles = Vehicle::orderBy('registration_number')->get();
        $users = User::orderBy('name')->get(); // REMOVED role filter
        
        return view('Backoffice.controls.edit', compact('vehicle', 'control', 'agencies', 'vehicles', 'users'));
    }

    /**
     * Update the specified control.
     */
    public function update(VehicleControlUpdateRequest $request, $vehicleId, VehicleControl $control)
    {
        $vehicle = Vehicle::findOrFail($vehicleId);
        
        if ($control->vehicle_id !== $vehicle->id) {
            abort(404);
        }
        
        $this->authorize('update', $vehicle);

        try {
            DB::beginTransaction();
            
            $control->update($request->validated());

            DB::commit();
            
            return redirect()->route('backoffice.vehicles.controls.index', $vehicle->id)
                ->with('toast', [
                    'title' => 'Mis à jour', 
                    'message' => 'Contrôle mis à jour avec succès.', 
                    'dot' => '#0d6efd', 
                    'delay' => 3500, 
                    'time' => 'now'
                ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('toast', [
                'title' => 'Erreur', 
                'message' => 'Erreur lors de la mise à jour: ' . $e->getMessage(),
                'dot' => '#dc3545', 
                'delay' => 3500, 
                'time' => 'now'
            ]);
        }
    }

    /**
     * Remove the specified control.
     */
    public function destroy(Request $request, $vehicleId, VehicleControl $control)
    {
        $vehicle = Vehicle::findOrFail($vehicleId);
        
        if ($control->vehicle_id !== $vehicle->id) {
            abort(404);
        }
        
        try {
            DB::beginTransaction();
            
            // Check if control has items
            if ($control->items()->count() > 0) {
                return redirect()->back()->with('toast', [
                    'title' => 'Erreur', 
                    'message' => 'Impossible de supprimer ce contrôle car il contient des éléments.', 
                    'dot' => '#dc3545'
                ]);
            }
            
            $control->delete();
            DB::commit();
            
            $referer = $request->header('referer');
            $isGlobalView = str_contains($referer, '/vehicles/all/controls');
            
            if ($isGlobalView) {
                return redirect()
                    ->route('backoffice.vehicle-documents.controls.index')
                    ->with('toast', [
                        'title' => 'Supprimé', 
                        'message' => 'Contrôle supprimé avec succès.', 
                        'dot' => '#dc3545'
                    ]);
            } else {
                return redirect()
                    ->route('backoffice.vehicles.controls.index', ['vehicle' => $vehicle->id])
                    ->with('toast', [
                        'title' => 'Supprimé', 
                        'message' => 'Contrôle supprimé avec succès.', 
                        'dot' => '#dc3545'
                    ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('toast', [
                'title' => 'Erreur', 
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage(), 
                'dot' => '#dc3545'
            ]);
        }
    }
}
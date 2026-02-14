<?php

namespace App\Http\Controllers\Backoffice\Vehicles;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleControl;
use App\Models\VehicleControlItem;
use App\Http\Requests\Backoffice\VehicleControlItem\VehicleControlItemStoreRequest;
use App\Http\Requests\Backoffice\VehicleControlItem\VehicleControlItemUpdateRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class ControlItemController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the control items.
     */
    public function index(Request $request, $vehicleId, $controlId = null)
    {
        // ============ GLOBAL VIEW - ALL CONTROL ITEMS ============
        if ($controlId === 'all' || $request->is('*/vehicle-documents/control-items*')) {
            $query = VehicleControlItem::with('vehicleControl.vehicle');
            
            // 🔎 SEARCH
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('item_key', 'like', "%{$search}%")
                      ->orWhere('label', 'like', "%{$search}%")
                      ->orWhere('comment', 'like', "%{$search}%")
                      ->orWhereHas('vehicleControl', function ($sub) use ($search) {
                          $sub->where('control_number', 'like', "%{$search}%")
                              ->orWhereHas('vehicle', function ($v) use ($search) {
                                  $v->where('registration_number', 'like', "%{$search}%");
                              });
                      });
                });
            }

            // 🔤 FILTER BY STATUS
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // 🔤 SORT
            $sort = $request->get('sort', 'key_asc');
            if ($sort === 'key_desc') {
                $query->orderBy('item_key', 'desc');
            } elseif ($sort === 'status') {
                $query->orderBy('status')->orderBy('item_key');
            } else {
                $query->orderBy('item_key', 'asc');
            }

            $items = $query->paginate(15)->withQueryString();

            return view('Backoffice.control-items.index', [
                'vehicle' => null,
                'control' => null,
                'items' => $items,
                'isGlobalView' => true
            ]);
        }
        
        // ============ SINGLE CONTROL VIEW ============
        $vehicle = Vehicle::findOrFail($vehicleId);
        $control = VehicleControl::where('vehicle_id', $vehicle->id)->findOrFail($controlId);
        
        $this->authorize('view', $vehicle);

        $query = $control->items();

        // 🔎 SEARCH
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('item_key', 'like', "%{$search}%")
                  ->orWhere('label', 'like', "%{$search}%")
                  ->orWhere('comment', 'like', "%{$search}%");
            });
        }

        // 🔤 FILTER BY STATUS
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 🔤 SORT
        $sort = $request->get('sort', 'key_asc');
        if ($sort === 'key_desc') {
            $query->orderBy('item_key', 'desc');
        } elseif ($sort === 'status') {
            $query->orderBy('status')->orderBy('item_key');
        } else {
            $query->orderBy('item_key', 'asc');
        }

        $items = $query->paginate(15)->withQueryString();

        return view('Backoffice.control-items.index', compact('vehicle', 'control', 'items'));
    }

    /**
     * Global index for all control items.
     */
    public function globalIndex(Request $request)
    {
        return $this->index($request, 1, 'all');
    }

    /**
     * Show the form for creating a new control item.
     */
    public function create(Vehicle $vehicle, VehicleControl $control)
    {
        $this->authorize('update', $vehicle);
        return view('Backoffice.control-items.create', compact('vehicle', 'control'));
    }

    /**
     * Store a newly created control item.
     */
    public function store(VehicleControlItemStoreRequest $request, Vehicle $vehicle, VehicleControl $control)
    {
        $this->authorize('update', $vehicle);

        try {
            DB::beginTransaction();
            
            $data = $request->validated();
            $data['vehicle_control_id'] = $control->id;
            
            VehicleControlItem::create($data);

            DB::commit();
            return redirect()->route('backoffice.vehicles.controls.items.index', [$vehicle->id, $control->id])
                ->with('toast', [
                    'title' => 'Créé', 
                    'message' => 'Élément de contrôle créé avec succès.', 
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
     * Display the specified control item.
     */
    public function show(Vehicle $vehicle, VehicleControl $control, VehicleControlItem $item)
    {
        $this->authorize('view', $vehicle);
        $this->verifyResource($vehicle, $control, $item);
        return view('Backoffice.control-items.show', compact('vehicle', 'control', 'item'));
    }

    /**
     * Show the form for editing the specified control item.
     */
    public function edit(Vehicle $vehicle, VehicleControl $control, VehicleControlItem $item)
    {
        $this->authorize('update', $vehicle);
        $this->verifyResource($vehicle, $control, $item);
        return view('Backoffice.control-items.edit', compact('vehicle', 'control', 'item'));
    }

    /**
     * Update the specified control item.
     */
    public function update(VehicleControlItemUpdateRequest $request, Vehicle $vehicle, VehicleControl $control, VehicleControlItem $item)
    {
        $this->authorize('update', $vehicle);
        $this->verifyResource($vehicle, $control, $item);

        try {
            DB::beginTransaction();
            
            $item->update($request->validated());

            DB::commit();
            return redirect()->route('backoffice.vehicles.controls.items.index', [$vehicle->id, $control->id])
                ->with('toast', [
                    'title' => 'Mis à jour', 
                    'message' => 'Élément de contrôle mis à jour avec succès.', 
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
     * Remove the specified control item.
     */
    public function destroy(Request $request, $vehicleId, $controlId, VehicleControlItem $item)
    {
        // Verify the item belongs to the correct control and vehicle
        if ($item->vehicleControl->vehicle_id != $vehicleId || $item->vehicle_control_id != $controlId) {
            abort(404);
        }
        
        try {
            DB::beginTransaction();
            $item->delete();
            DB::commit();
            
            // Check if this was from global view by looking at referer
            $referer = $request->header('referer');
            $isGlobalView = $referer && str_contains($referer, '/vehicle-documents/control-items');
            
            if ($isGlobalView) {
                return redirect()
                    ->route('backoffice.vehicle-documents.control-items.index')
                    ->with('toast', [
                        'title' => 'Supprimé', 
                        'message' => 'Élément de contrôle supprimé avec succès.', 
                        'dot' => '#dc3545'
                    ]);
            } else {
                return redirect()
                    ->route('backoffice.vehicles.controls.items.index', ['vehicle' => $vehicleId, 'control' => $controlId])
                    ->with('toast', [
                        'title' => 'Supprimé', 
                        'message' => 'Élément de contrôle supprimé avec succès.', 
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

    /**
     * Verify that the item belongs to the control and vehicle.
     */
    private function verifyResource(Vehicle $vehicle, VehicleControl $control, VehicleControlItem $item): void
    {
        if ($control->vehicle_id !== $vehicle->id || $item->vehicle_control_id !== $control->id) {
            abort(404);
        }
    }
}
<div class="table-responsive">
    <table class="table datatable align-middle">
        <thead class="thead-light">
            <tr>
                <th width="50" class="text-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="select-all">
                    </div>
                </th>
                
                @if(isset($isGlobalView) && $isGlobalView)
                    <th>Véhicule</th>
                    <th>Contrôle</th>
                @endif
                
                <th>Clé</th>
                <th>Libellé</th>
                <th>Statut</th>
                <th>Commentaire</th>
                <th width="80">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
            <tr>
                <td class="text-center">
                    <div class="form-check">
                        <input class="form-check-input item-checkbox" type="checkbox" value="{{ $item->id }}">
                    </div>
                </td>
                
                @if(isset($isGlobalView) && $isGlobalView)
                    <td>
                        @if($item->vehicleControl && $item->vehicleControl->vehicle)
                            <a href="{{ route('backoffice.vehicles.show', $item->vehicleControl->vehicle->id) }}" class="fw-medium">
                                {{ $item->vehicleControl->vehicle->registration_number }}
                            </a>
                            @if($item->vehicleControl->vehicle->registration_city)
                                <br><small class="text-muted">{{ $item->vehicleControl->vehicle->registration_city }}</small>
                            @endif
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @if($item->vehicleControl)
                            <a href="{{ route('backoffice.vehicles.controls.show', [$item->vehicleControl->vehicle_id, $item->vehicle_control_id]) }}">
                                {{ $item->vehicleControl->control_number }}
                            </a>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                @endif
                
                <td><code>{{ $item->item_key }}</code></td>
                <td>{{ $item->label ?? $item->item_key }}</td>
                <td>
                    <span class="{{ $item->status_badge_class }}">
                        {{ $item->status_text }}
                    </span>
                </td>
                <td>{{ $item->comment ?? '—' }}</td>
                <td class="text-center">
                    @include('Backoffice.control-items.partials._actions', ['item' => $item])
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ (isset($isGlobalView) && $isGlobalView) ? '8' : '6' }}" class="text-center py-5">
                    <div class="text-center">
                        <i class="ti ti-clipboard-list fs-48 text-gray-4 mb-3"></i>
                        <h5 class="mb-2">Aucun élément trouvé</h5>
                        <p class="text-muted mb-3">Aucun élément de contrôle n'a été trouvé</p>
                        @if(!isset($isGlobalView) || !$isGlobalView)
                            @if(isset($control) && $control)
                                <a href="{{ route('backoffice.vehicles.controls.items.create', [$vehicle->id, $control->id]) }}" class="btn btn-primary">
                                    <i class="ti ti-plus me-2"></i>
                                    Ajouter un élément
                                </a>
                            @endif
                        @endif
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('select-all');
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                document.querySelectorAll('.item-checkbox').forEach(cb => {
                    cb.checked = selectAll.checked;
                });
            });
        }
    });
</script>
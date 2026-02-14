<div class="dropdown">
    <button class="btn btn-icon btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
        <i class="ti ti-dots-vertical"></i>
    </button>

    <ul class="dropdown-menu dropdown-menu-end p-2">
        @if($item->vehicleControl)
            @php
                $vehicleId = $item->vehicleControl->vehicle_id;
                $controlId = $item->vehicle_control_id;
            @endphp
            <li>
                <a class="dropdown-item rounded-1" href="{{ route('backoffice.vehicles.controls.items.show', [$vehicleId, $controlId, $item->id]) }}">
                    <i class="ti ti-eye me-2"></i>Voir détails
                </a>
            </li>
            <li>
                <a class="dropdown-item rounded-1" href="{{ route('backoffice.vehicles.controls.items.edit', [$vehicleId, $controlId, $item->id]) }}">
                    <i class="ti ti-edit me-2"></i>Modifier
                </a>
            </li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li>
                <a class="dropdown-item rounded-1 text-danger" 
                   href="javascript:void(0);"
                   data-bs-toggle="modal" 
                   data-bs-target="#delete_control_item"
                   data-delete-action="{{ route('backoffice.vehicles.controls.items.destroy', [$vehicleId, $controlId, $item->id]) }}"
                   data-delete-details="Élément <strong>{{ $item->item_key }}</strong> - {{ $item->label ?? '' }}">
                    <i class="ti ti-trash me-2"></i>Supprimer
                </a>
            </li>
        @else
            <li>
                <span class="dropdown-item text-muted disabled">
                    <i class="ti ti-eye me-2"></i>Détails non disponibles
                </span>
            </li>
            <li>
                <span class="dropdown-item text-muted disabled">
                    <i class="ti ti-edit me-2"></i>Modifier non disponible
                </span>
            </li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li>
                <span class="dropdown-item text-muted disabled">
                    <i class="ti ti-trash me-2"></i>Supprimer non disponible
                </span>
            </li>
        @endif
    </ul>
</div>
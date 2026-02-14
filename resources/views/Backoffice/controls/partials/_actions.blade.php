<div class="dropdown">
    <button class="btn btn-icon btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-haspopup="true">
        <i class="ti ti-dots-vertical"></i>
    </button>

    <ul class="dropdown-menu dropdown-menu-end p-2">
        <li>
            <a class="dropdown-item rounded-1" href="{{ route('backoffice.vehicles.controls.show', [$control->vehicle_id, $control->id]) }}">
                <i class="ti ti-eye me-2"></i>Voir détails
            </a>
        </li>
        <li>
            <a class="dropdown-item rounded-1" href="{{ route('backoffice.vehicles.controls.edit', [$control->vehicle_id, $control->id]) }}">
                <i class="ti ti-edit me-2"></i>Modifier
            </a>
        </li>
        <li>
            <a class="dropdown-item rounded-1" href="{{ route('backoffice.vehicles.controls.items.index', [$control->vehicle_id, $control->id]) }}">
                <i class="ti ti-checklist me-2"></i>Éléments
                <span class="badge bg-secondary ms-2">{{ $control->items_count }}</span>
            </a>
        </li>
        <li>
            <hr class="dropdown-divider">
        </li>
        <li>
            <a class="dropdown-item rounded-1 text-danger" 
               href="javascript:void(0);"
               data-bs-toggle="modal" 
               data-bs-target="#delete_control"
               data-delete-action="{{ route('backoffice.vehicles.controls.destroy', [$control->vehicle_id, $control->id]) }}"
               data-delete-details="Contrôle <strong>{{ $control->control_number }}</strong>">
                <i class="ti ti-trash me-2"></i>Supprimer
            </a>
        </li>
    </ul>
</div>
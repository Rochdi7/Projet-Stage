<div class="dropdown d-inline-block">
    <button class="btn btn-icon btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="ti ti-dots-vertical"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end p-2">
        <li>
            <a class="dropdown-item rounded-1" href="{{ route('backoffice.vehicles.technical-checks.show', [$technicalCheck->vehicle_id, $technicalCheck->id]) }}">
                <i class="ti ti-eye me-2"></i>
                Voir détails
            </a>
        </li>
        <li>
            <a class="dropdown-item rounded-1" href="{{ route('backoffice.vehicles.technical-checks.edit', [$technicalCheck->vehicle_id, $technicalCheck->id]) }}">
                <i class="ti ti-edit me-2"></i>
                Modifier
            </a>
        </li>
        <li>
            <hr class="dropdown-divider">
        </li>
        <li>
            <a class="dropdown-item rounded-1 text-danger" 
               href="#"
               onclick="event.preventDefault(); if(confirm('Êtes-vous sûr de vouloir supprimer ce contrôle technique ?')) { document.getElementById('delete-technical-check-form-{{ $technicalCheck->id }}').submit(); }">
                <i class="ti ti-trash me-2"></i>
                Supprimer
            </a>
            <form id="delete-technical-check-form-{{ $technicalCheck->id }}" 
                  action="{{ route('backoffice.vehicles.technical-checks.destroy', [$technicalCheck->vehicle_id, $technicalCheck->id]) }}" 
                  method="POST" 
                  style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </li>
    </ul>
</div>
<div class="dropdown d-inline-block">
    <button class="btn btn-icon btn-sm" type="button" data-bs-toggle="dropdown">
        <i class="ti ti-dots-vertical"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end p-2">
        <li><a class="dropdown-item rounded-1" href="{{ route('backoffice.vehicles.oil-changes.show', [$oilChange->vehicle_id, $oilChange->id]) }}"><i class="ti ti-eye me-2"></i>Voir détails</a></li>
        <li><a class="dropdown-item rounded-1" href="{{ route('backoffice.vehicles.oil-changes.edit', [$oilChange->vehicle_id, $oilChange->id]) }}"><i class="ti ti-edit me-2"></i>Modifier</a></li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <a class="dropdown-item rounded-1 text-danger" href="#"
               onclick="event.preventDefault(); if(confirm('Êtes-vous sûr de vouloir supprimer cette vidange ?')) { document.getElementById('delete-oil-form-{{ $oilChange->id }}').submit(); }">
                <i class="ti ti-trash me-2"></i>Supprimer
            </a>
            <form id="delete-oil-form-{{ $oilChange->id }}" action="{{ route('backoffice.vehicles.oil-changes.destroy', [$oilChange->vehicle_id, $oilChange->id]) }}" method="POST" style="display: none;">
                @csrf @method('DELETE')
            </form>
        </li>
    </ul>
</div>
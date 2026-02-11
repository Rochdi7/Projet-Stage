<div class="dropdown">
    <button class="btn btn-icon btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="ti ti-dots-vertical"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end p-2">
        <li>
            <a class="dropdown-item rounded-1" href="{{ route('backoffice.clients.show', $client) }}">
                <i class="ti ti-eye me-2"></i>
                Voir détails
            </a>
        </li>
        <li>
            <a class="dropdown-item rounded-1" href="{{ route('backoffice.clients.edit', $client) }}">
                <i class="ti ti-edit me-2"></i>
                Modifier
            </a>
        </li>
        <li>
            <hr class="dropdown-divider">
        </li>
        <li>
            {{-- ✅ DELETE BUTTON WITH ALL DATA ATTRIBUTES --}}
            <a class="dropdown-item rounded-1 text-danger" 
               href="#"
               data-bs-toggle="modal" 
               data-bs-target="#delete_client"
               data-delete-action="{{ route('backoffice.clients.destroy', $client->id) }}"
               data-client-name="{{ $client->full_name ?? $client->first_name . ' ' . $client->last_name }}">
                <i class="ti ti-trash me-2"></i>
                Supprimer
            </a>
        </li>
    </ul>
</div>
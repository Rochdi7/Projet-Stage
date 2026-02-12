<div class="dropdown">
    <button class="btn btn-icon btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="ti ti-dots-vertical"></i>
    </button>
    <ul class="dropdown-menu dropdown-menu-end p-2">
        <li>
            <a class="dropdown-item rounded-1" href="{{ route('backoffice.vehicles.vignettes.show', [$vehicle->id, $vignette->id]) }}">
                <i class="ti ti-eye me-2"></i>
                Voir détails
            </a>
        </li>
        <li>
            <a class="dropdown-item rounded-1" href="{{ route('backoffice.vehicles.vignettes.edit', [$vehicle->id, $vignette->id]) }}">
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
               onclick="event.preventDefault(); event.stopPropagation(); 
                        document.getElementById('deleteVignetteForm').action = '{{ route('backoffice.vehicles.vignettes.destroy', [$vehicle->id, $vignette->id]) }}'; 
                        document.getElementById('deleteVignetteDetails').innerText = 'Vignette {{ $vignette->year }} - {{ number_format($vignette->amount, 2, ',', ' ') }} €'; 
                        new bootstrap.Modal(document.getElementById('delete_vignette')).show(); 
                        return false;">
                <i class="ti ti-trash me-2"></i>
                Supprimer
            </a>
        </li>
    </ul>
</div>
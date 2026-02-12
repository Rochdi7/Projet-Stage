<div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
    <div class="my-auto mb-2">
        <h4 class="mb-1">Vignettes</h4>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('backoffice.dashboard') }}">Accueil</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('backoffice.vehicles.index') }}">Véhicules</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('backoffice.vehicles.show', $vehicle) }}">
                        {{ $vehicle->license_plate ?? 'Véhicule' }}
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('backoffice.vehicles.vignettes.index', $vehicle) }}">Vignettes</a>
                </li>
                @if(request()->routeIs('backoffice.vehicles.vignettes.show'))
                    <li class="breadcrumb-item active" aria-current="page">Détails</li>
                @elseif(request()->routeIs('backoffice.vehicles.vignettes.edit'))
                    <li class="breadcrumb-item active" aria-current="page">Modification</li>
                @elseif(request()->routeIs('backoffice.vehicles.vignettes.create'))
                    <li class="breadcrumb-item active" aria-current="page">Ajout</li>
                @else
                    <li class="breadcrumb-item active" aria-current="page">Liste</li>
                @endif
            </ol>
        </nav>
    </div>
    
    @if(request()->routeIs('backoffice.vehicles.vignettes.show'))
        <div class="mt-2 mt-md-0">
            <a href="{{ route('backoffice.vehicles.vignettes.index', $vehicle) }}" class="btn btn-sm btn-white">
                <i class="ti ti-arrow-left me-1"></i>Retour à la liste
            </a>
        </div>
    @endif
</div>
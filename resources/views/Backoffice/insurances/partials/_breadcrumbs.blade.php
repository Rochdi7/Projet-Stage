<div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
    <div class="my-auto mb-2">
        <h4 class="mb-1">Assurances</h4>
        <nav>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('backoffice.dashboard') }}">Accueil</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('backoffice.vehicles.index') }}">Véhicules</a>
                </li>
                @if(isset($vehicle) && $vehicle)
                    <li class="breadcrumb-item">
                        <a href="{{ route('backoffice.vehicles.show', $vehicle->id) }}">
                            {{ $vehicle->registration_number ?? 'Véhicule' }}
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('backoffice.vehicles.insurances.index', $vehicle->id) }}">Assurances</a>
                    </li>
                @else
                    <li class="breadcrumb-item active">Assurances</li>
                @endif
                
                @if(request()->routeIs('backoffice.vehicles.insurances.show'))
                    <li class="breadcrumb-item active">Détails</li>
                @elseif(request()->routeIs('backoffice.vehicles.insurances.edit'))
                    <li class="breadcrumb-item active">Modification</li>
                @elseif(request()->routeIs('backoffice.vehicles.insurances.create'))
                    <li class="breadcrumb-item active">Ajout</li>
                @endif
            </ol>
        </nav>
    </div>
</div>
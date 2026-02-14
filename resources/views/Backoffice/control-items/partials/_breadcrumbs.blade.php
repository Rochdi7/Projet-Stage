@if(isset($isGlobalView) && $isGlobalView)
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h4 class="mb-1">Tous les éléments de contrôle</h4>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('backoffice.dashboard') }}">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('backoffice.vehicles.index') }}">Véhicules</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('backoffice.vehicle-documents.controls.index') }}">Contrôles</a></li>
                    <li class="breadcrumb-item active">Éléments</li>
                </ol>
            </nav>
        </div>
    </div>
@else
    <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
        <div class="my-auto mb-2">
            <h4 class="mb-1">Éléments de contrôle</h4>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('backoffice.dashboard') }}">Accueil</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('backoffice.vehicles.index') }}">Véhicules</a></li>
                    @if(isset($vehicle) && $vehicle)
                        <li class="breadcrumb-item"><a href="{{ route('backoffice.vehicles.show', $vehicle->id) }}">{{ $vehicle->registration_number }}</a></li>
                    @endif
                    @if(isset($control) && $control)
                        <li class="breadcrumb-item"><a href="{{ route('backoffice.vehicles.controls.show', [$vehicle->id, $control->id]) }}">{{ $control->control_number }}</a></li>
                    @endif
                    <li class="breadcrumb-item active">Éléments</li>
                </ol>
            </nav>
        </div>
        @if(isset($control) && $control)
        <div class="mt-2 mt-md-0">
            <a href="{{ route('backoffice.vehicles.controls.items.create', [$vehicle->id, $control->id]) }}" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i>Ajouter un élément
            </a>
        </div>
        @endif
    </div>
@endif
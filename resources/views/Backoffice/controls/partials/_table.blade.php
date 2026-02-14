<style>
    .table-responsive { 
        overflow: visible !important; 
    }
    .badge-success { 
        background: #d4edda; 
        color: #155724; 
        padding: 0.35rem 0.75rem; 
        border-radius: 50px; 
        font-weight: 500; 
    }
    .badge-warning { 
        background: #fff3cd; 
        color: #856404; 
        padding: 0.35rem 0.75rem; 
        border-radius: 50px; 
        font-weight: 500; 
    }
    .btn-icon { 
        width: 32px; 
        height: 32px; 
        padding: 0; 
        display: inline-flex; 
        align-items: center; 
        justify-content: center; 
        border-radius: 8px; 
        color: #6c757d; 
        background: transparent; 
        border: 1px solid transparent;
        transition: all 0.2s;
    }
    .btn-icon:hover { 
        background: #f8f9fa; 
        border-color: #dee2e6; 
        color: #0d6efd; 
    }
    .btn-icon i { 
        font-size: 18px; 
    }
    th:last-child, td:last-child { 
        width: 80px; 
        text-align: center !important; 
        vertical-align: middle !important;
    }
    .form-check {
        display: flex;
        justify-content: center;
        margin: 0;
        padding: 0;
    }
    .dropdown-menu {
        z-index: 9999 !important;
    }
    .mileage-badge { 
        background: #e8f5e9; 
        color: #2e7d32; 
        padding: 0.35rem 0.75rem; 
        border-radius: 50px; 
        font-weight: 500; 
        white-space: nowrap;
    }
</style>

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
                @endif
                
                <th>N° Contrôle</th>
                <th>Agence</th>
                <th>Km Départ</th>
                <th>Km Arrivée</th>
                <th>Différence</th>
                <th>Statut</th>
                <th>Éléments</th>
                <th width="80">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($controls as $control)
            <tr>
                <td class="text-center">
                    <div class="form-check">
                        <input class="form-check-input control-checkbox" type="checkbox" value="{{ $control->id }}">
                    </div>
                </td>
                
                @if(isset($isGlobalView) && $isGlobalView)
                    <td>
                        @if($control->vehicle)
                            <a href="{{ route('backoffice.vehicles.show', $control->vehicle->id) }}" class="fw-medium">
                                {{ $control->vehicle->registration_number }}
                            </a>
                            @if($control->vehicle->registration_city)
                                <br><small class="text-muted">{{ $control->vehicle->registration_city }}</small>
                            @endif
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                @endif
                
                <td>
                    <a href="{{ route('backoffice.vehicles.controls.show', [$control->vehicle_id, $control->id]) }}" class="fw-medium">
                        {{ $control->control_number }}
                    </a>
                </td>
                <td>{{ $control->agency->name ?? '—' }}</td>
                <td><span class="mileage-badge">{{ number_format($control->start_mileage, 0, ',', ' ') }} km</span></td>
                <td>
                    @if($control->end_mileage)
                        <span class="mileage-badge">{{ number_format($control->end_mileage, 0, ',', ' ') }} km</span>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    @if($control->mileage_difference)
                        <span class="badge bg-info-transparent">
                            +{{ number_format($control->mileage_difference, 0, ',', ' ') }} km
                        </span>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    <span class="badge {{ $control->status_badge_class }} text-white">
                        {{ $control->status }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('backoffice.vehicles.controls.items.index', [$control->vehicle_id, $control->id]) }}" 
                       class="badge bg-secondary text-white text-decoration-none">
                        <i class="ti ti-checklist me-1"></i>
                        {{ $control->items_count }}
                    </a>
                </td>
                <td class="text-center">
                    @include('Backoffice.controls.partials._actions', ['control' => $control])
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ (isset($isGlobalView) && $isGlobalView) ? '10' : '9' }}" class="text-center py-5">
                    <div class="text-center">
                        <i class="ti ti-clipboard-list fs-48 text-gray-4 mb-3"></i>
                        <h5 class="mb-2">Aucun contrôle trouvé</h5>
                        <p class="text-muted mb-3">Aucun contrôle n'a été trouvé</p>
                        @if(!isset($isGlobalView) || !$isGlobalView)
                            @if(isset($vehicle) && $vehicle)
                                <a href="{{ route('backoffice.vehicles.controls.create', ['vehicle' => $vehicle->id]) }}" class="btn btn-primary">
                                    <i class="ti ti-plus me-2"></i>Ajouter un contrôle
                                </a>
                            @else
                                <a href="{{ route('backoffice.vehicles.controls.create') }}" class="btn btn-primary">
                                    <i class="ti ti-plus me-2"></i>Ajouter un contrôle
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
                document.querySelectorAll('.control-checkbox').forEach(cb => {
                    cb.checked = selectAll.checked;
                });
            });
        }
    });
</script>

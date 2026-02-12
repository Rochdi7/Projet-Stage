<head>
    <style>
        .table-responsive,
        .custom-datatable-filter,
        .dataTables_wrapper {
            overflow: visible !important;
        }
        .badge-amount {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-weight: 500;
        }
    </style>
</head>

<table class="table datatable">
    <thead class="thead-light">
        <tr>
            <th class="no-sort" width="50">
                <div class="form-check form-check-md">
                    <input class="form-check-input" type="checkbox" id="select-all">
                </div>
            </th>
            <th>Date</th>
            <th>Année</th>
            <th>Montant</th>
            <th>Notes</th>
            <th>Date d'ajout</th>
            <th width="80">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($vignettes as $vignette)
        <tr>
            <td>
                <div class="form-check form-check-md">
                    <input class="form-check-input vignette-checkbox" type="checkbox" value="{{ $vignette->id }}">
                </div>
            </td>
            <td>
                <div class="d-flex flex-column">
                    <span class="fw-medium">{{ $vignette->date->format('d/m/Y') }}</span>
                </div>
            </td>
            <td>
                <div class="d-flex flex-column">
                    <span class="fw-medium" style="color: #212529;">{{ $vignette->year }}</span>
                </div>
            </td>
            <td>
                <span class="badge-amount">
                    {{ number_format($vignette->amount, 2, ',', ' ') }} DH
                </span>
            </td>
            <td>
                @if($vignette->notes)
                    <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $vignette->notes }}">
                        {{ Str::limit($vignette->notes, 30) }}
                    </span>
                @else
                    <span class="text-muted">—</span>
                @endif
            </td>
            <td>
                <div class="d-flex flex-column">
                    <small class="fw-medium">{{ $vignette->created_at->format('d/m/Y') }}</small>
                    <small class="text-muted">{{ $vignette->created_at->format('H:i') }}</small>
                </div>
            </td>
            <td>
                @include('Backoffice.vignettes.partials._actions', ['vignette' => $vignette, 'vehicle' => $vehicle])
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center py-5">
                <div class="text-center">
                    <i class="ti ti-ticket-off fs-48 text-gray-4 mb-3"></i>
                    <h5 class="mb-2">Aucune vignette trouvée</h5>
                    <!-- <p class="text-muted mb-3">Commencez par ajouter une nouvelle vignette</p>
                    <a href="{{ route('backoffice.vehicles.vignettes.create', $vehicle->id) }}" class="btn btn-primary">
                        <i class="ti ti-plus me-2"></i>
                        Ajouter une vignette
                    </a> -->
                </div>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<script>
    document.getElementById('select-all').addEventListener('change', function () {
        let checkboxes = document.querySelectorAll('.vignette-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>
<style>
    .table-responsive { overflow: visible !important; }
    .badge-amount { background: #e8f5e9; color: #2e7d32; padding: 0.35rem 0.75rem; border-radius: 50px; font-weight: 500; }
    .btn-icon { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; }
    .btn-icon i { font-size: 18px; }
    th:last-child, td:last-child { width: 80px; text-align: center !important; }
</style>

<table class="table datatable align-middle">
    <thead class="thead-light">
        <tr>
            <th width="50" class="text-center"><div class="form-check"><input class="form-check-input" type="checkbox" id="select-all"></div></th>
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
            <td class="text-center"><div class="form-check"><input class="form-check-input vignette-checkbox" type="checkbox" value="{{ $vignette->id }}"></div></td>
            <td><span class="fw-medium">{{ $vignette->date->format('d/m/Y') }}</span></td>
            <td><span class="fw-medium">{{ $vignette->year }}</span></td>
            <td><span class="badge-amount">{{ number_format($vignette->amount, 2, ',', ' ') }} DH</span></td>
            <td>@if($vignette->notes)<span title="{{ $vignette->notes }}">{{ Str::limit($vignette->notes, 30) }}</span>@else<span class="text-muted">—</span>@endif</td>
            <td><small class="fw-medium">{{ $vignette->created_at->format('d/m/Y') }}</small><br><small class="text-muted">{{ $vignette->created_at->format('H:i') }}</small></td>
            <td class="text-center">@include('Backoffice.vignettes.partials._actions', ['vignette' => $vignette])</td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center py-5">
                <!-- <i class="ti ti-ticket-off fs-48 text-gray-4 mb-3"></i> -->
                <h5 class="mb-2">Aucune vignette trouvée</h5>
                @if(isset($vehicle) && $vehicle)
                    <!-- <a href="{{ route('backoffice.vehicles.vignettes.create', ['vehicle' => $vehicle->id]) }}" class="btn btn-primary mt-3">
                        <i class="ti ti-plus me-2"></i>Ajouter une vignette
                    </a> -->
                @else
                    <!-- <a href="{{ route('backoffice.vehicles.create') }}" class="btn btn-primary mt-3">
                        <i class="ti ti-plus me-2"></i>Créer un véhicule
                    </a> -->
                @endif
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<script>
    document.getElementById('select-all')?.addEventListener('change', function() {
        document.querySelectorAll('.vignette-checkbox').forEach(cb => cb.checked = this.checked);
    });
</script>
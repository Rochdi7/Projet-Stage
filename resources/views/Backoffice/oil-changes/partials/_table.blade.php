<style>
    .badge-amount { background: #e8f5e9; color: #2e7d32; padding: 0.35rem 0.75rem; border-radius: 50px; font-weight: 500; }
    .badge-mileage { background: #e3f2fd; color: #0d47a1; padding: 0.35rem 0.75rem; border-radius: 50px; font-weight: 500; }
    .badge-ok { background: #d4edda; color: #155724; padding: 0.35rem 0.75rem; border-radius: 50px; font-weight: 500; }
    .badge-due-soon { background: #fff3cd; color: #856404; padding: 0.35rem 0.75rem; border-radius: 50px; font-weight: 500; }
    .badge-overdue { background: #f8d7da; color: #721c24; padding: 0.35rem 0.75rem; border-radius: 50px; font-weight: 500; }
    .btn-icon { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; }
    th:last-child, td:last-child { width: 80px; text-align: center !important; }
</style>

<table class="table datatable align-middle">
    <thead class="thead-light">
        <tr>
            <th width="50" class="text-center"><div class="form-check"><input class="form-check-input" type="checkbox" id="select-all"></div></th>
            <th>Date</th>
            <th>Mécanicien</th>
            <th>Kilométrage</th>
            <th>Prochaine</th>
            <th>Reste</th>
            <th>Montant</th>
            <th>Statut</th>
            <th>Observations</th>
            <th width="80">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($oilChanges as $oilChange)
        <tr>
            <td class="text-center"><div class="form-check"><input class="form-check-input oil-change-checkbox" type="checkbox" value="{{ $oilChange->id }}"></div></td>
            <td><span class="fw-medium">{{ $oilChange->formatted_date }}</span></td>
            <td>{{ $oilChange->mechanic_name ?? '—' }}</td>
            <td><span class="badge-mileage"><i class="ti ti-speedometer me-1"></i>{{ $oilChange->formatted_mileage }}</span></td>
            <td><span class="badge-mileage">{{ $oilChange->formatted_next_mileage }}</span></td>
            <td><span class="fw-medium">{{ $oilChange->formatted_remaining_mileage }}</span></td>
            <td><span class="badge-amount">{{ number_format($oilChange->amount, 2, ',', ' ') }} DH</span></td>
            <td><span class="badge {{ $oilChange->status_badge_class }} text-white">{{ $oilChange->status_text }}</span></td>
            <td>@if($oilChange->observations)<span title="{{ $oilChange->observations }}">{{ Str::limit($oilChange->observations, 20) }}</span>@else<span class="text-muted">—</span>@endif</td>
            <td class="text-center">@include('Backoffice.oil-changes.partials._actions', ['oilChange' => $oilChange])</td>
        </tr>
        @empty
        <tr>
            <td colspan="10" class="text-center py-5">
                <!-- <i class="ti ti-droplet-off fs-48 text-gray-4 mb-3"></i> -->
                <h5 class="mb-2">Aucune vidange trouvée</h5>
                @if(isset($vehicle) && $vehicle)
                    <a href="{{ route('backoffice.vehicles.oil-changes.create', ['vehicle' => $vehicle->id]) }}" class="btn btn-primary mt-3">
                        <i class="ti ti-plus me-2"></i>Ajouter une vidange
                    </a>
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
        document.querySelectorAll('.oil-change-checkbox').forEach(cb => cb.checked = this.checked);
    });
</script>
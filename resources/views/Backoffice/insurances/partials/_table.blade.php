<style>
    .badge-amount { background: #e8f5e9; color: #2e7d32; padding: 0.35rem 0.75rem; border-radius: 50px; font-weight: 500; }
    .badge-expired { background: #f8d7da; color: #721c24; padding: 0.35rem 0.75rem; border-radius: 50px; font-weight: 500; }
    .badge-expiring { background: #fff3cd; color: #856404; padding: 0.35rem 0.75rem; border-radius: 50px; font-weight: 500; }
    .btn-icon { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; }
    th:last-child, td:last-child { width: 80px; text-align: center !important; }
</style>

<table class="table datatable align-middle">
    <thead class="thead-light">
        <tr>
            <th width="50" class="text-center"><div class="form-check"><input class="form-check-input" type="checkbox" id="select-all"></div></th>
            <th>Date</th>
            <th>Compagnie</th>
            <th>Police</th>
            <th>Montant</th>
            <th>Échéance</th>
            <th>Statut</th>
            <th>Notes</th>
            <th width="80">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($insurances as $insurance)
        <tr>
            <td class="text-center"><div class="form-check"><input class="form-check-input insurance-checkbox" type="checkbox" value="{{ $insurance->id }}"></div></td>
            <td><span class="fw-medium">{{ $insurance->formatted_date }}</span></td>
            <td>{{ $insurance->company_name ?? '—' }}</td>
            <td>{{ $insurance->policy_number ?? '—' }}</td>
            <td><span class="badge-amount">{{ number_format($insurance->amount, 2, ',', ' ') }} DH</span></td>
            <td>{{ $insurance->formatted_next_date }}</td>
            <td><span class="badge {{ $insurance->status_badge_class }} text-white">{{ $insurance->status_text }}</span></td>
            <td>@if($insurance->notes)<span title="{{ $insurance->notes }}">{{ Str::limit($insurance->notes, 20) }}</span>@else<span class="text-muted">—</span>@endif</td>
            <td class="text-center">@include('Backoffice.insurances.partials._actions', ['insurance' => $insurance])</td>
        </tr>
        @empty
        <tr>
            <td colspan="9" class="text-center py-5">
                <!-- <i class="ti ti-shield-off fs-48 text-gray-4 mb-3"></i> -->
                <h5 class="mb-2">Aucune assurance trouvée</h5>
                @if(isset($vehicle) && $vehicle)
                    <a href="{{ route('backoffice.vehicles.insurances.create', ['vehicle' => $vehicle->id]) }}" class="btn btn-primary mt-3">
                        <i class="ti ti-plus me-2"></i>Ajouter une assurance
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
        document.querySelectorAll('.insurance-checkbox').forEach(cb => cb.checked = this.checked);
    });
</script>
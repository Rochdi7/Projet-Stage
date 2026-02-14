<div class="table-responsive">
    <table class="table align-middle">
        <thead class="thead-light">
            <tr>
                <th width="50" class="text-center">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="select-all">
                    </div>
                </th>
                <th>N° Contrat</th>
                <th>Client</th>
                <th>Véhicule</th>
                <th>Dates</th>
                <th>Montant</th>
                <th>Statut</th>
                <th width="80">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($contracts as $contract)
            <tr>
                <td class="text-center">
                    <div class="form-check">
                        <input class="form-check-input contract-checkbox" type="checkbox" value="{{ $contract->id }}">
                    </div>
                </td>
                
                <td>
                    <div class="contract-info">
                        <a href="{{ route('backoffice.rental-contracts.show', $contract) }}" class="fw-medium">
                            {{ $contract->contract_number }}
                        </a>
                        <br>
                        <small>
                            <i class="ti ti-calendar me-1"></i>{{ $contract->created_at->format('d/m/Y') }}
                        </small>
                    </div>
                </td>
                
                <td>
                    <div class="contract-info">
                        <span class="fw-medium">{{ $contract->primaryClient->first_name ?? '' }} {{ $contract->primaryClient->last_name ?? '' }}</span>
                        <br>
                        <small>
                            <i class="ti ti-phone me-1"></i>{{ $contract->primaryClient->phone ?? 'N/A' }}
                        </small>
                    </div>
                </td>
                
                <td>
                    <div class="contract-info">
                        <a href="{{ route('backoffice.vehicles.show', $contract->vehicle_id) }}" class="fw-medium">
                            {{ $contract->vehicle->registration_number ?? 'N/A' }}
                        </a>
                        <br>
                        <small>{{ $contract->vehicle->model->name ?? 'N/C' }}</small>
                    </div>
                </td>
                
                <td>
                    <div class="contract-info">
                        <span><i class="ti ti-calendar-check me-1"></i>{{ $contract->formatted_start_date }}</span>
                        <br>
                        <span><i class="ti ti-calendar-x me-1"></i>{{ $contract->formatted_end_date }}</span>
                    </div>
                </td>
                
                <td>
                    <span class="amount-badge">{{ $contract->formatted_total_amount }}</span>
                    @if($contract->deposit_amount)
                        <br><small class="text-muted">Caution: {{ $contract->formatted_deposit }}</small>
                    @endif
                </td>
                
                <td>
                    <span class="badge badge-{{ str_replace('_', '-', $contract->status) }}">
                        {{ $contract->status_text }}
                    </span>
                    <br>
                    <small>
                        <i class="ti ti-clock me-1"></i>{{ $contract->acceptance_text }}
                    </small>
                </td>
                
                <td class="text-center">
                    @include('backoffice.rental-contracts.partials._actions', ['contract' => $contract])
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-5">
                    <div class="text-center">
                        <i class="ti ti-file-text fs-48 text-gray-4 mb-3"></i>
                        <h5 class="mb-2">Aucun contrat trouvé</h5>
                        <p class="text-muted mb-3">Commencez par créer un nouveau contrat</p>
                        <a href="{{ route('backoffice.rental-contracts.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-2"></i>Nouveau contrat
                        </a>
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
                document.querySelectorAll('.contract-checkbox').forEach(cb => {
                    cb.checked = selectAll.checked;
                });
            });
        }
    });
</script>
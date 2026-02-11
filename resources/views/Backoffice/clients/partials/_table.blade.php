<style>
    .client-avatar-table {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        object-fit: cover;
    }
    .client-avatar-placeholder-table {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 16px;
    }
</style>

<table class="table datatable">
    <thead class="thead-light">
        <tr>
            <th class="no-sort" width="50">
                <div class="form-check form-check-md">
                    <input class="form-check-input" type="checkbox" id="select-all">
                </div>
            </th>
            <th>Photo</th>
            <th>Client</th>
            <th>Agence</th>
            <th>Contact</th>
            <th>Permis</th>
            <th>Statut</th>
            <th>Date d'ajout</th>
            <th width="80">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($clients as $client)
        <tr>
            <td>
                <div class="form-check form-check-md">
                    <input class="form-check-input client-checkbox" type="checkbox">
                </div>
            </td>
            <td>
                @if($client->avatar_url)
                    <img src="{{ $client->avatar_url }}" 
                         alt="{{ $client->full_name }}"
                         class="client-avatar-table">
                @else
                    <div class="client-avatar-placeholder-table">
                        {{ $client->avatar_initials }}
                    </div>
                @endif
            </td>
            <td>
                <div class="d-flex flex-column">
                    <h6 class="fw-medium mb-0">
                        <a href="{{ route('backoffice.clients.show', $client) }}" class="text-dark">
                            {{ $client->full_name }}
                        </a>
                    </h6>
                    <small class="text-muted">ID: #{{ $client->id }}</small>
                </div>
            </td>
            <td>
                <div class="d-flex align-items-center">
                    <i class="ti ti-building me-1 text-gray-4"></i>
                    <span>{{ $client->agency->name ?? '—' }}</span>
                </div>
            </td>
            <td>
                <div class="d-flex flex-column">
                    @if($client->email)
                        <a href="mailto:{{ $client->email }}" class="text-primary small">
                            <i class="ti ti-mail me-1"></i>{{ Str::limit($client->email, 20) }}
                        </a>
                    @endif
                    @if($client->phone)
                        <a href="tel:{{ $client->phone }}" class="text-success small mt-1">
                            <i class="ti ti-phone me-1"></i>{{ $client->phone }}
                        </a>
                    @endif
                </div>
            </td>
            <td>
                @if($client->driving_license_number)
                    <span class="badge bg-info-transparent">
                        <i class="ti ti-id me-1"></i>
                        {{ Str::limit($client->driving_license_number, 10) }}
                    </span>
                @else
                    <span class="text-muted">—</span>
                @endif
            </td>
            <td>
                @if($client->status == 'active')
                    <span class="badge bg-success-transparent">
                        <i class="ti ti-point-filled text-success me-1"></i>
                        Actif
                    </span>
                @elseif($client->status == 'inactive')
                    <span class="badge bg-danger-transparent">
                        <i class="ti ti-point-filled text-danger me-1"></i>
                        Inactif
                    </span>
                @else
                    <span class="badge bg-dark-transparent">
                        <i class="ti ti-point-filled text-dark me-1"></i>
                        Blacklisté
                    </span>
                @endif
            </td>
            <td>
                <div class="d-flex flex-column">
                    <small class="fw-medium">{{ $client->created_at->format('d/m/Y') }}</small>
                    <small class="text-muted">{{ $client->created_at->format('H:i') }}</small>
                </div>
            </td>
            <td>
                @include('backoffice.clients.partials._actions', ['client' => $client])
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="9" class="text-center py-5">
                <div class="text-center">
                    <i class="ti ti-users fs-48 text-gray-4 mb-3"></i>
                    <h5 class="mb-2">Aucun client trouvé</h5>
                    <p class="text-muted mb-3">Commencez par ajouter un nouveau client</p>
                    <a href="{{ route('backoffice.clients.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-2"></i>
                        Ajouter un client
                    </a>
                </div>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<script>
    document.getElementById('select-all').addEventListener('change', function () {
        let checkboxes = document.querySelectorAll('.client-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });
</script>
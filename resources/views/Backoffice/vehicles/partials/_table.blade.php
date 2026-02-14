<head>
    <style>
        /* Fix DataTables dropdown clipping */
        .dataTables_wrapper,
        .dataTables_wrapper .table-responsive {
            overflow: visible !important;
        }

        /* Ensure dropdown appears above table */
        .dataTables_wrapper .dropdown-menu {
            z-index: 1055;
        }

        /* Empty state styling */
        .empty-state {
            padding: 40px 20px;
            text-align: center;
        }
        .empty-state i {
            font-size: 48px;
            color: #adb5bd;
            margin-bottom: 16px;
        }
        
        /* Action button styling */
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
        
        /* Checkbox centering */
        .form-check {
            display: flex;
            justify-content: center;
            margin: 0;
            padding: 0;
        }
        
        /* Table cell vertical alignment */
        .table td, .table th {
            vertical-align: middle;
        }
    </style>
</head>

<!-- Custom Data Table -->
<div class="custom-datatable-filter">
    <table class="table datatable align-middle">
        <thead class="thead-light">
            <tr>
                <th class="no-sort" width="50" style="text-align: center;">
                    <div class="form-check form-check-md">
                        <input class="form-check-input" type="checkbox" id="select-all">
                    </div>
                </th>
                <th>CAR</th>
                <th>BASE LOCATION</th>
                <th>PRICE (PER DAY)</th>
                <th>MILEAGE</th>
                <th>CREATED DATE</th>
                <th>STATUS</th>
                <th width="80" style="text-align: center;">ACTIONS</th>
            </tr>
        </thead>

        <tbody>
            @forelse($vehicles as $vehicle)
                @php
                    // Get media URL
                    $mediaUrl = $vehicle->getFirstMediaUrl('vehicle_photos');
                    
                    // Set photo URL - use media if exists, otherwise use placeholder
                    if (!empty($mediaUrl)) {
                        $photoUrl = $mediaUrl;
                    } else {
                        $photoUrl = asset('assets/place-holder.webp');
                    }
                    
                    // Get car title
                    $brandName = optional(optional($vehicle->model)->brand)->name;
                    $modelName = optional($vehicle->model)->name;

                    $carTitle = trim(($brandName ? $brandName . ' ' : '') . ($modelName ?? ''));
                    if ($carTitle === '') {
                        $carTitle = $vehicle->registration_number;
                    }

                    // Generate initials for ultra fallback (if image fails)
                    $words = explode(' ', trim($carTitle));
                    if (count($words) >= 2) {
                        $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                    } else {
                        $initials = strtoupper(substr($carTitle, 0, 2));
                    }
                    
                    // Generate consistent color for initials fallback
                    $colors = ['#4361ee', '#3a0ca3', '#4cc9f0', '#f72585', '#b5179e', '#4895ef', '#560bad', '#06d6a0', '#ffbe0b', '#ef476f'];
                    $colorIndex = abs(crc32($vehicle->id ?? $carTitle)) % count($colors);
                    $bgColor = $colors[$colorIndex];

                    // Status
                    $status = $vehicle->status;
                    $statusLabel = match ($status) {
                        'available' => 'Disponible',
                        'unavailable' => 'Indisponible',
                        'maintenance' => 'Maintenance',
                        'sold' => 'Vendu',
                        default => '—',
                    };
                    $statusDot = match ($status) {
                        'available' => 'text-success',
                        'unavailable' => 'text-danger',
                        'maintenance' => 'text-warning',
                        'sold' => 'text-secondary',
                        default => 'text-muted',
                    };

                    $createdDate = optional($vehicle->created_at)->format('d M Y');
                    $createdTime = optional($vehicle->created_at)->format('h:i A');

                    // Base location
                    $baseLocation = $vehicle->registration_city ?: '—';

                    $daily = $vehicle->daily_rate !== null ? number_format((float) $vehicle->daily_rate, 2) : null;
                    $mileage = $vehicle->current_mileage !== null ? number_format((int) $vehicle->current_mileage) : null;
                @endphp

                <tr>
                    <td style="text-align: center; vertical-align: middle;">
                        <div class="form-check form-check-md">
                            <input class="form-check-input row-check" type="checkbox" value="{{ $vehicle->id }}">
                        </div>
                    </td>

                    <td style="vertical-align: middle;">
                        <div class="d-flex align-items-center">
                            <a href="{{ route('backoffice.vehicles.show', $vehicle) }}" class="avatar me-2 flex-shrink-0">
                                <img src="{{ $photoUrl }}" 
                                     class="rounded-3" 
                                     alt="{{ $carTitle }}" 
                                     style="width: 50px; height: 50px; object-fit: cover;"
                                     onerror="this.onerror=null; this.src='{{ asset('assets/place-holder.webp') }}'; 
                                              this.onerror=function(){ 
                                                  this.style.display='none'; 
                                                  this.parentElement.innerHTML += '<div style=\'width:50px;height:50px;background-color:{{ $bgColor }};color:white;display:flex;align-items:center;justify-content:center;border-radius:8px;font-weight:600;font-size:18px;\'>{{ $initials }}</div>';
                                              };">
                            </a>
                            <div>
                                <h6 class="mb-1">
                                    <a href="{{ route('backoffice.vehicles.show', $vehicle) }}" class="fs-14 fw-semibold">
                                        {{ $carTitle }}
                                    </a>
                                </h6>
                                <p class="mb-0">
                                    {{ $vehicle->registration_number }}
                                    @if ($vehicle->year)
                                        <span class="ms-2">{{ $vehicle->year }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </td>

                    <td style="vertical-align: middle;">
                        {{ $baseLocation }}
                    </td>

                    <td style="vertical-align: middle;">
                        <p class="fs-14 fw-semibold text-gray-9 mb-0">
                            @if ($daily !== null)
                                {{ $daily }} MAD
                            @else
                                —
                            @endif
                        </p>
                    </td>

                    <td style="vertical-align: middle;">
                        <p class="text-gray-9 mb-0">
                            @if ($mileage !== null)
                                {{ $mileage }} km
                            @else
                                —
                            @endif
                        </p>
                    </td>

                    <td style="vertical-align: middle;">
                        <h6 class="fs-14 fw-normal mb-0">{{ $createdDate ?: '—' }}</h6>
                        <p class="fs-13 mb-0">{{ $createdTime ?: '' }}</p>
                    </td>

                    <td style="vertical-align: middle;">
                        <span class="badge badge-dark-transparent">
                            <i class="ti ti-point-filled {{ $statusDot }} me-1"></i>{{ $statusLabel }}
                        </span>
                    </td>

                    <td style="text-align: center; vertical-align: middle;">
                        <div class="dropdown d-inline-block">
                            <button class="btn btn-icon btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-end p-2">
                                <li>
                                    <a class="dropdown-item rounded-1"
                                       href="{{ route('backoffice.vehicles.show', $vehicle) }}">
                                        <i class="ti ti-eye me-1"></i> Voir détails
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item rounded-1"
                                       href="{{ route('backoffice.vehicles.edit', $vehicle) }}">
                                        <i class="ti ti-edit me-1"></i> Modifier
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <button type="button" 
                                            class="dropdown-item text-danger rounded-1"
                                            onclick="showDeleteModal('{{ $vehicle->id }}', '{{ addslashes($carTitle) }}', '{{ route('backoffice.vehicles.destroy', $vehicle) }}')">
                                        <i class="ti ti-trash me-1"></i> Supprimer
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <div class="empty-state">
                            <h5 class="mb-2">Aucun véhicule trouvé</h5>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Select all checkbox
        const selectAll = document.getElementById('select-all');
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                document.querySelectorAll('.row-check').forEach(cb => {
                    cb.checked = selectAll.checked;
                });
            });
        }

        // Individual checkbox - update select all state
        const rowCheckboxes = document.querySelectorAll('.row-check');
        if (rowCheckboxes.length > 0 && selectAll) {
            rowCheckboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    const allChecked = Array.from(rowCheckboxes).every(c => c.checked);
                    const anyChecked = Array.from(rowCheckboxes).some(c => c.checked);
                    selectAll.checked = allChecked;
                    selectAll.indeterminate = !allChecked && anyChecked;
                });
            });
        }
    });
</script>
<head>
    <style>
        /* Ensure dropdowns are visible */
.dropdown-menu {
    display: none;
    position: absolute;
    z-index: 9999;
}

.dropdown-menu.show {
    display: block !important;
}

/* Fix for table overflow */
.table-responsive {
    overflow: visible !important;
}

/* Ensure buttons are clickable */
.btn-icon {
    cursor: pointer;
    pointer-events: auto;
}
    </style>
    @section('content')
<script>
// Override the native alert function to block DataTables warnings
(function() {
    // Store the original alert
    var originalAlert = window.alert;
    
    // Replace with filtered version
    window.alert = function(message) {
        // Check if this is a DataTables warning
        if (message && (message.includes('DataTables') || message.includes('datatables'))) {
            console.log('DataTables warning blocked:', message);
            return; // Block the alert
        }
        // Allow other alerts through
        originalAlert(message);
    };
    
    // Also set DataTables error mode if available
    if (window.$ && $.fn && $.fn.dataTable) {
        $.fn.dataTable.ext.errMode = 'none';
    }
})();
</script>
</head>
<?php $page = 'controls'; ?>
@extends('layout.mainlayout_admin')

@section('content')
<style>
    .btn-icon {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        color: #6c757d;
        background: transparent;
        border: 1px solid transparent;
        transition: all 0.2s;
        cursor: pointer;
    }
    
    .btn-icon:hover {
        background: #f8f9fa;
        border-color: #dee2e6;
        color: #0d6efd;
    }
    
    .btn-icon i {
        font-size: 18px;
    }
    
    .badge-success {
        background: #d4edda;
        color: #155724;
        padding: 0.35rem 0.65rem;
        border-radius: 4px;
        font-weight: 500;
    }
    
    .badge-warning {
        background: #fff3cd;
        color: #856404;
        padding: 0.35rem 0.65rem;
        border-radius: 4px;
        font-weight: 500;
    }
    
    td:last-child {
        width: 60px;
        text-align: center;
        vertical-align: middle;
    }
    
    .table-responsive { 
        overflow: visible !important; 
    }
    
    .dropdown-menu {
        z-index: 9999 !important;
    }
    
    .form-check {
        display: flex;
        justify-content: center;
        margin: 0;
        padding: 0;
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

<div class="page-wrapper">
    <div class="content me-4">

        @include('Backoffice.controls.partials._breadcrumbs')

        <form method="GET" id="filterForm" action="{{ request()->url() }}">
            <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
                <div class="d-flex align-items-center flex-wrap row-gap-3">
                    <div class="dropdown me-2">
                        <a href="#" class="dropdown-toggle btn btn-white d-inline-flex align-items-center" data-bs-toggle="dropdown" role="button">
                            <i class="ti ti-filter me-1"></i> Trier : 
                            @if(request('sort') == 'oldest')
                                Plus anciens
                            @elseif(request('sort') == 'mileage_asc')
                                Kilométrage ↑
                            @elseif(request('sort') == 'mileage_desc')
                                Kilométrage ↓
                            @else
                                Plus récents
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end p-2">
                            <li>
                                <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}">
                                    Plus récents
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'oldest']) }}">
                                    Plus anciens
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'mileage_desc']) }}">
                                    Kilométrage (plus élevé)
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'mileage_asc']) }}">
                                    Kilométrage (moins élevé)
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <a href="#filtercollapse" class="filtercollapse coloumn d-inline-flex align-items-center" data-bs-toggle="collapse">
                            <i class="ti ti-filter me-1"></i> Filtres
                        </a>
                    </div>
                </div>

                <div class="d-flex my-xl-auto right-content align-items-center flex-wrap row-gap-3">
                    <div class="top-search me-2">
                        <div class="top-search-group position-relative">
                            <span class="input-icon">
                                <i class="ti ti-search"></i>
                            </span>
                            <input type="text"
                                   name="search"
                                   id="searchInput"
                                   value="{{ request('search') }}"
                                   class="form-control"
                                   placeholder="Rechercher un contrôle...">
                            @if(request('search'))
                                <button type="button" class="btn btn-link position-absolute" style="right: 5px; top: 50%; transform: translateY(-50%); padding: 0; color: #6c757d; z-index: 10;" onclick="clearSearch()">
                                    <i class="ti ti-x"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="mb-0">
                        <a href="{{ route('backoffice.vehicles.controls.create') }}" class="btn btn-primary d-flex align-items-center">
                            <i class="ti ti-plus me-2"></i>Ajouter un contrôle
                        </a>
                    </div>
                </div>
            </div>

            <div class="collapse" id="filtercollapse">
                <div class="filterbox p-3 mb-3 bg-light-100 rounded">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-medium">Statut</label>
                            <select name="status" form="filterForm" class="form-select" onchange="this.form.submit()">
                                <option value="">Tous</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminé</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En cours</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <a href="{{ request()->url() }}" class="btn btn-sm btn-outline-danger w-100">
                                <i class="ti ti-x me-1"></i>Tout effacer
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="custom-datatable-filter table-responsive">
            @include('Backoffice.controls.partials._table')
        </div>

        @if(isset($controls) && $controls->total() > 0)
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Affichage de {{ $controls->firstItem() }} à {{ $controls->lastItem() }} sur {{ $controls->total() }} contrôles
            </div>
            <div>
                {{ $controls->withQueryString()->links() }}
            </div>
        </div>
        @endif

    </div>

    <div class="footer d-sm-flex align-items-center justify-content-between bg-white p-3">
        <p class="mb-0">
            <a href="javascript:void(0);">Privacy Policy</a>
            <a href="javascript:void(0);" class="ms-4">Terms of Use</a>
        </p>
        <p>&copy; 2025 Dreamsrent, Made with <span class="text-danger">❤</span> by
            <a href="javascript:void(0);" class="text-secondary">Dreams</a>
        </p>
    </div>
</div>




<!-- Bootstrap JS (make sure it's loaded) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Main Script with improved dropdown handling -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto search
    const form = document.getElementById('filterForm');
    const input = document.getElementById('searchInput');

    if (form && input) {
        let timer;
        input.addEventListener('input', function() {
            clearTimeout(timer);
            timer = setTimeout(() => form.submit(), 400);
        });
    }

    // Initialize all dropdowns
    initializeAllDropdowns();
    
    // Handle document clicks to close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        const isDropdownToggle = event.target.closest('[data-bs-toggle="dropdown"]');
        const isInsideDropdown = event.target.closest('.dropdown-menu');
        
        // If click is outside any dropdown or its toggle, close all dropdowns
        if (!isDropdownToggle && !isInsideDropdown) {
            closeAllDropdowns();
        }
    });
    
    // Prevent dropdown from closing when clicking inside the dropdown menu
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        menu.addEventListener('click', function(event) {
            event.stopPropagation();
        });
    });
});

function initializeAllDropdowns() {
    if (typeof bootstrap === 'undefined' || !bootstrap.Dropdown) {
        console.error('Bootstrap Dropdown is not available');
        return;
    }

    // Get all dropdown toggles
    var dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
    
    dropdownElementList.forEach(function (dropdownToggleEl) {
        try {
            // Create new dropdown instance
            const dropdown = new bootstrap.Dropdown(dropdownToggleEl);
            
            // Add custom click handler for better toggle behavior
            dropdownToggleEl.addEventListener('click', function(event) {
                event.preventDefault();
                event.stopPropagation();
                
                // Check if this dropdown is currently shown
                const isShown = this.classList.contains('show') || 
                               this.getAttribute('aria-expanded') === 'true';
                
                if (isShown) {
                    // If shown, hide it
                    dropdown.hide();
                } else {
                    // If not shown, hide all others first then show this one
                    closeAllDropdowns();
                    dropdown.show();
                }
            });
            
        } catch (error) {
            console.error('Error initializing dropdown:', error);
        }
    });
}

function closeAllDropdowns() {
    if (typeof bootstrap === 'undefined' || !bootstrap.Dropdown) {
        return;
    }
    
    document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(toggle => {
        try {
            const dropdown = bootstrap.Dropdown.getInstance(toggle);
            if (dropdown) {
                dropdown.hide();
            }
        } catch (error) {
            // Ignore errors
        }
    });
}

function clearSearch() {
    const input = document.getElementById('searchInput');
    if (input) {
        input.value = '';
        document.getElementById('filterForm').submit();
    }
}
</script>



@include('Backoffice.controls.partials._modal_delete')
@include('Backoffice.controls.partials._modals_js')
@endsection
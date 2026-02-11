<head>
    <style>
        /* FIX: allow dropdowns inside tables */
        .table-responsive,
        .custom-datatable-filter {
            overflow: visible !important;
        }
    </style>
</head>

<?php $page = 'agents'; ?>
@extends('layout.mainlayout_admin')

@section('content')
<div class="page-wrapper">
    <div class="content me-4">

        @include('backoffice.agents.partials._breadcrumbs')

        <!-- Table Header -->
        <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">
            <div class="d-flex align-items-center flex-wrap row-gap-3">
                <div class="dropdown me-2">
                    <a href="javascript:void(0);" class="dropdown-toggle btn btn-white d-inline-flex align-items-center"
                        data-bs-toggle="dropdown">
                        <i class="ti ti-filter me-1"></i> Trier : Derniers
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end p-2">
                        <li><a href="javascript:void(0);" class="dropdown-item rounded-1">Derniers</a></li>
                        <li><a href="javascript:void(0);" class="dropdown-item rounded-1">A → Z</a></li>
                        <li><a href="javascript:void(0);" class="dropdown-item rounded-1">Z → A</a></li>
                    </ul>
                </div>

                <div class="dropdown">
                    <a href="#filtercollapse" class="filtercollapse coloumn d-inline-flex align-items-center"
                        data-bs-toggle="collapse" role="button">
                        <i class="ti ti-filter me-1"></i> Filtres
                        <span class="badge badge-xs rounded-pill bg-danger ms-2">0</span>
                    </a>
                </div>
            </div>

            <div class="d-flex my-xl-auto right-content align-items-center flex-wrap row-gap-3">
                <div class="top-search me-2">
                    <div class="top-search-group">
                        <span class="input-icon">
                            <i class="ti ti-search"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Rechercher un agent...">
                    </div>
                </div>

                <div class="mb-0">
                    <a href="{{ route('backoffice.agents.create') }}" class="btn btn-primary d-flex align-items-center">
                        <i class="ti ti-plus me-2"></i>Ajouter un agent
                    </a>
                </div>
            </div>
        </div>

        <div class="collapse" id="filtercollapse">
            <div class="filterbox mb-3 d-flex align-items-center">
                <h6 class="me-3">Filtres</h6>
                <div class="dropdown me-3">
                    <a href="javascript:void(0);" class="dropdown-toggle btn btn-white d-inline-flex align-items-center"
                        data-bs-toggle="dropdown" data-bs-auto-close="outside">
                        Agence
                    </a>
                    <ul class="dropdown-menu dropdown-menu-md p-2">
                        @foreach($agencies ?? [] as $agency)
                            <li class="dropdown-item">{{ $agency->name }}</li>
                        @endforeach
                    </ul>
                </div>
                <a href="javascript:void(0);" class="me-2 text-purple links">Appliquer</a>
                <a href="javascript:void(0);" class="text-danger links">Tout effacer</a>
            </div>
        </div>

        <!-- Custom Data Table -->
        <div class="custom-datatable-filter table-responsive">
            @include('backoffice.agents.partials._table', ['agents' => $agents])
        </div>

        <div class="table-footer">
            <div class="d-flex justify-content-end">
                {{ $agents->links() }}
            </div>
        </div>

    </div>

    <!-- Footer -->
    <div class="footer d-sm-flex align-items-center justify-content-between bg-white p-3">
        <p class="mb-0">2024 © Rental Car. All rights reserved.</p>
        <p class="mb-0">v1.0</p>
    </div>
</div>

{{-- ✅ DELETE MODAL - MUST BE HERE, INSIDE @section('content') --}}
@include('backoffice.agents.partials._modal_delete')
@endsection

{{-- ✅ ONLY ONE SCRIPT SECTION --}}
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Delete modal script loaded');
    
    const deleteModal = document.getElementById('delete_agent');
    
    if (!deleteModal) {
        console.error('❌ Delete modal not found!');
        return;
    }
    
    console.log('✅ Delete modal found');
    
    deleteModal.addEventListener('show.bs.modal', function(event) {
        console.log('✅ Modal show event triggered');
        
        const button = event.relatedTarget;
        
        if (!button) {
            console.error('❌ No button found');
            return;
        }
        
        const action = button.getAttribute('data-delete-action');
        const agentName = button.getAttribute('data-agent-name');
        
        console.log('✅ Delete action:', action);
        console.log('✅ Agent name:', agentName);
        
        const form = document.getElementById('deleteAgentForm');
        const nameSpan = document.getElementById('deleteAgentName');
        
        if (form) {
            form.action = action;
            console.log('✅ Form action set to:', form.action);
        } else {
            console.error('❌ Form not found!');
        }
        
        if (nameSpan) {
            nameSpan.textContent = agentName || 'cet agent';
            console.log('✅ Name set to:', nameSpan.textContent);
        }
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Stop dropdown from interfering with modal events
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        menu.addEventListener('click', function(e) {
            // Allow clicks on our delete button to pass through
            if (e.target.closest('[onclick*="openDeleteModal"]')) {
                return; // Let our handler work
            }
            e.stopPropagation(); // Prevent dropdown from closing
        });
    });
});
</script>
@endsection
<?php $page = 'cars'; ?>
@extends('layout.mainlayout_admin')

@section('content')
    <div class="page-wrapper">
        <div class="content me-4">

            {{-- Breadcrumb + top buttons (Print/Export/Add) --}}
            @include('backoffice.vehicles.partials._breadcrumbs')

            {{-- Table header (sort/date/filter + bulk/search/columns) --}}
            @include('backoffice.vehicles.partials._table_header')

            {{-- Collapse filters (block “Filters”) --}}
            @include('backoffice.vehicles.partials._filters')

            {{-- Data table --}}
            @include('backoffice.vehicles.partials._table')

            <div class="table-footer"></div>
        </div>

        {{-- Footer theme --}}
        @include('backoffice.vehicles.partials._footer')
    </div>
    <!-- Delete Vehicle Modal (même design que rôles) -->
<div class="modal fade" id="deleteVehicleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="deleteVehicleForm">
                @csrf
                @method('DELETE')
                <div class="modal-body text-center">
                    <span class="avatar avatar-lg bg-transparent-danger rounded-circle text-danger mb-3">
                        <i class="ti ti-trash-x fs-26"></i>
                    </span>
                    <h4 class="mb-1">Supprimer le véhicule</h4>
                    <p class="mb-3" id="deleteVehicleText">Êtes-vous sûr de vouloir supprimer ce véhicule ?</p>
                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">Oui, supprimer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function showDeleteModal(vehicleId, vehicleName, deleteUrl) {
    // Mettre à jour le texte
    const deleteText = document.getElementById('deleteVehicleText');
    deleteText.textContent = `Êtes-vous sûr de vouloir supprimer le véhicule « ${vehicleName} » ?`;
    
    // Mettre à jour l'action du formulaire
    const deleteForm = document.getElementById('deleteVehicleForm');
    deleteForm.action = deleteUrl;
    
    // Afficher le modal
    const modal = new bootstrap.Modal(document.getElementById('deleteVehicleModal'));
    modal.show();
}
</script>
@endsection

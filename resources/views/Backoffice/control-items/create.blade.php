<?php $page = 'control-items'; ?>
@extends('layout.mainlayout_admin')

@section('content')
<div class="page-wrapper">
    <div class="content me-0">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="mb-3">
                    <a href="{{ route('backoffice.vehicles.controls.items.index', [$vehicle->id, $control->id]) }}" class="d-inline-flex align-items-center fw-medium">
                        <i class="ti ti-arrow-left me-1"></i>
                        Retour à la liste
                    </a>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="ti ti-checklist me-2"></i>
                            Ajouter un élément de contrôle
                        </h4>
                        <p class="text-muted mb-0 mt-1">
                            Contrôle: <strong>{{ $control->control_number }}</strong> - Véhicule: <strong>{{ $vehicle->registration_number }}</strong>
                        </p>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('backoffice.vehicles.controls.items.store', [$vehicle->id, $control->id]) }}" 
                              method="POST" 
                              class="needs-validation" 
                              novalidate>
                            @csrf

                            <div class="row">
                                <!-- Item Key -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            Clé <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               name="item_key"
                                               value="{{ old('item_key') }}"
                                               class="form-control @error('item_key') is-invalid @enderror"
                                               required
                                               maxlength="100"
                                               placeholder="Ex: freins, pneus, lumières...">
                                        @error('item_key')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Label -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            Libellé
                                        </label>
                                        <input type="text"
                                               name="label"
                                               value="{{ old('label') }}"
                                               class="form-control @error('label') is-invalid @enderror"
                                               maxlength="150"
                                               placeholder="État des freins, Pression des pneus...">
                                        @error('label')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            Statut <span class="text-danger">*</span>
                                        </label>
                                        <select name="status" 
                                                class="form-select @error('status') is-invalid @enderror" 
                                                required>
                                            <option value="">Sélectionner un statut</option>
                                            <option value="yes" {{ old('status') == 'yes' ? 'selected' : '' }}>Oui (Conforme)</option>
                                            <option value="no" {{ old('status') == 'no' ? 'selected' : '' }}>Non (Non conforme)</option>
                                            <option value="na" {{ old('status') == 'na' ? 'selected' : '' }}>N/A (Non applicable)</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Comment -->
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            Commentaire
                                        </label>
                                        <textarea name="comment"
                                                  class="form-control @error('comment') is-invalid @enderror"
                                                  rows="4"
                                                  maxlength="255"
                                                  placeholder="Informations complémentaires...">{{ old('comment') }}</textarea>
                                        @error('comment')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-3 mt-4">
                                <a href="{{ route('backoffice.vehicles.controls.items.index', [$vehicle->id, $control->id]) }}" class="btn btn-light px-4">
                                    <i class="ti ti-x me-1"></i>
                                    Annuler
                                </a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="ti ti-device-floppy me-1"></i>
                                    Créer l'élément
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Bootstrap validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
});
</script>
@endpush
@endsection
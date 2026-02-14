<?php $page = 'controls'; ?>
@extends('layout.mainlayout_admin')

@section('content')
<div class="page-wrapper">
    <div class="content me-0">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="mb-3">
                    <a href="{{ route('backoffice.vehicles.controls.index', $vehicle->id ?? 1) }}" class="d-inline-flex align-items-center fw-medium">
                        <i class="ti ti-arrow-left me-1"></i>
                        Retour à la liste
                    </a>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="ti ti-clipboard-plus me-2"></i>
                            Ajouter un contrôle
                        </h4>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('backoffice.vehicles.controls.store') }}" 
                              method="POST" 
                              class="needs-validation" 
                              novalidate>
                            @csrf

                            <div class="row">
                                <!-- Vehicle Selection -->
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            Véhicule <span class="text-danger">*</span>
                                        </label>
                                        <select name="vehicle_id" 
                                                id="vehicle_select"
                                                class="form-select @error('vehicle_id') is-invalid @enderror" 
                                                required>
                                            <option value="">Sélectionner un véhicule</option>
                                            @foreach($vehicles as $v)
                                                <option value="{{ $v->id }}" 
                                                    {{ old('vehicle_id', $vehicle->id ?? '') == $v->id ? 'selected' : '' }}>
                                                    {{ $v->registration_number }} - {{ $v->registration_city ?? '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('vehicle_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Agency -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            Agence <span class="text-danger">*</span>
                                        </label>
                                        <select name="agency_id" 
                                                class="form-select @error('agency_id') is-invalid @enderror" 
                                                required>
                                            <option value="">Sélectionner une agence</option>
                                            @foreach($agencies as $agency)
                                                <option value="{{ $agency->id }}" {{ old('agency_id') == $agency->id ? 'selected' : '' }}>
                                                    {{ $agency->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('agency_id')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Control Number -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            Numéro de contrôle <span class="text-danger">*</span>
                                        </label>
                                        <input type="text"
                                               name="control_number"
                                               value="{{ old('control_number') }}"
                                               class="form-control @error('control_number') is-invalid @enderror"
                                               required
                                               maxlength="50"
                                               placeholder="CTRL-{{ date('Ymd') }}-001">
                                        @error('control_number')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Performed By -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            Effectué par
                                        </label>
                                        <select name="performed_by" 
                                                class="form-select @error('performed_by') is-invalid @enderror">
                                            <option value="">Sélectionner un agent</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ old('performed_by') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('performed_by')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Start Mileage -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            Kilométrage départ <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="number"
                                                   name="start_mileage"
                                                   value="{{ old('start_mileage') }}"
                                                   class="form-control @error('start_mileage') is-invalid @enderror"
                                                   required
                                                   min="0"
                                                   max="9999999"
                                                   placeholder="125000">
                                            <span class="input-group-text">km</span>
                                        </div>
                                        @error('start_mileage')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- End Mileage -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            Kilométrage arrivée
                                        </label>
                                        <div class="input-group">
                                            <input type="number"
                                                   name="end_mileage"
                                                   value="{{ old('end_mileage') }}"
                                                   class="form-control @error('end_mileage') is-invalid @enderror"
                                                   min="0"
                                                   max="9999999"
                                                   placeholder="125050">
                                            <span class="input-group-text">km</span>
                                        </div>
                                        @error('end_mileage')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Laissez vide si le contrôle est en cours</small>
                                    </div>
                                </div>

                                <!-- Notes -->
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Notes</label>
                                        <textarea name="notes"
                                                  class="form-control @error('notes') is-invalid @enderror"
                                                  rows="4"
                                                  placeholder="Informations complémentaires...">{{ old('notes') }}</textarea>
                                        @error('notes')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-3 mt-4">
                                <a href="{{ route('backoffice.vehicles.controls.index', $vehicle->id ?? 1) }}" class="btn btn-light px-4">
                                    <i class="ti ti-x me-1"></i>
                                    Annuler
                                </a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="ti ti-device-floppy me-1"></i>
                                    Créer le contrôle
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
<?php $page = 'rental-contracts'; ?>
@extends('layout.mainlayout_admin')

@section('content')
<style>
    .wizard-nav {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 2rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 10px;
    }
    .wizard-nav .nav-item {
        flex: 1;
        min-width: 150px;
    }
    .wizard-nav .nav-link {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        background: white;
        border-radius: 8px;
        color: #6c757d;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s;
        border: 1px solid #dee2e6;
        cursor: pointer;
    }
    .wizard-nav .nav-link i {
        margin-right: 8px;
        font-size: 1.2rem;
    }
    .wizard-nav .nav-link.active {
        background: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }
    .fieldset {
        display: none;
    }
    .fieldset.active {
        display: block;
    }
</style>

<div class="page-wrapper">
    <div class="content me-0">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="mb-3">
                    <a href="{{ route('backoffice.rental-contracts.index') }}" class="d-inline-flex align-items-center fw-medium">
                        <i class="ti ti-arrow-left me-1"></i> Retour à la liste
                    </a>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="ti ti-file-plus me-2"></i>
                            Nouveau contrat de location
                        </h4>
                    </div>

                    <div class="card-body">
                        <!-- Wizard Navigation -->
                        <div class="wizard-nav">
                            <div class="nav-item">
                                <a class="nav-link active" data-tab="1">
                                    <i class="ti ti-user"></i>
                                    Client & Véhicule
                                </a>
                            </div>
                            <div class="nav-item">
                                <a class="nav-link" data-tab="2">
                                    <i class="ti ti-calendar"></i>
                                    Dates & Lieux
                                </a>
                            </div>
                            <div class="nav-item">
                                <a class="nav-link" data-tab="3">
                                    <i class="ti ti-currency-dollar"></i>
                                    Tarifs & Observations
                                </a>
                            </div>
                        </div>

                        <form action="{{ route('backoffice.rental-contracts.store') }}" method="POST" class="needs-validation" novalidate>
                            @csrf

                            <!-- Tab 1: Client & Véhicule -->
                            <fieldset class="fieldset active" id="tab1">
                                <div class="row">
                                    <!-- Contract Number (Auto-generated) -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">N° Contrat</label>
                                            <input type="text" class="form-control" value="{{ $contractNumber }}" readonly>
                                            <small class="text-muted">Généré automatiquement</small>
                                        </div>
                                    </div>

                                    <!-- Vehicle -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Véhicule <span class="text-danger">*</span>
                                            </label>
                                            <select name="vehicle_id" class="form-select @error('vehicle_id') is-invalid @enderror" required>
                                                <option value="">Sélectionner un véhicule</option>
                                                @foreach($vehicles as $vehicle)
                                                    <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                                        {{ $vehicle->registration_number }} - {{ $vehicle->model->name ?? 'N/C' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('vehicle_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Primary Client -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Client principal <span class="text-danger">*</span>
                                            </label>
                                            <select name="primary_client_id" class="form-select @error('primary_client_id') is-invalid @enderror" required>
                                                <option value="">Sélectionner un client</option>
                                                @foreach($clients as $client)
                                                    <option value="{{ $client->id }}" {{ old('primary_client_id') == $client->id ? 'selected' : '' }}>
                                                        {{ $client->first_name }} {{ $client->last_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('primary_client_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Secondary Client -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Client secondaire</label>
                                            <select name="secondary_client_id" class="form-select @error('secondary_client_id') is-invalid @enderror">
                                                <option value="">Aucun</option>
                                                @foreach($clients as $client)
                                                    <option value="{{ $client->id }}" {{ old('secondary_client_id') == $client->id ? 'selected' : '' }}>
                                                        {{ $client->first_name }} {{ $client->last_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('secondary_client_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="button" class="btn btn-primary next-tab" data-next="2">
                                        Suivant <i class="ti ti-chevron-right ms-1"></i>
                                    </button>
                                </div>
                            </fieldset>

                            <!-- Tab 2: Dates & Lieux -->
                            <fieldset class="fieldset" id="tab2">
                                <div class="row">
                                    <!-- Start Date -->
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Date de début <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" name="start_date" value="{{ old('start_date') }}" 
                                                   class="form-control @error('start_date') is-invalid @enderror" 
                                                   min="{{ date('Y-m-d') }}" required>
                                            @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Start Time -->
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Heure de début <span class="text-danger">*</span>
                                            </label>
                                            <input type="time" name="start_time" value="{{ old('start_time', '10:00') }}" 
                                                   class="form-control @error('start_time') is-invalid @enderror" required>
                                            @error('start_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- End Date -->
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Date de fin <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" name="end_date" value="{{ old('end_date') }}" 
                                                   class="form-control @error('end_date') is-invalid @enderror" required>
                                            @error('end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- End Time -->
                                    <div class="col-md-3">
                                        <div class="mb-3">
                                            <label class="form-label">Heure de fin</label>
                                            <input type="time" name="end_time" value="{{ old('end_time', '10:00') }}" 
                                                   class="form-control @error('end_time') is-invalid @enderror">
                                            @error('end_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Optionnel</small>
                                        </div>
                                    </div>

                                    <!-- Pickup Location -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Lieu de prise en charge <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="pickup_location" value="{{ old('pickup_location') }}" 
                                                   class="form-control @error('pickup_location') is-invalid @enderror" 
                                                   placeholder="Adresse de prise en charge" required>
                                            @error('pickup_location')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Dropoff Location -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Lieu de restitution <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="dropoff_location" value="{{ old('dropoff_location') }}" 
                                                   class="form-control @error('dropoff_location') is-invalid @enderror" 
                                                   placeholder="Adresse de restitution" required>
                                            @error('dropoff_location')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-light prev-tab" data-prev="1">
                                        <i class="ti ti-chevron-left me-1"></i> Précédent
                                    </button>
                                    <button type="button" class="btn btn-primary next-tab" data-next="3">
                                        Suivant <i class="ti ti-chevron-right ms-1"></i>
                                    </button>
                                </div>
                            </fieldset>

                            <!-- Tab 3: Tarifs & Observations -->
                            <fieldset class="fieldset" id="tab3">
                                <div class="row">
                                    <!-- Daily Rate -->
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Tarif journalier (MAD) <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="number" name="daily_rate" value="{{ old('daily_rate') }}" 
                                                       class="form-control @error('daily_rate') is-invalid @enderror" 
                                                       step="0.01" min="0" required>
                                                <span class="input-group-text">MAD</span>
                                            </div>
                                            @error('daily_rate')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Discount -->
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Remise (MAD)</label>
                                            <div class="input-group">
                                                <input type="number" name="discount_amount" value="{{ old('discount_amount', 0) }}" 
                                                       class="form-control @error('discount_amount') is-invalid @enderror" 
                                                       step="0.01" min="0">
                                                <span class="input-group-text">MAD</span>
                                            </div>
                                            @error('discount_amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Deposit -->
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Dépôt de garantie (MAD)</label>
                                            <div class="input-group">
                                                <input type="number" name="deposit_amount" value="{{ old('deposit_amount') }}" 
                                                       class="form-control @error('deposit_amount') is-invalid @enderror" 
                                                       step="0.01" min="0">
                                                <span class="input-group-text">MAD</span>
                                            </div>
                                            @error('deposit_amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Statut initial</label>
                                            <select name="status" class="form-select @error('status') is-invalid @enderror">
                                                <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Brouillon</option>
                                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Source -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Source</label>
                                            <select name="source" class="form-select @error('source') is-invalid @enderror">
                                                <option value="backoffice" {{ old('source', 'backoffice') == 'backoffice' ? 'selected' : '' }}>Backoffice</option>
                                                <option value="website" {{ old('source') == 'website' ? 'selected' : '' }}>Site web</option>
                                                <option value="mobile" {{ old('source') == 'mobile' ? 'selected' : '' }}>Application mobile</option>
                                                <option value="other" {{ old('source') == 'other' ? 'selected' : '' }}>Autre</option>
                                            </select>
                                            @error('source')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Observations -->
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Observations</label>
                                            <textarea name="observations" class="form-control @error('observations') is-invalid @enderror" 
                                                      rows="4" placeholder="Informations complémentaires...">{{ old('observations') }}</textarea>
                                            @error('observations')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-light prev-tab" data-prev="2">
                                        <i class="ti ti-chevron-left me-1"></i> Précédent
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-1"></i> Créer le contrat
                                    </button>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab Navigation
    const tabs = document.querySelectorAll('.nav-link[data-tab]');
    const fieldsets = document.querySelectorAll('.fieldset');
    
    function showTab(tabNumber) {
        fieldsets.forEach(f => f.classList.remove('active'));
        document.getElementById(`tab${tabNumber}`).classList.add('active');
        
        tabs.forEach(t => t.classList.remove('active'));
        document.querySelector(`.nav-link[data-tab="${tabNumber}"]`).classList.add('active');
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            showTab(this.getAttribute('data-tab'));
        });
    });

    document.querySelectorAll('.next-tab').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            showTab(this.getAttribute('data-next'));
        });
    });

    document.querySelectorAll('.prev-tab').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            showTab(this.getAttribute('data-prev'));
        });
    });

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
@endsection
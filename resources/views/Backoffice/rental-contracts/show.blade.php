<?php $page = 'rental-contract-details'; ?>
@extends('layout.mainlayout_admin')

@section('content')
<style>
    .info-label {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }
    .info-value {
        font-weight: 500;
        margin-bottom: 1rem;
    }
    .badge-draft { background: #e2e3e5; color: #383d41; }
    .badge-pending { background: #fff3cd; color: #856404; }
    .badge-accepted { background: #cce5ff; color: #004085; }
    .badge-in_progress { background: #d1ecf1; color: #0c5460; }
    .badge-completed { background: #d4edda; color: #155724; }
    .badge-cancelled { background: #f8d7da; color: #721c24; }
    .amount-display {
        font-size: 1.5rem;
        font-weight: 600;
        color: #198754;
    }
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
    .info-panel {
        display: none;
    }
    .info-panel.active {
        display: block;
    }
</style>

<div class="page-wrapper">
    <div class="content me-0">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <a href="{{ route('backoffice.rental-contracts.index') }}" class="d-inline-flex align-items-center fw-medium">
                        <i class="ti ti-arrow-left me-1"></i> Retour à la liste
                    </a>
                    <div>
                        <a href="{{ route('backoffice.rental-contracts.edit', $rentalContract) }}" class="btn btn-primary">
                            <i class="ti ti-edit me-1"></i>Modifier
                        </a>
                    </div>
                </div>

                <!-- Header Card -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div class="d-flex align-items-center">
                                <span class="avatar avatar-lg me-3" style="border-radius: 10px; background-color: #f0f3f8;">
                                    <span class="avatar-title fw-bold fs-24 text-primary">
                                        <i class="ti ti-file-text"></i>
                                    </span>
                                </span>
                                <div>
                                    <h4 class="mb-1">{{ $rentalContract->contract_number }}</h4>
                                    <p class="mb-0 text-muted">
                                        <i class="ti ti-calendar me-1"></i>
                                        Créé le {{ $rentalContract->created_at->format('d/m/Y à H:i') }}
                                    </p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge badge-{{ str_replace('_', '-', $rentalContract->status) }} fs-6 p-2">
                                    {{ $rentalContract->status_text }}
                                </span>
                                <span class="badge {{ $rentalContract->acceptance_badge_class }} fs-6 p-2">
                                    {{ $rentalContract->acceptance_text }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Tabs -->
                <div class="wizard-nav">
                    <div class="nav-item">
                        <a class="nav-link active" data-panel="1">
                            <i class="ti ti-user"></i>
                            Client & Véhicule
                        </a>
                    </div>
                    <div class="nav-item">
                        <a class="nav-link" data-panel="2">
                            <i class="ti ti-calendar"></i>
                            Dates & Lieux
                        </a>
                    </div>
                    <div class="nav-item">
                        <a class="nav-link" data-panel="3">
                            <i class="ti ti-currency-dollar"></i>
                            Tarifs & Observations
                        </a>
                    </div>
                </div>

                <!-- Panel 1: Client & Véhicule -->
                <div class="info-panel active" id="panel1">
                    <div class="row">
                        <!-- Client Information -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="ti ti-user me-2"></i>
                                        Client principal
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-label">Nom</div>
                                            <div class="info-value">
                                                <a href="{{ route('backoffice.clients.show', $rentalContract->primary_client_id) }}">
                                                    {{ $rentalContract->primaryClient->first_name ?? '' }} {{ $rentalContract->primaryClient->last_name ?? '' }}
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-label">Téléphone</div>
                                            <div class="info-value">{{ $rentalContract->primaryClient->phone ?? 'N/A' }}</div>
                                        </div>
                                        @if($rentalContract->primaryClient && $rentalContract->primaryClient->email)
                                        <div class="col-md-12">
                                            <div class="info-label">Email</div>
                                            <div class="info-value">{{ $rentalContract->primaryClient->email }}</div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if($rentalContract->secondaryClient)
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="ti ti-users me-2"></i>
                                        Client secondaire
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-label">Nom</div>
                                            <div class="info-value">
                                                <a href="{{ route('backoffice.clients.show', $rentalContract->secondary_client_id) }}">
                                                    {{ $rentalContract->secondaryClient->first_name ?? '' }} {{ $rentalContract->secondaryClient->last_name ?? '' }}
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-label">Téléphone</div>
                                            <div class="info-value">{{ $rentalContract->secondaryClient->phone ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Vehicle Information -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="ti ti-car me-2"></i>
                                        Véhicule
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-label">Véhicule</div>
                                            <div class="info-value">
                                                <a href="{{ route('backoffice.vehicles.show', $rentalContract->vehicle_id) }}">
                                                    {{ $rentalContract->vehicle->registration_number ?? 'N/A' }}
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-label">Modèle</div>
                                            <div class="info-value">{{ $rentalContract->vehicle->model->name ?? 'N/C' }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-label">Kilométrage</div>
                                            <div class="info-value">{{ number_format($rentalContract->vehicle->current_mileage ?? 0) }} km</div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-label">Statut</div>
                                            <div class="info-value">
                                                <span class="badge {{ $rentalContract->vehicle->status_badge_class ?? 'badge-secondary' }}">
                                                    {{ $rentalContract->vehicle->status_text ?? 'N/A' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel 2: Dates & Lieux -->
                <div class="info-panel" id="panel2">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="ti ti-calendar me-2"></i>
                                        Dates et heures
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-label">Début</div>
                                            <div class="info-value">
                                                {{ $rentalContract->formatted_start_date }} à {{ $rentalContract->formatted_start_time }}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-label">Fin</div>
                                            <div class="info-value">
                                                {{ $rentalContract->formatted_end_date }} à {{ $rentalContract->formatted_end_time }}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-label">Durée prévue</div>
                                            <div class="info-value">{{ $rentalContract->planned_days }} jour(s)</div>
                                        </div>
                                        @if($rentalContract->actual_start_at)
                                        <div class="col-md-6">
                                            <div class="info-label">Début réel</div>
                                            <div class="info-value">{{ $rentalContract->actual_start_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                        @endif
                                        @if($rentalContract->actual_end_at)
                                        <div class="col-md-6">
                                            <div class="info-label">Fin réelle</div>
                                            <div class="info-value">{{ $rentalContract->actual_end_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="ti ti-map-pin me-2"></i>
                                        Lieux
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-label">Prise en charge</div>
                                            <div class="info-value">{{ $rentalContract->pickup_location }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-label">Restitution</div>
                                            <div class="info-value">{{ $rentalContract->dropoff_location }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel 3: Tarifs & Observations -->
                <div class="info-panel" id="panel3">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="ti ti-currency-dollar me-2"></i>
                                        Informations financières
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-label">Tarif journalier</div>
                                            <div class="info-value">{{ $rentalContract->formatted_daily_rate }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-label">Remise</div>
                                            <div class="info-value">{{ number_format($rentalContract->discount_amount, 2) }} MAD</div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-label">Total</div>
                                            <div class="info-value amount-display">{{ $rentalContract->formatted_total_amount }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-label">Dépôt de garantie</div>
                                            <div class="info-value">{{ $rentalContract->formatted_deposit }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            @if($rentalContract->observations)
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="ti ti-notes me-2"></i>
                                        Observations
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $rentalContract->observations }}</p>
                                </div>
                            </div>
                            @endif

                            @if($rentalContract->cancelled_at)
                            <div class="card mb-4 border-danger">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="card-title mb-0">
                                        <i class="ti ti-alert-triangle me-2"></i>
                                        Annulation
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-label">Date d'annulation</div>
                                            <div class="info-value">{{ $rentalContract->cancelled_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-label">Raison</div>
                                            <div class="info-value">{{ $rentalContract->cancellation_reason }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Panel Navigation
    const panels = document.querySelectorAll('.nav-link[data-panel]');
    const infoPanels = document.querySelectorAll('.info-panel');
    
    function showPanel(panelNumber) {
        infoPanels.forEach(p => p.classList.remove('active'));
        document.getElementById(`panel${panelNumber}`).classList.add('active');
        
        panels.forEach(p => p.classList.remove('active'));
        document.querySelector(`.nav-link[data-panel="${panelNumber}"]`).classList.add('active');
    }

    panels.forEach(panel => {
        panel.addEventListener('click', function(e) {
            e.preventDefault();
            showPanel(this.getAttribute('data-panel'));
        });
    });
});
</script>

@include('backoffice.rental-contracts.partials._modal_delete')
@endsection
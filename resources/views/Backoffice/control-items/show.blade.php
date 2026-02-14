<?php $page = 'control-item-details'; ?>
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
    .badge-success {
        background: #d4edda;
        color: #155724;
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-weight: 500;
    }
    .badge-danger {
        background: #f8d7da;
        color: #721c24;
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-weight: 500;
    }
    .badge-secondary {
        background: #e2e3e5;
        color: #383d41;
        padding: 0.35rem 0.75rem;
        border-radius: 50px;
        font-weight: 500;
    }
</style>

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
                        <h4 class="mb-0">Détails de l'élément de contrôle</h4>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4">
                            <div class="d-flex align-items-center">
                                <span class="avatar avatar-lg me-3" style="border-radius: 10px; background-color: #f0f3f8;">
                                    <span class="avatar-title fw-bold fs-24 text-primary">
                                        <i class="ti ti-checklist"></i>
                                    </span>
                                </span>

                                <div>
                                    <h5 class="mb-1">{{ $item->item_key }}</h5>
                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                        <span class="{{ $item->status_badge_class }}">
                                            {{ $item->status_text }}
                                        </span>
                                        <span class="text-muted">
                                            <i class="ti ti-clipboard-list me-1"></i>
                                            {{ $control->control_number }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('backoffice.vehicles.controls.items.edit', [$vehicle->id, $control->id, $item->id]) }}" 
                                   class="btn btn-primary">
                                    <i class="ti ti-edit me-1"></i>
                                    Modifier
                                </a>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="info-label">Clé</div>
                                <div class="info-value"><code>{{ $item->item_key }}</code></div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Libellé</div>
                                <div class="info-value">{{ $item->label ?? '—' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Statut</div>
                                <div class="info-value">
                                    <span class="{{ $item->status_badge_class }}">
                                        {{ $item->status_text }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Date de création</div>
                                <div class="info-value">{{ $item->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                            @if($item->comment)
                            <div class="col-12">
                                <div class="info-label">Commentaire</div>
                                <div class="info-value p-3 bg-light rounded">{{ $item->comment }}</div>
                            </div>
                            @endif
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <hr>
                                <h6 class="mb-3">Informations du contrôle parent</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="info-label">Véhicule</div>
                                        <div class="info-value">
                                            <a href="{{ route('backoffice.vehicles.show', $vehicle->id) }}">
                                                {{ $vehicle->registration_number }}
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-label">Contrôle</div>
                                        <div class="info-value">
                                            <a href="{{ route('backoffice.vehicles.controls.show', [$vehicle->id, $control->id]) }}">
                                                {{ $control->control_number }}
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-label">Agence</div>
                                        <div class="info-value">{{ $control->agency->name ?? '—' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer d-sm-flex align-items-center justify-content-between bg-white p-3">
        <p class="mb-0">2025 © Dreamsrent. All rights reserved.</p>
        <p class="mb-0">v1.0</p>
    </div>
</div>

@include('Backoffice.control-items.partials._modal_delete')
@endsection
<?php $page = 'control-details'; ?>
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
    .stat-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        text-align: center;
    }
    .stat-value {
        font-size: 1.5rem;
        font-weight: 600;
        color: #0d6efd;
    }
    .stat-label {
        font-size: 0.85rem;
        color: #6c757d;
    }
</style>

<div class="page-wrapper">
    <div class="content me-0">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="mb-3">
                    <a href="{{ route('backoffice.vehicles.controls.index', $vehicle->id) }}" class="d-inline-flex align-items-center fw-medium">
                        <i class="ti ti-arrow-left me-1"></i>
                        Retour à la liste
                    </a>
                </div>

                {{-- BASIC CARD --}}
                <div class="card">
                    <div class="card-body">
                        <div class="border-bottom mb-3 pb-3">
                            <h5>Détails du contrôle</h5>
                        </div>

                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div class="d-flex align-items-center">
                                <span class="avatar avatar-lg me-3" style="border-radius: 10px; background-color: #f0f3f8;">
                                    <span class="avatar-title fw-bold fs-24 text-primary">
                                        <i class="ti ti-clipboard-list"></i>
                                    </span>
                                </span>

                                <div>
                                    <h6 class="mb-1">{{ $control->control_number }}</h6>
                                    <div class="d-flex align-items-center flex-wrap gap-2">
                                        <span class="badge {{ $control->status_badge_class }} text-white">
                                            {{ $control->status }}
                                        </span>
                                        <span class="text-muted">
                                            <i class="ti ti-car me-1"></i>
                                            {{ $vehicle->registration_number }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('backoffice.vehicles.controls.items.index', [$vehicle->id, $control->id]) }}" 
                                   class="btn btn-outline-primary">
                                    <i class="ti ti-checklist me-1"></i>
                                    Éléments ({{ $control->items_count }})
                                </a>
                                <a href="{{ route('backoffice.vehicles.controls.edit', [$vehicle->id, $control->id]) }}" 
                                   class="btn btn-primary">
                                    <i class="ti ti-edit me-1"></i>
                                    Modifier
                                </a>
                            </div>
                        </div>

                        {{-- Statistics --}}
                        <div class="row mt-4">
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <div class="stat-value">{{ $control->items_count }}</div>
                                    <div class="stat-label">Total éléments</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <div class="stat-value text-success">{{ $control->passed_items_count }}</div>
                                    <div class="stat-label">Conformes</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <div class="stat-value text-danger">{{ $control->failed_items_count }}</div>
                                    <div class="stat-label">Non conformes</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-card">
                                    <div class="stat-value">{{ $control->formatted_mileage_difference }}</div>
                                    <div class="stat-label">Distance parcourue</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- DETAILS CARD --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Informations détaillées</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="info-label">Agence</div>
                                <div class="info-value">{{ $control->agency->name ?? '—' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Effectué par</div>
                                <div class="info-value">{{ $control->performer->name ?? '—' }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Date de création</div>
                                <div class="info-value">{{ $control->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Kilométrage départ</div>
                                <div class="info-value">{{ $control->formatted_start_mileage }}</div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Kilométrage arrivée</div>
                                <div class="info-value">{{ $control->formatted_end_mileage }}</div>
                            </div>
                            @if($control->notes)
                            <div class="col-12">
                                <div class="info-label">Notes</div>
                                <div class="info-value p-3 bg-light rounded">{{ $control->notes }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ITEMS TABLE --}}
                @if($control->items->count() > 0)
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Éléments de contrôle</h5>
                        <a href="{{ route('backoffice.vehicles.controls.items.create', [$vehicle->id, $control->id]) }}" 
                           class="btn btn-sm btn-primary">
                            <i class="ti ti-plus me-1"></i>
                            Ajouter un élément
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Clé</th>
                                        <th>Libellé</th>
                                        <th>Statut</th>
                                        <th>Commentaire</th>
                                        <th width="80">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($control->items as $item)
                                    <tr>
                                        <td><code>{{ $item->item_key }}</code></td>
                                        <td>{{ $item->label ?? $item->item_key }}</td>
                                        <td>
                                            <span class="{{ $item->status_badge_class }}">
                                                {{ $item->status_text }}
                                            </span>
                                        </td>
                                        <td>{{ $item->comment ?? '—' }}</td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-icon btn-sm" type="button" data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end p-2">
                                                    <li>
                                                        <a class="dropdown-item" 
                                                           href="{{ route('backoffice.vehicles.controls.items.show', [$vehicle->id, $control->id, $item->id]) }}">
                                                            <i class="ti ti-eye me-2"></i>Voir
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" 
                                                           href="{{ route('backoffice.vehicles.controls.items.edit', [$vehicle->id, $control->id, $item->id]) }}">
                                                            <i class="ti ti-edit me-2"></i>Modifier
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item text-danger" 
                                                           href="javascript:void(0);"
                                                           data-bs-toggle="modal" 
                                                           data-bs-target="#delete_control_item"
                                                           data-delete-action="{{ route('backoffice.vehicles.controls.items.destroy', [$vehicle->id, $control->id, $item->id]) }}"
                                                           data-delete-details="Élément <strong>{{ $item->item_key }}</strong>">
                                                            <i class="ti ti-trash me-2"></i>Supprimer
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>

    <div class="footer d-sm-flex align-items-center justify-content-between bg-white p-3">
        <p class="mb-0">2025 © Dreamsrent. All rights reserved.</p>
        <p class="mb-0">v1.0</p>
    </div>
</div>

@include('Backoffice.controls.partials._modal_delete')
@endsection
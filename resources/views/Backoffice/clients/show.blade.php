<?php $page = 'client-details'; ?>
@extends('layout.mainlayout_admin')

@section('content')
<div class="page-wrapper">
    <div class="content me-0">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="mb-3">
                    <a href="{{ route('backoffice.clients.index') }}" class="d-inline-flex align-items-center fw-medium">
                        <i class="ti ti-arrow-left me-1"></i>Clients
                    </a>
                </div>

                {{-- BASIC CARD --}}
                <div class="card">
                    <div class="card-body">
                        <div class="border-bottom mb-3 pb-3">
                            <h5>Détails de base</h5>
                        </div>

                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div class="d-flex align-items-center">
                                <span class="avatar avatar-lg me-3" style="border-radius: 10px; overflow:hidden; background-color: #f0f3f8;">
                                    @if($client->avatar_url)
                                        <img src="{{ $client->avatar_url }}" 
                                             alt="{{ $client->full_name }}"
                                             style="width:100%; height:100%; object-fit:cover;">
                                    @else
                                        <span class="avatar-title fw-bold fs-24 text-primary">
                                            {{ $client->avatar_initials }}
                                        </span>
                                    @endif
                                </span>

                                <div>
                                    <h6 class="mb-1">{{ $client->full_name }}</h6>
                                    <div class="d-flex align-items-center">
                                        <p class="mb-0 me-2">
                                            <i class="ti ti-building me-1"></i>
                                            {{ $client->agency->name ?? '—' }}
                                        </p>
                                        <p class="mb-0">
                                            Client depuis :
                                            {{ optional($client->created_at)->format('d M Y') ?: '—' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex align-items-center flex-wrap gap-3">
                                @if($client->status == 'active')
                                    <span class="badge badge-md bg-success-transparent">
                                        <i class="ti ti-point-filled text-success me-1"></i>Actif
                                    </span>
                                @elseif($client->status == 'inactive')
                                    <span class="badge badge-md bg-danger-transparent">
                                        <i class="ti ti-point-filled text-danger me-1"></i>Inactif
                                    </span>
                                @else
                                    <span class="badge badge-md bg-dark-transparent">
                                        <i class="ti ti-point-filled text-dark me-1"></i>Blacklisté
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TABS CARD --}}
                <div class="card mb-4 mb-xl-0">
                    <div class="card-header py-0">
                        <ul class="nav nav-tabs nav-tabs-bottom tab-dark">
                            <li class="nav-item">
                                <a class="nav-link active" href="#client-overview" data-bs-toggle="tab">Overview</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#client-documents" data-bs-toggle="tab">Documents</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#client-notes" data-bs-toggle="tab">Notes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#client-history" data-bs-toggle="tab">History</a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content">

                            {{-- OVERVIEW --}}
                            <div class="tab-pane fade active show" id="client-overview">
                                <div class="border-bottom mb-3 pb-3">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            <div class="mb-3">
                                                <h6 class="fs-14 fw-semibold mb-1">Prénom</h6>
                                                <p class="fs-13">{{ $client->first_name }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="mb-3">
                                                <h6 class="fs-14 fw-semibold mb-1">Nom</h6>
                                                <p class="fs-13">{{ $client->last_name }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="mb-3">
                                                <h6 class="fs-14 fw-semibold mb-1">Email</h6>
                                                <p class="fs-13">{{ $client->email ?: '—' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="mb-3">
                                                <h6 class="fs-14 fw-semibold mb-1">Téléphone</h6>
                                                <p class="fs-13">{{ $client->phone ?: '—' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="mb-3">
                                                <h6 class="fs-14 fw-semibold mb-1">Date de naissance</h6>
                                                <p class="fs-13">{{ $client->birth_date ? $client->birth_date->format('d/m/Y') : '—' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="mb-3">
                                                <h6 class="fs-14 fw-semibold mb-1">Nationalité</h6>
                                                <p class="fs-13">{{ $client->nationality ?: '—' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="mb-3">
                                                <h6 class="fs-14 fw-semibold mb-1">Agence</h6>
                                                <p class="fs-13">{{ $client->agency->name ?? '—' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="mb-3">
                                                <h6 class="fs-14 fw-semibold mb-1">Adresse</h6>
                                                <p class="fs-13">{{ $client->address ?: '—' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <div class="mb-3">
                                                <h6 class="fs-14 fw-semibold mb-1">Ville</h6>
                                                <p class="fs-13">{{ $client->city ?: '—' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <div class="mb-3">
                                                <h6 class="fs-14 fw-semibold mb-1">Pays</h6>
                                                <p class="fs-13">{{ $client->country ?: '—' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6">
                                            <div class="mb-3">
                                                <h6 class="fs-14 fw-semibold mb-1">Statut</h6>
                                                <p class="fs-13">
                                                    @if($client->status == 'active')
                                                        <span class="badge bg-success">Actif</span>
                                                    @elseif($client->status == 'inactive')
                                                        <span class="badge bg-danger">Inactif</span>
                                                    @else
                                                        <span class="badge bg-dark">Blacklisté</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <h6 class="fw-semibold mb-2">Pièces d'identité</h6>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="text-muted fs-12">CIN :</span>
                                            <p class="fs-13 fw-medium">{{ $client->cin_number ?: '—' }}</p>
                                            @if($client->cin_valid_until)
                                                <small class="text-muted">Valide jusqu'au {{ $client->cin_valid_until->format('d/m/Y') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="text-muted fs-12">Passeport :</span>
                                            <p class="fs-13 fw-medium">{{ $client->passport_number ?: '—' }}</p>
                                            @if($client->passport_issue_date)
                                                <small class="text-muted">Délivré le {{ $client->passport_issue_date->format('d/m/Y') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="text-muted fs-12">Permis de conduire :</span>
                                            <p class="fs-13 fw-medium">{{ $client->driving_license_number ?: '—' }}</p>
                                            @if($client->driving_license_issue_date)
                                                <small class="text-muted">Délivré le {{ $client->driving_license_issue_date->format('d/m/Y') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-2">
                                            <span class="text-muted fs-12">Évaluation :</span>
                                            <p class="fs-13 fw-medium">
                                                @if($client->rating_average)
                                                    {{ number_format($client->rating_average, 1) }} / 5 ({{ $client->rating_count }} avis)
                                                @else
                                                    Pas encore évalué
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-lg-12">
<a href="{{ route('backoffice.clients.edit', $client) }}"
   class="btn btn-primary btn-sm d-inline-flex align-items-center">
    <i class="ti ti-edit me-1"></i>
    Modifier
</a>

                                    </div>
                                </div>
                            </div>
                            {{-- /OVERVIEW --}}

                            {{-- DOCUMENTS --}}
                            <div class="tab-pane fade" id="client-documents">
                                <div class="text-center py-4">
                                    <i class="ti ti-file-text fs-40 text-gray-4 mb-2"></i>
                                    <h6>Aucun document</h6>
                                    <p class="text-muted">Les documents du client apparaîtront ici</p>
                                </div>
                            </div>
                            {{-- /DOCUMENTS --}}

                            {{-- NOTES --}}
                            <div class="tab-pane fade" id="client-notes">
                                <div class="text-muted">
                                    <div class="d-flex align-items-center justify-content-between mb-3">
                                        <h6>Notes internes</h6>
                                        <a href="javascript:void(0);" 
                                           class="btn btn-sm btn-primary"
                                           data-bs-toggle="modal"
                                           data-bs-target="#edit_client"
                                           data-edit-action="{{ route('backoffice.clients.update', $client) }}"
                                           data-client-notes="{{ $client->notes }}">
                                            <i class="ti ti-edit me-1"></i>Éditer
                                        </a>
                                    </div>
                                    @if($client->notes)
                                        <div class="p-3 bg-light-100 rounded">
                                            {{ $client->notes }}
                                        </div>
                                    @else
                                        <div class="text-center py-5">
                                            <i class="ti ti-notes fs-40 text-gray-3 mb-2"></i>
                                            <p class="mb-0">Aucune note disponible</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            {{-- /NOTES --}}

                            {{-- HISTORY --}}
                            <div class="tab-pane fade" id="client-history">
                                <div class="activity-timeline">
                                    <div class="d-flex align-items-start mb-3">
                                        <span class="badge bg-success rounded-circle p-2 me-3 mt-1">
                                            <i class="ti ti-plus fs-12"></i>
                                        </span>
                                        <div>
                                            <p class="mb-1 fw-medium">Client créé</p>
                                            <small class="text-muted">{{ optional($client->created_at)->format('d M Y, H:i') }}</small>
                                        </div>
                                    </div>
                                    @if($client->updated_at && $client->updated_at != $client->created_at)
                                        <div class="d-flex align-items-start mb-3">
                                            <span class="badge bg-info rounded-circle p-2 me-3 mt-1">
                                                <i class="ti ti-edit fs-12"></i>
                                            </span>
                                            <div>
                                                <p class="mb-1 fw-medium">Dernière modification</p>
                                                <small class="text-muted">{{ $client->updated_at->format('d M Y, H:i') }}</small>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            {{-- /HISTORY --}}

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Footer-->
    <div class="footer d-sm-flex align-items-center justify-content-between bg-white p-3">
        <p class="mb-0">
            <a href="javascript:void(0);">Privacy Policy</a>
            <a href="javascript:void(0);" class="ms-4">Terms of Use</a>
        </p>
        <p>&copy; 2025 Dreamsrent, Made with <span class="text-danger">❤</span> by
            <a href="javascript:void(0);" class="text-secondary">Dreams</a>
        </p>
    </div>
    <!-- /Footer-->
</div>
<!-- /Page Wrapper -->

{{-- Include Modals --}}
@include('backoffice.clients.partials._modal_edit')
@include('backoffice.clients.partials._modal_delete')
@endsection
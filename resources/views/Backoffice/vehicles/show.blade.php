<?php $page = 'vehicle-details'; ?>

@extends('layout.mainlayout_admin')

@section('content')
<div class="page-wrapper">
    <div class="content me-0">

        <div class="mb-3">
            <a href="{{ route('backoffice.vehicles.index') }}"
               class="d-inline-flex align-items-center fw-medium">
                <i class="ti ti-arrow-left me-1"></i> Véhicules
            </a>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">Détails du véhicule</h5>

                <p><strong>Matricule :</strong> {{ $vehicle->registration_number }}</p>
                <p><strong>Année :</strong> {{ $vehicle->year }}</p>
                <p><strong>Couleur :</strong> {{ $vehicle->color }}</p>
                <p><strong>Kilométrage :</strong> {{ number_format($vehicle->current_mileage) }} km</p>
                <p><strong>Status :</strong> {{ ucfirst($vehicle->status) }}</p>
                <p><strong>Prix / jour :</strong> {{ number_format($vehicle->daily_rate, 2) }} MAD</p>

            </div>
        </div>

    </div>
</div>
@endsection

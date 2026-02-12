<?php $page = 'vignettes'; ?>
@extends('layout.mainlayout_admin')

@section('content')
<div class="page-wrapper">
    <div class="content me-4">

        @include('Backoffice.vignettes.partials._breadcrumbs', ['vehicle' => $vehicle])

        <!-- FILTER + SEARCH FORM -->
        <form method="GET" id="filterForm">

            <div class="d-flex align-items-center justify-content-between flex-wrap row-gap-3 mb-3">

                <div class="d-flex align-items-center flex-wrap row-gap-3">

                    <!-- SORT -->
                    <div class="dropdown me-2">
                        <a href="#" class="dropdown-toggle btn btn-white d-inline-flex align-items-center"
                           data-bs-toggle="dropdown">
                            <i class="ti ti-filter me-1"></i>
                            Trier :
                            @if(request('sort') == 'oldest')
                                Plus anciennes
                            @elseif(request('sort') == 'amount_asc')
                                Montant ↑
                            @elseif(request('sort') == 'amount_desc')
                                Montant ↓
                            @elseif(request('sort') == 'year_asc')
                                Année ↑
                            @elseif(request('sort') == 'year_desc')
                                Année ↓
                            @else
                                Plus récentes
                            @endif
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end p-2">
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('backoffice.vehicles.vignettes.index', array_merge(['vehicle' => $vehicle->id], request()->except('sort'), ['sort'=>'latest'])) }}">
                                    Plus récentes
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('backoffice.vehicles.vignettes.index', array_merge(['vehicle' => $vehicle->id], request()->except('sort'), ['sort'=>'oldest'])) }}">
                                    Plus anciennes
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('backoffice.vehicles.vignettes.index', array_merge(['vehicle' => $vehicle->id], request()->except('sort'), ['sort'=>'amount_desc'])) }}">
                                    Montant (plus élevé)
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('backoffice.vehicles.vignettes.index', array_merge(['vehicle' => $vehicle->id], request()->except('sort'), ['sort'=>'amount_asc'])) }}">
                                    Montant (moins élevé)
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('backoffice.vehicles.vignettes.index', array_merge(['vehicle' => $vehicle->id], request()->except('sort'), ['sort'=>'year_desc'])) }}">
                                    Année (récente)
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('backoffice.vehicles.vignettes.index', array_merge(['vehicle' => $vehicle->id], request()->except('sort'), ['sort'=>'year_asc'])) }}">
                                    Année (ancienne)
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- FILTER TOGGLE -->
                    <div>
                        <a href="#filtercollapse"
                           class="filtercollapse coloumn d-inline-flex align-items-center"
                           data-bs-toggle="collapse">
                            <i class="ti ti-filter me-1"></i> Filtres
                        </a>
                    </div>

                </div>

                <!-- SEARCH -->
                <div class="d-flex my-xl-auto right-content align-items-center flex-wrap row-gap-3">

                    <div class="top-search me-2">
                        <div class="top-search-group">
                            <span class="input-icon">
                                <i class="ti ti-search"></i>
                            </span>
                            <input type="text"
                                   name="search"
                                   id="searchInput"
                                   value="{{ request('search') }}"
                                   class="form-control"
                                   placeholder="Rechercher une vignette...">
                        </div>
                    </div>

                    <div class="mb-0">
                        <a href="{{ route('backoffice.vehicles.vignettes.create', $vehicle) }}"
                           class="btn btn-primary d-flex align-items-center">
                            <i class="ti ti-plus me-2"></i>Ajouter une vignette
                        </a>
                    </div>

                </div>

            </div>

        </form>
        <!-- END HEADER -->


        <!-- FILTER COLLAPSE -->
        <div class="collapse" id="filtercollapse">
            <div class="filterbox mb-3 d-flex align-items-center">
                <h6 class="me-3">Filtres</h6>

                <div class="dropdown me-3">
                    <a href="#" class="dropdown-toggle btn btn-white d-inline-flex align-items-center"
                       data-bs-toggle="dropdown">
                        Année :
                        @if(request('year'))
                            {{ request('year') }}
                        @else
                            Toutes
                        @endif
                    </a>

                    <ul class="dropdown-menu dropdown-menu-md p-2">
                        <li>
                            <a class="dropdown-item"
                               href="{{ route('backoffice.vehicles.vignettes.index', ['vehicle' => $vehicle->id]) }}">
                                Toutes
                            </a>
                        </li>
                        @foreach($availableYears ?? [] as $year)
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('backoffice.vehicles.vignettes.index', ['vehicle' => $vehicle->id, 'year' => $year]) }}">
                                    {{ $year }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="dropdown me-3">
                    <a href="#" class="dropdown-toggle btn btn-white d-inline-flex align-items-center"
                       data-bs-toggle="dropdown">
                        Montant min :
                        @if(request('amount_min'))
                            {{ request('amount_min') }} €
                        @else
                            Aucun
                        @endif
                    </a>

                    <ul class="dropdown-menu dropdown-menu-md p-3" style="min-width: 250px;">
                        <li>
                            <div class="px-2">
                                <label class="form-label">Montant minimum</label>
                                <input type="number" 
                                       form="filterForm"
                                       name="amount_min" 
                                       value="{{ request('amount_min') }}"
                                       class="form-control mb-2"
                                       placeholder="0.00"
                                       step="0.01">
                                <button type="submit" form="filterForm" class="btn btn-primary btn-sm w-100">
                                    Appliquer
                                </button>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="dropdown me-3">
                    <a href="#" class="dropdown-toggle btn btn-white d-inline-flex align-items-center"
                       data-bs-toggle="dropdown">
                        Montant max :
                        @if(request('amount_max'))
                            {{ request('amount_max') }} €
                        @else
                            Aucun
                        @endif
                    </a>

                    <ul class="dropdown-menu dropdown-menu-md p-3" style="min-width: 250px;">
                        <li>
                            <div class="px-2">
                                <label class="form-label">Montant maximum</label>
                                <input type="number" 
                                       form="filterForm"
                                       name="amount_max" 
                                       value="{{ request('amount_max') }}"
                                       class="form-control mb-2"
                                       placeholder="9999.99"
                                       step="0.01">
                                <button type="submit" form="filterForm" class="btn btn-primary btn-sm w-100">
                                    Appliquer
                                </button>
                            </div>
                        </li>
                    </ul>
                </div>

                <a href="{{ route('backoffice.vehicles.vignettes.index', ['vehicle' => $vehicle->id]) }}"
                   class="text-danger links">
                    Tout effacer
                </a>
            </div>
        </div>


        <!-- TABLE -->
        <div class="custom-datatable-filter table-responsive">
            @include('Backoffice.vignettes.partials._table')
        </div>

        <!-- PAGINATION -->
        <div class="table-footer">
            <div class="d-flex justify-content-end">
                {{ $vignettes->withQueryString()->links() }}
            </div>
        </div>

    </div>

    <div class="footer d-sm-flex align-items-center justify-content-between bg-white p-3">
        <p class="mb-0">2024 © Rental Car. All rights reserved.</p>
        <p class="mb-0">v1.0</p>
    </div>
</div>


<!-- AUTO SEARCH SCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('filterForm');
    const input = document.getElementById('searchInput');

    if (!form || !input) return;

    let debounceTimer;

    input.addEventListener('input', function () {

        clearTimeout(debounceTimer);

        debounceTimer = setTimeout(function () {
            form.submit();
        }, 400);

    });

});
</script>

@include('Backoffice.vignettes.partials._modal_delete')

@endsection
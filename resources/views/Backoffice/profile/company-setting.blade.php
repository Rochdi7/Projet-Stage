<?php $page = 'company-settings'; ?>
@extends('layout.mainlayout_admin')

@section('content')
    <div class="page-wrapper">
        <div class="content me-4 pb-0">

            <!-- Breadcrumb -->
            <div class="d-md-flex d-block align-items-center justify-content-between page-breadcrumb mb-3">
                <div class="my-auto mb-2">
                    <h2 class="mb-1">Paramètres</h2>
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ url('admin/index') }}">Accueil</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Paramètres</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- /Breadcrumb -->

            <div class="row">
                <div class="col-xl-3">
                    <!-- inner sidebar -->
                    @include('Backoffice.profile.partials._agency_settings_sidebar', [
                        'agency' => $agency,
                        'active' => 'company',
                    ])
                    <!-- /inner sidebar -->
                </div>

                <div class="col-xl-9">
                    <div class="card">
                        <div class="card-header">
                            <h5>Paramètres Société</h5>
                        </div>

                        <form action="{{ url('admin/company-setting') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="card-body">

                                <!-- LOGO + SIGNATURE -->
                                <div class="localization-content mb-3">
                                    <h6 class="mb-3">Identité visuelle</h6>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="fw-medium text-gray-9 mb-1">Logo</p>
                                            <div class="d-flex align-items-center flex-wrap row-gap-3 mb-3">
                                                <div
                                                    class="d-flex align-items-center justify-content-center avatar avatar-xxl me-3 flex-shrink-0 text-dark frames">
                                                    <img src="{{ $agency?->getFirstMediaUrl('agency_logo') ?: URL::asset('admin_assets/img/settings/company-logo-01.jpg') }}"
                                                        class="rounded-circle" alt="Logo">
                                                </div>

                                                <div class="profile-upload">
                                                    <div class="profile-uploader d-flex align-items-center">
                                                        <div class="drag-upload-btn btn btn-md btn-dark">
                                                            <i class="ti ti-photo-up fs-14"></i> Changer
                                                            <input type="file" name="logo"
                                                                class="form-control image-sign" accept="image/*">
                                                        </div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <p class="fs-14 mb-0">Taille recommandée : 500px × 500px</p>
                                                    </div>
                                                    @error('logo')
                                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <p class="fw-medium text-gray-9 mb-1">Signature</p>
                                            <div class="d-flex align-items-center flex-wrap row-gap-3 mb-3">
                                                <div
                                                    class="d-flex align-items-center justify-content-center avatar avatar-xxl me-3 flex-shrink-0 text-dark frames">
                                                    <img src="{{ $agency?->getFirstMediaUrl('agency_signature') ?: URL::asset('admin_assets/img/settings/company-logo-01.jpg') }}"
                                                        class="rounded-circle" alt="Signature">
                                                </div>

                                                <div class="profile-upload">
                                                    <div class="profile-uploader d-flex align-items-center">
                                                        <div class="drag-upload-btn btn btn-md btn-dark">
                                                            <i class="ti ti-photo-up fs-14"></i> Changer
                                                            <input type="file" name="signature"
                                                                class="form-control image-sign" accept="image/*">
                                                        </div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <p class="fs-14 mb-0">Signature scannée (PNG/JPG recommandé)</p>
                                                    </div>
                                                    @error('signature')
                                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- INFOS DE BASE -->
                                <div class="localization-content mb-3">
                                    <h6 class="mb-3">Informations de base</h6>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Nom de l’agence <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="name" class="form-control"
                                                    value="{{ old('name', $agency->name ?? '') }}">
                                                @error('name')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Raison sociale</label>
                                                <input type="text" name="legal_name" class="form-control"
                                                    value="{{ old('legal_name', $agency->legal_name ?? '') }}">
                                                @error('legal_name')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Email</label>
                                                <input type="email" name="email" class="form-control"
                                                    value="{{ old('email', $agency->email ?? '') }}">
                                                @error('email')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Téléphone</label>
                                                <input type="text" name="phone" id="phone" class="form-control"
                                                    value="{{ old('phone', $agency->phone ?? '') }}">
                                                @error('phone')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Site web</label>
                                                <input type="text" name="website" class="form-control"
                                                    value="{{ old('website', $agency->website ?? '') }}">
                                                @error('website')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Devise par défaut</label>
                                                <input type="text" name="default_currency" class="form-control"
                                                    value="{{ old('default_currency', $agency->default_currency ?? 'MAD') }}">
                                                @error('default_currency')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea name="description" class="form-control" rows="3">{{ old('description', $agency->description ?? '') }}</textarea>
                                                @error('description')
                                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- IDENTIFIANTS -->
                                <div class="localization-content mb-3">
                                    <h6 class="mb-3">Identifiants légaux</h6>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">TP</label>
                                                <input type="text" name="tp_number" class="form-control"
                                                    value="{{ old('tp_number', $agency->tp_number ?? '') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">RC</label>
                                                <input type="text" name="rc_number" class="form-control"
                                                    value="{{ old('rc_number', $agency->rc_number ?? '') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">IF</label>
                                                <input type="text" name="if_number" class="form-control"
                                                    value="{{ old('if_number', $agency->if_number ?? '') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">ICE</label>
                                                <input type="text" name="ice_number" class="form-control"
                                                    value="{{ old('ice_number', $agency->ice_number ?? '') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">N° TVA</label>
                                                <input type="text" name="vat_number" class="form-control"
                                                    value="{{ old('vat_number', $agency->vat_number ?? '') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Date de création</label>
                                                <input type="date" name="creation_date" class="form-control"
                                                    value="{{ old('creation_date', optional($agency->creation_date)->format('Y-m-d')) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- ADRESSE -->
                                <div class="localization-content mb-3">
                                    <h6 class="mb-3">Adresse</h6>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label">Adresse</label>
                                                <input type="text" name="address" class="form-control"
                                                    value="{{ old('address', $agency->address ?? '') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Ville</label>
                                                <input type="text" name="city" class="form-control"
                                                    value="{{ old('city', $agency->city ?? '') }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Pays</label>
                                                <input type="text" name="country" class="form-control"
                                                    value="{{ old('country', $agency->country ?? '') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="card-footer">
                                <div class="d-flex align-items-center justify-content-end">
                                    <a href="{{ url('admin/index') }}" class="btn btn-light me-2">Annuler</a>
                                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>

        <div class="footer d-sm-flex align-items-center justify-content-between bg-white p-3">
            <p class="mb-0">
                <a href="javascript:void(0);">Politique de confidentialité</a>
                <a href="javascript:void(0);" class="ms-4">Conditions d’utilisation</a>
            </p>
            <p>&copy; 2025 Dreamsrent, Made with <span class="text-danger">❤</span> by <a href="javascript:void(0);"
                    class="text-secondary">Dreams</a></p>
        </div>
    </div>
@endsection

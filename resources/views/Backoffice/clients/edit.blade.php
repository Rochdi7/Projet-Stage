<?php $page = 'clients'; ?>
@extends('layout.mainlayout_admin')

@section('content')
    <div class="page-wrapper">
        <div class="content me-0">
            <div class="row justify-content-center">
                <div class="col-lg-8">

                    <div class="mb-3">
                        <a href="{{ route('backoffice.clients.index') }}" class="d-inline-flex align-items-center fw-medium">
                            <i class="ti ti-arrow-left me-1"></i>
                            Retour à la liste
                        </a>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">
                                <i class="ti ti-edit me-2"></i>
                                Modifier le client
                            </h4>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('backoffice.clients.update', $client) }}" method="POST"
                                enctype="multipart/form-data" class="needs-validation" novalidate>
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="remove_avatar" id="removeAvatar" value="0">

                                <!-- Photo Upload -->
                                <div class="mb-4">
                                    <label class="form-label fw-medium">Photo du client</label>
                                    <div class="d-flex align-items-center flex-wrap row-gap-3">
                                        <div id="avatarFrame"
                                            class="d-flex align-items-center justify-content-center avatar avatar-xxl border me-3 flex-shrink-0 text-dark frames position-relative"
                                            style="overflow:hidden;border-radius:16px; background: #f8f9fa; border: 2px dashed #dee2e6; width: 120px; height: 120px;">

                                            @if ($client->hasAvatar())
                                                <img id="avatarImg" src="{{ $client->avatar_url }}" alt="Client photo"
                                                    style="width:100%;height:100%;object-fit:cover;">
                                                <a href="javascript:void(0);" id="avatarTrash"
                                                    class="avatar-badge bg-light text-danger m-1"
                                                    style="position: absolute; top: 5px; right: 5px; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                                <i id="avatarIcon" class="ti ti-photo-up text-gray-4 fs-32"
                                                    style="display:none;"></i>
                                            @else
                                                <i id="avatarIcon" class="ti ti-photo-up text-gray-4 fs-32"></i>
                                                <img id="avatarImg" src="" alt="Client photo"
                                                    style="display:none;width:100%;height:100%;object-fit:cover;">
                                                <a href="javascript:void(0);" id="avatarTrash"
                                                    class="avatar-badge bg-light text-danger m-1"
                                                    style="display:none; position: absolute; top: 5px; right: 5px; width: 30px; height: 30px; border-radius: 50%; align-items: center; justify-content: center;">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                            @endif
                                        </div>

                                        <div class="profile-upload flex-grow-1">
                                            <div class="profile-uploader d-flex align-items-center flex-wrap gap-2">
                                                <div class="drag-upload-btn btn btn-md btn-dark position-relative">
                                                    <i class="ti ti-upload me-1"></i>
                                                    Changer la photo
                                                    <input type="file" name="avatar" id="avatarInput"
                                                        class="position-absolute top-0 start-0 w-100 h-100 opacity-0"
                                                        accept="image/jpeg,image/png,image/jpg,image/gif"
                                                        style="cursor: pointer;">
                                                </div>
                                                @if ($client->hasAvatar())
                                                    <button type="button" class="btn btn-md btn-light" id="avatarClearBtn">
                                                        <i class="ti ti-trash me-1"></i>
                                                        Supprimer
                                                    </button>
                                                @endif
                                            </div>
                                            <div class="mt-2">
                                                <p class="fs-12 text-muted mb-0">
                                                    <i class="ti ti-info-circle me-1"></i>
                                                    Formats: JPG, PNG, GIF • Taille max: 2MB
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Agence -->
                                <div class="mb-3">
                                    <label class="form-label">
                                        Agence <span class="text-danger">*</span>
                                    </label>
                                    <select name="agency_id" class="form-select @error('agency_id') is-invalid @enderror"
                                        required>
                                        <option value="">Sélectionner une agence</option>
                                        @foreach ($agencies as $agency)
                                            <option value="{{ $agency->id }}"
                                                {{ old('agency_id', $client->agency_id) == $agency->id ? 'selected' : '' }}>
                                                {{ $agency->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('agency_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <!-- Prénom -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Prénom <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="first_name"
                                                value="{{ old('first_name', $client->first_name) }}"
                                                class="form-control @error('first_name') is-invalid @enderror" required
                                                maxlength="100">
                                            @error('first_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Nom -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Nom <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="last_name"
                                                value="{{ old('last_name', $client->last_name) }}"
                                                class="form-control @error('last_name') is-invalid @enderror" required
                                                maxlength="100">
                                            @error('last_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email"
                                                value="{{ old('email', $client->email) }}"
                                                class="form-control @error('email') is-invalid @enderror" maxlength="150">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Téléphone -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                Téléphone <span class="text-danger">*</span>
                                            </label>
                                            <input type="tel" name="phone"
                                                value="{{ old('phone', $client->phone) }}"
                                                class="form-control @error('phone') is-invalid @enderror" required
                                                maxlength="50">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Date de naissance -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Date de naissance</label>
                                            <input type="date" name="birth_date"
                                                value="{{ old('birth_date', $client->birth_date ? $client->birth_date->format('Y-m-d') : '') }}"
                                                class="form-control @error('birth_date') is-invalid @enderror">
                                            @error('birth_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Nationalité -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Nationalité</label>
                                            <input type="text" name="nationality"
                                                value="{{ old('nationality', $client->nationality) }}"
                                                class="form-control @error('nationality') is-invalid @enderror">
                                            @error('nationality')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Adresse -->
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Adresse</label>
                                            <input type="text" name="address"
                                                value="{{ old('address', $client->address) }}"
                                                class="form-control @error('address') is-invalid @enderror">
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Ville -->
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Ville</label>
                                            <input type="text" name="city"
                                                value="{{ old('city', $client->city) }}"
                                                class="form-control @error('city') is-invalid @enderror">
                                            @error('city')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Pays -->
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Pays</label>
                                            <input type="text" name="country"
                                                value="{{ old('country', $client->country) }}"
                                                class="form-control @error('country') is-invalid @enderror">
                                            @error('country')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Statut -->
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Statut</label>
                                            <select name="status"
                                                class="form-select @error('status') is-invalid @enderror">
                                                <option value="active"
                                                    {{ old('status', $client->status) == 'active' ? 'selected' : '' }}>
                                                    Actif</option>
                                                <option value="inactive"
                                                    {{ old('status', $client->status) == 'inactive' ? 'selected' : '' }}>
                                                    Inactif</option>
                                                <option value="blacklisted"
                                                    {{ old('status', $client->status) == 'blacklisted' ? 'selected' : '' }}>
                                                    Blacklisté</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- CIN -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Numéro CIN</label>
                                            <input type="text" name="cin_number"
                                                value="{{ old('cin_number', $client->cin_number) }}"
                                                class="form-control @error('cin_number') is-invalid @enderror">
                                            @error('cin_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Validité CIN</label>
                                            <input type="date" name="cin_valid_until"
                                                value="{{ old('cin_valid_until', $client->cin_valid_until ? $client->cin_valid_until->format('Y-m-d') : '') }}"
                                                class="form-control @error('cin_valid_until') is-invalid @enderror">
                                            @error('cin_valid_until')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Passeport -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Numéro passeport</label>
                                            <input type="text" name="passport_number"
                                                value="{{ old('passport_number', $client->passport_number) }}"
                                                class="form-control @error('passport_number') is-invalid @enderror">
                                            @error('passport_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Date délivrance passeport</label>
                                            <input type="date" name="passport_issue_date"
                                                value="{{ old('passport_issue_date', $client->passport_issue_date ? $client->passport_issue_date->format('Y-m-d') : '') }}"
                                                class="form-control @error('passport_issue_date') is-invalid @enderror">
                                            @error('passport_issue_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Permis de conduire -->
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Numéro permis</label>
                                            <input type="text" name="driving_license_number"
                                                value="{{ old('driving_license_number', $client->driving_license_number) }}"
                                                class="form-control @error('driving_license_number') is-invalid @enderror">
                                            @error('driving_license_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Date délivrance permis</label>
                                            <input type="date" name="driving_license_issue_date"
                                                value="{{ old('driving_license_issue_date', $client->driving_license_issue_date ? $client->driving_license_issue_date->format('Y-m-d') : '') }}"
                                                class="form-control @error('driving_license_issue_date') is-invalid @enderror">
                                            @error('driving_license_issue_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Notes -->
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Notes</label>
                                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $client->notes) }}</textarea>
                                            @error('notes')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-3 mt-4">
                                    <a href="{{ route('backoffice.clients.index') }}" class="btn btn-light px-4">
                                        <i class="ti ti-x me-1"></i>
                                        Annuler
                                    </a>
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="ti ti-device-floppy me-1"></i>
                                        Mettre à jour
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
                const avatarInput = document.getElementById('avatarInput');
                const avatarImg = document.getElementById('avatarImg');
                const avatarIcon = document.getElementById('avatarIcon');
                const avatarTrash = document.getElementById('avatarTrash');
                const avatarClear = document.getElementById('avatarClearBtn');
                const removeInput = document.getElementById('removeAvatar');
                const avatarFrame = document.getElementById('avatarFrame');

                if (avatarInput) {
                    avatarInput.addEventListener('change', function() {
                        const file = this.files[0];
                        if (!file) return;

                        if (file.size > 2 * 1024 * 1024) {
                            alert('La photo ne doit pas dépasser 2MB');
                            this.value = '';
                            return;
                        }

                        if (!file.type.match('image.*')) {
                            alert('Veuillez sélectionner une image valide');
                            this.value = '';
                            return;
                        }

                        removeInput.value = '0';
                        const url = URL.createObjectURL(file);

                        avatarImg.src = url;
                        avatarImg.style.display = '';
                        avatarIcon.style.display = 'none';
                        if (avatarTrash) avatarTrash.style.display = 'flex';
                        avatarFrame.style.border = '2px solid #0d6efd';
                        avatarImg.onload = () => URL.revokeObjectURL(url);
                    });
                }

                if (avatarTrash) {
                    avatarTrash.addEventListener('click', function() {
                        removeInput.value = '1';
                        if (avatarInput) avatarInput.value = '';
                        avatarImg.src = '';
                        avatarImg.style.display = 'none';
                        avatarIcon.style.display = 'flex';
                        avatarTrash.style.display = 'none';
                        avatarFrame.style.border = '2px dashed #dee2e6';
                    });
                }

                if (avatarClear) {
                    avatarClear.addEventListener('click', function() {
                        removeInput.value = '1';
                        if (avatarInput) avatarInput.value = '';
                        avatarImg.src = '';
                        avatarImg.style.display = 'none';
                        avatarIcon.style.display = 'flex';
                        if (avatarTrash) avatarTrash.style.display = 'none';
                        avatarFrame.style.border = '2px dashed #dee2e6';
                    });
                }

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

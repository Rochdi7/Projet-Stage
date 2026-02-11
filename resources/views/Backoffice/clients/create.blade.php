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

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-3">
                            <i class="ti ti-alert-circle me-1"></i>
                            <strong>Erreur de validation</strong>
                            <ul class="mb-0 mt-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-header">
                            <h4 class="mb-0">
                                <i class="ti ti-user-plus me-2"></i>
                                Ajouter un client
                            </h4>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('backoffice.clients.store') }}" method="POST"
                                enctype="multipart/form-data" class="needs-validation" novalidate>
                                @csrf

                                <!-- PHOTO UPLOAD WITH COMPLETE VALIDATION -->
                                <div class="mb-4">
                                    <label class="form-label fw-medium">
                                        Photo du client
                                        <span class="text-muted fs-12">(2MB max - JPG, PNG, GIF)</span>
                                    </label>

                                    <div class="d-flex align-items-center flex-wrap row-gap-3">
                                        <!-- Photo Preview Frame -->
                                        <div id="avatarFrame"
                                            class="d-flex align-items-center justify-content-center avatar avatar-xxl border me-3 flex-shrink-0 text-dark frames"
                                            style="overflow:hidden;border-radius:16px; background: #f8f9fa; border: 2px dashed #dee2e6; width: 120px; height: 120px;">
                                            <i id="avatarIcon" class="ti ti-photo-up text-gray-4 fs-32"></i>
                                            <img id="avatarImg" src="" alt="Aperçu"
                                                style="display:none;width:100%;height:100%;object-fit:cover;">
                                        </div>

                                        <div class="profile-upload flex-grow-1">
                                            <div class="profile-uploader d-flex align-items-center flex-wrap gap-2">
                                                <div class="drag-upload-btn btn btn-md btn-dark position-relative">
                                                    <i class="ti ti-upload me-1"></i>
                                                    Choisir une photo
                                                    <input type="file" name="avatar" id="avatarInput"
                                                        class="position-absolute top-0 start-0 w-100 h-100 opacity-0"
                                                        accept="image/jpeg,image/png,image/jpg,image/gif"
                                                        style="cursor: pointer;">
                                                </div>
                                                <button type="button" class="btn btn-md btn-light" id="avatarClearBtn">
                                                    <i class="ti ti-trash me-1"></i>
                                                    Effacer
                                                </button>
                                            </div>

                                            <!-- Photo Validation Messages -->
                                            <div id="photoValidationMessage" class="mt-2">
                                                @error('avatar')
                                                    <div class="text-danger small">
                                                        <i class="ti ti-alert-circle me-1"></i>{{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="mt-1">
                                                <p class="fs-12 text-muted mb-0">
                                                    <i class="ti ti-info-circle me-1"></i>
                                                    Formats: JPG, PNG, GIF • Max: 2MB • Max: 2000x2000px
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>

                    <!-- Agence -->
                    <div class="mb-3">
                        <label class="form-label">
                            Agence <span class="text-danger">*</span>
                        </label>
                        <select name="agency_id" class="form-select @error('agency_id') is-invalid @enderror" required>
                            <option value="">Sélectionner une agence</option>
                            @foreach ($agencies as $agency)
                                <option value="{{ $agency->id }}" {{ old('agency_id') == $agency->id ? 'selected' : '' }}>
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
                                <input type="text" name="first_name" value="{{ old('first_name') }}"
                                    class="form-control @error('first_name') is-invalid @enderror" required maxlength="100"
                                    placeholder="Jean">
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
                                <input type="text" name="last_name" value="{{ old('last_name') }}"
                                    class="form-control @error('last_name') is-invalid @enderror" required maxlength="100"
                                    placeholder="Dupont">
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror" maxlength="150"
                                    placeholder="client@example.com">
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
                                <input type="tel" name="phone" value="{{ old('phone') }}"
                                    class="form-control @error('phone') is-invalid @enderror" required maxlength="50"
                                    placeholder="+33 1 23 45 67 89">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Date de naissance -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Date de naissance</label>
                                <input type="date" name="birth_date" value="{{ old('birth_date') }}"
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
                                <input type="text" name="nationality" value="{{ old('nationality') }}"
                                    class="form-control @error('nationality') is-invalid @enderror"
                                    placeholder="Française">
                                @error('nationality')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Adresse -->
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Adresse</label>
                                <input type="text" name="address" value="{{ old('address') }}"
                                    class="form-control @error('address') is-invalid @enderror"
                                    placeholder="123 Rue de Paris">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Ville -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Ville</label>
                                <input type="text" name="city" value="{{ old('city') }}"
                                    class="form-control @error('city') is-invalid @enderror" placeholder="Paris">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Pays -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Pays</label>
                                <input type="text" name="country" value="{{ old('country', 'France') }}"
                                    class="form-control @error('country') is-invalid @enderror" placeholder="France">
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Statut -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Statut</label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>
                                        Actif</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactif
                                    </option>
                                    <option value="blacklisted" {{ old('status') == 'blacklisted' ? 'selected' : '' }}>
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
                                <input type="text" name="cin_number" value="{{ old('cin_number') }}"
                                    class="form-control @error('cin_number') is-invalid @enderror"
                                    placeholder="AB123456">
                                @error('cin_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Validité CIN</label>
                                <input type="date" name="cin_valid_until" value="{{ old('cin_valid_until') }}"
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
                                <input type="text" name="passport_number" value="{{ old('passport_number') }}"
                                    class="form-control @error('passport_number') is-invalid @enderror"
                                    placeholder="AB123456">
                                @error('passport_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Date délivrance passeport</label>
                                <input type="date" name="passport_issue_date"
                                    value="{{ old('passport_issue_date') }}"
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
                                    value="{{ old('driving_license_number') }}"
                                    class="form-control @error('driving_license_number') is-invalid @enderror"
                                    placeholder="AB123456">
                                @error('driving_license_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Date délivrance permis</label>
                                <input type="date" name="driving_license_issue_date"
                                    value="{{ old('driving_license_issue_date') }}"
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
                                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3"
                                    placeholder="Informations complémentaires...">{{ old('notes') }}</textarea>
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
                            Créer le client
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
                const avatarClear = document.getElementById('avatarClearBtn');
                const avatarFrame = document.getElementById('avatarFrame');
                const validationMessage = document.getElementById('photoValidationMessage');

                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                const maxSize = 2 * 1024 * 1024; // 2MB
                const maxWidth = 2000;
                const maxHeight = 2000;

                function showError(message) {
                    if (validationMessage) {
                        validationMessage.innerHTML =
                            `<div class="text-danger small"><i class="ti ti-alert-circle me-1"></i>${message}</div>`;
                    }
                }

                function clearError() {
                    if (validationMessage) {
                        validationMessage.innerHTML = '';
                    }
                }

                function resetAvatar() {
                    if (avatarInput) avatarInput.value = '';
                    if (avatarImg) {
                        avatarImg.src = '';
                        avatarImg.style.display = 'none';
                    }
                    if (avatarIcon) avatarIcon.style.display = 'flex';
                    if (avatarFrame) {
                        avatarFrame.style.border = '2px dashed #dee2e6';
                        avatarFrame.style.borderColor = '#dee2e6';
                    }
                    clearError();
                }

                if (avatarInput) {
                    avatarInput.addEventListener('change', function() {
                        const file = this.files[0];

                        clearError();

                        if (!file) {
                            resetAvatar();
                            return;
                        }

                        // Validate file type
                        if (!allowedTypes.includes(file.type)) {
                            showError('Format non supporté. Utilisez JPG, PNG ou GIF.');
                            resetAvatar();
                            return;
                        }

                        // Validate file size
                        if (file.size > maxSize) {
                            showError('L\'image ne doit pas dépasser 2MB.');
                            resetAvatar();
                            return;
                        }

                        // Validate image dimensions
                        const img = new Image();
                        img.onload = function() {
                            if (img.width > maxWidth || img.height > maxHeight) {
                                showError(
                                    `Dimensions trop grandes. Maximum ${maxWidth}x${maxHeight} pixels.`);
                                resetAvatar();
                                return;
                            }

                            const url = URL.createObjectURL(file);
                            avatarImg.src = url;
                            avatarImg.style.display = '';
                            avatarIcon.style.display = 'none';
                            avatarFrame.style.border = '2px solid #0d6efd';
                            avatarFrame.style.borderColor = '#0d6efd';

                            avatarImg.onload = () => URL.revokeObjectURL(url);
                        };

                        img.onerror = function() {
                            showError('L\'image est corrompue ou invalide.');
                            resetAvatar();
                        };

                        img.src = URL.createObjectURL(file);
                    });
                }

                if (avatarClear) {
                    avatarClear.addEventListener('click', resetAvatar);
                }

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

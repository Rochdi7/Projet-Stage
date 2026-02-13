{{-- Add Client Modal --}}
<div class="modal fade" id="add_client">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="mb-0">Créer un client</h5>
                <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ti ti-x fs-16"></i>
                </button>
            </div>

            <form id="addClientForm"
                  action="{{ route('backoffice.clients.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="needs-validation"
                  novalidate>
                @csrf

                <div class="modal-body pb-1">
                    <div class="row">

                        {{-- IMAGE UPLOAD - PHOTO DU CLIENT --}}
                        <div class="mb-3">
                            <label class="form-label">Photo du client</label>
                            
                            <div class="d-flex align-items-center flex-wrap row-gap-3 mb-3">
                                <div id="addClientAvatarFrame"
                                     class="d-flex align-items-center justify-content-center avatar avatar-xxl border me-3 flex-shrink-0 text-dark frames"
                                     style="overflow:hidden;border-radius:8px; background-color: #f8f9fa;">
                                    <i id="addClientAvatarIcon" class="ti ti-photo-up text-gray-4 fs-24"></i>
                                    <img id="addClientAvatarImg"
                                         src=""
                                         alt="Client photo"
                                         style="display:none;width:100%;height:100%;object-fit:cover;">
                                </div>

                                <div class="profile-upload">
                                    <div class="profile-uploader d-flex align-items-center">
                                        <div class="drag-upload-btn btn btn-md btn-dark">
                                            <i class="ti ti-photo-up fs-14"></i>
                                            Upload
                                            <input type="file"
                                                   name="avatar"
                                                   id="addClientAvatarInput"
                                                   class="form-control image-sign"
                                                   accept="image/*">
                                        </div>
                                        <button type="button" 
                                                class="btn btn-md btn-light ms-2" 
                                                id="addClientAvatarClearBtn">
                                            <i class="ti ti-trash me-1"></i>Retirer
                                        </button>
                                    </div>
                                    <div class="mt-2">
                                        <p class="fs-14 mb-0">
                                            <i class="ti ti-info-circle me-1"></i>
                                            Format: JPG, PNG, GIF • Taille max: 2MB • Dimensions: 300x300px
                                        </p>
                                    </div>
                                    @error('avatar')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- AGENCY --}}
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Agence <span class="text-danger">*</span></label>
                                <select name="agency_id"
                                        class="select @error('agency_id') is-invalid @enderror"
                                        required>
                                    <option value="">Sélectionner une agence</option>
                                    @forelse($agencies ?? [] as $agency)
                                        <option value="{{ $agency->id }}" {{ old('agency_id') == $agency->id ? 'selected' : '' }}>
                                            {{ $agency->name }}
                                        </option>
                                    @empty
                                        <option value="" disabled>Aucune agence disponible</option>
                                    @endforelse
                                </select>
                                <div class="invalid-feedback">Veuillez sélectionner une agence.</div>
                                @error('agency_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- FULL NAME --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nom complet <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="full_name"
                                       value="{{ old('full_name') }}"
                                       class="form-control @error('full_name') is-invalid @enderror"
                                       required
                                       maxlength="150"
                                       placeholder="Jean Dupont">
                                <div class="invalid-feedback">Veuillez saisir le nom complet.</div>
                                @error('full_name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- EMAIL --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       class="form-control @error('email') is-invalid @enderror"
                                       maxlength="150"
                                       placeholder="client@example.com">
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- PHONE --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Téléphone</label>
                                <input type="text"
                                       name="phone"
                                       value="{{ old('phone') }}"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       maxlength="50"
                                       placeholder="+33 1 23 45 67 89">
                                @error('phone')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- DATE OF BIRTH --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Date de naissance</label>
                                <input type="date"
                                       name="date_of_birth"
                                       value="{{ old('date_of_birth') }}"
                                       class="form-control @error('date_of_birth') is-invalid @enderror">
                                @error('date_of_birth')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- ADDRESS --}}
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Adresse</label>
                                <input type="text"
                                       name="address"
                                       value="{{ old('address') }}"
                                       class="form-control @error('address') is-invalid @enderror"
                                       placeholder="123 Rue de Paris">
                                @error('address')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- CITY --}}
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Ville</label>
                                <input type="text"
                                       name="city"
                                       value="{{ old('city') }}"
                                       class="form-control @error('city') is-invalid @enderror"
                                       placeholder="Paris">
                                @error('city')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- POSTAL CODE --}}
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Code postal</label>
                                <input type="text"
                                       name="postal_code"
                                       value="{{ old('postal_code') }}"
                                       class="form-control @error('postal_code') is-invalid @enderror"
                                       placeholder="75001">
                                @error('postal_code')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- COUNTRY --}}
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Pays</label>
                                <input type="text"
                                       name="country"
                                       value="{{ old('country', 'France') }}"
                                       class="form-control @error('country') is-invalid @enderror"
                                       placeholder="France">
                                @error('country')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- LICENSE NUMBER --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Numéro de permis</label>
                                <input type="text"
                                       name="license_number"
                                       value="{{ old('license_number') }}"
                                       class="form-control @error('license_number') is-invalid @enderror"
                                       placeholder="AB123456">
                                @error('license_number')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- LICENSE EXPIRY --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Expiration du permis</label>
                                <input type="date"
                                       name="license_expiry"
                                       value="{{ old('license_expiry') }}"
                                       class="form-control @error('license_expiry') is-invalid @enderror">
                                @error('license_expiry')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- ID CARD NUMBER --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Numéro de carte d'identité</label>
                                <input type="text"
                                       name="id_card_number"
                                       value="{{ old('id_card_number') }}"
                                       class="form-control @error('id_card_number') is-invalid @enderror"
                                       placeholder="CNI123456">
                                @error('id_card_number')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- LINKED USER (optional) --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Utilisateur lié (optionnel)</label>
                                <select name="user_id"
                                        class="select @error('user_id') is-invalid @enderror">
                                    <option value="">Aucun utilisateur lié</option>
                                    @forelse($users ?? [] as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} ({{ $user->email }})
                                        </option>
                                    @empty
                                    @endforelse
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Lier un compte utilisateur existant à ce client</small>
                            </div>
                        </div>

                        {{-- IS ACTIVE --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="is_active" 
                                           id="is_active"
                                           value="1" 
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Client actif
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- NOTES --}}
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="notes"
                                          class="form-control @error('notes') is-invalid @enderror"
                                          rows="3"
                                          placeholder="Informations complémentaires...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <div class="d-flex justify-content-center">
                        <a href="javascript:void(0);" class="btn btn-light me-3" data-bs-dismiss="modal">Annuler</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>Créer le client
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>
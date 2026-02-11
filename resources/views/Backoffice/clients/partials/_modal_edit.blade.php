{{-- Edit Client Modal --}}
<div class="modal fade" id="edit_client">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="mb-0">Modifier un client</h5>
                <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ti ti-x fs-16"></i>
                </button>
            </div>

            <form id="editClientForm"
                  action="#"
                  method="POST"
                  enctype="multipart/form-data"
                  class="needs-validation"
                  novalidate>
                @csrf
                @method('PUT')

                <input type="hidden" name="remove_avatar" id="editClientRemoveAvatar" value="0">

                <div class="modal-body pb-1">
                    <div class="row">

                        {{-- IMAGE UPLOAD - PHOTO DU CLIENT --}}
                        <div class="mb-3">
                            <label class="form-label">Photo du client</label>
                            
                            <div class="d-flex align-items-center flex-wrap row-gap-3 mb-3">
                                <div id="editClientAvatarFrame"
                                     class="d-flex align-items-center justify-content-center avatar avatar-xxl border me-3 flex-shrink-0 text-dark frames position-relative"
                                     style="overflow:hidden;border-radius:8px; background-color: #f8f9fa;">
                                    <img id="editClientAvatarImg"
                                         src=""
                                         class="img-fluid"
                                         alt="img"
                                         style="display:none;width:100%;height:100%;object-fit:cover;border-radius:6px;">
                                    <i id="editClientAvatarIcon" class="ti ti-photo-up text-gray-4 fs-24"></i>
                                    <a href="javascript:void(0);"
                                       id="editClientAvatarTrash"
                                       class="avatar-badge bg-light text-danger m-1"
                                       style="display:none;">
                                        <i class="ti ti-trash"></i>
                                    </a>
                                </div>

                                <div class="profile-upload">
                                    <div class="profile-uploader d-flex align-items-center">
                                        <div class="drag-upload-btn btn btn-md btn-dark">
                                            <i class="ti ti-photo-up fs-14"></i>
                                            Upload
                                            <input type="file"
                                                   name="avatar"
                                                   id="editClientAvatarInput"
                                                   class="form-control image-sign"
                                                   accept="image/*">
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <p class="fs-14">Upload Image size 180*180, within 5MB</p>
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
                                <select id="editClientAgency"
                                        name="agency_id"
                                        class="select @error('agency_id') is-invalid @enderror"
                                        required>
                                    <option value="">Sélectionner une agence</option>
                                    @forelse($agencies ?? [] as $agency)
                                        <option value="{{ $agency->id }}">{{ $agency->name }}</option>
                                    @empty
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
                                       id="editClientFullName"
                                       name="full_name"
                                       class="form-control @error('full_name') is-invalid @enderror"
                                       required
                                       maxlength="150">
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
                                       id="editClientEmail"
                                       name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       maxlength="150">
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
                                       id="editClientPhone"
                                       name="phone"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       maxlength="50">
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
                                       id="editClientDateOfBirth"
                                       name="date_of_birth"
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
                                       id="editClientAddress"
                                       name="address"
                                       class="form-control @error('address') is-invalid @enderror">
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
                                       id="editClientCity"
                                       name="city"
                                       class="form-control @error('city') is-invalid @enderror">
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
                                       id="editClientPostalCode"
                                       name="postal_code"
                                       class="form-control @error('postal_code') is-invalid @enderror">
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
                                       id="editClientCountry"
                                       name="country"
                                       class="form-control @error('country') is-invalid @enderror">
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
                                       id="editClientLicenseNumber"
                                       name="license_number"
                                       class="form-control @error('license_number') is-invalid @enderror">
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
                                       id="editClientLicenseExpiry"
                                       name="license_expiry"
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
                                       id="editClientIdCardNumber"
                                       name="id_card_number"
                                       class="form-control @error('id_card_number') is-invalid @enderror">
                                @error('id_card_number')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- LINKED USER --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Utilisateur lié</label>
                                <select id="editClientUser"
                                        name="user_id"
                                        class="select @error('user_id') is-invalid @enderror">
                                    <option value="">Aucun utilisateur lié</option>
                                    @forelse($users ?? [] as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @empty
                                    @endforelse
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- IS ACTIVE --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="is_active" 
                                           id="editClientIsActive"
                                           value="1">
                                    <label class="form-check-label" for="editClientIsActive">
                                        Client actif
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- NOTES --}}
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea id="editClientNotes"
                                          name="notes"
                                          class="form-control @error('notes') is-invalid @enderror"
                                          rows="3"></textarea>
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
                            <i class="ti ti-device-floppy me-1"></i>Enregistrer
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>
{{-- Edit Client --}}
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

                        {{-- PHOTO UPLOAD --}}
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Photo du client</label>
                            <div class="d-flex align-items-center flex-wrap row-gap-3">
                                <div id="editClientAvatarFrame"
                                     class="d-flex align-items-center justify-content-center avatar avatar-xxl border me-3 flex-shrink-0 text-dark frames position-relative"
                                     style="overflow:hidden;border-radius:8px; background-color: #f8f9fa; width: 120px; height: 120px;">
                                    <img id="editClientAvatarImg"
                                         src=""
                                         alt="Client photo"
                                         style="display:none;width:100%;height:100%;object-fit:cover;">
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
                                        <p class="fs-14">Format: JPG, PNG, GIF • Taille max: 2MB</p>
                                    </div>
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
                            </div>
                        </div>

                        {{-- FIRST NAME --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text"
                                       id="editClientFirstName"
                                       name="first_name"
                                       class="form-control @error('first_name') is-invalid @enderror"
                                       required
                                       maxlength="100">
                                <div class="invalid-feedback">Veuillez saisir le prénom.</div>
                            </div>
                        </div>

                        {{-- LAST NAME --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text"
                                       id="editClientLastName"
                                       name="last_name"
                                       class="form-control @error('last_name') is-invalid @enderror"
                                       required
                                       maxlength="100">
                                <div class="invalid-feedback">Veuillez saisir le nom.</div>
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
                            </div>
                        </div>

                        {{-- PHONE --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                                <input type="text"
                                       id="editClientPhone"
                                       name="phone"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       required
                                       maxlength="50">
                                <div class="invalid-feedback">Veuillez saisir le téléphone.</div>
                            </div>
                        </div>

                        {{-- BIRTH DATE --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Date de naissance</label>
                                <input type="date"
                                       id="editClientBirthDate"
                                       name="birth_date"
                                       class="form-control @error('birth_date') is-invalid @enderror">
                            </div>
                        </div>

                        {{-- NATIONALITY --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nationalité</label>
                                <input type="text"
                                       id="editClientNationality"
                                       name="nationality"
                                       class="form-control @error('nationality') is-invalid @enderror">
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
                            </div>
                        </div>

                        {{-- STATUS --}}
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Statut</label>
                                <select id="editClientStatus"
                                        name="status"
                                        class="select @error('status') is-invalid @enderror">
                                    <option value="active">Actif</option>
                                    <option value="inactive">Inactif</option>
                                    <option value="blacklisted">Blacklisté</option>
                                </select>
                            </div>
                        </div>

                        {{-- CIN NUMBER --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Numéro CIN</label>
                                <input type="text"
                                       id="editClientCinNumber"
                                       name="cin_number"
                                       class="form-control @error('cin_number') is-invalid @enderror">
                            </div>
                        </div>

                        {{-- CIN VALID UNTIL --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Validité CIN</label>
                                <input type="date"
                                       id="editClientCinValidUntil"
                                       name="cin_valid_until"
                                       class="form-control @error('cin_valid_until') is-invalid @enderror">
                            </div>
                        </div>

                        {{-- PASSPORT NUMBER --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Numéro passeport</label>
                                <input type="text"
                                       id="editClientPassportNumber"
                                       name="passport_number"
                                       class="form-control @error('passport_number') is-invalid @enderror">
                            </div>
                        </div>

                        {{-- PASSPORT ISSUE DATE --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Date délivrance passeport</label>
                                <input type="date"
                                       id="editClientPassportIssueDate"
                                       name="passport_issue_date"
                                       class="form-control @error('passport_issue_date') is-invalid @enderror">
                            </div>
                        </div>

                        {{-- DRIVING LICENSE NUMBER --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Numéro permis</label>
                                <input type="text"
                                       id="editClientDrivingLicenseNumber"
                                       name="driving_license_number"
                                       class="form-control @error('driving_license_number') is-invalid @enderror">
                            </div>
                        </div>

                        {{-- DRIVING LICENSE ISSUE DATE --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Date délivrance permis</label>
                                <input type="date"
                                       id="editClientDrivingLicenseIssueDate"
                                       name="driving_license_issue_date"
                                       class="form-control @error('driving_license_issue_date') is-invalid @enderror">
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
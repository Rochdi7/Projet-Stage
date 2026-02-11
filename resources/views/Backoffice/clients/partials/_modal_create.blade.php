{{-- Add Client --}}
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

                        {{-- FIRST NAME --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="first_name"
                                       value="{{ old('first_name') }}"
                                       class="form-control @error('first_name') is-invalid @enderror"
                                       required
                                       maxlength="100"
                                       placeholder="Jean">
                                <div class="invalid-feedback">Veuillez saisir le prénom.</div>
                                @error('first_name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- LAST NAME --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="last_name"
                                       value="{{ old('last_name') }}"
                                       class="form-control @error('last_name') is-invalid @enderror"
                                       required
                                       maxlength="100"
                                       placeholder="Dupont">
                                <div class="invalid-feedback">Veuillez saisir le nom.</div>
                                @error('last_name')
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
                                <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="phone"
                                       value="{{ old('phone') }}"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       required
                                       maxlength="50"
                                       placeholder="+33 1 23 45 67 89">
                                <div class="invalid-feedback">Veuillez saisir le téléphone.</div>
                                @error('phone')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- BIRTH DATE --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Date de naissance</label>
                                <input type="date"
                                       name="birth_date"
                                       value="{{ old('birth_date') }}"
                                       class="form-control @error('birth_date') is-invalid @enderror">
                                @error('birth_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- NATIONALITY --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nationalité</label>
                                <input type="text"
                                       name="nationality"
                                       value="{{ old('nationality') }}"
                                       class="form-control @error('nationality') is-invalid @enderror"
                                       placeholder="Française">
                                @error('nationality')
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

                        {{-- STATUS --}}
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Statut</label>
                                <select name="status"
                                        class="select @error('status') is-invalid @enderror">
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Actif</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                                    <option value="blacklisted" {{ old('status') == 'blacklisted' ? 'selected' : '' }}>Blacklisté</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- CIN NUMBER --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Numéro CIN</label>
                                <input type="text"
                                       name="cin_number"
                                       value="{{ old('cin_number') }}"
                                       class="form-control @error('cin_number') is-invalid @enderror"
                                       placeholder="AB123456">
                                @error('cin_number')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- CIN VALID UNTIL --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Validité CIN</label>
                                <input type="date"
                                       name="cin_valid_until"
                                       value="{{ old('cin_valid_until') }}"
                                       class="form-control @error('cin_valid_until') is-invalid @enderror">
                                @error('cin_valid_until')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- PASSPORT NUMBER --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Numéro passeport</label>
                                <input type="text"
                                       name="passport_number"
                                       value="{{ old('passport_number') }}"
                                       class="form-control @error('passport_number') is-invalid @enderror"
                                       placeholder="AB123456">
                                @error('passport_number')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- PASSPORT ISSUE DATE --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Date délivrance passeport</label>
                                <input type="date"
                                       name="passport_issue_date"
                                       value="{{ old('passport_issue_date') }}"
                                       class="form-control @error('passport_issue_date') is-invalid @enderror">
                                @error('passport_issue_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- DRIVING LICENSE NUMBER --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Numéro permis</label>
                                <input type="text"
                                       name="driving_license_number"
                                       value="{{ old('driving_license_number') }}"
                                       class="form-control @error('driving_license_number') is-invalid @enderror"
                                       placeholder="AB123456">
                                @error('driving_license_number')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- DRIVING LICENSE ISSUE DATE --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Date délivrance permis</label>
                                <input type="date"
                                       name="driving_license_issue_date"
                                       value="{{ old('driving_license_issue_date') }}"
                                       class="form-control @error('driving_license_issue_date') is-invalid @enderror">
                                @error('driving_license_issue_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
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
{{-- Edit Agent --}}
<div class="modal fade" id="edit_agent">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="mb-0">Modifier un agent</h5>
                <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ti ti-x fs-16"></i>
                </button>
            </div>

            <form id="editAgentForm"
                  action="#"
                  method="POST"
                  class="needs-validation"
                  novalidate>
                @csrf
                @method('PUT')

                <div class="modal-body pb-1">
                    <div class="row">

                        {{-- AGENCY --}}
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Agence <span class="text-danger">*</span></label>
                                <select id="editAgentAgency"
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
                                       id="editAgentFullName"
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
                                       id="editAgentEmail"
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
                                       id="editAgentPhone"
                                       name="phone"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       maxlength="50">
                                @error('phone')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- LINKED USER --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Utilisateur lié</label>
                                <select id="editAgentUser"
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

                        {{-- NOTES --}}
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea id="editAgentNotes"
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
{{-- /Edit Agent --}}
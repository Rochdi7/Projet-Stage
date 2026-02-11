{{-- Add Agent --}}
<div class="modal fade" id="add_agent">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="mb-0">Créer un agent</h5>
                <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ti ti-x fs-16"></i>
                </button>
            </div>

            <form id="addAgentForm"
                  action="{{ route('backoffice.agents.store') }}"
                  method="POST"
                  class="needs-validation"
                  novalidate>
                @csrf

                <div class="modal-body pb-1">
                    <div class="row">

                        {{-- AGENCY (super-admin only or always show) --}}
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
                                       placeholder="agent@example.com">
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
                                <small class="text-muted">Lier un compte utilisateur existant à cet agent</small>
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
                            <i class="ti ti-plus me-1"></i>Créer l'agent
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>
{{-- /Add Agent --}}
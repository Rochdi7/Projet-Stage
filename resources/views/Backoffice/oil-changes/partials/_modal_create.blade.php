{{-- Add Vignette Modal --}}
<div class="modal fade" id="add_vignette">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="mb-0">Ajouter une vignette</h5>
                <button type="button" class="btn-close custom-btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="ti ti-x fs-16"></i>
                </button>
            </div>

            <form id="addVignetteForm"
                  action="{{ route('backoffice.vehicles.vignettes.store', $vehicle) }}"
                  method="POST"
                  class="needs-validation"
                  novalidate>
                @csrf

                <div class="modal-body pb-1">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Date <span class="text-danger">*</span></label>
                                <input type="date"
                                       name="date"
                                       value="{{ old('date', date('Y-m-d')) }}"
                                       class="form-control"
                                       required
                                       max="{{ date('Y-m-d') }}">
                                <div class="invalid-feedback">Veuillez saisir la date.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Montant <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number"
                                           name="amount"
                                           class="form-control"
                                           required
                                           step="0.01"
                                           min="0"
                                           placeholder="0.00">
                                    <span class="input-group-text">€</span>
                                </div>
                                <div class="invalid-feedback">Veuillez saisir le montant.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Année <span class="text-danger">*</span></label>
                                <select name="year" class="form-select" required>
                                    <option value="">Sélectionner</option>
                                    @for($y = date('Y') + 1; $y >= 2000; $y--)
                                        <option value="{{ $y }}" {{ old('year') == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                                <div class="invalid-feedback">Veuillez sélectionner l'année.</div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="notes"
                                          class="form-control"
                                          rows="3"
                                          placeholder="Informations complémentaires..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="d-flex justify-content-center">
                        <a href="javascript:void(0);" class="btn btn-light me-3" data-bs-dismiss="modal">Annuler</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i>Créer la vignette
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>
{{-- =========================
DELETE CONTROL ITEM MODAL
========================= --}}
<div class="modal fade" id="delete_control_item" tabindex="-1" aria-labelledby="deleteControlItemLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center">
                <span class="avatar avatar-lg bg-transparent-danger rounded-circle text-danger mb-3">
                    <i class="ti ti-trash-x fs-26"></i>
                </span>
                <h4 class="mb-1">Supprimer l'élément</h4>
                <p class="mb-3" id="deleteControlItemText">Êtes-vous sûr de vouloir supprimer cet élément ?</p>
                
                <form method="POST" action="" id="deleteControlItemForm">
                    @csrf
                    @method('DELETE')
                    
                    <div class="d-flex justify-content-center">
                        <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger">Oui, supprimer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteModal = document.getElementById('delete_control_item');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            
            if (button) {
                const action = button.getAttribute('data-delete-action');
                const details = button.getAttribute('data-delete-details') || 'cet élément';
                
                const form = document.getElementById('deleteControlItemForm');
                const text = document.getElementById('deleteControlItemText');
                
                // Debug log
                console.log('Delete action URL:', action);
                
                if (action && form && action !== '#' && action !== '') {
                    form.action = action;
                    // Enable form submission
                    form.onsubmit = null;
                } else {
                    // Disable form submission if no valid action
                    form.action = '#';
                    form.onsubmit = function(e) {
                        e.preventDefault();
                        alert('Impossible de supprimer cet élément: les données sont incomplètes.');
                        return false;
                    };
                }
                
                if (text && details) {
                    text.innerHTML = 'Êtes-vous sûr de vouloir supprimer ' + details + ' ?';
                }
            }
        });
    }
});
</script>
<form wire:submit='delete'>
    <div class="modal-body">
        <h6>
            Are you sure you want to delete this variant?
            <br>
            <b>#{{ $variant?->sku }}</b>
        </h6>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-danger">Yes, Delete</button>
    </div>
</form>

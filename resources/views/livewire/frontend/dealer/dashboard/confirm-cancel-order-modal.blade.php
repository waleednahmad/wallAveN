<form wire:submit='cancelOrder'>
    <div class="modal-body">
        <h6>Are you sure you want to cancel order <b>#{{ $order?->id }}</b></h6>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-danger">Yes, Cancel</button>
    </div>
</form>

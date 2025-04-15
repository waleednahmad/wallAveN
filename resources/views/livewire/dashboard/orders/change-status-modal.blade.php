<form wire:submit.prevent="changeStatus">
    <div wire:loading.remove>
        <div class="modal-body">
            <h5>
                Order: #{{ $order?->id }}
            </h5>
            <hr>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select wire:model="status" class="form-select" id="status">
                    @foreach ($this->statuses as $status)
                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
                @error('status')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
        </div>

    </div>
</form>

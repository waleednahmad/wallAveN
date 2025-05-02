<div class="modal-body">
    <form wire:submit.prevent="update">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" readonly class="form-control" id="name" wire:model="name">
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" wire:model="title">
            @error('title')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="meta_desc" class="form-label">Meta Description</label>
            <textarea class="form-control" id="meta_desc" wire:model="meta_desc" rows="4"></textarea>
            @error('meta_desc')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="keywords" class="form-label">Keywords</label>
            <textarea class="form-control" id="keywords" wire:model="keywords" rows="2"></textarea>
            @error('keywords')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

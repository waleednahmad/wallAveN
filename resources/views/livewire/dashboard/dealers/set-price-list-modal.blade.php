<form wire:submit.prevent='save' enctype="multipart/form-data">
    <div class="modal-body">
        <div class="row">
            {{-- Sender driver --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="price_list_id">Select Price List
                                <span class="text-danger">
                                    *
                                </span>
                            </label>
                            <select @class([
                                'form-control',
                                'is-invalid' => $errors->has('price_list_id'),
                            ]) id="price_list_id" wire:model.live="price_list_id"
                                name="price_list_id" required>
                                <option value="-1">
                                    Select a price list
                                </option>
                                @foreach ($this->priceLists as $list)
                                    <option value="{{ $list->id }}">
                                        {{ $list->name }} ({{ $list->percentage }}%)
                                    </option>
                                @endforeach
                            </select>
                            @error('price_list_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
            wire:click="$dispatch('closeModal')">Close</button>
        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:loading.class="btn-secondary"
            wire:target="save,images,video">
            Save
        </button>
    </div>
</form>

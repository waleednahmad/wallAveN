<form wire:submit.prevent="save">
    <div class="modal-body">
        <div class="row">
            {{-- name --}}
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Name
                        <span class="text-danger">
                            *
                        </span>
                    </label>
                    <input type="text" @class(['form-control', 'is-invalid' => $errors->has('name')]) id="name" required wire:model="name">
                    @error('name')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-between">
                <h5>
                    Values
                </h5>

                <button type="button" class="btn btn-primary" wire:click="addValue" wire:loading.attr="disabled"
                    wire:target="addValue">
                    <i class="fas fa-plus"></i>
                </button>
            </div>

            <div class="pt-3 conatiner">
                <div class="row">
                    @forelse ($values as $value)
                        <div class="col-md-6">
                            <div class="card position-relative">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="value_{{ $loop->index }}">Value
                                            <span class="text-danger">
                                                *
                                            </span>
                                        </label>
                                        <input type="text" @class([
                                            'form-control',
                                            'is-invalid' => $errors->has("values.{$loop->index}.value"),
                                        ])
                                            id="value_{{ $loop->index }}" required
                                            wire:model="values.{{ $loop->index }}.value">
                                        @error("values.{$loop->index}.value")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                {{-- Remove Icon --}}
                                @if (count($values) > 1)
                                    <button type="button" class="btn btn-danger btn-sm position-absolute "
                                        style="top: 5px; right: 5px" wire:click="removeValue({{ $loop->index }})"
                                        wire:loading.attr="disabled">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>
            </div>

            {{-- all errors --}}
            @if ($errors->any())
                <div class="col-md-12">
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>
                                    {{ $error }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
            {{-- spinner --}}
            <span wire:loading>
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            </span>
            Save
        </button>
    </div>
</form>

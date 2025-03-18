<div>
    <div class="modal-body">
        {{-- search --}}
        <div class="mb-3 input-group">
            <input type="text" class="form-control" placeholder="Search dealer" wire:model.live="search">
        </div>

        {{-- dealers preview --}}

        <div class="row">
            @php
                $dealer = auth('representative')->user()?->buyingFor ?? auth('web')->user()?->buyingFor;
                $dealerId = $dealer ? $dealer->id : null;
            @endphp
            @forelse ($dealers as $dealerItem)
                <div class="col-md-4">
                    <div @class([
                        'card',
                        'shadow  border-danger' => $dealerItem->id == $dealerId ? true : false,
                    ])>
                        <div class="card-body">
                            <h5 class="card-title">{{ $dealerItem->name }}</h5>
                            <p class="card-text"
                                style="text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                                {{ $dealerItem->email }}
                            </p>
                            <p class="card-text">{{ $dealerItem->phone }}</p>
                            <hr>
                            <p class="card-text">{{ $dealerItem->address }}</p>
                            <button class="btn" style="background-color: #000; color: #fff;"
                                wire:loading.attr="disabled"
                                wire:click="selectDealer({{ $dealerItem->id }})">Select</button>
                        </div>
                    </div>
                </div>

            @empty
                <div class="col-md-12">
                    <div class="alert alert-warning" role="alert">
                        No dealer found!
                    </div>
                </div>
            @endforelse
        </div>
    </div>
    @if ($dealer)
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" wire:click='removeDealer'>
                Remove Selection
            </button>
        </div>
    @endif

</div>

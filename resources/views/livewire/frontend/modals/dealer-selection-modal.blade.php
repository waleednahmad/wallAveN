<div>
    {{-- search --}}
    <div class="modal-header">
        <div class="input-group">
            <input type="search" class="form-control" placeholder="Search dealer" wire:model.live="search">
        </div>

    </div>
    <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
        {{-- dealers preview --}}

        <div class="row">
            @php
                $dealer = auth('representative')->user()?->buyingFor ?? auth('web')->user()?->buyingFor;
                $dealerId = $dealer ? $dealer->id : null;
            @endphp
            @forelse ($dealers as $dealerItem)
                <div class="col-md-4 mb-2">
                    <div @class([
                        'card h-100',
                        'shadow  border-danger' => $dealerItem->id == $dealerId ? true : false,
                    ])>
                        <div class="card-body d-flex flex-column justify-content-between">
                            <div>

                                @if ($dealerItem->company_name)
                                    <h5 class="card-text">
                                        {{ $dealerItem->company_name }}
                                    </h5>
                                @endif
                                {{-- <h5 class="card-title">{{ $dealerItem->name }}</h5> --}}
                                {{-- <p class="card-text"
                            style="text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                            {{ $dealerItem->email }}
                        </p>
                        <p class="card-text">{{ $dealerItem->phone }}</p> --}}
                                <hr>
                                @php
                                    $address = $dealerItem->address ? explode(',', $dealerItem->address)[0] : '---';
                                    $city = $dealerItem->city ?? '---';
                                    $state = $dealerItem->state ?? '---';
                                    $zip_code = $dealerItem->zip_code ?? '---';
                                @endphp
                                {{ $address }}<br>
                                {{ $city }}{{ $city != '---' ? ',' : '' }}
                                {{ $state }}{{ $state != '---' ? ' ' : '' }}
                                {{ $zip_code }}
                                <p class="card-text">{{ $dealerItem->address }}</p>
                            </div>

                            <button class="btn mt-2" style="background-color: #000; color: #fff;"
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

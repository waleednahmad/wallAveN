<div
    x-on:select-updated="
        if ($event.detail.target === 'category_id') {
            $wire.set('categoryId', $event.detail.value);
        } else if ($event.detail.target === 'subcategory_ids') {
            $wire.set('subcategoryIds', $event.detail.value);
        }
    "
    @if ($this->hasInProgress) wire:poll.5s @endif
>
    <style>
        .catalog-card { border: 0; border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
        .catalog-card .card-header {
            background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
            color: #fff;
            border-radius: 14px 14px 0 0 !important;
            border: 0;
            padding: 1rem 1.25rem;
        }
        .catalog-card .card-header .card-title { color: #fff; font-weight: 600; margin: 0; }
        .catalog-cover-drop {
            border: 2px dashed #cbd5e1;
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            background: #f8fafc;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .catalog-cover-drop:hover { border-color: #2563eb; background: #eff6ff; }
        .catalog-cover-drop .icon { font-size: 1.75rem; color: #94a3b8; margin-bottom: 0.5rem; }
        .catalog-cover-drop input[type=file] { display: none; }
        .catalog-cover-preview { max-height: 110px; border-radius: 8px; }
        .catalog-stat {
            background: #f1f5f9;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .catalog-stat .stat-icon {
            width: 36px; height: 36px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            background: #2563eb; color: #fff;
        }
        .catalog-stat .stat-label { font-size: 0.75rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; }
        .catalog-stat .stat-value { font-size: 1.1rem; font-weight: 600; color: #0f172a; }
        .layout-option {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.75rem;
            cursor: pointer;
            transition: all 0.15s ease;
            text-align: center;
            background: #fff;
        }
        .layout-option:hover { border-color: #93c5fd; }
        .layout-option.active { border-color: #2563eb; background: #eff6ff; }
        .layout-option .grid-preview {
            display: grid; gap: 3px;
            margin: 0 auto 0.5rem; width: 60px;
        }
        .layout-option .grid-preview > div { background: #2563eb; aspect-ratio: 1; border-radius: 2px; opacity: 0.85; }
        .catalog-row { transition: background 0.15s ease; }
        .catalog-row:hover { background: #f8fafc; }
        .status-pill {
            font-size: 0.7rem; padding: 0.25rem 0.6rem; border-radius: 999px;
            font-weight: 600; letter-spacing: 0.02em; text-transform: uppercase;
        }
        .spinner-mini {
            width: 12px; height: 12px; border: 2px solid currentColor;
            border-right-color: transparent; border-radius: 50%;
            display: inline-block; animation: spinner-mini-rotate 0.7s linear infinite;
            vertical-align: -2px;
        }
        @keyframes spinner-mini-rotate { to { transform: rotate(360deg); } }
        .empty-catalogs {
            padding: 3rem 1rem;
            text-align: center;
            color: #94a3b8;
        }
        .empty-catalogs .icon { font-size: 2.5rem; margin-bottom: 0.5rem; opacity: 0.5; }
    </style>

    {{-- Flash messages --}}
    @if (session('catalog_message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('catalog_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('catalog_error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-1"></i> {{ session('catalog_error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-3">
        {{-- LEFT: Form --}}
        <div class="col-lg-7">
            <div class="card catalog-card">
                <div class="card-header d-flex align-items-center">
                    <i class="fas fa-file-pdf me-2"></i>
                    <h5 class="card-title mb-0">Generate New Catalog</h5>
                </div>
                <div class="card-body p-4">
                    <form wire:submit.prevent="generate">
                        {{-- Catalog name --}}
                        <div class="mb-3">
                            <label for="catalogName" class="form-label fw-semibold">Catalog Name <span class="text-muted fw-normal">(optional)</span></label>
                            <input type="text" class="form-control" id="catalogName" wire:model="catalogName"
                                placeholder="e.g. Spring 2026 Collection" maxlength="150">
                        </div>

                        {{-- Filters --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <livewire:form.select-dropdown
                                    :items="$categories"
                                    target-model="category_id"
                                    label="Category"
                                    placeholder="All Categories"
                                    :searchable="true"
                                />
                            </div>
                            <div class="col-md-6">
                                <livewire:form.select-dropdown
                                    :items="$subcategories"
                                    target-model="subcategory_ids"
                                    label="Sub Categories"
                                    placeholder="All Sub Categories"
                                    :searchable="true"
                                    :multiple="true"
                                    :key="'subcat-' . ($categoryId ?? 'none')"
                                />
                            </div>
                        </div>

                        {{-- Estimated count --}}
                        <div class="catalog-stat mb-3">
                            <div class="stat-icon"><i class="fas fa-box"></i></div>
                            <div>
                                <div class="stat-label">Products to include</div>
                                <div class="stat-value">{{ number_format($this->estimatedProductsCount) }} products</div>
                            </div>
                        </div>

                        {{-- Layout selector --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Page Layout</label>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div wire:click="$set('layout', '2x3')"
                                        class="layout-option @if ($layout === '2x3') active @endif">
                                        <div class="grid-preview" style="grid-template-columns: repeat(2, 1fr);">
                                            <div></div><div></div><div></div><div></div><div></div><div></div>
                                        </div>
                                        <div class="fw-semibold small">2 × 3</div>
                                        <div class="text-muted" style="font-size: 0.7rem;">6 per page</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div wire:click="$set('layout', '3x3')"
                                        class="layout-option @if ($layout === '3x3') active @endif">
                                        <div class="grid-preview" style="grid-template-columns: repeat(3, 1fr);">
                                            <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div>
                                        </div>
                                        <div class="fw-semibold small">3 × 3</div>
                                        <div class="text-muted" style="font-size: 0.7rem;">9 per page</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Cover images --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Front Cover</label>
                                <label class="catalog-cover-drop d-block mb-0">
                                    <input type="file" wire:model="frontCover" accept="image/*">
                                    <div wire:loading wire:target="frontCover">
                                        <span class="spinner-mini text-primary"></span>
                                        <div class="small text-primary mt-1">Uploading...</div>
                                    </div>
                                    <div wire:loading.remove wire:target="frontCover">
                                        @if ($frontCover)
                                            <img src="{{ $frontCover->temporaryUrl() }}" class="catalog-cover-preview" alt="Front">
                                            <div class="small text-success mt-1"><i class="fas fa-check"></i> Ready</div>
                                        @else
                                            <i class="fas fa-cloud-upload-alt icon"></i>
                                            <div class="small text-muted">Click to upload front cover</div>
                                        @endif
                                    </div>
                                </label>
                                @error('frontCover') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Back Cover</label>
                                <label class="catalog-cover-drop d-block mb-0">
                                    <input type="file" wire:model="backCover" accept="image/*">
                                    <div wire:loading wire:target="backCover">
                                        <span class="spinner-mini text-primary"></span>
                                        <div class="small text-primary mt-1">Uploading...</div>
                                    </div>
                                    <div wire:loading.remove wire:target="backCover">
                                        @if ($backCover)
                                            <img src="{{ $backCover->temporaryUrl() }}" class="catalog-cover-preview" alt="Back">
                                            <div class="small text-success mt-1"><i class="fas fa-check"></i> Ready</div>
                                        @else
                                            <i class="fas fa-cloud-upload-alt icon"></i>
                                            <div class="small text-muted">Click to upload back cover</div>
                                        @endif
                                    </div>
                                </label>
                                @error('backCover') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Footer text --}}
                        <div class="mb-4">
                            <label for="footerText" class="form-label fw-semibold">Footer Text</label>
                            <input type="text" class="form-control" id="footerText" wire:model="footerText" maxlength="500">
                            <small class="text-muted">Shown at the bottom of every page.</small>
                        </div>

                        {{-- Submit --}}
                        <div class="d-flex align-items-center justify-content-between">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Generation runs in the background &mdash; you can leave this page.
                            </small>
                            <button type="submit" class="btn btn-primary px-4" wire:loading.attr="disabled" wire:target="generate,frontCover,backCover">
                                <span wire:loading.remove wire:target="generate">
                                    <i class="fas fa-rocket me-1"></i> Queue Catalog
                                </span>
                                <span wire:loading wire:target="generate">
                                    <span class="spinner-mini"></span> Queuing...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- RIGHT: Recent catalogs --}}
        <div class="col-lg-5">
            <div class="card catalog-card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-history me-2"></i>
                        <h5 class="card-title mb-0">Recent Catalogs</h5>
                    </div>
                    @if ($this->hasInProgress)
                        <span class="badge bg-light text-primary">
                            <span class="spinner-mini"></span> Live updating
                        </span>
                    @endif
                </div>

                {{-- Filter bar --}}
                <div class="p-3 border-bottom" style="background:#f8fafc;">
                    <div class="position-relative mb-2">
                        <i class="fas fa-search position-absolute"
                           style="top:50%; left:.75rem; transform:translateY(-50%); color:#94a3b8; font-size:.8rem;"></i>
                        <input type="text" class="form-control form-control-sm"
                               style="padding-left:2rem;"
                               wire:model.live.debounce.400ms="search"
                               placeholder="Search by name or footer text...">
                        @if ($search !== '')
                            <button type="button"
                                    class="btn-close position-absolute"
                                    style="top:50%; right:.6rem; transform:translateY(-50%); font-size:.65rem;"
                                    wire:click="$set('search', '')"></button>
                        @endif
                    </div>
                    <div class="row g-2">
                        <div class="col-6">
                            <select class="form-select form-select-sm" wire:model.live="statusFilter">
                                <option value="">All statuses</option>
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="completed">Completed</option>
                                <option value="failed">Failed</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <select class="form-select form-select-sm" wire:model.live="layoutFilter">
                                <option value="">All layouts</option>
                                <option value="2x3">2 × 3 (6 / page)</option>
                                <option value="3x3">3 × 3 (9 / page)</option>
                            </select>
                        </div>
                    </div>
                    @if ($search !== '' || $statusFilter !== '' || $layoutFilter !== '')
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <small class="text-muted">
                                <i class="fas fa-filter me-1"></i>
                                {{ $this->recentCatalogsTotal }} match{{ $this->recentCatalogsTotal === 1 ? '' : 'es' }}
                            </small>
                            <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none"
                                    wire:click="clearFilters">
                                <i class="fas fa-times me-1"></i> Clear filters
                            </button>
                        </div>
                    @endif
                </div>

                <div class="card-body p-0"
                     id="catalog-list-scroll"
                     style="max-height: 640px; overflow-y: auto;">
                    @forelse ($this->recentCatalogs as $catalog)
                        <div class="catalog-row p-3 border-bottom" wire:key="catalog-{{ $catalog->id }}">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="flex-grow-1 me-2" style="min-width: 0;">
                                    <div class="fw-semibold text-truncate">{{ $catalog->name }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">
                                        {{ $catalog->created_at->diffForHumans() }}
                                        @if ($catalog->products_count !== null)
                                            &middot; {{ $catalog->products_count }} products
                                        @endif
                                        @if ($catalog->humanFileSize())
                                            &middot; {{ $catalog->humanFileSize() }}
                                        @endif
                                    </div>
                                </div>
                                <span class="status-pill {{ $catalog->statusBadgeClass() }} text-white flex-shrink-0">
                                    @if ($catalog->isProcessing())
                                        <span class="spinner-mini"></span>
                                    @endif
                                    {{ $catalog->statusLabel() }}
                                </span>
                            </div>

                            @if ($catalog->isFailed() && $catalog->error_message)
                                <div class="alert alert-danger py-1 px-2 mb-2" style="font-size: 0.75rem;">
                                    {{ \Illuminate\Support\Str::limit($catalog->error_message, 200) }}
                                </div>
                            @endif

                            <div class="d-flex gap-2">
                                @if ($catalog->isCompleted())
                                    <a href="{{ route('dashboard.catalog.download', $catalog->id) }}"
                                        class="btn btn-sm btn-success" target="_blank">
                                        <i class="fas fa-download me-1"></i> Download
                                    </a>
                                @endif
                                @if ($catalog->isFailed())
                                    <button type="button" class="btn btn-sm btn-warning"
                                        wire:click="retryCatalog({{ $catalog->id }})">
                                        <i class="fas fa-redo me-1"></i> Retry
                                    </button>
                                @endif
                                @if (!$catalog->isInProgress())
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                        wire:click="deleteCatalog({{ $catalog->id }})"
                                        wire:confirm="Delete this catalog and its file?">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="empty-catalogs">
                            <i class="fas fa-folder-open icon"></i>
                            @if ($search !== '' || $statusFilter !== '' || $layoutFilter !== '')
                                <div>No catalogs match your filters</div>
                                <button type="button" class="btn btn-sm btn-link" wire:click="clearFilters">Clear filters</button>
                            @else
                                <div>No catalogs yet</div>
                                <small>Generated catalogs will appear here.</small>
                            @endif
                        </div>
                    @endforelse

                    {{-- Infinite-scroll sentinel + loading state --}}
                    @if ($this->hasMoreCatalogs)
                        <div class="text-center py-3"
                             x-data="{
                                 observer: null,
                                 init() {
                                     this.observer = new IntersectionObserver((entries) => {
                                         if (entries[0].isIntersecting) {
                                             $wire.loadMore();
                                         }
                                     }, { root: this.$root.closest('#catalog-list-scroll'), rootMargin: '120px' });
                                     this.observer.observe(this.$el);
                                 },
                                 destroy() { this.observer && this.observer.disconnect(); }
                             }"
                             wire:key="catalog-load-more-{{ $perPage }}">
                            <div wire:loading.remove wire:target="loadMore">
                                <button type="button" class="btn btn-sm btn-outline-primary" wire:click="loadMore">
                                    <i class="fas fa-chevron-down me-1"></i> Load more
                                </button>
                            </div>
                            <div wire:loading.flex wire:target="loadMore" class="justify-content-center align-items-center text-primary" style="display:none;">
                                <span class="spinner-mini me-2"></span>
                                <span class="small">Loading more catalogs...</span>
                            </div>
                        </div>
                    @elseif (count($this->recentCatalogs) > 0 && $this->recentCatalogsTotal > 8)
                        <div class="text-center py-3 text-muted small">
                            <i class="fas fa-check-circle me-1"></i>
                            All {{ $this->recentCatalogsTotal }} catalogs loaded
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div
    x-data="{
        open: false,
        search: '',
        selected: @js($selected),
        selectedMultiple: @js($selectedMultiple ?? []),
        multiple: @js($multiple),
        searchable: @js($searchable),
        placeholder: @js($placeholder),
        items: @js($items),
        labelKey: @js($labelKey),
        valueKey: @js($valueKey),
        targetModel: @js($targetModel),

        init() {
            this.$watch('open', (value) => {
                if (value && this.searchable) {
                    this.$nextTick(() => {
                        if (this.$refs.searchInput) {
                            this.$refs.searchInput.focus();
                        }
                    });
                }
                if (!value) {
                    this.search = '';
                }
            });
        },

        toggle() {
            this.open = !this.open;
        },

        close() {
            this.open = false;
        },

        selectItem(value) {
            if (this.multiple) {
                const strValue = String(value);
                const index = this.selectedMultiple.findIndex(v => String(v) == strValue);
                if (index > -1) {
                    this.selectedMultiple = this.selectedMultiple.filter((v, i) => i !== index);
                } else {
                    this.selectedMultiple = [...this.selectedMultiple, value];
                }
                this.$dispatch('select-updated', {
                    target: this.targetModel,
                    value: [...this.selectedMultiple]
                });
            } else {
                this.selected = value;
                this.$dispatch('select-updated', {
                    target: this.targetModel,
                    value: value
                });
                this.close();
            }
        },

        removeItem(value) {
            const strValue = String(value);
            this.selectedMultiple = this.selectedMultiple.filter(v => String(v) != strValue);
            this.$dispatch('select-updated', {
                target: this.targetModel,
                value: [...this.selectedMultiple]
            });
        },

        clearSelection() {
            if (this.multiple) {
                this.selectedMultiple = [];
                this.$dispatch('select-updated', {
                    target: this.targetModel,
                    value: []
                });
            } else {
                this.selected = null;
                this.$dispatch('select-updated', {
                    target: this.targetModel,
                    value: null
                });
            }
        },

        isSelected(value) {
            if (this.multiple) {
                return this.selectedMultiple.some(v => String(v) == String(value));
            }
            return this.selected !== null && String(this.selected) == String(value);
        },

        getSelectedLabel() {
            if (this.selected === null || this.selected === '' || this.selected === undefined) {
                return '';
            }
            const item = this.items.find(i => String(i[this.valueKey]) == String(this.selected));
            return item ? item[this.labelKey] : '';
        },

        getDisplayText() {
            if (this.multiple) {
                const count = this.selectedMultiple.length;
                return count > 0 ? count + ' selected' : this.placeholder;
            }
            const label = this.getSelectedLabel();
            return label || this.placeholder;
        },

        getSelectedItems() {
            return this.items.filter(item =>
                this.selectedMultiple.some(v => String(v) == String(item[this.valueKey]))
            ).map(item => ({
                value: item[this.valueKey],
                label: item[this.labelKey]
            }));
        },

        getFilteredItems() {
            if (!this.search || this.search.trim() === '') {
                return this.items;
            }
            const searchLower = this.search.toLowerCase();
            return this.items.filter(item => {
                const label = String(item[this.labelKey] || '');
                return label.toLowerCase().includes(searchLower);
            });
        },

        hasSelection() {
            if (this.multiple) {
                return this.selectedMultiple.length > 0;
            }
            return this.selected !== null && this.selected !== '' && this.selected !== undefined;
        }
    }"
    @click.outside="close()"
    @keydown.escape.window="open && close()"
    class="position-relative">

    <style>
        .select-dropdown-rotate {
            transform: rotate(180deg);
        }
        .select-dropdown-transition {
            transition: transform 0.2s ease-in-out;
        }
        .select-dropdown-item:hover {
            background-color: #f8f9fa;
        }
        .select-dropdown-item.selected {
            background-color: #0d6efd;
            color: white;
        }
        .select-dropdown-item.selected:hover {
            background-color: #0b5ed7;
        }
    </style>

    {{-- Label --}}
    @if ($label)
        <label class="form-label">
            {{ $label }}
            @if ($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    {{-- Main Select Button --}}
    <div
        @click="toggle()"
        class="form-control d-flex align-items-center justify-content-between"
        style="cursor: pointer; min-height: 38px;"
        :class="{ 'border-primary shadow-sm': open }">

        {{-- Display Selected Items --}}
        <div class="d-flex flex-wrap gap-1 flex-grow-1 overflow-hidden">
            <template x-if="multiple">
                <template x-if="selectedMultiple.length === 0">
                    <span class="text-muted" x-text="placeholder"></span>
                </template>
            </template>
            <template x-if="multiple && selectedMultiple.length > 0">
                <div class="d-flex flex-wrap gap-1">
                    <template x-for="item in getSelectedItems()" :key="item.value">
                        <span class="badge bg-primary d-flex align-items-center gap-1 py-1">
                            <span x-text="item.label"></span>
                            <span
                                @click.stop="removeItem(item.value)"
                                class="ms-1 cursor-pointer"
                                style="cursor: pointer; font-size: 0.7rem; line-height: 1;">
                                &times;
                            </span>
                        </span>
                    </template>
                </div>
            </template>
            <template x-if="!multiple">
                <span x-text="getDisplayText()" :class="{ 'text-muted': !hasSelection() }"></span>
            </template>
        </div>

        {{-- Clear & Arrow Icons --}}
        <div class="d-flex align-items-center gap-2 ms-2 flex-shrink-0">
            <template x-if="hasSelection()">
                <span
                    @click.stop="clearSelection()"
                    class="text-muted"
                    style="cursor: pointer; font-size: 0.85rem; line-height: 1;">
                    &times;
                </span>
            </template>
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="12"
                height="12"
                fill="currentColor"
                class="select-dropdown-transition text-muted"
                :class="{ 'select-dropdown-rotate': open }"
                viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
            </svg>
        </div>
    </div>

    {{-- Dropdown Panel --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="position-absolute w-100 bg-white border rounded shadow mt-1"
        style="z-index: 1055; max-height: 300px; overflow: hidden;"
        @click.stop>

        {{-- Search Input --}}
        <template x-if="searchable">
            <div class="p-2 border-bottom">
                <input
                    type="text"
                    x-ref="searchInput"
                    x-model="search"
                    class="form-control form-control-sm"
                    placeholder="Search..."
                    @keydown.enter.prevent
                    @keydown.escape.stop="close()">
            </div>
        </template>

        {{-- Options List --}}
        <div class="overflow-auto" style="max-height: 220px;">
            <template x-for="item in getFilteredItems()" :key="item[valueKey]">
                <div
                    @click.stop="selectItem(item[valueKey])"
                    class="px-3 py-2 d-flex align-items-center justify-content-between select-dropdown-item"
                    :class="{ 'selected': isSelected(item[valueKey]) }"
                    style="cursor: pointer;">
                    <span x-text="item[labelKey]"></span>
                    <template x-if="isSelected(item[valueKey])">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                        </svg>
                    </template>
                </div>
            </template>
            <template x-if="getFilteredItems().length === 0">
                <div class="px-3 py-3 text-muted text-center">
                    <small>No options found</small>
                </div>
            </template>
        </div>
    </div>

    {{-- Hidden inputs for form submission --}}
    <template x-if="multiple">
        <template x-for="val in selectedMultiple" :key="'hidden-' + val">
            <input type="hidden" :name="targetModel + '[]'" :value="val">
        </template>
    </template>
    <template x-if="!multiple">
        <input type="hidden" :name="targetModel" :value="selected || ''">
    </template>
</div>

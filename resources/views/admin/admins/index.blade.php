@extends('admin.layout.app')

@section('breadcrumb')
    @include('admin.layout.partials.page-header', [
        'title' => 'Admins',
        'links' => [
            [
                'name' => 'Dashboard',
                'url' => route('dashboard'),
            ],
            [
                'name' => 'Admins',
                'url' => '#',
            ],
        ],
    ])
@endsection

@php
    $admin = null;
@endphp

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#createAdmin"
                    aria-controls="createAdmin">
                    Add new admin
                </button>
            </div>
        </div>
        <div class="card-body">
            <livewire:tables.admin-table />
        </div>
    </div>

    {{-- Create Offacnvas --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="createAdmin" aria-labelledby="createAdminLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="createAdminLabel">
                Add New Admin
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="card">
                <livewire:dashboard.admins.create-admin-form />
            </div>
        </div>
    </div>

    {{-- Edit Offcanvas --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="editAdmin" aria-labelledby="editAdminLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="editAdminLabel">
                Edit Admin
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="card">

                <livewire:dashboard.admins.edit-admin-form :admin="$admin" />
            </div>
        </div>
    </div>

    {{-- Update Password --}}
    <div class="offcanvas offcanvas-end" tabindex="-1" id="updatePassword" aria-labelledby="updatePasswordLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="updatePasswordLabel">
                Update Password
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="card">
                <livewire:dashboard.admins.update-password-form :admin="$admin" />
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            // ============== Edit Offcanvas ==============
            Livewire.on('openEditOffcanvas', (event) => {
                let offcanvas = new bootstrap.Offcanvas(document.getElementById('editAdmin'));
                offcanvas.show();
                $admin = event[0];
                Livewire.dispatch('mount', $admin);
            });

            // ============== CLose Edit Offcanvas ==============
            Livewire.on('closeEditOffcanvas', (event) => {
                bootstrap.Offcanvas.getInstance(document.getElementById('editAdmin')).hide();
            });

            // ============== Close Create Offcanvas ==============
            Livewire.on('closeCreateOffcanvas', (event) => {
                bootstrap.Offcanvas.getInstance(document.getElementById('createAdmin')).hide();
            });

            // ============== Update Password Offcanvas ==============
            Livewire.on('openUpdatePasswordOffcanvas', (event) => {
                let offcanvas = new bootstrap.Offcanvas(document.getElementById('updatePassword'));
                offcanvas.show();
                $admin = event[0];
                // dispatch mount event
                Livewire.dispatch('mountPass', $admin);
            });

            // ============== Close Update Password Offcanvas ==============
            Livewire.on('closeUpdatePasswordOffcanvas', (event) => {
                bootstrap.Offcanvas.getInstance(document.getElementById('updatePassword')).hide();
            });
        });
    </script>
@endpush

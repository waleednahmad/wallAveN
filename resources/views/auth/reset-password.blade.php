@extends('frontend.layout.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <h2 class="mb-4 text-center">
                    {{ __('Reset Password') }}
                </h2>

                <div class="card">
                    <div class="card-body">
                        <form class="row g-4" method="POST" action="{{ route('password.store') }}">
                            @csrf
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email<span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" required
                                        value="{{ old('email', $request->email) }}">
                                </div>
                            </div>

                            <input type="hidden" name="token" value="{{ $request->route('token') }}">

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password<span
                                            class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password<span
                                            class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" required>
                                </div>
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif


                            <button type="submit" class="btn btn-secondary"
                                style="max-width: 200px; margin: 0 auto; display: block;">
                                Reset Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

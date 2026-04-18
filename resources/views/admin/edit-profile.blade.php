@extends('admin.layouts.app')

@section('title', $title ?? 'Edit Profile')
@section('body_class', 'edit-profile-page')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/edit-profile.css') }}?v={{ filemtime(public_path('css/edit-profile.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/shared-navbar.css') }}?v={{ filemtime(public_path('css/shared-navbar.css')) }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@endpush

@section('content')
    <div class="profile-page">
        <main class="profile-shell">
            <x-topbar active="" :can-access-audit="$canAccessAudit" :user="$user" />

            <div class="container">
                <div class="profile-header">
                    <h2>Personal Information</h2>
                    <button type="button" class="btn btn-danger" data-open-signout-modal>Sign Out</button>
                </div>

                @if (session('profile_status'))
                    <p class="flash-message flash-success">{{ session('profile_status') }}</p>
                @endif

                @if ($errors->any())
                    <p class="flash-message flash-error">{{ $errors->first() }}</p>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="profile-form">
                    @csrf

                    <div class="form-group form-group-media">
                        <div class="media-stack">
                            @if ($profileImageUrl)
                                <img src="{{ $profileImageUrl }}" alt="{{ $user->name }}" class="avatar-preview" data-avatar-preview>
                            @else
                                <div class="avatar-mark" data-avatar-placeholder>
                                    {{ strtoupper(collect(explode(' ', $user->name))->filter()->map(fn ($part) => substr($part, 0, 1))->take(2)->implode('')) }}
                                </div>
                            @endif

                            <input type="file" name="profile_image" accept="image/*" data-avatar-input>
                        </div>

                        <div class="media-stack">
                            @if ($signatureImageUrl)
                                <img src="{{ $signatureImageUrl }}" alt="Signature of {{ $user->name }}" class="signature-preview" data-signature-preview>
                            @else
                                <div class="signature-mark">No signature uploaded</div>
                            @endif

                            <input type="file" name="signature_image" accept="image/*" data-signature-input>
                        </div>
                    </div>

                    <div class="two-column">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="first_name" value="{{ $firstName }}" required>
                        </div>

                        <div class="form-group">
                            <label>Middle Name</label>
                            <input type="text" name="middle_name" value="{{ $middleName }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="last_name" value="{{ $lastName }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Position</label>
                        <select name="position">
                            <option value="">Position</option>
                            @foreach ($positionOptions as $option)
                                <option value="{{ $option }}" {{ $position === $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Project</label>
                        <select name="project">
                            <option value="">Project</option>
                            @foreach ($projectOptions as $option)
                                <option value="{{ $option }}" {{ $project === $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Bureau</label>
                        <select name="bureau">
                            <option value="">Bureau</option>
                            @foreach ($bureauOptions as $option)
                                <option value="{{ $option }}" {{ $bureau === $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    
                    <div class="form-group">
                        <label>Office</label>
                        <select name="office">
                            @foreach ($officeOptions as $option)
                                <option value="{{ $option }}" {{ $office === $option ? 'selected' : '' }}>
                                    {{ $option }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </main>
    </div>

    <div class="confirm-backdrop" data-signout-modal>
        <div class="confirm-modal">
            <h3>Sign Out</h3>
            <p>Are you sure you want to sign out?</p>

            <button type="button" data-close-signout-modal class="btn">Cancel</button>
            <a href="{{ route('logout') }}" class="btn btn-danger">Confirm</a>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/profile.js') }}" defer></script>
@endpush

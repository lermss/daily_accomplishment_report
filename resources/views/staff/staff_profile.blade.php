@extends('staff.layouts.app')

@section('content')
@php
    // ADD THIS CODE
    $staffRouteBase = app(\App\Services\AuthFlowService::class)->staffPortalPrefix($user->role ?? null);
@endphp

<link rel="stylesheet" href="{{ asset('css/edit-profile.css') }}">

<style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f9;
            margin: 0;
        }

        /* PROFILE CONTAINER */
        .container {
            max-width: 1000px;
            margin: 30px auto;
            background: #fff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        .profile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .avatar-preview, .avatar-mark {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            object-fit: cover;
            background: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 24px;
        }

        .signature-preview, .signature-mark {
            width: 220px;
            height: 80px;
            border-radius: 8px;
            object-fit: contain;
            background: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
            font-size: 14px;
            padding: 8px;
        }

        .profile-form {
            margin-top: 30px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .two-column {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 10px 18px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-primary {
            background: #1f4e79;
            color: white;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }
</style>

<!-- PROFILE -->
<div class="container">

    <div class="profile-header">
        <h2>Personal Information</h2>
        <button type="button" class="btn btn-danger" id="staffSignOutTrigger">Sign Out</button>
    </div>

    <form method="POST" action="{{ route($staffRouteBase . '.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @php
            $normalizeStoragePath = static function (?string $path): ?string {
                if (!$path) {
                    return null;
                }

                $path = str_replace('\\', '/', trim($path));
                $path = preg_replace('#^/?storage/app/public/#', '', $path);
                $path = preg_replace('#^/?public/#', '', $path);
                $path = preg_replace('#^/?storage/#', '', $path);

                return ltrim($path, '/');
            };

            $profilePath = $normalizeStoragePath(($user->profile_image ?? null) ?: ($user->avatar_path ?? null));
            $signaturePath = $normalizeStoragePath(($user->signature_image ?? null) ?: ($user->signature_path ?? null));

            $profileAssetUrl = $profilePath ? asset('storage/' . $profilePath) : asset('images/default.png');
            $signatureAssetUrl = $signaturePath ? asset('storage/' . $signaturePath) : null;
            $resolvedProfileImageUrl = $profileImageUrl ?: $profileAssetUrl;
            $resolvedSignatureImageUrl = ($signatureImageUrl ?? null) ?: $signatureAssetUrl;
        @endphp

        <!-- PROFILE IMAGE -->
        <div class="form-group">
            @if ($resolvedProfileImageUrl)
                <img src="{{ $resolvedProfileImageUrl }}" class="avatar-preview" alt="{{ $user->name }}">
            @else
                <div class="avatar-mark">
                    {{ strtoupper(substr($user->name,0,2)) }}
                </div>
            @endif

            <input type="file" name="profile_image">
        </div>

        <!-- SIGNATURE IMAGE -->
        <div class="form-group">
            @if ($resolvedSignatureImageUrl)
                <img src="{{ $resolvedSignatureImageUrl }}" class="signature-preview" alt="Signature of {{ $user->name }}">
            @else
                <div class="signature-mark">No signature uploaded</div>
            @endif

            <input type="file" name="signature_image">
        </div>

        <!-- NAME -->
        <div class="two-column">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="first_name" value= "{{ $firstName }}" readonly>
            </div>
    
           
            <div class="form-group">
                <label>Middle Name</label>
                <input type="text" name="middle_name" value="{{ $middleName }}" readonly>
            </div>

            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name" value="{{ $lastName }}" readonly>
            </div>
        </div>

        <!-- SELECTS -->
        <div class="form-group">
            <label>Position</label>
            <select name="position">
                <option value="">Select position</option>
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

        <!-- SAVE -->
        <button type="submit" class="btn btn-primary">Save Changes</button>

    </form>
</div>

<script src="{{ asset('js/profile.js') }}" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const signOutTrigger = document.getElementById('staffSignOutTrigger');

    if (!signOutTrigger) {
        return;
    }

    signOutTrigger.addEventListener('click', function () {
        if (typeof window.openStaffConfirmModal !== 'function') {
            window.location.href = '{{ route('logout') }}';
            return;
        }

        window.openStaffConfirmModal({
            title: 'Sign Out',
            message: 'Are you sure you want to sign out?',
            confirmText: 'Sign Out',
            cancelText: 'Cancel',
            variant: 'danger',
            onConfirm: function () {
                window.location.href = '{{ route('logout') }}';
            }
        });
    });
});
</script>

@endsection

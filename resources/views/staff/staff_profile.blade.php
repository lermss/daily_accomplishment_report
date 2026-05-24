@extends('staff.layouts.app')

@section('content')
@php
    // ADD THIS CODE
    $staffRouteBase = app(\App\Services\AuthFlowService::class)->staffPortalPrefix($user->role ?? null);
@endphp

<link rel="stylesheet" href="{{ asset('css/edit-profile.css') }}">

<style>
    body { font-family: 'Poppins', sans-serif; background: linear-gradient(145deg, #f0f4f9, #e8eef6); margin: 0; min-height: 100vh; }

    /* ── OUTER WRAPPER ── */
    .profile-wrapper { max-width: 860px; margin: 36px auto; padding: 0 20px 40px; }

    /* ── HERO CARD ── */
    .profile-hero-card {
        background: linear-gradient(135deg, #1e3a5f 0%, #0c5ea0 55%, #1976d2 100%);
        border-radius: 24px 24px 0 0;
        padding: 36px 36px 28px;
        display: flex; align-items: center; gap: 24px;
        position: relative; overflow: hidden;
        box-shadow: 0 8px 32px rgba(10,63,114,.28);
    }
    .profile-hero-card::after {
        content: ''; position: absolute; width: 220px; height: 220px;
        border-radius: 50%; background: rgba(255,255,255,.06);
        top: -70px; right: -70px;
    }
    .profile-hero-avatar-ring {
        width: 88px; height: 88px; border-radius: 50%; flex-shrink: 0;
        border: 3px solid rgba(255,255,255,.5);
        box-shadow: 0 0 0 6px rgba(255,255,255,.12);
        overflow: hidden; background: rgba(255,255,255,.18);
        display: flex; align-items: center; justify-content: center;
        font-size: 28px; font-weight: 700; color: #fff;
    }
    .profile-hero-avatar-ring img { width: 100%; height: 100%; object-fit: cover; }
    .profile-hero-info h1 { font-size: 1.5rem; font-weight: 700; color: #fff; margin: 0 0 4px; }
    .profile-hero-info .role-badge {
        display: inline-block; padding: 3px 12px; border-radius: 50px;
        background: rgba(255,255,255,.2); color: rgba(255,255,255,.9);
        font-size: .78rem; font-weight: 600; letter-spacing: .3px;
    }
    .profile-hero-info .email-badge { display: block; color: rgba(255,255,255,.65); font-size: .82rem; margin-top: 5px; }

    /* ── FORM CARD ── */
    .profile-form-card {
        background: #fff; border-radius: 0 0 24px 24px;
        padding: 32px 36px 36px;
        box-shadow: 0 8px 32px rgba(0,0,0,.08);
    }
    .profile-form-card h2 { font-size: 1rem; font-weight: 700; color: #1e293b; margin-bottom: 24px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9; }

    /* ── MEDIA UPLOAD ROW ── */
    .media-upload-row { display: flex; gap: 28px; flex-wrap: wrap; margin-bottom: 24px; align-items: flex-start; }
    .media-upload-item { display: flex; flex-direction: column; gap: 8px; }
    .media-upload-label { font-size: .78rem; font-weight: 600; color: #64748b; letter-spacing: .4px; text-transform: uppercase; }
    .avatar-preview, .avatar-mark {
        width: 80px; height: 80px; border-radius: 50%; object-fit: cover;
        background: linear-gradient(145deg, #eef3f8, #b7c9da);
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 22px; color: #1f4e79;
        border: 3px solid #e2e8f0;
    }
    .signature-preview, .signature-mark {
        width: 200px; min-height: 72px; border-radius: 10px;
        object-fit: contain; background: #f8fafc;
        border: 1.5px dashed #cbd5e1;
        display: flex; align-items: center; justify-content: center;
        color: #94a3b8; font-size: .82rem; padding: 8px 12px;
    }
    .media-upload-item input[type="file"] { font-size: .78rem; color: #64748b; }

    /* ── TWO COLUMN ── */
    .two-column { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }

    /* ── FORM GROUPS ── */
    .form-group { margin-bottom: 18px; }
    .form-group label { display: block; margin-bottom: 6px; font-size: .82rem; font-weight: 600; color: #475569; }

    .form-group input, .form-group select {
        width: 100%; padding: 10px 14px; border-radius: 10px;
        border: 1.5px solid #e2e8f0; background: #fff;
        font: inherit; font-size: .875rem; color: #1e293b;
        outline: none; transition: border-color .2s, box-shadow .2s;
        box-shadow: 0 1px 3px rgba(0,0,0,.04);
    }
    .form-group input:focus, .form-group select:focus {
        border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,.12);
    }
    .form-group input[readonly] { background: #f8fafc; color: #94a3b8; cursor: default; }
    .form-group input[readonly]:focus { border-color: #e2e8f0; box-shadow: none; }

    /* ── BUTTONS ── */
    .form-actions { display: flex; gap: 12px; margin-top: 28px; }
    .btn {
        padding: 11px 24px; border: none; border-radius: 50px;
        font: inherit; font-size: .875rem; font-weight: 600;
        cursor: pointer; transition: transform .2s, box-shadow .2s;
    }
    .btn-primary {
        background: linear-gradient(135deg, #1e40af, #1d4ed8);
        color: #fff; box-shadow: 0 4px 16px rgba(29,78,216,.3);
    }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(29,78,216,.4); }
    .btn-danger {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        color: #fff; box-shadow: 0 4px 16px rgba(220,38,38,.3);
    }
    .btn-danger:hover { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(220,38,38,.4); }

    /* ── RESPONSIVE ── */
    @media (max-width: 640px) {
        .profile-hero-card { flex-direction: column; text-align: center; padding: 28px 20px; }
        .profile-form-card { padding: 24px 20px; }
        .two-column { grid-template-columns: 1fr; }
        .media-upload-row { flex-direction: column; }
        .signature-preview, .signature-mark { width: 100%; }
    }
</style>

<!-- PROFILE -->
<div class="profile-wrapper">

    @php
        $normalizeStoragePath = static function (?string $path): ?string {
            if (!$path) return null;
            $path = str_replace('\\', '/', trim($path));
            $path = preg_replace('#^/?storage/app/public/#', '', $path);
            $path = preg_replace('#^/?public/#', '', $path);
            $path = preg_replace('#^/?storage/#', '', $path);
            return ltrim($path, '/');
        };

        $profilePath   = $normalizeStoragePath(($user->profile_image ?? null) ?: ($user->avatar_path ?? null));
        $signaturePath = $normalizeStoragePath(($user->signature_image ?? null) ?: ($user->signature_path ?? null));
        $profileAssetUrl   = $profilePath   ? asset('storage/' . $profilePath)   : null;
        $signatureAssetUrl = $signaturePath ? asset('storage/' . $signaturePath) : null;
        $resolvedProfileImageUrl   = $profileImageUrl  ?: $profileAssetUrl;
        $resolvedSignatureImageUrl = ($signatureImageUrl ?? null) ?: $signatureAssetUrl;

        $initials = strtoupper(collect(explode(' ', $user->name))->filter()->map(fn($p) => substr($p,0,1))->take(2)->implode(''));
        $roleLabel = ucwords(str_replace(['-','_'], ' ', $user->role ?? ''));
    @endphp

    <!-- Hero Card -->
    <div class="profile-hero-card">
        <div class="profile-hero-avatar-ring">
            @if ($resolvedProfileImageUrl)
                <img src="{{ $resolvedProfileImageUrl }}" alt="{{ $user->name }}">
            @else
                {{ $initials }}
            @endif
        </div>
        <div class="profile-hero-info">
            <h1>{{ $user->name }}</h1>
            <span class="role-badge">{{ $roleLabel }}</span>
            <span class="email-badge">{{ $user->email }}</span>
        </div>
    </div>

    <!-- Form Card -->
    <div class="profile-form-card">
        <h2>Personal Information</h2>

        <form method="POST" action="{{ route($staffRouteBase . '.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- MEDIA UPLOADS -->
            <div class="media-upload-row">
                <div class="media-upload-item">
                    <span class="media-upload-label">Profile Photo</span>
                    @if ($resolvedProfileImageUrl)
                        <img src="{{ $resolvedProfileImageUrl }}" class="avatar-preview" alt="{{ $user->name }}">
                    @else
                        <div class="avatar-mark">{{ $initials }}</div>
                    @endif
                    <input type="file" name="profile_image">
                </div>

                <div class="media-upload-item">
                    <span class="media-upload-label">Signature</span>
                    @if ($resolvedSignatureImageUrl)
                        <img src="{{ $resolvedSignatureImageUrl }}" class="signature-preview" alt="Signature">
                    @else
                        <div class="signature-mark">No signature uploaded</div>
                    @endif
                    <input type="file" name="signature_image">
                </div>
            </div>

            <!-- NAME -->
            <div class="two-column">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" value="{{ $firstName }}" readonly>
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

            <!-- DROPDOWNS -->
            <div class="form-group">
                <label>Position</label>
                <select name="position">
                    <option value="">Select position</option>
                    @foreach ($positionOptions as $option)
                        <option value="{{ $option }}" {{ $position === $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Project</label>
                <select name="project">
                    @foreach ($projectOptions as $option)
                        <option value="{{ $option }}" {{ $project === $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Bureau</label>
                <select name="bureau">
                    @foreach ($bureauOptions as $option)
                        <option value="{{ $option }}" {{ $bureau === $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Office</label>
                <select name="office">
                    @foreach ($officeOptions as $option)
                        <option value="{{ $option }}" {{ $office === $option ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
            </div>

            <!-- ACTIONS -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <button type="button" class="btn btn-danger" id="staffSignOutTrigger">Sign Out</button>
            </div>
        </form>
    </div>
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

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Daily Accomplishment Report</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/shared-navbar.css') }}?v={{ filemtime(public_path('css/shared-navbar.css')) }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>

    /* ACTION ICONS */
.report-actions button{
border:none;
background:#f8f9fa;
width:35px;
height:35px;
border-radius:8px;
font-size:16px;
margin-left:6px;
display:flex;
align-items:center;
justify-content:center;
transition:0.2s;
}

.report-actions button:hover{
transform:scale(1.1);
}

/* EDIT */
.edit-btn{
color:#198754;
}

.edit-btn:hover{
background:#e8f5e9;
}

/* DELETE */
.delete-btn{
color:#dc3545;
}

.delete-btn:hover{
background:#fdecea;
}

.staff-confirm-modal[hidden] {
display:none;
}

.staff-confirm-modal {
position:fixed;
inset:0;
z-index:1400;
display:grid;
place-items:center;
padding:20px;
}

.staff-confirm-modal__backdrop {
position:absolute;
inset:0;
background:rgba(15, 23, 42, 0.55);
backdrop-filter:blur(6px);
}

.staff-confirm-modal__dialog {
position:relative;
width:min(460px, 100%);
padding:32px 28px 24px;
border-radius:28px;
border:1px solid rgba(215, 227, 240, 0.9);
background:
radial-gradient(circle at top, rgba(219, 234, 254, 0.9), rgba(255, 255, 255, 0) 42%),
linear-gradient(180deg, #ffffff 0%, #f7fbff 100%);
box-shadow:0 30px 70px rgba(15, 23, 42, 0.22);
text-align:center;
}

.staff-confirm-modal__icon {
width:72px;
height:72px;
margin:0 auto 18px;
border-radius:22px;
display:inline-flex;
align-items:center;
justify-content:center;
background:linear-gradient(135deg, #dbeafe, #bfdbfe);
color:#1d4ed8;
box-shadow:0 16px 28px rgba(29, 78, 216, 0.16);
}

.staff-confirm-modal__icon svg {
width:30px;
height:30px;
fill:currentColor;
}

.staff-confirm-modal__icon.is-danger {
background:linear-gradient(135deg, #fee2e2, #fecaca);
color:#dc2626;
box-shadow:0 16px 28px rgba(220, 38, 38, 0.16);
}

.staff-confirm-modal__icon.is-success {
background:linear-gradient(135deg, #dcfce7, #bbf7d0);
color:#15803d;
box-shadow:0 16px 28px rgba(21, 128, 61, 0.16);
}

.staff-confirm-modal__copy {
display:grid;
gap:10px;
}

.staff-confirm-modal__copy h2 {
margin:0;
font-size:1.35rem;
line-height:1.2;
font-weight:700;
color:#17324b;
}

.staff-confirm-modal__copy p {
margin:0 auto;
max-width:32ch;
font-size:0.96rem;
line-height:1.65;
color:#5f7082;
}

.staff-confirm-modal__actions {
display:flex;
justify-content:center;
gap:12px;
margin-top:24px;
flex-wrap:wrap;
}

.staff-confirm-button {
min-width:136px;
min-height:46px;
padding:0 18px;
border-radius:14px;
border:1px solid transparent;
font:inherit;
font-size:0.9rem;
font-weight:600;
cursor:pointer;
transition:transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease, background-color 0.2s ease, color 0.2s ease;
}

.staff-confirm-button:hover {
transform:translateY(-1px);
}

.staff-confirm-button--secondary {
background:#ffffff;
border-color:#d3dfeb;
color:#52667a;
box-shadow:inset 0 1px 0 rgba(255, 255, 255, 0.9);
}

.staff-confirm-button--secondary:hover {
background:#f8fbfe;
border-color:#bed0e0;
color:#1f3d58;
}

.staff-confirm-button--primary {
background:linear-gradient(135deg, #1f4e79, #2c6ba3);
color:#ffffff;
box-shadow:0 16px 28px rgba(31, 78, 121, 0.22);
}

.staff-confirm-button--primary:hover {
box-shadow:0 20px 34px rgba(31, 78, 121, 0.28);
}

.staff-confirm-button--danger {
background:linear-gradient(135deg, #dc2626, #b91c1c);
color:#ffffff;
box-shadow:0 16px 28px rgba(220, 38, 38, 0.22);
}

.staff-confirm-button--danger:hover {
box-shadow:0 20px 34px rgba(220, 38, 38, 0.28);
}

.staff-confirm-button--success {
background:linear-gradient(135deg, #15803d, #16a34a);
color:#ffffff;
box-shadow:0 16px 28px rgba(21, 128, 61, 0.22);
}

.staff-confirm-button--success:hover {
box-shadow:0 20px 34px rgba(21, 128, 61, 0.28);
}

.staff-confirm-button:focus-visible {
outline:3px solid rgba(31, 78, 121, 0.16);
outline-offset:2px;
}

@media (max-width: 576px) {
.staff-confirm-modal {
padding:16px;
}

.staff-confirm-modal__dialog {
padding:24px 20px 20px;
border-radius:24px;
}

.staff-confirm-modal__icon {
width:64px;
height:64px;
border-radius:20px;
}

.staff-confirm-modal__actions {
flex-direction:column-reverse;
}

.staff-confirm-button {
width:100%;
}
}

</style>

@stack('styles')

</head>

<body class="@yield('body_class')">

@include('partials.navbar-staff')

<!-- PAGE CONTENT -->

@hasSection('full_width_content')
    @yield('full_width_content')
@else
    <div class="container mt-4">
        @yield('content')
    </div>
@endif

@include('staff.layouts.confirm-modal')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.querySelector('[data-staff-confirm-modal]');
    const titleNode = document.querySelector('[data-staff-confirm-title]');
    const messageNode = document.querySelector('[data-staff-confirm-message]');
    const iconNode = document.querySelector('[data-staff-confirm-icon]');
    const cancelButton = document.querySelector('[data-staff-confirm-cancel]');
    const confirmButton = document.querySelector('[data-staff-confirm-submit]');
    const closeTargets = document.querySelectorAll('[data-staff-confirm-close]');

    if (!modal || !titleNode || !messageNode || !iconNode || !cancelButton || !confirmButton) {
        return;
    }

    let onConfirm = null;
    let previousOverflow = '';

    function closeStaffConfirmModal() {
        modal.hidden = true;
        document.body.style.overflow = previousOverflow;
        onConfirm = null;
        confirmButton.classList.remove('staff-confirm-button--danger', 'staff-confirm-button--success');
        confirmButton.classList.add('staff-confirm-button--primary');
        confirmButton.textContent = 'Confirm';
        cancelButton.textContent = 'Cancel';
        iconNode.classList.remove('is-danger', 'is-success');
    }

    window.openStaffConfirmModal = function (options) {
        const config = options || {};

        titleNode.textContent = config.title || 'Confirm Action';
        messageNode.textContent = config.message || 'Are you sure you want to continue?';
        cancelButton.textContent = config.cancelText || 'Cancel';
        confirmButton.textContent = config.confirmText || 'Confirm';

        confirmButton.classList.remove('staff-confirm-button--primary', 'staff-confirm-button--danger', 'staff-confirm-button--success');
        iconNode.classList.remove('is-danger', 'is-success');

        if (config.variant === 'danger') {
            confirmButton.classList.add('staff-confirm-button--danger');
            iconNode.classList.add('is-danger');
        } else if (config.variant === 'success') {
            confirmButton.classList.add('staff-confirm-button--success');
            iconNode.classList.add('is-success');
        } else {
            confirmButton.classList.add('staff-confirm-button--primary');
        }

        previousOverflow = document.body.style.overflow;
        document.body.style.overflow = 'hidden';
        modal.hidden = false;
        onConfirm = typeof config.onConfirm === 'function' ? config.onConfirm : null;
        confirmButton.focus();
    };

    closeTargets.forEach(function (target) {
        target.addEventListener('click', closeStaffConfirmModal);
    });

    cancelButton.addEventListener('click', closeStaffConfirmModal);

    confirmButton.addEventListener('click', function () {
        const confirmAction = onConfirm;
        closeStaffConfirmModal();
        if (typeof confirmAction === 'function') {
            confirmAction();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && !modal.hidden) {
            closeStaffConfirmModal();
        }
    });
});
</script>

</body>
</html>

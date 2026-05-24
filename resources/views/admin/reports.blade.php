{{-- This page shows the admin and super admin report dashboard, including summary cards, the reports table, and the report review modal. --}}
@extends('admin.layouts.app')

@section('title', $title)
@section('body_class', 'admin-reports-page')

{{-- Styles loaded for the shared admin dashboard look and the report-page-specific design. --}}
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}?v={{ filemtime(public_path('css/admin-dashboard.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/admin-reports.css') }}?v={{ filemtime(public_path('css/admin-reports.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/shared-dashboard-theme.css') }}?v={{ filemtime(public_path('css/shared-dashboard-theme.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/shared-navbar.css') }}?v={{ filemtime(public_path('css/shared-navbar.css')) }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@endpush

@section('content')
    {{-- Main layout structure for the report dashboard page. --}}
    <div class="admin-page">
        <main class="admin-shell">
            {{-- Top navigation bar for admin pages. --}}
            <x-topbar :active="$isSuperAdminView ? 'reports' : ($mode === 'dashboard' ? 'dashboard' : 'reports')" :can-access-audit="$canAccessAudit" :user="$user" />


            {{-- Main dashboard container.
                 `data-admin-dashboard` lets JavaScript detect this page.
                 `data-dashboard-mode` tells the script which report mode is active.
                 `data-can-manage` tells the script if the current user can update report status.
                 `data-csrf-token` provides the CSRF token for secure AJAX review actions. --}}
                 
            <section class="admin-content" data-admin-dashboard data-dashboard-mode="{{ $mode }}" data-can-manage="{{ $canManageReportRecords ? 'true' : 'false' }}" data-csrf-token="{{ csrf_token() }}" data-auto-open-report-id="{{ $autoOpenReportId ?? '' }}">
                {{-- Flash/status message shown after successful actions. --}}
                @if (session('status'))
                    <div class="status-message">{{ session('status') }}</div>
                @endif

                {{-- Summary cards for quick report totals and status navigation. --}}
                @include('admin.partials.reports-summary')
                {{-- Main reports table with filters, rows, and action buttons. --}}
                @include('admin.partials.reports-table')
            </section>
        </main>
    </div>

    {{-- Reusable modal for viewing report details and review actions. --}}
    @include('admin.partials.reports-modal')

    <div class="delete-confirm-modal" data-delete-confirm-modal hidden>
        <div class="delete-confirm-modal__backdrop" data-delete-confirm-close></div>
        <section class="delete-confirm-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="delete-confirm-title">
            <div class="delete-confirm-modal__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="M9 3h6l1 2h4v2H4V5h4l1-2Zm1 6h2v8h-2V9Zm4 0h2v8h-2V9ZM7 9h2v8H7V9Zm-1 11h12a2 2 0 0 0 2-2V8H4v10a2 2 0 0 0 2 2Z"/></svg>
            </div>
            <div class="delete-confirm-modal__copy">
                <h2 id="delete-confirm-title">Confirm Delete</h2>
                <p data-delete-confirm-message>Are you sure you want to delete this report?</p>
            </div>
            <div class="delete-confirm-modal__actions">
                <button type="button" class="delete-confirm-button delete-confirm-button--secondary" data-delete-confirm-cancel>Cancel</button>
                <button type="button" class="delete-confirm-button delete-confirm-button--danger" data-delete-confirm-submit>Delete</button>
            </div>
        </section>
    </div>
@endsection

{{-- JavaScript files used for shared filtering helpers and report dashboard interactions. --}}
@push('scripts')
    <script src="{{ asset('js/search-filter.js') }}" defer></script>
    <script src="{{ asset('js/admin-reports.js') }}?v={{ filemtime(public_path('js/admin-reports.js')) }}" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Bulk delete functionality
            const selectAllCheckbox = document.getElementById('selectAll');
            const reportCheckboxes = document.querySelectorAll('.report-checkbox:not([disabled])');
            const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
            const bulkDeleteForm = document.getElementById('bulkDeleteForm');
            const deleteConfirmModal = document.querySelector('[data-delete-confirm-modal]');
            const deleteConfirmMessage = document.querySelector('[data-delete-confirm-message]');
            const deleteConfirmCancel = document.querySelector('[data-delete-confirm-cancel]');
            const deleteConfirmSubmit = document.querySelector('[data-delete-confirm-submit]');
            const deleteConfirmCloseTargets = document.querySelectorAll('[data-delete-confirm-close]');
            let pendingDeleteSubmission = null;

            function openDeleteConfirm(message, onConfirm) {
                if (!deleteConfirmModal || !deleteConfirmMessage || !deleteConfirmSubmit) {
                    if (typeof onConfirm === 'function') {
                        onConfirm();
                    }
                    return;
                }

                pendingDeleteSubmission = onConfirm;
                deleteConfirmMessage.textContent = message;
                deleteConfirmModal.hidden = false;
                document.body.style.overflow = 'hidden';
                deleteConfirmSubmit.focus();
            }

            function closeDeleteConfirm() {
                if (!deleteConfirmModal) {
                    return;
                }

                deleteConfirmModal.hidden = true;
                document.body.style.overflow = '';
                pendingDeleteSubmission = null;
            }

            function updateDeleteButton() {
                const checkedBoxes = document.querySelectorAll('.report-checkbox:checked');
                deleteSelectedBtn.disabled = checkedBoxes.length === 0;
            }

            selectAllCheckbox.addEventListener('change', function() {
                reportCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateDeleteButton();
            });

            reportCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const allChecked = Array.from(reportCheckboxes).every(cb => cb.checked);
                    const someChecked = Array.from(reportCheckboxes).some(cb => cb.checked);
                    
                    selectAllCheckbox.checked = allChecked;
                    selectAllCheckbox.indeterminate = someChecked && !allChecked;
                    
                    updateDeleteButton();
                });
            });

            bulkDeleteForm.addEventListener('submit', function(e) {
                const checkedBoxes = document.querySelectorAll('.report-checkbox:checked');
                if (checkedBoxes.length === 0) {
                    e.preventDefault();
                    return;
                }

                e.preventDefault();
                openDeleteConfirm(
                    `Are you sure you want to delete ${checkedBoxes.length} selected approved report(s)?`,
                    function () {
                        closeDeleteConfirm();
                        bulkDeleteForm.submit();
                    }
                );
            });

            deleteSelectedBtn.addEventListener('click', function() {
                const checkedBoxes = document.querySelectorAll('.report-checkbox:checked');
                if (checkedBoxes.length === 0) {
                    return;
                }

                openDeleteConfirm(
                    `Are you sure you want to delete ${checkedBoxes.length} selected approved report(s)?`,
                    function () {
                        closeDeleteConfirm();
                        bulkDeleteForm.submit();
                    }
                );
            });

            deleteConfirmCloseTargets.forEach(function (element) {
                element.addEventListener('click', closeDeleteConfirm);
            });

            if (deleteConfirmCancel) {
                deleteConfirmCancel.addEventListener('click', closeDeleteConfirm);
            }

            if (deleteConfirmSubmit) {
                deleteConfirmSubmit.addEventListener('click', function () {
                    const submitAction = pendingDeleteSubmission;
                    if (typeof submitAction === 'function') {
                        submitAction();
                    } else {
                        closeDeleteConfirm();
                    }
                });
            }

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && deleteConfirmModal && !deleteConfirmModal.hidden) {
                    closeDeleteConfirm();
                }
            });
        });
    </script>

    {{-- ── Real-time count polling for PH Admin & Super Admin ─────────────────── --}}
    <script>
    (function () {
        // Use the correct poll endpoint based on the viewer's role.
        // Super admins must NOT hit /dashboard/admin/poll — that endpoint is
        // restricted to ph-admin/admin roles and would flush their session.
        const POLL_URL  = {{ $isSuperAdminView ? "'/dashboard/super-admin/poll'" : "'/dashboard/admin/poll'" }};
        const INTERVAL  = 20000; // every 20 s

        // Snapshot counts from server at page load
        const knownCounts = {
            employees : parseInt(document.querySelector('[data-count-key="employees"]')?.textContent || '0'),
            approved  : parseInt(document.querySelector('[data-count-key="approved"]')?.textContent  || '0'),
            pending   : parseInt(document.querySelector('[data-count-key="pending"]')?.textContent   || '0'),
            revisions : parseInt(document.querySelector('[data-count-key="revisions"]')?.textContent || '0'),
        };
        let knownLatestPendingAt = null; // track newest submission timestamp

        function showAdminToast(message) {
            const prev = document.getElementById('admin-poll-toast');
            if (prev) prev.remove();

            const toast = document.createElement('div');
            toast.id = 'admin-poll-toast';
            toast.style.cssText = [
                'position:fixed','bottom:28px','right:28px','z-index:9999',
                'background:linear-gradient(135deg,#10b981,#059669)',
                'color:#fff',
                'border-radius:14px','padding:14px 22px','font-size:0.92rem',
                'font-weight:600','box-shadow:0 8px 28px rgba(0,0,0,0.18)',
                'display:flex','align-items:center','gap:10px',
                'min-width:260px','max-width:400px',
                'animation:adminToastIn 0.3s ease'
            ].join(';');

            if (!document.getElementById('admin-toast-kf')) {
                const s = document.createElement('style');
                s.id = 'admin-toast-kf';
                s.textContent = '@keyframes adminToastIn{from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:none}}';
                document.head.appendChild(s);
            }

            toast.innerHTML = '📋 ' + message
                + ' <span style="cursor:pointer;opacity:0.7;margin-left:auto;padding-left:8px;" onclick="this.parentElement.remove()">✕</span>';
            document.body.appendChild(toast);
        }

        function isModalOpen() {
            const modal = document.querySelector('[data-report-modal]');
            return modal && !modal.hidden;
        }

        function autoReload() {
            if (isModalOpen()) {
                const modal = document.querySelector('[data-report-modal]');
                if (modal) {
                    const obs = new MutationObserver(function () {
                        if (!isModalOpen()) { obs.disconnect(); window.location.reload(); }
                    });
                    obs.observe(modal, { attributes: true, attributeFilter: ['hidden'] });
                }
                return;
            }
            window.location.reload();
        }

        async function poll() {
            try {
                const res = await fetch(POLL_URL, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' }
                });
                if (!res.ok) return;
                const data = await res.json();
                const fresh = data.counts || {};

                let changed = false;
                ['employees', 'approved', 'pending', 'revisions'].forEach(function (key) {
                    const freshVal = parseInt(fresh[key] ?? 0);
                    if (freshVal !== knownCounts[key]) { changed = true; knownCounts[key] = freshVal; }
                });

                const freshAt = data.latest_pending_at;
                if (freshAt && knownLatestPendingAt && freshAt !== knownLatestPendingAt) { changed = true; }
                if (freshAt) knownLatestPendingAt = freshAt;

                if (changed) {
                    showAdminToast('New submission received — updating…');
                    setTimeout(autoReload, 1500);
                }
            } catch (_) { /* silently ignore */ }
        }

        // Kick off first poll to record the baseline timestamp
        fetch(POLL_URL, { headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' } })
            .then(r => r.json())
            .then(d => { knownLatestPendingAt = d.latest_pending_at || null; })
            .catch(() => {});

        setInterval(poll, INTERVAL);
    })();
    </script>
@endpush

(() => {
    const searchInput = document.getElementById('reports-search-input');
    const statusFilter = document.getElementById('reports-status-filter');
    const pendingOnlyButton = document.getElementById('reports-pending-only');
    const rows = Array.from(document.querySelectorAll('.report-row'));
    const emptyRow = document.getElementById('reports-empty-client');

    const closeOtherDetails = (current) => {
        document.querySelectorAll('.report-action-dropdown').forEach((dropdown) => {
            if (dropdown !== current) {
                dropdown.removeAttribute('open');
            }
        });
    };

    document.querySelectorAll('.report-action-dropdown').forEach((dropdown) => {
        dropdown.addEventListener('toggle', () => {
            if (dropdown.open) {
                closeOtherDetails(dropdown);
            }
        });
    });

    document.addEventListener('click', (event) => {
        const clickedInside = event.target.closest('.report-action-dropdown');
        if (!clickedInside) {
            closeOtherDetails(null);
        }
    });

    const applyLiveFilter = () => {
        if (!searchInput || !statusFilter || rows.length === 0 || !emptyRow) {
            return;
        }

        const query = searchInput.value.trim().toLowerCase();
        const selectedStatus = statusFilter.value;
        let visibleCount = 0;

        rows.forEach((row) => {
            const rowText = row.dataset.filterText || '';
            const rowStatus = row.dataset.filterStatus || '';
            const matchQuery = query === '' || rowText.includes(query);
            const matchStatus = selectedStatus === '' || rowStatus === selectedStatus;
            const showRow = matchQuery && matchStatus;

            row.classList.toggle('hidden', !showRow);
            if (showRow) {
                visibleCount += 1;
            }
        });

        emptyRow.classList.toggle('hidden', visibleCount > 0);
    };

    if (searchInput && statusFilter && rows.length > 0 && emptyRow) {
        searchInput.addEventListener('input', applyLiveFilter);
        statusFilter.addEventListener('change', applyLiveFilter);
        applyLiveFilter();
    }

    if (pendingOnlyButton && statusFilter) {
        pendingOnlyButton.addEventListener('click', () => {
            statusFilter.value = statusFilter.value === 'pending' ? '' : 'pending';
            applyLiveFilter();
        });
    }

    document.querySelectorAll('.js-confirm-action').forEach((form) => {
        form.addEventListener('submit', (event) => {
            const message = form.getAttribute('data-confirm-message') || 'Are you sure?';
            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    });


    
    const dashboard = document.querySelector('[data-admin-dashboard]');

    if (!dashboard) {
        return;
    }

    const mode = dashboard.dataset.dashboardMode;
    const canManage = dashboard.dataset.canManage === 'true';
    const csrfToken = dashboard.dataset.csrfToken;
    const reportsBody = dashboard.querySelector('[data-reports-body]');
    const reportRows = Array.from(reportsBody.querySelectorAll('[data-report-row]'));
    const emptyStateRow = reportsBody.querySelector('[data-empty-state-row]');
    const cards = Array.from(dashboard.querySelectorAll('[data-summary-card]'));
    const dashboardSearchInput = dashboard.querySelector('[data-report-search]');
    const statusSelect = dashboard.querySelector('[data-status-filter]');
    const resultsSummary = dashboard.querySelector('[data-results-summary]');
    const modal = document.querySelector('[data-report-modal]');
    const modalSubtitle = modal.querySelector('[data-modal-subtitle]');
    const modalStatusPill = modal.querySelector('[data-modal-status-pill]');
    const previewFileName = modal.querySelector('[data-preview-file-name]');
    const previewUserName = modal.querySelector('[data-preview-user-name]');
    const previewSubmittedAt = modal.querySelector('[data-preview-submitted-at]');
    const previewStatusLabel = modal.querySelector('[data-preview-status-label]');
    const previewEntries = modal.querySelector('[data-preview-entries]');
    const previewSignature = modal.querySelector('[data-preview-signature]');
    const previewSignatoryName = modal.querySelector('[data-preview-signatory-name]');
    const previewDownload = modal.querySelector('[data-preview-download]');
    const previewPdfExport = modal.querySelector('[data-preview-pdf-export]');
    const reviewChoices = Array.from(modal.querySelectorAll('[data-review-choice]'));
    const reviewComment = modal.querySelector('[data-review-comment]');
    const existingComment = modal.querySelector('[data-existing-comment]');
    const existingCommentText = modal.querySelector('[data-existing-comment-text]');
    const approveButton = modal.querySelector('[data-approve-button]');
    const returnButton = modal.querySelector('[data-return-button]');
    const reviewFeedback = modal.querySelector('[data-review-feedback]');
    let activeCardFilter = mode === 'approved' ? 'approved' : mode === 'pending' ? 'pending' : mode === 'revisions' ? 'for_revision' : 'all';
    let currentReport = null;

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, function (character) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[character];
        });
    }

    function statusClass(status) {
        if (status === 'approved') return 'status-approved';
        if (status === 'pending') return 'status-pending';
        if (status === 'for_revision') return 'status-revision';
        return 'status-default';
    }

    function humanizeStatus(status) {
        return String(status || 'unknown').replace(/_/g, ' ').replace(/\b\w/g, function (character) {
            return character.toUpperCase();
        });
    }

    function updateResultsSummary(count) {
        if (resultsSummary) {
            resultsSummary.innerHTML = '<strong>' + count + '</strong> visible ' + (count === 1 ? 'report' : 'reports');
        }
    }

    function syncCards() {
        cards.forEach(function (card) {
            card.classList.toggle('is-active', card.dataset.filterValue === activeCardFilter || (activeCardFilter === 'all' && card.dataset.filterValue === 'all'));
        });
    }

    function applyFilters() {
        const search = (dashboardSearchInput?.value || '').trim().toLowerCase();
        const status = statusSelect?.value || '';
        let visible = 0;

        reportRows.forEach(function (row) {
            const rowSearch = row.dataset.search || '';
            const rowStatus = row.dataset.status || '';
            const show = (activeCardFilter === 'all' || rowStatus === activeCardFilter) && (search === '' || rowSearch.includes(search)) && (status === '' || rowStatus === status);
            row.classList.toggle('row-hidden', !show);
            if (show) visible += 1;
        });

        if (emptyStateRow) {
            emptyStateRow.classList.toggle('row-hidden', visible !== 0);
        }

        updateResultsSummary(visible);
        syncCards();
    }

    function updateCounts(counts) {
        if (!counts) return;
        Object.entries(counts).forEach(function ([key, value]) {
            document.querySelectorAll('[data-count-key="' + key + '"]').forEach(function (element) {
                element.textContent = value;
            });
        });
    }

    function resetControls(status) {
        reviewChoices.forEach(function (input) { input.checked = false; });
        if (reviewComment) {
            reviewComment.value = '';
            reviewComment.hidden = true;
        }
        if (existingComment) {
            existingComment.hidden = true;
        }
        if (existingCommentText) {
            existingCommentText.textContent = '';
        }
        if (approveButton) {
            approveButton.hidden = true;
            approveButton.disabled = status === 'approved';
        }
        if (returnButton) {
            returnButton.hidden = true;
            returnButton.disabled = status === 'approved';
        }
        if (reviewFeedback) {
            reviewFeedback.textContent = '';
        }
    }

    function openModal() {
        modal.hidden = false;
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        modal.hidden = true;
        document.body.style.overflow = '';
        currentReport = null;
        resetControls('pending');
    }

    function renderEntries(entries) {
        previewEntries.innerHTML = entries.length
            ? entries.map(function (entry) {
                return '<tr><td>' + escapeHtml(entry.period || 'N/A') + '</td><td>' + escapeHtml(entry.activity || 'Not provided') + '</td><td>' + escapeHtml(entry.details || 'No details provided.') + '</td><td>' + escapeHtml(entry.remarks || 'No remarks.') + '</td></tr>';
            }).join('')
            : '<tr><td colspan="4">No accomplishment entries were added to this report yet.</td></tr>';
    }

    function renderSignature(url) {
        previewSignature.innerHTML = url
            ? '<img src="' + escapeHtml(url) + '" alt="Staff signature">'
            : '<div class="signature-placeholder">No signature uploaded</div>';
    }

    function updateModalStatus(status) {
        const label = humanizeStatus(status);
        modalStatusPill.textContent = label;
        modalStatusPill.className = 'modal-status-pill ' + statusClass(status);
        previewStatusLabel.textContent = label;
    }

    function populateModal(report) {
        currentReport = report;
        modalSubtitle.textContent = report.file_name + ' prepared by ' + report.user_name;
        previewFileName.textContent = report.file_name;
        previewUserName.textContent = report.user_name;
        previewSubmittedAt.textContent = report.submitted_at;
        previewSignatoryName.textContent = report.user_name;
        renderEntries(report.entries || []);
        renderSignature(report.signature_url || '');
        updateModalStatus(report.status);
        resetControls(report.status);
        if (existingComment && existingCommentText && report.review_comment) {
            existingComment.hidden = false;
            existingCommentText.textContent = report.review_comment;
        }

        if (previewDownload) {
            if (report.download_url) {
                previewDownload.hidden = false;
                previewDownload.href = report.download_url;
            } else {
                previewDownload.hidden = true;
                previewDownload.removeAttribute('href');
            }
        }

        if (previewPdfExport) {
            previewPdfExport.hidden = false;
            previewPdfExport.href = '/dashboard/admin/reports/' + report.id + '/export-pdf';
        }
    }

    async function submitReview(status) {
        if (!currentReport || !currentReport.status_url) return;
        if (reviewFeedback) reviewFeedback.textContent = 'Saving review decision...';

        const payload = new URLSearchParams();
        payload.set('_token', csrfToken);
        payload.set('status', status);

        if (status === 'for_revision' && reviewComment && reviewComment.value.trim() !== '') {
            payload.set('comment', reviewComment.value.trim());
        }

        try {
            const response = await fetch(currentReport.status_url, {
                method: 'POST',
                headers: {
                    Accept: 'application/json',
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: payload.toString()
            });

            if (!response.ok) throw new Error('Review request failed.');

            const data = await response.json();
            const updatedStatus = data.report?.status || status;
            const row = reportsBody.querySelector('[data-report-id="' + currentReport.id + '"]');
            currentReport.status = updatedStatus;
            currentReport.review_comment = data.report?.review_comment || '';

            if (row) {
                row.dataset.status = updatedStatus;
                row.dataset.search = ((row.dataset.search || '').replace(/\b(approved|pending|for_revision)\b/g, '').trim() + ' ' + updatedStatus).trim();

                const pill = row.querySelector('[data-status-pill]');
                if (pill) {
                    pill.textContent = data.report?.status_label || humanizeStatus(updatedStatus);
                    pill.className = 'status-pill ' + statusClass(updatedStatus);
                }
            }

            updateCounts(data.counts || {});
            updateModalStatus(updatedStatus);
            if (existingComment && existingCommentText) {
                existingComment.hidden = !currentReport.review_comment;
                existingCommentText.textContent = currentReport.review_comment || '';
            }
            resetControls(updatedStatus);
            applyFilters();
            if (reviewFeedback) reviewFeedback.textContent = data.message || 'Review saved successfully.';
        } catch (error) {
            if (reviewFeedback) reviewFeedback.textContent = 'Unable to save the review right now.';
        }
    }

    if (dashboardSearchInput) dashboardSearchInput.addEventListener('input', applyFilters);
    if (statusSelect) statusSelect.addEventListener('change', applyFilters);

    cards.forEach(function (card) {
        card.addEventListener('click', function (event) {
            if (mode !== 'dashboard' || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
            event.preventDefault();
            activeCardFilter = card.dataset.filterValue || 'all';
            applyFilters();
        });
    });

    dashboard.querySelectorAll('[data-open-report-modal]').forEach(function (button) {
        button.addEventListener('click', function () {
            populateModal(JSON.parse(button.dataset.report || '{}'));
            openModal();
        });
    });

    modal.querySelectorAll('[data-close-report-modal]').forEach(function (button) {
        button.addEventListener('click', closeModal);
    });

    modal.addEventListener('click', function (event) {
        if (event.target === modal) closeModal();
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && !modal.hidden) closeModal();
    });

    reviewChoices.forEach(function (input) {
        input.addEventListener('change', function () {
            if (!canManage || !currentReport) return;
            const selected = input.value;
            if (reviewComment) reviewComment.hidden = selected !== 'for_revision';
            if (approveButton) approveButton.hidden = selected !== 'approved' || currentReport.status === 'approved';
            if (returnButton) returnButton.hidden = selected !== 'for_revision' || currentReport.status === 'approved';
        });
    });

    if (approveButton) approveButton.addEventListener('click', function () { submitReview('approved'); });
    if (returnButton) returnButton.addEventListener('click', function () { submitReview('for_revision'); });

    applyFilters();
})();

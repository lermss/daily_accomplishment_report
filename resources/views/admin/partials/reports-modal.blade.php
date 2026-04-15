{{-- Modal preview keeps the report page A4-style while review controls stay outside the preview area. --}}
<div class="report-modal" data-report-modal hidden>
    <div class="report-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="reportModalTitle">
        <div class="report-modal__header">
            <div><h2 id="reportModalTitle">Accomplishment Report</h2><p data-modal-subtitle>Select a report to inspect the full submission and review status.</p></div>
            <button type="button" class="report-modal__close" data-close-report-modal aria-label="Close report modal">&times;</button>
        </div>
        <div class="report-modal__body">
            <div class="report-preview-shell">
                <div class="report-preview">
                    <div class="report-preview__title"><h3>Daily Accomplishment Report</h3><p data-preview-file-name>No file selected</p></div>
                    <div class="report-meta-grid">
                        <div class="meta-card"><span>Prepared By</span><strong data-preview-user-name>Not available</strong></div>
                        <div class="meta-card"><span>Date Submitted</span><strong data-preview-submitted-at>N/A</strong></div>
                        <div class="meta-card"><span>Current Status</span><strong data-preview-status-label>Pending</strong></div>
                    </div>
                    <table class="preview-table">
                        <thead><tr><th style="width:22%">Period</th><th style="width:22%">Activity</th><th style="width:32%">Details</th><th style="width:24%">Remarks</th></tr></thead>
                        <tbody data-preview-entries><tr><td colspan="4">Select a report to load the accomplishment details.</td></tr></tbody>
                    </table>
                    <div class="prepared-by">
                        <div class="signature-box" data-preview-signature><div class="signature-placeholder">No signature uploaded</div></div>
                        <strong data-preview-signatory-name>Not available</strong>
                        <span>Prepared by</span>
                    </div>
                </div>
            </div>
            <aside class="review-sidebar">
                <div class="sidebar-card">
                    <h3>Review Decision</h3>
                    <p>Use the review options outside the A4 preview so the report content stays clean and print-friendly.</p>
                    <span class="modal-status-pill status-pending" data-modal-status-pill>Pending</span>
                    @if ($canManageReportRecords)
                        <div class="review-options">
                            <label class="review-option"><input type="radio" name="report_review_status" value="approved" data-review-choice><span><strong>Approve</strong><span>Confirm the report and move it into the approved state.</span></span></label>
                            <label class="review-option"><input type="radio" name="report_review_status" value="for_revision" data-review-choice><span><strong>For Revision</strong><span>Return the report to staff and include revision notes for reference.</span></span></label>
                        </div>
                        <textarea class="review-comment" data-review-comment hidden placeholder="Add revision notes or comments for this report..."></textarea>
                        <div class="comment-panel" data-existing-comment hidden>
                            <strong>Saved Revision Comment</strong>
                            <p data-existing-comment-text></p>
                        </div>
                        <div class="action-stack" style="margin-top:16px;display:grid;gap:12px;">
                            <button type="button" class="modal-primary-button" data-approve-button hidden>Approve Report</button>
                            <button type="button" class="modal-secondary-button" data-return-button hidden>Return For Revision</button>
                        </div>
                        <div class="review-feedback" data-review-feedback></div>
                    @else
                        <div class="modal-note">This view is read-only in monitoring mode. Status changes remain available only to admin users.</div>
                    @endif
                    <a href="#" class="action-button download-link" data-preview-download hidden><svg viewBox="0 0 24 24"><path d="M12 3a1 1 0 0 1 1 1v8.59l2.3-2.29 1.4 1.41L12 16.41l-4.7-4.7 1.4-1.41L11 12.59V4a1 1 0 0 1 1-1Zm-7 14h14v3H5Z"/></svg><span>Download Attachment</span></a>
                </div>
            </aside>
        </div>
    </div>
</div>

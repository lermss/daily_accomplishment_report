<div class="staff-confirm-modal" data-staff-confirm-modal hidden>
    <div class="staff-confirm-modal__backdrop" data-staff-confirm-close></div>
    <section
        class="staff-confirm-modal__dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="staff-confirm-title"
        aria-describedby="staff-confirm-message"
    >
        <div class="staff-confirm-modal__icon" data-staff-confirm-icon aria-hidden="true">
            <svg viewBox="0 0 24 24" focusable="false">
                <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm1 14h-2v-2h2Zm0-4h-2V7h2Z"/>
            </svg>
        </div>

        <div class="staff-confirm-modal__copy">
            <h2 id="staff-confirm-title" data-staff-confirm-title>Confirm Action</h2>
            <p id="staff-confirm-message" data-staff-confirm-message>Are you sure you want to continue?</p>
        </div>

        <div class="staff-confirm-modal__actions">
            <button type="button" class="staff-confirm-button staff-confirm-button--secondary" data-staff-confirm-cancel>Cancel</button>
            <button type="button" class="staff-confirm-button staff-confirm-button--primary" data-staff-confirm-submit>Confirm</button>
        </div>
    </section>
</div>

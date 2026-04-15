/**
 * Toast Notification System
 * Provides client-side error handling and notification display
 */

class ToastNotification {
    constructor() {
        this.container = this.createContainer();
    }

    createContainer() {
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'toast-container';
            document.body.appendChild(container);
            this.addStyles();
        }
        return container;
    }

    addStyles() {
        const style = document.createElement('style');
        style.textContent = `
            .toast-container {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                max-width: 400px;
            }

            .toast {
                background: white;
                border-radius: 8px;
                padding: 16px;
                margin-bottom: 10px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                display: flex;
                align-items: center;
                animation: slideIn 0.3s ease-in-out;
                gap: 12px;
                min-height: 60px;
            }

            .toast-content {
                flex: 1;
            }

            .toast-title {
                font-weight: 600;
                font-size: 14px;
                margin: 0;
            }

            .toast-message {
                font-size: 13px;
                margin: 4px 0 0 0;
                opacity: 0.8;
            }

            .toast-icon {
                font-size: 20px;
                min-width: 24px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .toast-close {
                background: none;
                border: none;
                cursor: pointer;
                font-size: 18px;
                opacity: 0.5;
                padding: 0;
                margin-left: 8px;
                transition: opacity 0.2s;
            }

            .toast-close:hover {
                opacity: 1;
            }

            .toast.success {
                border-left: 4px solid #28a745;
            }

            .toast.success .toast-icon {
                color: #28a745;
            }

            .toast.error,
            .toast.danger {
                border-left: 4px solid #dc3545;
            }

            .toast.error .toast-icon,
            .toast.danger .toast-icon {
                color: #dc3545;
            }

            .toast.warning {
                border-left: 4px solid #ffc107;
            }

            .toast.warning .toast-icon {
                color: #ffc107;
            }

            .toast.info {
                border-left: 4px solid #17a2b8;
            }

            .toast.info .toast-icon {
                color: #17a2b8;
            }

            @keyframes slideIn {
                from {
                    transform: translateX(400px);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            @keyframes slideOut {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(400px);
                    opacity: 0;
                }
            }

            .toast.hide {
                animation: slideOut 0.3s ease-in-out;
            }

            @media (max-width: 576px) {
                .toast-container {
                    left: 10px;
                    right: 10px;
                    max-width: none;
                }

                .toast {
                    padding: 12px;
                }

                .toast-title {
                    font-size: 13px;
                }

                .toast-message {
                    font-size: 12px;
                }
            }
        `;
        document.head.appendChild(style);
    }

    show(message, options = {}) {
        const {
            type = 'info',
            title = this.getTitleForType(type),
            duration = 5000,
            icon = this.getIconForType(type),
        } = options;

        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        
        toast.innerHTML = `
            <div class="toast-icon">${icon}</div>
            <div class="toast-content">
                <p class="toast-title">${title}</p>
                <p class="toast-message">${message}</p>
            </div>
            <button class="toast-close" aria-label="Close notification">×</button>
        `;

        const closeBtn = toast.querySelector('.toast-close');
        closeBtn.addEventListener('click', () => this.hide(toast));

        this.container.appendChild(toast);

        if (duration > 0) {
            setTimeout(() => this.hide(toast), duration);
        }

        return toast;
    }

    hide(toast) {
        if (!toast) return;
        
        toast.classList.add('hide');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }

    success(message, options = {}) {
        return this.show(message, { ...options, type: 'success' });
    }

    error(message, options = {}) {
        return this.show(message, { ...options, type: 'error' });
    }

    warning(message, options = {}) {
        return this.show(message, { ...options, type: 'warning' });
    }

    info(message, options = {}) {
        return this.show(message, { ...options, type: 'info' });
    }

    getTitleForType(type) {
        const titles = {
            'success': 'Success',
            'error': 'Error',
            'danger': 'Error',
            'warning': 'Warning',
            'info': 'Information',
        };
        return titles[type] || 'Notification';
    }

    getIconForType(type) {
        const icons = {
            'success': '✓',
            'error': '✕',
            'danger': '✕',
            'warning': '⚠',
            'info': 'ℹ',
        };
        return icons[type] || '•';
    }
}

// Global instance
window.toast = new ToastNotification();

// Example usage:
// window.toast.success('Operation completed successfully!');
// window.toast.error('An error occurred. Please try again.', { duration: 0 });
// window.toast.warning('This action cannot be undone.');
// window.toast.info('New updates are available.');

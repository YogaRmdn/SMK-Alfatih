(() => {
    const onReady = (fn) => {
        if (document.readyState !== 'loading') {
            fn();
        } else {
            document.addEventListener('DOMContentLoaded', fn);
        }
    };

    onReady(() => {
        initDropdowns();
        initModals();
        initTabs();
        initAlertDismiss();
        initConfirmDialog();
        initToasts();
        initMobileNav();
    });

    /* ---------------- Mobile nav ---------------- */
    function initMobileNav() {
        const toggle = document.querySelector('[data-nav-toggle]');
        const menu = document.querySelector('[data-nav-menu]');
        if (!toggle || !menu) return;

        toggle.addEventListener('click', () => {
            const isHidden = menu.classList.contains('hidden');
            menu.classList.toggle('hidden', !isHidden);
            toggle.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
            toggle.setAttribute('aria-label', isHidden ? 'Tutup menu navigasi' : 'Buka menu navigasi');
        });
    }

    /* ---------------- Dropdown ---------------- */
    function initDropdowns() {
        document.addEventListener('click', (e) => {
            const toggle = e.target.closest('[data-dropdown-toggle]');
            const menu = toggle?.closest('[data-dropdown]')?.querySelector('[data-dropdown-menu]');

            if (toggle) {
                const isOpen = menu && !menu.classList.contains('hidden');
                closeAllDropdowns();
                if (!isOpen) {
                    menu.classList.remove('hidden');
                }
                return;
            }

            if (e.target.closest('[data-dropdown-close]')) {
                closeAllDropdowns();
                return;
            }

            if (!e.target.closest('[data-dropdown]')) {
                closeAllDropdowns();
            }
        });
    }

    function closeAllDropdowns() {
        document.querySelectorAll('[data-dropdown-menu]').forEach((m) => m.classList.add('hidden'));
    }

    /* ---------------- Modal ---------------- */
    function initModals() {
        document.addEventListener('click', (e) => {
            const closeBtn = e.target.closest('[data-modal-close]');
            if (closeBtn) {
                closeModal(closeBtn.closest('[data-modal]'));
                return;
            }
            if (e.target.hasAttribute('data-modal-backdrop')) {
                closeModal(e.target.closest('[data-modal]'));
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeModal(document.querySelector('[data-modal]:not(.hidden)'));
            }
        });
    }

    window.openModal = (id) => {
        const modal = typeof id === 'string' ? document.getElementById(id) : id;
        if (!modal) return;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        modal.querySelector('input, button, select, textarea, a[href]')?.focus();
    };

    window.closeModal = (id) => {
        const modal = typeof id === 'string' ? document.getElementById(id) : id;
        closeModal(modal);
    };

    function closeModal(modal) {
        if (!modal) return;
        modal.classList.add('hidden');
        if (!document.querySelector('[data-modal]:not(.hidden)')) {
            document.body.style.overflow = '';
        }
    }

    /* ---------------- Tabs ---------------- */
    function initTabs() {
        document.querySelectorAll('[data-tabs]').forEach((tabsEl) => {
            const triggers = tabsEl.querySelectorAll('[data-tab-trigger]');
            const panels = tabsEl.querySelectorAll('[data-tab-panel]');

            const activate = (target) => {
                triggers.forEach((t) => t.setAttribute('data-active', 'false'));
                panels.forEach((p) => p.classList.add('hidden'));

                const activeTrigger = tabsEl.querySelector(`[data-tab-trigger][data-target="${target}"]`);
                if (activeTrigger) activeTrigger.setAttribute('data-active', 'true');

                const panel = tabsEl.querySelector(target);
                if (panel) panel.classList.remove('hidden');
            };

            triggers.forEach((trigger) => {
                trigger.addEventListener('click', () => activate(trigger.dataset.target));
            });

            if (triggers.length > 0) {
                const firstActive = tabsEl.querySelector('[data-tab-trigger][data-active="true"]');
                activate(firstActive ? firstActive.dataset.target : triggers[0].dataset.target);
            }
        });
    }

    /* ---------------- Alert dismiss ---------------- */
    function initAlertDismiss() {
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('[data-alert-dismiss]');
            if (!btn) return;
            const target = btn.dataset.alertDismiss;
            const el = target ? document.querySelector(target) : btn.closest('[role="alert"]');
            el?.remove();
        });
    }

    /* ---------------- Confirm dialog ---------------- */
    function initConfirmDialog() {
        const dialog = document.querySelector('[data-confirm-dialog]');
        if (!dialog) return;

        const title = dialog.querySelector('#confirm-dialog-title');
        const message = dialog.querySelector('#confirm-dialog-message');
        const okBtn = dialog.querySelector('[data-confirm-ok]');
        const cancelBtn = dialog.querySelector('[data-confirm-cancel]');

        let onConfirm = null;

        const close = () => {
            dialog.classList.add('hidden');
            document.body.style.overflow = '';
        };

        const open = () => {
            dialog.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            cancelBtn.focus();
        };

        dialog.addEventListener('click', (e) => {
            if (e.target.hasAttribute('data-confirm-backdrop')) close();
        });

        cancelBtn.addEventListener('click', close);
        okBtn.addEventListener('click', () => {
            close();
            if (typeof onConfirm === 'function') onConfirm();
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !dialog.classList.contains('hidden')) close();
        });

        window.confirmDialog = (options = {}) => {
            title.textContent = options.title || 'Apakah Anda yakin?';
            message.textContent = options.message || '';
            okBtn.textContent = options.confirmText || 'Ya, lanjutkan';
            cancelBtn.textContent = options.cancelText || 'Batal';
            okBtn.classList.toggle('bg-red-600', options.variant !== 'primary');

            if (options.formAction) {
                onConfirm = () => {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = options.formAction;
                    form.className = 'hidden';

                    const token = document.querySelector('meta[name="csrf-token"]')?.content;
                    if (token) {
                        form.appendChild(input('_token', token));
                    }
                    if (options.method && options.method.toLowerCase() !== 'post') {
                        form.appendChild(input('_method', options.method));
                    }

                    document.body.appendChild(form);
                    form.submit();
                };
            } else {
                onConfirm = options.onConfirm || null;
            }

            open();
        };

        function input(name, value) {
            const el = document.createElement('input');
            el.type = 'hidden';
            el.name = name;
            el.value = value;
            return el;
        }
    }

    /* ---------------- Toasts ---------------- */
    function initToasts() {
        const container = document.querySelector('[data-toast-container]');
        if (!container) return;

        const variants = {
            success: { border: 'border-emerald-200', icon: 'text-emerald-500', path: 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z' },
            error: { border: 'border-red-200', icon: 'text-red-500', path: 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z' },
            warning: { border: 'border-amber-200', icon: 'text-amber-500', path: 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z' },
            info: { border: 'border-sky-200', icon: 'text-sky-500', path: 'M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z' },
        };

        window.toast = (message, type = 'success', duration = 4000) => {
            const v = variants[type] || variants.info;

            const toastEl = document.createElement('div');
            toastEl.className = `pointer-events-auto flex items-start gap-3 rounded-xl border bg-white p-4 shadow-pop ${v.border} animate-toast-in`;
            toastEl.setAttribute('role', 'status');

            toastEl.innerHTML = `
                <svg class="size-5 shrink-0 mt-0.5 ${v.icon}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="${v.path}" />
                </svg>
                <p class="flex-1 text-sm text-slate-700"></p>
                <button type="button" class="shrink-0 rounded-md p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600" aria-label="Tutup">
                    <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            `;

            toastEl.querySelector('p').textContent = message;
            toastEl.querySelector('button').addEventListener('click', () => toastEl.remove());
            container.appendChild(toastEl);

            setTimeout(() => {
                toastEl.style.transition = 'opacity .3s, transform .3s';
                toastEl.style.opacity = '0';
                toastEl.style.transform = 'translateY(8px)';
                setTimeout(() => toastEl.remove(), 300);
            }, duration);
        };
    }
})();

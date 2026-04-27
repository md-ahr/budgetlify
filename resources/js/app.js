import { initBudgetlifyCharts, watchChartTheme } from './charts.js';

const themeStorageKey = 'budgetlify-theme';

function getResolvedTheme() {
    try {
        const stored = localStorage.getItem(themeStorageKey);
        if (stored === 'light' || stored === 'dark') {
            return stored;
        }
    } catch (e) {
        /* ignore */
    }
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
}

function syncThemeToggleLabels(mode) {
    document.querySelectorAll('.js-app-theme-toggle').forEach((btn) => {
        const toLight = btn.getAttribute('data-label-light');
        const toDark = btn.getAttribute('data-label-dark');
        if (toLight && toDark) {
            btn.setAttribute('aria-label', mode === 'dark' ? toLight : toDark);
        }
    });
}

function applyTheme(mode, persist) {
    const dark = mode === 'dark';
    document.documentElement.classList.toggle('dark', dark);
    if (persist) {
        try {
            localStorage.setItem(themeStorageKey, mode);
        } catch (e) {
            /* ignore */
        }
    }
    syncThemeToggleLabels(mode);
    document.querySelectorAll('[data-theme-set]').forEach((btn) => {
        const m = btn.getAttribute('data-theme-set');
        const active = m === mode;
        btn.setAttribute('aria-pressed', active ? 'true' : 'false');
        btn.classList.toggle('bg-white', active);
        btn.classList.toggle('shadow-sm', active);
        btn.classList.toggle('text-slate-900', active);
        btn.classList.toggle('dark:bg-slate-900', active);
        btn.classList.toggle('dark:text-slate-100', active);
        btn.classList.toggle('dark:shadow-none', active);
        btn.classList.toggle('dark:ring-1', active);
        btn.classList.toggle('dark:ring-white/12', active);
    });
}

function closeTopbarDropdowns() {
    document.querySelectorAll('details[data-topbar-dropdown][open]').forEach((el) => {
        el.removeAttribute('open');
    });
}

document.addEventListener('DOMContentLoaded', () => {
    applyTheme(getResolvedTheme(), false);

    initBudgetlifyCharts();
    watchChartTheme();

    document.querySelectorAll('.js-app-theme-toggle').forEach((btn) => {
        btn.addEventListener('click', () => {
            const next = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
            applyTheme(next, true);
        });
    });

    document.querySelectorAll('[data-theme-set]').forEach((btn) => {
        btn.addEventListener('click', () => {
            applyTheme(btn.getAttribute('data-theme-set') || 'light', true);
        });
    });

    document.addEventListener('click', (event) => {
        if (!(event.target instanceof Node)) {
            return;
        }
        document.querySelectorAll('details[data-topbar-dropdown][open]').forEach((el) => {
            if (!el.contains(event.target)) {
                el.removeAttribute('open');
            }
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape') {
            return;
        }
        closeTopbarDropdowns();
    });

    const sidebarToggle = document.getElementById('app-sidebar-toggle');

    document.querySelectorAll('#app-sidebar a[href]').forEach((link) => {
        link.addEventListener('click', () => {
            if (window.matchMedia('(max-width: 1023px)').matches && sidebarToggle instanceof HTMLInputElement) {
                sidebarToggle.checked = false;
            }
        });
    });

    const budgetDialog = document.getElementById('create-budget-dialog');
    const openBudgetBtn = document.getElementById('open-create-budget');

    openBudgetBtn?.addEventListener('click', (e) => {
        budgetDialog?.showModal();
        e.stopPropagation();
    });

    document.querySelectorAll('[data-close-create-budget]').forEach((el) => {
        el.addEventListener('click', () => {
            budgetDialog?.close();
        });
    });

    const transactionDialog = document.getElementById('create-transaction-dialog');

    document.querySelectorAll('[data-open-transaction-modal]').forEach((el) => {
        el.addEventListener('click', (e) => {
            transactionDialog?.showModal();
            e.stopPropagation();
        });
    });

    document.querySelectorAll('[data-close-create-transaction]').forEach((el) => {
        el.addEventListener('click', () => {
            transactionDialog?.close();
        });
    });

    document.querySelectorAll('[data-close-dialog]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const dialog = btn.closest('dialog');
            if (dialog instanceof HTMLDialogElement) {
                dialog.close();
            }
        });
    });

    document.addEventListener('click', (event) => {
        if (!(event.target instanceof Node)) {
            return;
        }
        document.querySelectorAll('dialog[data-app-modal][open]').forEach((node) => {
            if (!(node instanceof HTMLDialogElement)) {
                return;
            }
            if (!node.contains(event.target)) {
                node.close();
            }
        });
    });
});

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

/**
 * @param {HTMLDialogElement} dialog
 */
function resetTransactionFormForCreate(dialog) {
    const form = dialog.querySelector('#create-transaction-form');
    if (!(form instanceof HTMLFormElement)) {
        return;
    }
    const storeUrl = dialog.dataset.storeUrl;
    const defaultDate = dialog.dataset.defaultDate ?? '';
    if (storeUrl) {
        form.action = storeUrl;
    }
    const methodEl = dialog.querySelector('#transaction-form-method');
    if (methodEl instanceof HTMLInputElement) {
        methodEl.disabled = true;
    }
    const editingEl = dialog.querySelector('#transaction-editing-id');
    if (editingEl instanceof HTMLInputElement) {
        editingEl.disabled = true;
        editingEl.value = '';
    }
    const title = form.querySelector('#transaction-title');
    if (title instanceof HTMLInputElement) {
        title.value = '';
    }
    const amount = form.querySelector('#transaction-amount');
    if (amount instanceof HTMLInputElement) {
        amount.value = '';
    }
    const notes = form.querySelector('#transaction-notes');
    if (notes instanceof HTMLTextAreaElement) {
        notes.value = '';
    }
    const date = form.querySelector('#transaction-date');
    if (date instanceof HTMLInputElement) {
        date.value = defaultDate;
    }
    const type = form.querySelector('#transaction-type');
    if (type instanceof HTMLSelectElement) {
        type.selectedIndex = 0;
    }
    const category = form.querySelector('#transaction-category');
    if (category instanceof HTMLSelectElement) {
        category.selectedIndex = 0;
    }
    const heading = dialog.querySelector('#transaction-modal-heading');
    if (heading) {
        heading.textContent = dialog.dataset.textAddTitle ?? '';
    }
    const subtitle = dialog.querySelector('#transaction-modal-subtitle');
    if (subtitle) {
        subtitle.textContent = dialog.dataset.textAddSubtitle ?? '';
    }
    const submitLbl = dialog.querySelector('#transaction-submit-label');
    if (submitLbl) {
        submitLbl.textContent = dialog.dataset.textSave ?? '';
    }
}

/**
 * @param {HTMLDialogElement} dialog
 * @param {Record<string, string | number | null | undefined>} tx
 */
function configureTransactionFormForEdit(dialog, tx) {
    const form = dialog.querySelector('#create-transaction-form');
    if (!(form instanceof HTMLFormElement)) {
        return;
    }
    const base = dialog.dataset.transactionsBase?.replace(/\/$/, '') ?? '';
    if (base && tx.id != null) {
        form.action = `${base}/${tx.id}`;
    }
    const methodEl = dialog.querySelector('#transaction-form-method');
    if (methodEl instanceof HTMLInputElement) {
        methodEl.disabled = false;
    }
    const editingEl = dialog.querySelector('#transaction-editing-id');
    if (editingEl instanceof HTMLInputElement) {
        editingEl.disabled = false;
        editingEl.value = String(tx.id);
    }
    const title = form.querySelector('#transaction-title');
    if (title instanceof HTMLInputElement) {
        title.value = tx.title != null ? String(tx.title) : '';
    }
    const amount = form.querySelector('#transaction-amount');
    if (amount instanceof HTMLInputElement) {
        amount.value = tx.amount != null ? String(tx.amount) : '';
    }
    const notes = form.querySelector('#transaction-notes');
    if (notes instanceof HTMLTextAreaElement) {
        notes.value = tx.notes != null ? String(tx.notes) : '';
    }
    const date = form.querySelector('#transaction-date');
    if (date instanceof HTMLInputElement) {
        date.value = tx.occurred_on != null ? String(tx.occurred_on) : '';
    }
    const type = form.querySelector('#transaction-type');
    if (type instanceof HTMLSelectElement && tx.type != null) {
        type.value = String(tx.type);
    }
    const category = form.querySelector('#transaction-category');
    if (category instanceof HTMLSelectElement && tx.category != null) {
        category.value = String(tx.category);
    }
    const heading = dialog.querySelector('#transaction-modal-heading');
    if (heading) {
        heading.textContent = dialog.dataset.textEditTitle ?? '';
    }
    const subtitle = dialog.querySelector('#transaction-modal-subtitle');
    if (subtitle) {
        subtitle.textContent = dialog.dataset.textEditSubtitle ?? '';
    }
    const submitLbl = dialog.querySelector('#transaction-submit-label');
    if (submitLbl) {
        submitLbl.textContent = dialog.dataset.textSaveChanges ?? '';
    }
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

    document.querySelectorAll('dialog[data-app-modal]').forEach((node) => {
        if (!(node instanceof HTMLDialogElement)) {
            return;
        }
        node.addEventListener('cancel', (event) => {
            if (node.hasAttribute('data-prevent-light-dismiss')) {
                event.preventDefault();
            }
        });
    });

    document.querySelectorAll('[data-open-transaction-modal]').forEach((el) => {
        el.addEventListener('click', (e) => {
            if (transactionDialog instanceof HTMLDialogElement) {
                resetTransactionFormForCreate(transactionDialog);
                transactionDialog.showModal();
            }
            e.stopPropagation();
        });
    });

    document.querySelectorAll('[data-edit-transaction]').forEach((el) => {
        el.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            const raw = el.getAttribute('data-edit-transaction');
            if (!raw || !(transactionDialog instanceof HTMLDialogElement)) {
                return;
            }
            try {
                const tx = JSON.parse(raw);
                configureTransactionFormForEdit(transactionDialog, tx);
                transactionDialog.showModal();
            } catch {
                /* ignore malformed payload */
            }
        });
    });

    document.querySelectorAll('[data-close-create-transaction]').forEach((el) => {
        el.addEventListener('click', () => {
            transactionDialog?.close();
        });
    });

    if (transactionDialog?.hasAttribute('data-open-with-errors')) {
        transactionDialog.showModal();
    }

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
            if (node.hasAttribute('data-prevent-light-dismiss')) {
                return;
            }
            if (!node.contains(event.target)) {
                node.close();
            }
        });
    });
});

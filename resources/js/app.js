import './bootstrap';

// Add Alpine.js support for interactivity
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

function showDialog(message, { okText = 'OK', cancelText = null } = {}) {
    return new Promise(resolve => {
        const root = document.getElementById('modal-root');
        if (!root) {
            resolve(true);
            return;
        }
        const overlay = document.createElement('div');
        overlay.className = 'modal-overlay';
        const dialog = document.createElement('div');
        dialog.className = 'modal';
        dialog.innerHTML = `<p>${message}</p>
            <div class="actions">
                ${cancelText ? `<button type="button" class="btn-secondary" data-cancel>${cancelText}</button>` : ''}
                <button type="button" class="btn-primary" data-ok>${okText}</button>
            </div>`;
        overlay.appendChild(dialog);
        root.appendChild(overlay);

        const close = (result) => {
            root.removeChild(overlay);
            resolve(result);
        };

        overlay.addEventListener('click', () => close(false));
        dialog.addEventListener('click', e => e.stopPropagation());
        dialog.querySelector('[data-ok]').addEventListener('click', () => close(true));
        if (cancelText) {
            dialog.querySelector('[data-cancel]').addEventListener('click', () => close(false));
        }
    });
}

window.showConfirm = (message) => showDialog(message, { okText: 'Delete', cancelText: 'Cancel' });
window.showAlert = (message) => showDialog(message, { okText: 'OK' });

window.showMessageDetail = (msg) => {
    return new Promise(resolve => {
        const root = document.getElementById('modal-root');
        if (!root) {
            resolve(false);
            return;
        }
        const overlay = document.createElement('div');
        overlay.className = 'modal-overlay';
        const dialog = document.createElement('div');
        dialog.className = 'modal max-w-lg';
        dialog.innerHTML = `
            <h2 class="text-lg font-bold">${msg.name}</h2>
            <p class="text-sm text-gray-400">${msg.email} • ${msg.date}</p>
            <p class="whitespace-pre-line">${msg.message}</p>
            <div class="actions">
                <button type="button" class="btn-secondary" data-close>Close</button>
                <button type="button" class="btn-primary bg-red-600 hover:bg-red-700" data-delete>Delete</button>
            </div>`;
        overlay.appendChild(dialog);
        root.appendChild(overlay);

        const close = (result) => {
            root.removeChild(overlay);
            resolve(result);
        };

        overlay.addEventListener('click', () => close(false));
        dialog.addEventListener('click', e => e.stopPropagation());
        dialog.querySelector('[data-close]').addEventListener('click', () => close(false));
        dialog.querySelector('[data-delete]').addEventListener('click', async () => {
            const ok = await window.showConfirm('Delete this message?');
            if (ok) close(true);
        });
    });
};

window.handleRead = async (msg) => {
    const deleted = await window.showMessageDetail(msg);
    if (deleted) {
        document.getElementById(`delete-form-${msg.id}`).submit();
    }
};

function bindReadButtons(root = document) {
    root.querySelectorAll('[data-read]').forEach(btn => {
        btn.addEventListener('click', () => window.handleRead(JSON.parse(btn.dataset.read)));
    });
}

function bindConfirmForms(root = document) {
    root.querySelectorAll('form[data-confirm]').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const ok = await window.showConfirm(form.dataset.confirm);
            if (ok) form.submit();
        });
    });
}

function bindSidebar(container) {
    bindReadButtons(container);
    bindConfirmForms(container);
    container.querySelectorAll('nav a').forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            loadSidebarMessages(link.getAttribute('href'));
        });
    });
}

async function loadSidebarMessages(url = null) {
    const refreshBtn = document.getElementById('sidebar-refresh');
    if (refreshBtn) {
        refreshBtn.classList.add('loading');
        refreshBtn.disabled = true;
    }
    try {
        const adminPrefix = document.querySelector('meta[name="admin-prefix"]')?.content || 'admin';
        const endpoint = url || `/${adminPrefix}/messages/sidebar`;
        const res = await fetch(endpoint);
        if (!res.ok) throw new Error('Failed to load sidebar messages');
        const html = await res.text();
        const container = document.getElementById('sidebar-messages');
        if (container) {
            container.innerHTML = html;
            bindSidebar(container);
        }
    } catch (e) {
        console.error(e);
    } finally {
        if (refreshBtn) {
            refreshBtn.classList.remove('loading');
            refreshBtn.disabled = false;
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    bindConfirmForms();
    bindReadButtons();

    const refreshBtn = document.getElementById('sidebar-refresh');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', () => loadSidebarMessages());
    }

    const sidebar = document.getElementById('sidebar-messages');
    if (sidebar) {
        bindSidebar(sidebar);
    }
});

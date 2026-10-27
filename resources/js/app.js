// Tabs (desktop) that become an accordion on mobile: every [data-tab] button controls the matching [data-panel].
document.querySelectorAll('[data-tabs]').forEach((root) => {
    root.querySelectorAll('[data-tab]').forEach((tab) => {
        tab.addEventListener('click', () => {
            root.querySelectorAll('[data-tab]').forEach((other) => {
                other.setAttribute('aria-selected', String(other.dataset.tab === tab.dataset.tab));
            });
            root.querySelectorAll('[data-panel]').forEach((panel) => {
                panel.classList.toggle('hidden', panel.dataset.panel !== tab.dataset.tab);
            });
        });
    });
});

// Product carousels: prev/next buttons scroll one card at a time.
document.querySelectorAll('[data-carousel]').forEach((root) => {
    const track = root.querySelector('[data-track]');
    const step = () => track.firstElementChild?.getBoundingClientRect().width + 32 || track.clientWidth;

    root.querySelector('[data-prev]')?.addEventListener('click', () => track.scrollBy({ left: -step(), behavior: 'smooth' }));
    root.querySelector('[data-next]')?.addEventListener('click', () => track.scrollBy({ left: step(), behavior: 'smooth' }));
});

// Admin photo picker: preview the chosen image and accept files dropped onto the [data-dropzone].
document.querySelectorAll('[data-dropzone]').forEach((zone) => {
    const input = zone.querySelector('[data-image-input]');
    const preview = zone.querySelector('[data-image-preview]');
    const empty = zone.querySelector('[data-image-empty]');
    const name = zone.parentElement.querySelector('[data-image-name]');

    const show = () => {
        const file = input.files[0];
        if (!file) return;
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
        empty?.classList.add('hidden');
        if (name) name.textContent = file.name;
    };

    input.addEventListener('change', show);
    zone.addEventListener('dragover', (event) => {
        event.preventDefault();
        zone.dataset.dragging = '';
    });
    zone.addEventListener('dragleave', () => delete zone.dataset.dragging);
    zone.addEventListener('drop', (event) => {
        event.preventDefault();
        delete zone.dataset.dragging;
        if (event.dataTransfer.files.length) {
            input.files = event.dataTransfer.files;
            show();
        }
    });
});

// Toasts: close on ×, or by themselves after a few seconds unless the pointer is over them.
const dismissToast = (toast) => {
    toast.dataset.leaving = '';
    setTimeout(() => toast.remove(), 300);
};

const armToast = (toast) => {
    let timer = setTimeout(() => dismissToast(toast), 6000);
    toast.addEventListener('mouseenter', () => clearTimeout(timer));
    toast.addEventListener('mouseleave', () => (timer = setTimeout(() => dismissToast(toast), 3000)));
    toast.querySelector('[data-toast-close]').addEventListener('click', () => dismissToast(toast));
};

const showToast = (message) => {
    const region = document.querySelector('[data-toast-region]');
    const template = document.querySelector('[data-toast-template]');
    if (!region || !template) return;

    region.querySelectorAll('[data-toast]').forEach(dismissToast);
    const toast = template.content.firstElementChild.cloneNode(true);
    toast.querySelector('[data-toast-message]').textContent = message;
    region.append(toast);
    armToast(toast);
};

document.querySelectorAll('[data-toast-region] [data-toast]').forEach(armToast);

// Favorite hearts: toggle in place without reloading. Without JavaScript the form posts and redirects back.
document.querySelectorAll('[data-wishlist-form]').forEach((form) => {
    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const button = form.querySelector('button');
        button.disabled = true;

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: new FormData(form),
            });
            if (!response.ok) throw new Error(response.statusText);
            const { saved, count, message } = await response.json();

            document.querySelectorAll(`[data-wishlist-form][action="${form.action}"] button`).forEach((other) => {
                other.setAttribute('aria-pressed', String(saved));
            });
            document.querySelectorAll('[data-wishlist-count]').forEach((badge) => (badge.textContent = count));
            showToast(message);

            // On the favorites page an unsaved piece leaves the grid; the last one brings back the empty state.
            const item = form.closest('[data-wishlist-item]');
            if (!saved && item) {
                item.dataset.leaving = '';
                setTimeout(() => (count === 0 ? window.location.reload() : item.remove()), 300);
            }
        } catch {
            form.submit();
        } finally {
            button.disabled = false;
        }
    });
});

// "Chat with us" launcher: close the open panel on Escape or a click elsewhere on the page.
document.querySelectorAll('[data-chat]').forEach((chat) => {
    document.addEventListener('click', (event) => {
        if (chat.open && !chat.contains(event.target)) chat.open = false;
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && chat.open) {
            chat.open = false;
            chat.querySelector('summary').focus();
        }
    });
});

const adminPrefix = document.querySelector('meta[name="admin-prefix"]')?.getAttribute('content') || 'admin';
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-field]').forEach(initSimpleEditor);
});

function initSimpleEditor(wrapper) {
    const toolbar = wrapper.querySelector('[data-toolbar]');
    const editor = wrapper.querySelector('[contenteditable]');
    const textarea = wrapper.querySelector('textarea');

    if (textarea && editor) {
        editor.innerHTML = textarea.value;
    }

    const slugInput = document.querySelector('input[name="slug"]');
    const imageButton = toolbar ? toolbar.querySelector('button[data-command="image"]') : null;
    if (imageButton) {
        const updateImageButtonState = () => {
            const slugValue = slugInput ? slugInput.value.trim() : (wrapper.dataset.slug || '').trim();
            imageButton.disabled = slugValue === '';
        };
        updateImageButtonState();
        if (slugInput) {
            slugInput.addEventListener('input', updateImageButtonState);
        }
    }

    let savedSelection = null;
    const saveSelection = () => {
        const sel = document.getSelection();
        if (sel && sel.rangeCount > 0) {
            savedSelection = sel.getRangeAt(0);
        }
    };
    const restoreSelection = () => {
        const sel = document.getSelection();
        if (savedSelection) {
            sel.removeAllRanges();
            sel.addRange(savedSelection);
        }
    };
    editor.addEventListener('keyup', saveSelection);
    editor.addEventListener('mouseup', saveSelection);

    editor.addEventListener('keydown', (e) => {
        if (e.key === 'Delete' || e.key === 'Backspace') {
            const sel = document.getSelection();
            if (!sel || sel.rangeCount === 0) return;
            const node = sel.anchorNode;
            if (!node) return;
            const el = node.nodeType === Node.ELEMENT_NODE ? node : node.parentElement;
            if (el && el.tagName === 'IMG') {
                e.preventDefault();
                el.remove();
                return;
            }
            const range = sel.getRangeAt(0);
            const contents = range.cloneContents();
            if (contents.childNodes.length === 1 && contents.childNodes[0].nodeName === 'IMG') {
                e.preventDefault();
                range.deleteContents();
            }
        }
    });

      function showLinkDialog() {
        return new Promise(resolve => {
            const root = document.getElementById('modal-root');
            if (!root) {
                resolve(null);
                return;
            }
            const overlay = document.createElement('div');
            overlay.className = 'modal-overlay';
            const dialog = document.createElement('div');
            dialog.className = 'modal';
            dialog.innerHTML = `
                <label>URL
                    <input type="text" data-url placeholder="https://" />
                </label>
                <label>Target
                    <select data-target>
                        <option value="_self">Same tab</option>
                        <option value="_blank">New tab</option>
                    </select>
                </label>
                <label>Rel
                    <select data-rel>
                        <option value="">None</option>
                        <option value="nofollow">nofollow</option>
                        <option value="sponsored">sponsored</option>
                        <option value="ugc">ugc</option>
                    </select>
                </label>
                <div class="actions">
                    <button type="button" data-cancel class="btn-secondary">Cancel</button>
                    <button type="button" data-ok class="btn-primary">Insert</button>
                </div>`;
            overlay.appendChild(dialog);
            root.appendChild(overlay);

            const focusable = dialog.querySelectorAll('input, select, button');
            const first = focusable[0];
            const last = focusable[focusable.length - 1];
            first.focus();

            function trap(e) {
                if (e.key === 'Tab') {
                    if (e.shiftKey) {
                        if (document.activeElement === first) {
                            e.preventDefault();
                            last.focus();
                        }
                    } else {
                        if (document.activeElement === last) {
                            e.preventDefault();
                            first.focus();
                        }
                    }
                }
            }

            function close(result) {
                root.removeChild(overlay);
                dialog.removeEventListener('keydown', trap);
                editor.focus();
                resolve(result);
            }

            dialog.addEventListener('click', e => e.stopPropagation());
            overlay.addEventListener('click', e => e.stopPropagation());
            dialog.addEventListener('keydown', trap);
            dialog.querySelector('[data-cancel]').addEventListener('click', () => close(null));
            dialog.querySelector('[data-ok]').addEventListener('click', () => {
                const url = dialog.querySelector('[data-url]').value.trim();
                const target = dialog.querySelector('[data-target]').value;
                const rel = dialog.querySelector('[data-rel]').value;
                close({ url, target, rel });
            });
        });
      }

      function showImageSizeDialog(originalWidth, originalHeight) {
        return new Promise(resolve => {
            const root = document.getElementById('modal-root');
            if (!root) {
                resolve(null);
                return;
            }
            const overlay = document.createElement('div');
            overlay.className = 'modal-overlay';
            const dialog = document.createElement('div');
            dialog.className = 'modal';
            dialog.innerHTML = `
                <label>Width
                    <input type="number" data-width />
                </label>
                <label>Height
                    <input type="number" data-height />
                </label>
                <label class="inline-flex items-center gap-1"><input type="checkbox" data-lock checked /> Lock aspect ratio</label>
                <div class="actions">
                    <button type="button" data-cancel class="btn-secondary">Cancel</button>
                    <button type="button" data-ok class="btn-primary">Insert</button>
                </div>`;
            overlay.appendChild(dialog);
            root.appendChild(overlay);

            const widthInput = dialog.querySelector('[data-width]');
            const heightInput = dialog.querySelector('[data-height]');
            const lockInput = dialog.querySelector('[data-lock]');
            const aspect = originalHeight / originalWidth;
            const maxWidth = editor.clientWidth;
            const defaultWidth = Math.min(originalWidth, maxWidth);
            widthInput.value = defaultWidth;
            heightInput.value = Math.round(defaultWidth * aspect);

            widthInput.addEventListener('input', () => {
                let w = parseInt(widthInput.value, 10) || 0;
                if (w > maxWidth) w = maxWidth;
                if (w < 1) w = 1;
                widthInput.value = w;
                if (lockInput.checked) {
                    heightInput.value = Math.round(w * aspect);
                }
            });

            heightInput.addEventListener('input', () => {
                let h = parseInt(heightInput.value, 10) || 0;
                if (h < 1) h = 1;
                heightInput.value = h;
                if (lockInput.checked) {
                    let w = Math.round(h / aspect);
                    if (w > maxWidth) {
                        w = maxWidth;
                        h = Math.round(w * aspect);
                        heightInput.value = h;
                    }
                    widthInput.value = w;
                }
            });

            const focusable = dialog.querySelectorAll('input, button');
            const first = focusable[0];
            const last = focusable[focusable.length - 1];
            first.focus();

            function trap(e) {
                if (e.key === 'Tab') {
                    if (e.shiftKey) {
                        if (document.activeElement === first) {
                            e.preventDefault();
                            last.focus();
                        }
                    } else {
                        if (document.activeElement === last) {
                            e.preventDefault();
                            first.focus();
                        }
                    }
                }
            }

            function close(result) {
                root.removeChild(overlay);
                dialog.removeEventListener('keydown', trap);
                editor.focus();
                resolve(result);
            }

            dialog.addEventListener('click', (e) => e.stopPropagation());
            overlay.addEventListener('click', (e) => e.stopPropagation());
            dialog.addEventListener('keydown', trap);
            dialog.querySelector('[data-cancel]').addEventListener('click', () => close(null));
            dialog.querySelector('[data-ok]').addEventListener('click', () => {
                const width = parseInt(widthInput.value, 10);
                const height = parseInt(heightInput.value, 10);
                close({ width, height });
            });
        });
      }

    if (toolbar) {
        toolbar.querySelectorAll('button[data-command]').forEach(btn => {
            btn.addEventListener('click', () => {
                const command = btn.dataset.command;
                let value = btn.dataset.value || null;

                if (command === 'createLink') {
                    saveSelection();
                    showLinkDialog().then(data => {
                        if (data && data.url) {
                            restoreSelection();
                            editor.focus();
                            document.execCommand('createLink', false, data.url);
                            const selection = document.getSelection();
                            if (selection) {
                                let link = selection.anchorNode;
                                while (link && link.nodeName !== 'A') {
                                    link = link.parentNode;
                                }
                                if (link) {
                                    link.setAttribute('target', data.target || '_self');
                                    if (data.rel) {
                                        link.setAttribute('rel', data.rel);
                                    } else {
                                        link.removeAttribute('rel');
                                    }
                                }
                            }
                        }
                    });
                } else if (command === 'image') {
                    saveSelection();
                    const input = document.createElement('input');
                    input.type = 'file';
                    input.accept = 'image/*';
                    input.addEventListener('change', () => {
                        const file = input.files[0];
                        if (!file) return;

                        const context = wrapper.dataset.context || '';
                        const slug = slugInput ? slugInput.value.trim() : (wrapper.dataset.slug || '').trim();
                        if (!slug) {
                            alert('Please set a slug before uploading images.');
                            return;
                        }
                        const formData = new FormData();
                        formData.append('slug', slug);
                        formData.append('image', file);

                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', `/${adminPrefix}/${context}/upload-image`);
                        const tokenElement = wrapper.querySelector('[data-token]') || document.querySelector('meta[name="csrf-token"]');
                        if (tokenElement) {
                            const token = tokenElement.value || tokenElement.getAttribute('content');
                            if (token) {
                                xhr.setRequestHeader('X-CSRF-TOKEN', token);
                            }
                        }
                        xhr.onload = () => {
                            try {
                                const res = JSON.parse(xhr.responseText);
                                if (res.location) {
                                    const img = new Image();
                                    img.onload = () => {
                                        showImageSizeDialog(img.naturalWidth, img.naturalHeight).then(dim => {
                                            if (!dim) return;
                                            restoreSelection();
                                            editor.focus();
                                            document.execCommand('insertHTML', false, `<img src="${res.location}" width="${dim.width}" height="${dim.height}" alt="" />`);
                                        });
                                    };
                                    img.src = res.location;
                                }
                            } catch (e) {}
                        };
                        xhr.send(formData);
                    });
                    input.click();
                } else {
                    if (command === 'formatBlock' && value) {
                        value = `<${value}>`;
                    }
                    restoreSelection();
                    editor.focus();
                    document.execCommand(command, false, value);
                }
            });
        });
    }

    const form = wrapper.closest('form');
    if (form) {
        form.addEventListener('submit', () => {
            if (textarea) {
                textarea.value = editor.innerHTML;
            }
        });
    }
}

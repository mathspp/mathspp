(function () {
    const sections = document.querySelectorAll('.python-vault');
    if (!sections.length) {
        return;
    }

    sections.forEach((section) => {
        const searchInput = section.querySelector('[data-python-vault-search]');
        const cards = Array.from(section.querySelectorAll('[data-python-vault-card]'));
        const noResultsMessage = section.querySelector('[data-python-vault-no-results]');

        const filterCards = () => {
            if (!searchInput) {
                return;
            }

            const term = searchInput.value.trim().toLowerCase();
            let visible = 0;

            cards.forEach((card) => {
                const haystack = card.dataset.searchText || '';
                const matches = !term || haystack.indexOf(term) !== -1;

                card.style.display = matches ? '' : 'none';
                if (matches) {
                    visible += 1;
                }
            });

            if (noResultsMessage) {
                if (visible === 0) {
                    noResultsMessage.hidden = false;
                } else {
                    noResultsMessage.hidden = true;
                }
            }
        };

        if (searchInput) {
            searchInput.addEventListener('input', filterCards);
        }

        section.addEventListener('click', (event) => {
            const tagButton = event.target.closest('[data-python-vault-tag]');
            if (tagButton && searchInput) {
                event.preventDefault();
                const tagValue = tagButton.dataset.pythonVaultTag || tagButton.textContent || '';
                const currentValue = searchInput.value.trim();
                const normalizedTokens = currentValue.toLowerCase().split(/\s+/).filter(Boolean);
                const tagLower = tagValue.toLowerCase();

                if (!normalizedTokens.includes(tagLower)) {
                    searchInput.value = currentValue ? currentValue + ' ' + tagValue : tagValue;
                }

                searchInput.focus();
                const length = searchInput.value.length;
                try {
                    searchInput.setSelectionRange(length, length);
                } catch (error) {
                    // Some browsers do not support setSelectionRange on certain input types.
                }
                filterCards();
                return;
            }

            const copyButton = event.target.closest('[data-copy-target]');
            if (copyButton) {
                event.preventDefault();
                const targetSelector = copyButton.dataset.copyTarget;
                if (!targetSelector) {
                    return;
                }

                const targetElement = section.querySelector(targetSelector);
                if (!targetElement) {
                    return;
                }

                const originalLabel = copyButton.dataset.originalLabel || copyButton.textContent;
                copyButton.dataset.originalLabel = originalLabel;

                copyText(targetElement.textContent)
                    .then(() => {
                        copyButton.textContent = 'Copied!';
                        copyButton.disabled = true;
                        setTimeout(() => {
                            copyButton.textContent = originalLabel;
                            copyButton.disabled = false;
                        }, 2000);
                    })
                    .catch(() => {
                        copyButton.textContent = 'Copy failed';
                        copyButton.disabled = true;
                        setTimeout(() => {
                            copyButton.textContent = originalLabel;
                            copyButton.disabled = false;
                        }, 2000);
                    });
            }
        });

        if (searchInput) {
            filterCards();
        }
    });

    function copyText(text) {
        if (!text) {
            return Promise.reject(new Error('Nothing to copy'));
        }

        if (navigator.clipboard && typeof navigator.clipboard.writeText === 'function') {
            return navigator.clipboard.writeText(text);
        }

        return new Promise((resolve, reject) => {
            try {
                const textarea = document.createElement('textarea');
                textarea.value = text;
                textarea.setAttribute('readonly', '');
                textarea.style.position = 'absolute';
                textarea.style.left = '-9999px';
                document.body.appendChild(textarea);
                textarea.select();
                const successful = document.execCommand('copy');
                document.body.removeChild(textarea);
                if (!successful) {
                    reject(new Error('Copy command was unsuccessful'));
                    return;
                }
                resolve();
            } catch (error) {
                reject(error);
            }
        });
    }
})();

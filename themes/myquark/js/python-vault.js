(function () {
    const sections = document.querySelectorAll('.python-vault');
    if (!sections.length) {
        return;
    }

    const decodeHashQuery = () => {
        const rawHash = window.location.hash.replace(/^#/, '');
        if (!rawHash) {
            return '';
        }

        const normalized = rawHash.replace(/\+/g, ' ');
        try {
            return decodeURIComponent(normalized);
        } catch (error) {
            return normalized;
        }
    };

    const buildShareUrl = (query) => {
        const url = new URL(window.location.href);
        if (query) {
            url.hash = encodeURIComponent(query);
        } else {
            url.hash = '';
        }
        return url.toString();
    };

    const hashHandlers = [];
    let hashListenerBound = false;
    let slashListenerBound = false;

    const registerHashListener = () => {
        if (hashListenerBound) {
            return;
        }

        window.addEventListener('hashchange', () => {
            hashHandlers.forEach((handler) => handler());
        });

        hashListenerBound = true;
    };

    sections.forEach((section) => {
        const searchInput = section.querySelector('[data-python-vault-search]');
        const copyResultsButton = section.querySelector('[data-python-vault-copy-results]');
        const cards = Array.from(section.querySelectorAll('[data-python-vault-card]'));
        const noResultsMessage = section.querySelector('[data-python-vault-no-results]');
        let copyResultsResetTimeout;

        const filterCards = () => {
            const term = searchInput ? searchInput.value.trim().toLowerCase() : '';
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
                noResultsMessage.hidden = visible !== 0;
            }
        };

        const updateCopyResultsState = () => {
            if (!copyResultsButton) {
                return;
            }

            if (!copyResultsButton.dataset.originalLabel) {
                copyResultsButton.dataset.originalLabel = copyResultsButton.textContent.trim();
            }

            const query = searchInput ? searchInput.value.trim() : '';
            copyResultsButton.dataset.shareUrl = buildShareUrl(query);

            if (query) {
                copyResultsButton.setAttribute('aria-label', `Copy URL to results for “${query}”`);
            } else {
                copyResultsButton.setAttribute('aria-label', 'Copy URL to these results');
            }
        };

        const syncUrlWithQuery = () => {
            if (!searchInput) {
                return;
            }

            const query = searchInput.value.trim();
            const url = buildShareUrl(query);

            if (typeof history.replaceState === 'function') {
                history.replaceState(null, '', url);
            } else {
                window.location.hash = query ? encodeURIComponent(query) : '';
            }
        };

        const syncState = ({ fromHash = false } = {}) => {
            filterCards();
            updateCopyResultsState();
            if (!fromHash) {
                syncUrlWithQuery();
            }
        };

        if (searchInput) {
            searchInput.addEventListener('input', () => {
                syncState();
            });

            if (!slashListenerBound) {
                document.addEventListener('keydown', (event) => {
                    if (event.key !== '/' || event.altKey || event.ctrlKey || event.metaKey) {
                        return;
                    }

                    const target = event.target;
                    const tagName = target && target.tagName ? target.tagName.toLowerCase() : '';
                    const isEditable = target && target.isContentEditable;

                    if (tagName === 'input' || tagName === 'textarea' || tagName === 'select' || isEditable) {
                        return;
                    }

                    event.preventDefault();
                    searchInput.focus();
                    searchInput.select();
                });

                slashListenerBound = true;
            }
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

                syncState();
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

        if (copyResultsButton) {
            copyResultsButton.addEventListener('click', (event) => {
                event.preventDefault();

                const shareUrl = copyResultsButton.dataset.shareUrl || buildShareUrl(searchInput ? searchInput.value.trim() : '');
                const originalLabel = copyResultsButton.dataset.originalLabel || copyResultsButton.textContent.trim();
                copyResultsButton.dataset.originalLabel = originalLabel;

                const showCopyResult = (message) => {
                    window.clearTimeout(copyResultsResetTimeout);
                    copyResultsButton.textContent = message;
                    copyResultsResetTimeout = window.setTimeout(() => {
                        copyResultsButton.textContent = originalLabel;
                        copyResultsButton.disabled = false;
                    }, 2000);
                };

                copyResultsButton.disabled = true;

                copyText(shareUrl)
                    .then(() => {
                        showCopyResult('URL copied!');
                    })
                    .catch(() => {
                        showCopyResult('Copy failed');
                    });
            });
        }

        const applyHashQuery = () => {
            if (!searchInput) {
                filterCards();
                updateCopyResultsState();
                return;
            }

            const queryFromHash = decodeHashQuery();
            if (searchInput.value !== queryFromHash) {
                searchInput.value = queryFromHash;
            }

            syncState({ fromHash: true });
        };

        applyHashQuery();
        hashHandlers.push(applyHashQuery);
        registerHashListener();
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

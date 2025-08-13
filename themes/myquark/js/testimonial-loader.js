var wall = document.getElementById('testimonial-wall');
var btn = document.getElementById('reviews-load-more');
var loader = document.getElementById('reviews-loader');

// Init Bricklayer
var bricklayer = new Bricklayer(wall);

var API_URL = 'https://mathspp.com/api/testimonials.json';

function buildUrl(limit, offset, tagsCSV) {
    var url = new URL(API_URL);
    url.searchParams.set('limit', String(limit));
    url.searchParams.set('offset', String(offset));
    if (tagsCSV && tagsCSV.trim() !== '') {
        url.searchParams.set('review-tag', tagsCSV);
    }
    return url.toString();
}

function htmlStringToNode(html) {
    var tpl = document.createElement('template');
    tpl.innerHTML = html.trim();
    return tpl.content.firstElementChild;
}

function getColumns() {
    return wall.querySelectorAll('.bricklayer-column');
}

function shortestColumn(columns) {
    var minCol = null;
    var minH = Infinity;
    (columns.forEach ? columns : Array.prototype.slice.call(columns)).forEach(function (col) {
        var h = col.getBoundingClientRect().height;
        if (h < minH) { minH = h; minCol = col; }
    });
    return minCol || wall; // fallback pre-init
}

// Append a single card respecting Bricklayer's columns
function appendCard(node) {
    // Tag incoming node for animation
    node.classList.add('review-card--incoming');

    if (bricklayer && typeof bricklayer.append === 'function') {
        bricklayer.append(node);
    } else {
        var cols = getColumns();
        if (cols.length) shortestColumn(cols).appendChild(node);
        else wall.appendChild(node);
    }

    // Let layout settle, then flip animation class
    requestAnimationFrame(function () {
        node.classList.add('review-card--visible');
    });
}

// Wait for images in a fragment (or element) to finish (or be cached)
function onImagesLoad(el, cb) {
    var imgs = el.querySelectorAll ? el.querySelectorAll('img') : [];
    if (!imgs.length) { cb(); return; }
    var remaining = imgs.length;
    function done() { if (--remaining === 0) cb(); }
    (imgs.forEach ? imgs : Array.prototype.slice.call(imgs)).forEach(function (img) {
        if (img.complete) done();
        else {
            img.addEventListener('load', done, { once: true });
            img.addEventListener('error', done, { once: true });
        }
    });
}

// Find a stable anchor: last fully visible card (or last child of shortest column)
function pickAnchor() {
    var cols = getColumns();
    var candidates = [];
    (cols.length ? cols : [wall]).forEach ? cols.forEach(pushLast) : Array.prototype.forEach.call(cols, pushLast);
    function pushLast(col) {
        if (!col.lastElementChild) return;
        candidates.push(col.lastElementChild);
    }
    // Prefer the one closest to the bottom but still visible
    var viewportH = window.innerHeight || document.documentElement.clientHeight;
    var best = null;
    var bestBottom = -Infinity;
    candidates.forEach(function (el) {
        var r = el.getBoundingClientRect();
        if (r.top < viewportH && r.bottom > 0) {
            if (r.bottom > bestBottom) { bestBottom = r.bottom; best = el; }
        }
    });
    return best || candidates[candidates.length - 1] || null;
}

// Run a DOM mutation while preserving the anchor's on-screen position (no flicker)
function preserveScrollDuring(mutator) {
    var anchor = pickAnchor();
    if (!anchor) { mutator(); return; }

    // Measure before
    var beforeTop = anchor.getBoundingClientRect().top;

    // Force instant scroll behavior during the operation (avoid CSS smooth)
    document.documentElement.classList.add('preserve-scroll-now');

    // Mutate DOM
    mutator();

    // After layout changes, in the same frame adjust scroll by delta
    requestAnimationFrame(function () {
        var afterTop = anchor.getBoundingClientRect().top;
        var delta = afterTop - beforeTop;
        if (delta !== 0) {
            window.scrollBy(0, delta);
        }
        // Clean up asap
        document.documentElement.classList.remove('preserve-scroll-now');
    });
}

btn.addEventListener('click', async function () {
    var limit = parseInt(btn.dataset.limit || '10', 10);
    var offset = parseInt(btn.dataset.offset || '0', 10);
    var tags = btn.dataset.tags || '';

    btn.disabled = true;
    if (loader) loader.style.display = '';

    try {
        const res = await fetch(buildUrl(limit, offset, tags), {
            headers: { 'Accept': 'application/json' },
            credentials: 'same-origin'
        });
        if (!res.ok) throw new Error('Failed to load more testimonials');
        const data = await res.json();

        // Prepare nodes in a fragment
        var fragment = document.createDocumentFragment();
        var appendedCount = 0;

        if (Array.isArray(data.items)) {
            data.items.forEach(function (html) {
                var node = htmlStringToNode(html);
                if (node) {
                    fragment.appendChild(node);
                    appendedCount += 1;
                }
            });
        }

        // Wait for images in the new nodes (so layout is stable)
        onImagesLoad(fragment, function () {
            preserveScrollDuring(function () {
                // Move children out of fragment and into the Bricklayer columns
                while (fragment.firstChild) {
                    appendCard(fragment.firstChild);
                }
                if (bricklayer && typeof bricklayer.redraw === 'function') {
                    bricklayer.redraw();
                }
            });
        });

        // Update offset
        btn.dataset.offset = String(offset + (data.count || appendedCount));

        // Hide button if no more
        if (!data.has_more || (data.count || appendedCount) === 0) {
            btn.style.display = 'none';
        }
    } catch (err) {
        console.error(err);
        btn.textContent = 'Try again';
        btn.disabled = false;
    } finally {
        if (loader) loader.style.display = 'none';
        if (btn && btn.style.display !== 'none') btn.disabled = false;
    }
});

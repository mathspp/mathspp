document.addEventListener("DOMContentLoaded", function() {
    // Find all h tags in the page
    const headers = document.querySelectorAll('h1, h2, h3, h4, h5, h6');

    // Iterate through each header
    headers.forEach(header => {
        if (header.id !== undefined) { return; }

        // Generate the ID from the header text
        const id = header.textContent
            .toLowerCase()
            .replace(/\W+/g, '-')
            .replace(/^-+|-+$/g, '');
        header.id = id;

        // Create a link tag
        const link = document.createElement('a');
        link.setAttribute('data-anchor-icon', '#');
        link.href = `#${id}`;
        link.className = 'anchor-link';

        // Set the data-anchor-processed attribute on the header
        header.setAttribute('data-anchor-processed', 'true');

        // Append the link as a child of the header
        header.appendChild(link);
    });
});

document.body.addEventListener('click', (event) => {
    if (typeof event.target.dataset.anchorIcon !== 'undefined') {
      const href = event.target.href;
      navigator.clipboard.writeText(href)
      .then(() => {})
      .catch((error) => {
        console.error('Unable to copy to clipboard the anchor', error);
      });
    }
  }, true);

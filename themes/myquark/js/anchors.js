document.addEventListener("DOMContentLoaded", function() {
    // Find all h tags in the page
    const headers = document.querySelectorAll('h1, h2, h3, h4, h5, h6');

    // Loop through each header and process them
    headers.forEach(header => {
    // Create an ID from the header text
    const id = header.textContent
        .toLowerCase()
        .replace(/\s+/g, '-')
        .replace(/[^\w-]/g, '');

    // Add the ID to the header and set the data attribute
    header.id = id;
    header.setAttribute('data-anchor-processed', 'true');

    // Create a link tag
    const link = document.createElement('a');
    link.setAttribute('data-anchor-icon', '#');
    link.href = `#${id}`;
    link.className = 'anchor-link';

    // Add the link tag immediately after the header
    header.parentNode.insertBefore(link, header.nextSibling);
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

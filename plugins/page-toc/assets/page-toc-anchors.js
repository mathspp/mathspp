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
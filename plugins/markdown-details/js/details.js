(function() {
  const collapsibles = document.querySelectorAll( '.js-details' );
  Array.prototype.forEach.call(collapsibles, function(collapsible)
  {
    let btn = collapsible.querySelector( 'button' );
    collapsible.dataset['expanded'] = false;
    btn.setAttribute( 'aria-expanded', false );
    btn.onclick = function()
    {
      let expanded = btn.getAttribute( 'aria-expanded' ) === 'true';
      btn.setAttribute( 'aria-expanded', !expanded );
      collapsible.setAttribute( 'data-expanded', !expanded );
    }
  });
})()
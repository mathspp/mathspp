document.addEventListener("DOMContentLoaded", function(event){ 
  document.querySelectorAll("label.collapsible").forEach(function(el) { el.addEventListener("click", function(ev) { setTimeout(function(){ev.target.scrollIntoView({behavior: "smooth"})}, 100) }) })
})

// cf. https://chat.stackexchange.com/transcript/message/55084622#55084622
var highlight = function() {
    var id = window.location.hash.split("?")[0].slice(1);
    if (id) {
        var p = document.getElementById("li"+id).classList.add("highlight");
    }
}
window.onhashchange = highlight;
highlight();

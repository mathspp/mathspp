---
metadata:
    description: Use the Py Paste tool to share Python code across the Internet, forever.
---

<link rel="stylesheet" href="/pypaste/pypaste.css">

<button id="saveB" title="alt+s" class="button" onclick="save_py(true)"           >copy<br>link</button>
<button id="viewB" title="alt+v" class="button" onclick="editor_mode(2)"           >view        </button>
<button id="editB" title="alt+e" class="button" onclick="editor_mode(0)"           >edit        </button>
<button id="copyB" title="alt+c" class="button" onclick="copy_link(py_area.value)">copy<br>code</button>

<div style="top:4em; left:.5em; right:.5em; bottom:0; font-size:125%; color:#101010;">
    <textarea class="mainArea" id="py_area" spellcheck="false" style="width:100%;margin:10px" rows="10"></textarea>
    <pre><code class="mainArea python" id="genc">
    </code></pre>
</div>

<script src="https://mathspp.com/user/themes/myquark/js/pako.min.js"></script>

  <script type="text/javascript">
    var EDITOR_MODE = 0; // 0-edit; 2-view

    function editor_mode(mode) {
      EDITOR_MODE = mode;
      py_area.style.display = EDITOR_MODE==0? 'block' : 'none';
      genc.style.display = EDITOR_MODE==2? 'block' : 'none';
      editB.disabled = EDITOR_MODE==0;
      viewB.disabled = EDITOR_MODE==2;
      if (mode==0) py_area.focus();
      if (mode==2) highlight_code();
    }
    
    
    function highlight_code() {
        genc.innerHTML = py_area.value;
        hljs.highlightBlock(genc);
    }
    
    function save_py(copyLink = false) {
      let b64 = "#0"+py_enc(py_area.value);
      history.pushState({}, "", b64);
      if (copyLink) copy_link(location.href.replace("/#", "#"));
    }
    function py_enc(str = py_area.value) {
      let bytes = new TextEncoder("utf-8").encode(str);
      return py_arrToB64(py_deflate(bytes));
    }
    function py_dec(str) {
      try {
        return new TextDecoder("utf-8").decode(py_inflate(py_b64ToArr(str)));
      } catch (e) {
        return "failed to decode - full link not copied?";
      }
    }

    function py_arrToB64(arr) {
      var bytestr = "";
      arr.forEach(c => bytestr+= String.fromCharCode(c));
      return btoa(bytestr).replace(/\+/g, "@").replace(/=+/, "");
    }
    function py_b64ToArr(str) {
      return new Uint8Array([...atob(decodeURIComponent(str).replace(/@/g, "+"))].map(c=>c.charCodeAt()))
    }
    
    function py_deflate(arr) {
      return pako.deflateRaw(arr, {"level": 9});
    }
    function py_inflate(arr) {
      return pako.inflateRaw(arr);
    }

    function copy_link(str) {
      navigator.clipboard.writeText(str);
    }

    function py_load_page() {
      py_area.value = "";
      let hash = decodeURIComponent(location.hash.slice(1));
      let v = hash[0];
      hash = hash.slice(1); // remove version
      if (hash) {
        let parts = hash.split("#");
        py_area.value = parts[0]? py_dec(parts[0]) : "";
        editor_mode(py_area.value? 2 : 0);
      } else editor_mode(0);
    }
    py_load_page();
    window.onhashchange=py_load_page;
    
    document.addEventListener("keydown", e => {
      let code = e.code;
      let ctrl = e.ctrlKey;
      let alt = e.altKey;
      if (alt) {
        if (code == 'KeyS') { saveB.click(); e.preventDefault(); viewB.click(); }
        if (code == 'KeyE') { editB.click(); e.preventDefault(); py_area.focus(); }
        if (code == 'KeyV') { viewB.click(); e.preventDefault(); }
        if (code == 'KeyC') { copyB.click(); e.preventDefault(); }
      }
      if (ctrl && code == 'KeyR') save_py(false);
      if (code == 'F5') save_py(false);
    });
  </script>

<br />

---

# Py Paste

Use the Py Paste tool to share Python code across the Internet, forever.

---

The Py Paste is licensed under the [MIT License][mit] and based on [dzaima/paste][dzaima-paste].

<details>
<summary>License</summary>
Copyright 2022 Rodrigo Girão Serrão.
<br />
Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
<br />
<br />
The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
<br />
<br />
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
</details>

[mit]: https://opensource.org/licenses/MIT
[dzaima-paste]: https://github.com/dzaima/paste
[multiline]: https://mathspp.com/texpaste#0C8lXKM7PKUtVUEmsiDNS0FZIqgASyQq2CgYqCiWJ2alcXCoqFUBuTFpRYnK1bpJCTEGuQkxxYVFJdRJQg66CSWJybW21UWKtigoA

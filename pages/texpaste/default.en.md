---
metadata:
    description: Use the TeX Paste tool to share beautiful equations across the Internet, forever.
---

<button id="saveB" title="alt+s" style="font-size:1em;width:6em;height:4em;vertical-align:top;margin-top:1em;margin-bottom:1em;" onclick="save_tex(true)"      >copy<br>link</button>
<button id="viewB" title="alt+v" style="font-size:1em;width:6em;height:4em;vertical-align:top;margin-top:1em;margin-bottom:1em;" onclick="editor_mode(2)"           >typeset     </button>
<button id="editB" title="alt+e" style="font-size:1em;width:6em;height:4em;vertical-align:top;margin-top:1em;margin-bottom:1em;" onclick="editor_mode(0)"           >edit        </button>
<button id="copyB" title="alt+c" style="font-size:1em;width:6em;height:4em;vertical-align:top;margin-top:1em;margin-bottom:1em;" onclick="copy_link(tex_area.value)">copy<br>TeX</button>

<div style="top:4em; left:.5em; right:.5em; bottom:0; font-size:125%; color:#101010;">
    <textarea class="mainArea" id="tex_area" spellcheck="false" style="width:100%;margin:10px" rows="10"></textarea>
    <div class="mainArea" id="genc">
    </div>
</div>

<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
<script src="https://mathspp.com/user/themes/myquark/js/pako.min.js"></script>

  <script type="text/javascript">
    window.MathJax = {
        tex: {
            inlineMath: [['$', '$'], ['\\(', '\\)']]
        }
    };

    var EDITOR_MODE = 0; // 0-edit; 2-view

    function editor_mode(mode) {
      EDITOR_MODE = mode;
      tex_area.style.display = EDITOR_MODE==0? 'block' : 'none';
      genc.style.display = EDITOR_MODE==2? 'block' : 'none';
      editB.disabled = EDITOR_MODE==0;
      viewB.disabled = EDITOR_MODE==2;
      if (mode==0) tex_area.focus();
      if (mode==2) texify_input();
    }
    
    
    function texify_input() {
        genc.innerText = tex_area.value;
        genc.style="";
        MathJax.typeset();
    }
    
    function save_tex(copyLink = false) {
      let b64 = "#0"+tex_enc(tex_area.value);
      history.pushState({}, "", b64);
      if (copyLink) copy_link(location.href.replace("/#", "#"));
    }
    function tex_enc(str = tex_area.value) {
      let bytes = new TextEncoder("utf-8").encode(str);
      return tex_arrToB64(tex_deflate(bytes));
    }
    function tex_dec(str) {
      try {
        return new TextDecoder("utf-8").decode(tex_inflate(tex_b64ToArr(str)));
      } catch (e) {
        return "failed to decode - full link not copied?";
      }
    }

    function tex_arrToB64(arr) {
      var bytestr = "";
      arr.forEach(c => bytestr+= String.fromCharCode(c));
      return btoa(bytestr).replace(/\+/g, "@").replace(/=+/, "");
    }
    function tex_b64ToArr(str) {
      return new Uint8Array([...atob(decodeURIComponent(str).replace(/@/g, "+"))].map(c=>c.charCodeAt()))
    }
    
    function tex_deflate(arr) {
      return pako.deflateRaw(arr, {"level": 9});
    }
    function tex_inflate(arr) {
      return pako.inflateRaw(arr);
    }

    function copy_link(str) {
      navigator.clipboard.writeText(str);
    }

    function tex_load_page() {
      tex_area.value = "";
      let hash = decodeURIComponent(location.hash.slice(1));
      let v = hash[0];
      hash = hash.slice(1); // remove version
      if (hash) {
        let parts = hash.split("#");
        tex_area.value = parts[0]? tex_dec(parts[0]) : "";
        editor_mode(tex_area.value? 2 : 0);
      } else editor_mode(0);
    }
    tex_load_page();
    window.onhashchange=tex_load_page;
    
    document.addEventListener("keydown", e => {
      let code = e.code;
      let ctrl = e.ctrlKey;
      let alt = e.altKey;
      if (alt) {
        if (code == 'KeyS') { saveB.click(); e.preventDefault(); viewB.click(); }
        if (code == 'KeyE') { editB.click(); e.preventDefault(); tex_area.focus(); }
        if (code == 'KeyV') { viewB.click(); e.preventDefault(); }
        if (code == 'KeyC') { copyB.click(); e.preventDefault(); }
      }
      if (ctrl && code == 'KeyR') save_tex(false);
      if (code == 'F5') save_tex(false);
    });
  </script>

<br />

---

# TeX Paste

Use the TeX Paste tool to share beautiful equations across the Internet, forever.

## Instructions

Just type your equation(s) in the text area above. Use &#36; to surround inline expressions and &#36;&#36; to surround multiline expressions ([here's an example][multiline]).

Press the "typeset" button to preview your equation and press the "copy link" button to get a link to the equation you just typed. The links will never expire or stop working.

The TeX Paste is licensed under the [MIT License][mit] and based on [dzaima/paste][dzaima-paste].

<details>
<summary>License</summary>
Copyright 2020 Rodrigo Girão Serrão.
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

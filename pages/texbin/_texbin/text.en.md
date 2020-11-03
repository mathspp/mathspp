<button id="saveB" title="alt+s" style="font-size:1em;height:3em;width:6em" onclick="save(true)"      >copy<br>link</button>
  <button id="viewB" title="alt+v" style="font-size:1em;height:3em;width:6em" onclick="md(2)"           >typeset     </button>
  <button id="editB" title="alt+e" style="font-size:1em;height:3em;width:6em" onclick="md(0)"           >edit        </button>
  <button id="copyB" title="alt+c" style="font-size:1em;height:3em;width:6em" onclick="copy(main.value)">copy<br>code</button>
  <div style="position:absolute; top:4em; left:.5em; right:.5em; bottom:0">
    <textarea class="mainArea" id="main" spellcheck="false"></textarea>
    <div class="mainArea" id="genc" hidden>
    </div>
  </div>
  
  <script type="text/javascript">
    window.MathJax = {
    tex: {
        inlineMath: [['$', '$'], ['\\(', '\\)']]
    }
    };

    var MODE = 0; // 0-edit; 2-view

    function md(mode) {
      MODE = mode;
      main.style.display = MODE==0? 'block' : 'none';
      genc.style.display = MODE==2? 'block' : 'none';
      editB.disabled = MODE==0;
      viewB.disabled = MODE==2;
      if (mode==0) main.focus();
      if (mode==2) generate();
    }
    
    
    function generate() {
      genc.innerText = main.value;
      genc.style="";
        MathJax.typeset();
    }
    function colorCode(str, cols, prefix) {
      let prev = cols[0];
      for (let i = 1; i < cols.length; i++) {
        let curr = cols[i];
        if (curr) {
          if (curr===prev) cols[i] = undefined;
          else prev = curr;
        }
      }
      let code = "";
      for (let i = 0; i < str.length; i++) {
        let ccol = cols[i];
        if (ccol) {
          if (code) code+= "</span>";
          code+= "<span class="+prefix+ccol+">"
        }
        code+= html(str[i]);
      }
      code+= "</span>";
      genc.innerHTML = code;
    }
    
    function save(copyLink = false) {
      let b64 = "#0"+enc(main.value);
      history.pushState({}, "", b64);
      if (copyLink) copy(location.href.replace("/#", "#"));
    }
    function enc(str = main.value) {
      let bytes = new TextEncoder("utf-8").encode(str);
      return arrToB64(deflate(bytes));
    }
    function dec(str) {
      try {
        return new TextDecoder("utf-8").decode(inflate(b64ToArr(str)));
      } catch (e) {
        return "failed to decode - full link not copied?";
      }
    }

    function arrToB64(arr) {
      var bytestr = "";
      arr.forEach(c => bytestr+= String.fromCharCode(c));
      return btoa(bytestr).replace(/\+/g, "@").replace(/=+/, "");
    }
    function b64ToArr(str) {
      return new Uint8Array([...atob(decodeURIComponent(str).replace(/@/g, "+"))].map(c=>c.charCodeAt()))
    }
    
    function deflate(arr) {
      return pako.deflateRaw(arr, {"level": 9});
    }
    function inflate(arr) {
      return pako.inflateRaw(arr);
    }
    
    function html(str) {
      return new Option(str).innerHTML.replace(/\n/g,'<br>');
    }
    function copy(str) {
      navigator.clipboard.writeText(str);
    }
    function load() {
      main.value = "";
      let hash = decodeURIComponent(location.hash.slice(1));
      let v = hash[0];
      hash = hash.slice(1); // remove version
      if (hash) {
        let parts = hash.split("#");
        main.value = parts[0]? dec(parts[0]) : "";
        md(main.value? 2 : 0);
      } else md(0);
    }
    load();
    window.onhashchange=load;
    
    document.addEventListener("keydown", e => {
      let code = e.code;
      let ctrl = e.ctrlKey;
      let alt = e.altKey;
      if (alt) {
        if (code == 'KeyS') { saveB.click(); e.preventDefault(); viewB.click(); }
        if (code == 'KeyE') { editB.click(); e.preventDefault(); main.focus(); }
        if (code == 'KeyV') { viewB.click(); e.preventDefault(); }
        if (code == 'KeyC') { copyB.click(); e.preventDefault(); }
      }
      if (ctrl && code == 'KeyR') save(false);
      if (code == 'F5') save(false);
    });
  </script>

The TeX Bin is licensed under the [MIT License][mit].

<details>
<summary>License</summary>
Copyright 2020 Rodrigo Girão Serrão.

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
</details>

Based on [dzaima/paste][dzaima-paste].

[mit]: https://opensource.org/licenses/MIT
[dzaima-paste]: https://github.com/dzaima/paste

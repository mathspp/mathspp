<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.18/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.18/theme/material-darker.min.css">
<link rel="stylesheet" href="/python/python-playground.css">

# Python Playground

Write Python, run it entirely in your browser with Pyodide, and share it with a permalink.

<div class="python-playground" id="python-playground">
    <div class="python-playground__toolbar" role="toolbar" aria-label="Python playground controls">
        <label for="python-version">Python version</label>
        <select id="python-version" aria-describedby="runtime-status">
            <option value="0.28.3" selected>Python 3.13 (Pyodide 0.28.3)</option>
            <option value="0.27.7">Python 3.12 (Pyodide 0.27.7)</option>
            <option value="0.26.4">Python 3.12 (Pyodide 0.26.4)</option>
        </select>
        <button type="button" id="run-python" class="btn btn-primary">Run code</button>
        <button type="button" id="copy-permalink" class="btn">Copy permalink</button>
        <span id="runtime-status" class="python-playground__status" role="status" aria-live="polite">Runtime not loaded</span>
    </div>

    <label class="python-playground__label" for="python-code">Python code</label>
    <textarea id="python-code" name="python-code" spellcheck="false">print("Hello from Python!")</textarea>

    <div class="python-playground__output-heading">
        <strong>Output</strong>
        <button type="button" id="clear-output" class="btn btn-sm">Clear</button>
    </div>
    <pre id="python-output" class="python-playground__output" aria-live="polite" aria-label="Python output"></pre>
</div>

<noscript>This playground needs JavaScript to load CodeMirror and run Python in your browser.</noscript>

<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.18/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.18/mode/python/python.min.js"></script>
<script src="/python/python-playground.js"></script>

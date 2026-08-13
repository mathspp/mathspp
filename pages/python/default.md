<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.18/codemirror.min.css">
<link rel="stylesheet" href="/python/codemirror-flexoki.css?v=1">
<link rel="stylesheet" href="/python/python-playground.css">

# Python Playground

Write Python 3.14, run it entirely in your browser with Pyodide, and share it with a permalink.

<div class="python-playground" id="python-playground">
    <div class="python-playground__toolbar" role="toolbar" aria-label="Python playground controls">
        <button type="button" id="run-python" class="btn tooltip tooltip-bottom" data-tooltip="Run code (Ctrl/Cmd + Enter)">Run code</button>
        <button type="button" id="stop-python" class="btn" disabled>Stop</button>
        <button type="button" id="copy-code" class="btn">Copy code</button>
        <button type="button" id="copy-permalink" class="btn">Copy permalink</button>
        <span id="runtime-status" class="python-playground__status" role="status" aria-live="polite">Python 3.14 — ready to run</span>
    </div>

    <label class="python-playground__label" for="python-code">Python code</label>
    <textarea id="python-code" name="python-code" spellcheck="false">from functools import cache

@cache
def fibonacci(n: int) -> int:
    if n &lt;= 1:
        return n
    return fibonacci(n - 1) + fibonacci(n - 2)

print(fibonacci(10))</textarea>

    <div class="python-playground__output-heading">
        <strong>Output</strong>
        <button type="button" id="clear-output" class="btn btn-sm">Clear</button>
    </div>
    <pre id="python-output" class="python-playground__output" aria-live="polite" aria-label="Python output"></pre>
</div>

<noscript>This playground needs JavaScript to load CodeMirror and run Python in your browser.</noscript>

<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.18/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.18/mode/python/python.min.js"></script>
<script src="/python/python-playground.js?v=5"></script>

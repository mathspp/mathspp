(function () {
    "use strict";

    const codeTextarea = document.getElementById("python-code");
    if (!codeTextarea || typeof CodeMirror === "undefined") {
        return;
    }

    const versionSelect = document.getElementById("python-version");
    const runButton = document.getElementById("run-python");
    const permalinkButton = document.getElementById("copy-permalink");
    const clearButton = document.getElementById("clear-output");
    const output = document.getElementById("python-output");
    const status = document.getElementById("runtime-status");
    const runtimes = new Map();

    const editor = CodeMirror.fromTextArea(codeTextarea, {
        indentUnit: 4,
        lineNumbers: true,
        mode: "python",
        theme: "material-darker",
        viewportMargin: Infinity,
    });

    function encodeCode(code) {
        const bytes = new TextEncoder().encode(code);
        let binary = "";
        bytes.forEach((byte) => { binary += String.fromCharCode(byte); });
        return btoa(binary).replace(/\+/g, "-").replace(/\//g, "_").replace(/=+$/g, "");
    }

    function decodeCode(value) {
        const base64 = value.replace(/-/g, "+").replace(/_/g, "/");
        const padded = base64.padEnd(Math.ceil(base64.length / 4) * 4, "=");
        const binary = atob(padded);
        return new TextDecoder().decode(Uint8Array.from(binary, (character) => character.charCodeAt(0)));
    }

    function loadScript(source) {
        return new Promise((resolve, reject) => {
            const script = document.createElement("script");
            script.src = source;
            script.onload = resolve;
            script.onerror = () => reject(new Error("Could not download the selected Python runtime."));
            document.head.appendChild(script);
        });
    }

    async function getRuntime(version) {
        if (!runtimes.has(version)) {
            const runtimePromise = (async () => {
                const indexURL = `https://cdn.jsdelivr.net/pyodide/v${version}/full/`;
                status.textContent = `Loading Pyodide ${version}…`;
                await loadScript(`${indexURL}pyodide.js`);
                return window.loadPyodide({ indexURL });
            })();
            runtimes.set(version, runtimePromise);
        }
        return runtimes.get(version);
    }

    async function runCode() {
        runButton.disabled = true;
        output.classList.remove("is-error");
        output.textContent = "";

        try {
            const version = versionSelect.value;
            const pyodide = await getRuntime(version);
            pyodide.setStdout({ batched: (text) => { output.textContent += `${text}\n`; } });
            pyodide.setStderr({ batched: (text) => { output.textContent += `${text}\n`; } });
            status.textContent = `Running Python with Pyodide ${version}…`;
            const result = await pyodide.runPythonAsync(editor.getValue());
            if (result !== undefined) {
                output.textContent += `${result.toString()}\n`;
                if (result.destroy) result.destroy();
            }
            status.textContent = `Ready — Pyodide ${version}`;
        } catch (error) {
            output.classList.add("is-error");
            output.textContent += `${error.message || error}\n`;
            status.textContent = "Run failed";
        } finally {
            runButton.disabled = false;
        }
    }

    async function copyPermalink() {
        const url = new URL(window.location.href);
        url.searchParams.set("code", encodeCode(editor.getValue()));
        window.history.replaceState({}, "", url);
        try {
            await navigator.clipboard.writeText(url.toString());
            permalinkButton.textContent = "Copied!";
        } catch (_) {
            window.prompt("Copy this permalink:", url.toString());
        }
        window.setTimeout(() => { permalinkButton.textContent = "Copy permalink"; }, 2000);
    }

    const sharedCode = new URLSearchParams(window.location.search).get("code");
    if (sharedCode !== null) {
        try {
            editor.setValue(decodeCode(sharedCode));
        } catch (_) {
            status.textContent = "The permalink contains invalid code.";
        }
    }

    runButton.addEventListener("click", runCode);
    permalinkButton.addEventListener("click", copyPermalink);
    clearButton.addEventListener("click", () => {
        output.textContent = "";
        output.classList.remove("is-error");
    });
    editor.setOption("extraKeys", { "Ctrl-Enter": runCode, "Cmd-Enter": runCode });
}());

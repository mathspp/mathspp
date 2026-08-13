(function () {
    "use strict";

    const codeTextarea = document.getElementById("python-code");
    if (!codeTextarea || typeof CodeMirror === "undefined") {
        return;
    }

    const runButton = document.getElementById("run-python");
    const stopButton = document.getElementById("stop-python");
    const copyCodeButton = document.getElementById("copy-code");
    const permalinkButton = document.getElementById("copy-permalink");
    const clearButton = document.getElementById("clear-output");
    const output = document.getElementById("python-output");
    const status = document.getElementById("runtime-status");
    const maxPermalinkLength = 32000;
    let activeRun = 0;
    let worker;

    const editor = CodeMirror.fromTextArea(codeTextarea, {
        indentUnit: 4,
        lineNumbers: true,
        mode: "python",
        theme: "flexoki",
        viewportMargin: Infinity,
    });

    function encodeBytes(bytes) {
        let binary = "";
        bytes.forEach((byte) => { binary += String.fromCharCode(byte); });
        return btoa(binary).replace(/\+/g, "-").replace(/\//g, "_").replace(/=+$/g, "");
    }

    function decodeBytes(value) {
        const base64 = value.replace(/-/g, "+").replace(/_/g, "/");
        const padded = base64.padEnd(Math.ceil(base64.length / 4) * 4, "=");
        const binary = atob(padded);
        return Uint8Array.from(binary, (character) => character.charCodeAt(0));
    }

    function encodeCode(code) {
        return encodeBytes(new TextEncoder().encode(code));
    }

    function decodeCode(value) {
        const encodedCode = value.startsWith("raw.") ? value.slice(4) : value;
        return new TextDecoder().decode(decodeBytes(encodedCode));
    }

    function showPermalinkTooltip(message) {
        permalinkButton.classList.add("tooltip", "tooltip-bottom");
        permalinkButton.dataset.tooltip = message;
        window.setTimeout(() => {
            permalinkButton.classList.remove("tooltip", "tooltip-bottom");
            delete permalinkButton.dataset.tooltip;
        }, 3000);
    }

    function finishRun() {
        runButton.disabled = false;
        stopButton.disabled = true;
    }

    function discardWorker() {
        if (worker) {
            worker.terminate();
            worker = undefined;
        }
    }

    function getWorker() {
        if (!worker) {
            const pythonWorker = new Worker("/python/python-worker.js?v=2", { type: "module" });
            worker = pythonWorker;
            pythonWorker.addEventListener("message", ({ data }) => {
                if (data.run !== activeRun) return;
                if (data.type === "output") {
                    output.textContent += `${data.text}\n`;
                } else if (data.type === "status") {
                    status.textContent = data.text;
                } else if (data.type === "error") {
                    output.classList.add("is-error");
                    output.textContent += `${data.text}\n`;
                    status.textContent = "Run failed";
                    if (data.resetWorker) {
                        discardWorker();
                        finishRun();
                    }
                } else if (data.type === "done") {
                    finishRun();
                }
            });
            pythonWorker.addEventListener("error", (event) => {
                if (worker !== pythonWorker) return;
                event.preventDefault();
                output.classList.add("is-error");
                output.textContent += "The Python worker failed. Try running the code again.\n";
                status.textContent = "Run failed";
                discardWorker();
                finishRun();
            });
        }
        return worker;
    }

    function runCode() {
        if (runButton.disabled) return;

        activeRun += 1;
        runButton.disabled = true;
        stopButton.disabled = false;
        output.classList.remove("is-error");
        output.textContent = "";
        status.textContent = "Loading Python 3.14…";
        try {
            getWorker().postMessage({ code: editor.getValue(), run: activeRun, type: "run" });
        } catch (error) {
            output.classList.add("is-error");
            output.textContent = `${error.message || error}\n`;
            status.textContent = "Could not start the Python worker";
            discardWorker();
            finishRun();
        }
    }

    function stopCode() {
        if (stopButton.disabled) return;

        activeRun += 1;
        discardWorker();
        output.textContent += "Execution stopped.\n";
        status.textContent = "Stopped — Python 3.14";
        finishRun();
    }

    async function copyCode() {
        try {
            await navigator.clipboard.writeText(editor.getValue());
            copyCodeButton.textContent = "Copied!";
        } catch (_) {
            copyCodeButton.textContent = "Copy failed";
            status.textContent = "Could not copy the code.";
        }
        window.setTimeout(() => { copyCodeButton.textContent = "Copy code"; }, 2000);
    }

    async function copyPermalink() {
        const url = new URL(window.location.href);
        try {
            const fragment = new URLSearchParams(url.hash.slice(1));
            fragment.set("code", encodeCode(editor.getValue()));
            url.searchParams.delete("code");
            url.hash = fragment.toString();
            if (url.toString().length > maxPermalinkLength) {
                showPermalinkTooltip("This code is too long for a permalink.");
                return;
            }
            window.history.replaceState({}, "", url);
            await navigator.clipboard.writeText(url.toString());
            permalinkButton.textContent = "Copied!";
        } catch (_) {
            permalinkButton.textContent = "Copy failed";
            status.textContent = "Could not create or copy the permalink.";
        }
        window.setTimeout(() => { permalinkButton.textContent = "Copy permalink"; }, 2000);
    }

    const fragmentCode = new URLSearchParams(window.location.hash.slice(1)).get("code");
    const sharedCode = fragmentCode || new URLSearchParams(window.location.search).get("code");
    if (sharedCode !== null) {
        try {
            editor.setValue(decodeCode(sharedCode));
        } catch (_) {
            status.textContent = "The permalink contains invalid code.";
        }
    }

    runButton.addEventListener("click", runCode);
    stopButton.addEventListener("click", stopCode);
    copyCodeButton.addEventListener("click", copyCode);
    permalinkButton.addEventListener("click", copyPermalink);
    clearButton.addEventListener("click", () => {
        output.textContent = "";
        output.classList.remove("is-error");
    });
    editor.setOption("extraKeys", { "Ctrl-Enter": runCode, "Cmd-Enter": runCode });
}());

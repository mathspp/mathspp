"use strict";

const pyodideIndexURL = "https://cdn.jsdelivr.net/pyodide/v314.0.4/full/";
let pyodideRuntime;
let pyodideScriptLoaded = false;
let runtimePromise;

async function initializeRuntime() {
    if (!pyodideScriptLoaded) {
        importScripts("https://cdn.jsdelivr.net/pyodide/v314.0.4/full/pyodide.js");
        pyodideScriptLoaded = true;
    }
    return self.loadPyodide({ indexURL: pyodideIndexURL });
}

function getRuntime() {
    if (pyodideRuntime) return Promise.resolve(pyodideRuntime);

    if (!runtimePromise) {
        runtimePromise = initializeRuntime()
            .then((runtime) => {
                pyodideRuntime = runtime;
                return runtime;
            })
            .finally(() => { runtimePromise = undefined; });
    }
    return runtimePromise;
}

self.addEventListener("message", async ({ data }) => {
    if (data.type !== "run") return;

    const send = (type, details = {}) => {
        self.postMessage({ ...details, run: data.run, type });
    };

    try {
        send("status", { text: "Loading Python 3.14…" });
        const pyodide = await getRuntime();
        pyodide.setStdout({ batched: (text) => { send("output", { text }); } });
        pyodide.setStderr({ batched: (text) => { send("output", { text }); } });
        send("status", { text: "Running Python 3.14…" });
        const result = await pyodide.runPythonAsync(data.code);
        if (result !== undefined) {
            send("output", { text: result.toString() });
            if (result.destroy) result.destroy();
        }
        send("status", { text: "Ready — Python 3.14" });
    } catch (error) {
        send("error", { text: error.message || String(error) });
    } finally {
        send("done");
    }
});

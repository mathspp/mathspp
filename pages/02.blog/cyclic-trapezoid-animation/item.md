See an animation of a trapezoid innscribed in a circle, built with some maths and the help of an LLM.

===


## The animation

My brother asked for my help to build an animation of a trapezoid inscribed in a circle that kept changing his shape.
With [a bit of maths](/blog/til/cyclic-quadrilateral) and the help of ChatGPT for the UI, you can see the animation below.
Under the animation you can find a control panel that allows you to tweak some animation parameters, and under that you can find a brief explanation of how the animation works.

<style>
  .cyclic-trapezoid-embed {
    --ctp-panel-bg: color-mix(in srgb, var(--bg, #0b1020) 88%, transparent);
    --ctp-panel-border: color-mix(in srgb, var(--tx, #e5e7eb) 18%, transparent);
    --ctp-text: var(--tx, #e5e7eb);
    --ctp-muted: color-mix(in srgb, var(--tx, #e5e7eb) 65%, transparent);
    --ctp-input-bg: color-mix(in srgb, var(--bg, #0b1020) 92%, black 8%);

    width: 100%;
    box-sizing: border-box;
  }

  .cyclic-trapezoid-embed *,
  .cyclic-trapezoid-embed *::before,
  .cyclic-trapezoid-embed *::after {
    box-sizing: border-box;
  }

  .cyclic-trapezoid-embed .ctp-canvas-wrap {
    width: 100%;
  }

  .cyclic-trapezoid-embed canvas {
    display: block;
    width: 100%;
    height: min(70vh, 800px);
    min-height: 360px;
    background: var(--bg, #0b1020);
    border-radius: 16px;
  }

  .cyclic-trapezoid-embed .ctp-ui {
    margin-top: 16px;
    width: 100%;
    border-radius: 14px;
    background: var(--ctp-panel-bg);
    color: var(--ctp-text);
    border: 1px solid var(--ctp-panel-border);
    backdrop-filter: blur(8px);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.18);
    font-family: system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
    overflow: hidden;
  }

  .cyclic-trapezoid-embed .ctp-summary {
    list-style: none;
    cursor: pointer;
    padding: 14px;
    font-size: 18px;
    font-weight: 700;
    user-select: none;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  .cyclic-trapezoid-embed .ctp-summary::-webkit-details-marker {
    display: none;
  }

  .cyclic-trapezoid-embed .ctp-ui-content {
    padding: 0 14px 14px;
  }

  .cyclic-trapezoid-embed fieldset {
    margin: 0 0 12px;
    padding: 10px 10px 6px;
    border-radius: 10px;
    border: 1px solid var(--ctp-panel-border);
    min-width: 0;
  }

  .cyclic-trapezoid-embed legend {
    padding: 0 6px;
    color: var(--ctp-text);
    font-weight: 600;
    font-size: 14px;
  }

  .cyclic-trapezoid-embed .row {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 10px;
    align-items: center;
    margin-bottom: 8px;
  }

  .cyclic-trapezoid-embed .row label {
    font-size: 13px;
    color: var(--ctp-text);
  }

  .cyclic-trapezoid-embed .row .value {
    color: var(--ctp-muted);
    font-size: 12px;
    min-width: 64px;
    text-align: right;
    font-variant-numeric: tabular-nums;
  }

  .cyclic-trapezoid-embed .control {
    display: grid;
    grid-template-columns: 1fr;
    gap: 4px;
    margin-bottom: 10px;
  }

  .cyclic-trapezoid-embed .control label {
    font-size: 13px;
    color: var(--ctp-text);
  }

  .cyclic-trapezoid-embed input[type="range"],
  .cyclic-trapezoid-embed input[type="number"],
  .cyclic-trapezoid-embed input[type="color"] {
    width: 100%;
  }

  .cyclic-trapezoid-embed input[type="number"] {
    padding: 6px 8px;
    border-radius: 8px;
    border: 1px solid var(--ctp-panel-border);
    background: var(--ctp-input-bg);
    color: var(--ctp-text);
  }

  .cyclic-trapezoid-embed input[type="color"] {
    height: 36px;
    padding: 0;
    border: none;
    background: transparent;
    cursor: pointer;
  }

  .cyclic-trapezoid-embed .buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
  }

  .cyclic-trapezoid-embed button {
    appearance: none;
    border: 1px solid var(--ctp-panel-border);
    background: color-mix(in srgb, var(--bg, #0b1020) 80%, var(--tx, #e5e7eb) 8%);
    color: var(--ctp-text);
    border-radius: 10px;
    padding: 9px 12px;
    cursor: pointer;
    font-weight: 600;
  }

  .cyclic-trapezoid-embed button:hover {
    background: color-mix(in srgb, var(--bg, #0b1020) 72%, var(--tx, #e5e7eb) 14%);
  }

  .cyclic-trapezoid-embed .hint {
    font-size: 12px;
    color: var(--ctp-muted);
    line-height: 1.35;
    margin-top: 6px;
  }

  .cyclic-trapezoid-embed code {
    font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
  }
</style>

<div class="cyclic-trapezoid-embed" id="cyclic-trapezoid-embed">
  <div class="ctp-canvas-wrap">
    <canvas id="ctp-canvas"></canvas>
  </div>

  <details class="ctp-ui">
    <summary class="ctp-summary">Cyclic trapezoid controls</summary>

    <div class="ctp-ui-content">
      <fieldset>
        <legend>Colour</legend>

        <div class="control">
          <label for="ctp-bgColor">Background colour</label>
          <input id="ctp-bgColor" type="color" value="#0b1020" />
        </div>

        <div class="control">
          <label for="ctp-circleColor">Circle colour</label>
          <input id="ctp-circleColor" type="color" value="#94a3b8" />
        </div>

        <div class="control">
          <label for="ctp-pointColor">Points colour</label>
          <input id="ctp-pointColor" type="color" value="#f8fafc" />
        </div>

        <div class="control">
          <label for="ctp-parallelColor">Parallel sides colour</label>
          <input id="ctp-parallelColor" type="color" value="#fbbf24" />
        </div>

        <div class="control">
          <label for="ctp-nonParallelColor">Non-parallel sides colour</label>
          <input id="ctp-nonParallelColor" type="color" value="#60a5fa" />
        </div>
      </fieldset>

      <fieldset>
        <legend>Point A</legend>

        <div class="row">
          <label for="ctp-aPeriod">Period</label>
          <div class="value" id="ctp-aPeriodValue"></div>
        </div>
        <input id="ctp-aPeriod" type="range" min="2" max="60" step="0.1" value="11" />
      </fieldset>

      <fieldset>
        <legend>Point B</legend>

        <div class="row">
          <label for="ctp-bMax">Max angle</label>
          <div class="value" id="ctp-bMaxValue"></div>
        </div>
        <input id="ctp-bMax" type="range" min="15" max="180" step="1" value="90" />

        <div class="row">
          <label for="ctp-bPeriod">Period</label>
          <div class="value" id="ctp-bPeriodValue"></div>
        </div>
        <input id="ctp-bPeriod" type="range" min="2" max="60" step="0.1" value="7" />

        <div class="row">
          <label for="ctp-bPhase">Phase</label>
          <div class="value" id="ctp-bPhaseValue"></div>
        </div>
        <input id="ctp-bPhase" type="range" min="0" max="360" step="1" value="0" />
      </fieldset>

      <fieldset>
        <legend>Point D</legend>

        <div class="row">
          <label for="ctp-dMax">Max angle</label>
          <div class="value" id="ctp-dMaxValue"></div>
        </div>
        <input id="ctp-dMax" type="range" min="15" max="180" step="1" value="90" />

        <div class="row">
          <label for="ctp-dPeriod">Period</label>
          <div class="value" id="ctp-dPeriodValue"></div>
        </div>
        <input id="ctp-dPeriod" type="range" min="2" max="60" step="0.1" value="5" />

        <div class="row">
          <label for="ctp-dPhase">Phase</label>
          <div class="value" id="ctp-dPhaseValue"></div>
        </div>
        <input id="ctp-dPhase" type="range" min="0" max="360" step="1" value="60" />
      </fieldset>

      <fieldset>
        <legend>Global</legend>

        <div class="row">
          <label for="ctp-radius">Circle radius</label>
          <div class="value" id="ctp-radiusValue"></div>
        </div>
        <input id="ctp-radius" type="range" min="0.1" max="0.48" step="0.01" value="0.35" />

        <div class="row">
          <label for="ctp-speed">Global animation speed</label>
          <div class="value" id="ctp-speedValue"></div>
        </div>
        <input id="ctp-speed" type="range" min="0" max="4" step="0.01" value="1" />
      </fieldset>

      <div class="buttons">
        <button id="ctp-resetTime" type="button">Reset animation time</button>
      </div>

      <div class="hint">
        Point C is computed from the cyclic trapezoid rule:
        <code>angleC = alpha + angleB + angleD</code>.
      </div>
    </div>
  </details>
</div>

<script>
(() => {
  const root = document.getElementById("cyclic-trapezoid-embed");
  const canvas = document.getElementById("ctp-canvas");
  const ctx = canvas.getContext("2d");
  const TAU = 2 * Math.PI;

  const $ = (id) => document.getElementById(id);

  const controls = {
    bgColor: $("ctp-bgColor"),
    circleColor: $("ctp-circleColor"),
    pointColor: $("ctp-pointColor"),
    parallelColor: $("ctp-parallelColor"),
    nonParallelColor: $("ctp-nonParallelColor"),

    aPeriod: $("ctp-aPeriod"),
    bMax: $("ctp-bMax"),
    bPeriod: $("ctp-bPeriod"),
    bPhase: $("ctp-bPhase"),
    dMax: $("ctp-dMax"),
    dPeriod: $("ctp-dPeriod"),
    dPhase: $("ctp-dPhase"),
    radius: $("ctp-radius"),
    speed: $("ctp-speed"),

    aPeriodValue: $("ctp-aPeriodValue"),
    bMaxValue: $("ctp-bMaxValue"),
    bPeriodValue: $("ctp-bPeriodValue"),
    bPhaseValue: $("ctp-bPhaseValue"),
    dMaxValue: $("ctp-dMaxValue"),
    dPeriodValue: $("ctp-dPeriodValue"),
    dPhaseValue: $("ctp-dPhaseValue"),
    radiusValue: $("ctp-radiusValue"),
    speedValue: $("ctp-speedValue"),

    resetTime: $("ctp-resetTime"),
  };

  function clamp255(x) {
    return Math.max(0, Math.min(255, Math.round(x)));
  }

  function rgbToHex(r, g, b) {
    return "#" + [r, g, b].map((n) => clamp255(n).toString(16).padStart(2, "0")).join("");
  }

  function cssColorToHex(value, fallback) {
    if (!value) return fallback;

    const probe = document.createElement("span");
    probe.style.color = value;
    document.body.appendChild(probe);

    const computed = getComputedStyle(probe).color;
    probe.remove();

    const match = computed.match(/rgba?\(([^)]+)\)/);
    if (!match) return fallback;

    const parts = match[1].split(",").map((part) => Number(part.trim()));
    return rgbToHex(parts[0], parts[1], parts[2]);
  }

  function setDefaultColoursFromCSSVars() {
    const styles = getComputedStyle(root);

    controls.pointColor.value = cssColorToHex(styles.getPropertyValue("--tx").trim(), "#f8fafc");
    controls.circleColor.value = cssColorToHex(styles.getPropertyValue("--ui").trim(), "#94a3b8");
    controls.parallelColor.value = cssColorToHex(styles.getPropertyValue("--accent").trim(), "#fbbf24");
    controls.nonParallelColor.value = cssColorToHex(styles.getPropertyValue("--accent-2").trim(), "#60a5fa");
    controls.bgColor.value = cssColorToHex(styles.getPropertyValue("--bg").trim(), "#0b1020");
  }

  function resize() {
    const rect = canvas.getBoundingClientRect();
    const dpr = window.devicePixelRatio || 1;
    canvas.width = Math.floor(rect.width * dpr);
    canvas.height = Math.floor(rect.height * dpr);
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
  }

  function pointOnCircle(cx, cy, r, angle) {
    return {
      x: cx + r * Math.cos(angle),
      y: cy + r * Math.sin(angle),
    };
  }

  function drawPoint(p, label, color) {
    ctx.beginPath();
    ctx.arc(p.x, p.y, 6, 0, TAU);
    ctx.fillStyle = color;
    ctx.fill();

    ctx.font = "16px system-ui, sans-serif";
    ctx.fillStyle = color;
    ctx.fillText(label, p.x + 10, p.y - 10);
  }

  function drawSegment(p, q, color, width = 3) {
    ctx.beginPath();
    ctx.moveTo(p.x, p.y);
    ctx.lineTo(q.x, q.y);
    ctx.strokeStyle = color;
    ctx.lineWidth = width;
    ctx.stroke();
  }

  function hexToRgba(hex, alpha) {
    const clean = hex.replace("#", "");
    const value = parseInt(clean, 16);
    const r = (value >> 16) & 255;
    const g = (value >> 8) & 255;
    const b = value & 255;
    return `rgba(${r}, ${g}, ${b}, ${alpha})`;
  }

  function degToRad(deg) {
    return deg * Math.PI / 180;
  }

  function radToPiText(rad) {
    return `${(rad / Math.PI).toFixed(3)}\u03C0`;
  }

  function secondsText(x) {
    return `${Number(x).toFixed(1)}s`;
  }

  function updateValueLabels() {
    controls.aPeriodValue.textContent = secondsText(controls.aPeriod.value);
    controls.bMaxValue.textContent = `${controls.bMax.value}°`;
    controls.bPeriodValue.textContent = secondsText(controls.bPeriod.value);
    controls.bPhaseValue.textContent = `${controls.bPhase.value}°`;
    controls.dMaxValue.textContent = `${controls.dMax.value}°`;
    controls.dPeriodValue.textContent = secondsText(controls.dPeriod.value);
    controls.dPhaseValue.textContent = `${controls.dPhase.value}°`;
    controls.radiusValue.textContent = `${Math.round(Number(controls.radius.value) * 100)}% min(canvas)`;
    controls.speedValue.textContent = `${Number(controls.speed.value).toFixed(2)}×`;
  }

  for (const element of Object.values(controls)) {
    if (
      element instanceof HTMLInputElement &&
      (element.type === "range" || element.type === "color" || element.type === "number")
    ) {
      element.addEventListener("input", updateValueLabels);
    }
  }

  let simTime = 0;
  let lastNow = performance.now();

  controls.resetTime.addEventListener("click", () => {
    simTime = 0;
    lastNow = performance.now();
  });

  function angleA(t) {
    const period = Number(controls.aPeriod.value);
    return TAU * (t / period);
  }

  function angleB(t) {
    const min = Math.PI / 12;
    const max = degToRad(Number(controls.bMax.value));
    const mid = (min + max) / 2;
    const amp = (max - min) / 2;
    const period = Number(controls.bPeriod.value);
    const phase = degToRad(Number(controls.bPhase.value));
    return mid + amp * Math.cos(TAU * t / period + phase);
  }

  function angleD(t) {
    const min = Math.PI / 12;
    const max = degToRad(Number(controls.dMax.value));
    const mid = (min + max) / 2;
    const amp = (max - min) / 2;
    const period = Number(controls.dPeriod.value);
    const phase = degToRad(Number(controls.dPhase.value));
    return mid + amp * Math.cos(TAU * t / period + phase);
  }

  function drawInfo(alpha, beta, delta, width, height) {
    const x = 20;
    const y = height - 92;
    ctx.font = "14px ui-monospace, SFMono-Regular, Menlo, Consolas, monospace";
    ctx.fillStyle = hexToRgba(controls.pointColor.value, 0.9);
    ctx.fillText(`\u03B1 = ${radToPiText(((alpha % TAU) + TAU) % TAU)}`, x, y);
    ctx.fillText(`angleB = ${radToPiText(beta)}`, x, y + 22);
    ctx.fillText(`angleD = ${radToPiText(delta)}`, x, y + 44);
    ctx.fillText(`angleC = ${radToPiText(beta + delta)}`, x, y + 66);
  }

  function draw(now) {
    const dt = Math.min(0.05, (now - lastNow) / 1000);
    lastNow = now;
    simTime += dt * Number(controls.speed.value);

    const width = canvas.clientWidth;
    const height = canvas.clientHeight;
    const cx = width / 2;
    const cy = height / 2;
    const r = Math.min(width, height) * Number(controls.radius.value);

    const alpha = angleA(simTime);
    const beta = angleB(simTime);
    const delta = angleD(simTime);

    const angA = alpha;
    const angB = alpha + beta;
    const angC = alpha + beta + delta;
    const angD = alpha - delta;

    const A = pointOnCircle(cx, cy, r, angA);
    const B = pointOnCircle(cx, cy, r, angB);
    const C = pointOnCircle(cx, cy, r, angC);
    const D = pointOnCircle(cx, cy, r, angD);

    const bgColor = controls.bgColor.value;
    const circleColor = controls.circleColor.value;
    const pointColor = controls.pointColor.value;
    const parallelColor = controls.parallelColor.value;
    const nonParallelColor = controls.nonParallelColor.value;

    ctx.clearRect(0, 0, width, height);
    ctx.fillStyle = bgColor;
    ctx.fillRect(0, 0, width, height);

    ctx.beginPath();
    ctx.arc(cx, cy, r, 0, TAU);
    ctx.strokeStyle = hexToRgba(circleColor, 0.75);
    ctx.lineWidth = 2;
    ctx.stroke();

    ctx.beginPath();
    ctx.moveTo(A.x, A.y);
    ctx.lineTo(B.x, B.y);
    ctx.lineTo(C.x, C.y);
    ctx.lineTo(D.x, D.y);
    ctx.closePath();
    ctx.fillStyle = hexToRgba(nonParallelColor, 0.12);
    ctx.fill();

    drawSegment(A, B, parallelColor, 4);
    drawSegment(B, C, nonParallelColor, 3);
    drawSegment(C, D, parallelColor, 4);
    drawSegment(D, A, nonParallelColor, 3);

    drawSegment({ x: cx, y: cy }, A, hexToRgba(pointColor, 0.18), 1.5);
    drawSegment({ x: cx, y: cy }, B, hexToRgba(pointColor, 0.08), 1);
    drawSegment({ x: cx, y: cy }, D, hexToRgba(pointColor, 0.08), 1);

    drawPoint(A, "A", pointColor);
    drawPoint(B, "B", pointColor);
    drawPoint(C, "C", pointColor);
    drawPoint(D, "D", pointColor);

    drawInfo(alpha, beta, delta, width, height);

    requestAnimationFrame(draw);
  }

  setDefaultColoursFromCSSVars();
  updateValueLabels();
  resize();

  const resizeObserver = new ResizeObserver(resize);
  resizeObserver.observe(canvas);
  window.addEventListener("resize", resize);

  requestAnimationFrame((now) => {
    lastNow = now;
    draw(now);
  });
})();
</script>


##  How it works

The first thing I determined was that one of the points might as well move at a constant speed around the circle, to induce some movement, but without being too crazy.
Can you look at the animation and figure out which of the four points is the one that moves around at a constant speed?

It's point $A$.

Since the trapezoid $ABCD$ was supposed to be inscribed in a circle of centre $O$, I thought it was probably a good idea to define the position of point $A$ in terms of the angle $\alpha$ that the radius $OA$ makes with the radius that goes through $P$, if $P$ is the point $(1, 0)$.

After a bit of back and forth, and some doodling, I ended up defining the positions of $B$ and $D$ in terms of the angles that $OB$ and $OD$ make with $OA$.
This means that animating the points $B$ and $D$ is a matter of animating those angles.
Animating the angles is better than animating the positions of the points because it's easier to parametrise a single angle than it is to parametrise two coordinates.

The angle that defines points $B$ and $D$ is given by a function of the form

$$
\frac{\theta_{m} + \theta_{M}}{2} + \left(\theta_{M} - \theta_{m}\right) \cos\left(\frac{2\pi t}{\rho} + \phi\right)
$$

 - $t$ is the time elapsed during the animation;
 - $\theta_{m}$ and $\theta_{M}$ are the minimum and maximum attainable angles, respectively;
 - $\rho$ is the period of the animation of the angles; and
 - $\phi$ is the phase of the animation.

If points $B$ and $D$ have the exact same values for all four parameters, the animation doesn't look any fun.
To create a sense of pleasing chaos, you want the periods and the phases for points $B$ and $D$ to be different, even if the minimum and maximum angles are the same.

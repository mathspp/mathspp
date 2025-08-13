Explore the counterintuitive world of probabilities you get into when you flip a coin over and over again, with interactive animations to make it easier to understand how everything works.

===

<script src="https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.min.js"></script>

<style>
    canvas {
        display: block;
        margin: auto;
    }

    button {
        margin-top: 10px;
        padding: 0.6em 1.2em;
        font-size: 1rem;
        cursor: pointer;
    }

    .heads::after,
    .tails::after {
        content: "";
        display: inline-block;
        width: 1.2em;
        height: 1.5em;
        background-size: contain;
        background-repeat: no-repeat;
        margin-left: 0.25em;
        vertical-align: middle;
    }
    .heads::before {
        content: "Heads";
    }
    .heads::after {
        background-image: url('/blog/_test/heads.svg');
    }
    .tails::before {
        content: "Tails";
    }
    .tails::after {
        background-image: url('/blog/_test/tails.svg');
    }
</style>

<span class="icon-after">Hello</span>

<script>
    class CoinArea {
        constructor(width, height, angle = 45, z = 10) {
            this.scene = new THREE.Scene();
            this.camera = new THREE.PerspectiveCamera(angle, width / height, 0.1, 1000);
            this.camera.position.z = z;

            this.renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
            this.renderer.setSize(width, height);

            this.ambient = new THREE.AmbientLight(0xffffff, 2);
            this.scene.add(this.ambient);

            this.coins = [];

            this.isAnimating = false;
            this.flipResults = null;
            this.animationStart = null;
            this.callback = null;
        }

        addToContainer(container) {
            container.appendChild(this.renderer.domElement);
        }

        addCoin(coin) {
            this.coins.push(coin);
            this.scene.add(coin.coin);
        }

        flipCoins(duration = 1000, callback = (results) => {}) {
            if (this.isAnimating) return;
            this.callback = callback;
            this.duration = duration;

            this.isAnimating = true;
            this.animationStart = performance.now();
            // Collect the results immediately but start animating afterwards.
            this.flipResults = this.coins.map((coin) => coin.flip());
            requestAnimationFrame((time) => {
                this.animateFlips(time);
            });
        }

        animateFlips(time) {
            if (!this.isAnimating) return;

            const progress = Math.min((time - this.animationStart) / this.duration, 1);
            this.coins.forEach((coin) => coin.animateFlip(progress));

            this.renderScene();

            if (progress < 1) requestAnimationFrame((time) => {
                this.animateFlips(time);
            });
            else this.animationCallback();
        }

        animationCallback() { // Called when the flip animation is done.
            if (this.callback) this.callback(this.flipResults);
            this.isAnimating = false;
            this.flipResults = null;
            this.animationStart = null;
            this.callback = null;
        }

        renderScene() {
            this.renderer.render(this.scene, this.camera);
        }
    }

    class Coin {
        constructor(x = 0, y = 0, radius = 2) {
            this.radius = radius;
            this.thickness = 0.3;
            this.segments = 64;

            this.geometry = new THREE.CylinderGeometry(this.radius, this.radius, this.thickness, this.segments, 1, false);
            this.coin = new THREE.Mesh(this.geometry, coinMaterials);
            this.coin.rotation.x = Math.PI / 2;
            this.coin.position.x = x;
            this.coin.position.y = y;

            this.targetRotations = [0, 0, 0];
        }

        flip() {
            const isHeads = Math.random() < 0.5;
            this.targetRotations = [
                Math.PI / 2 + 2 * Math.PI * Math.floor(2 * Math.random()),  // x
                2 * Math.PI * Math.floor(2 * Math.random()),  // y
                (isHeads ? 0 : Math.PI) + 2 * Math.PI * Math.floor(2 + Math.random() * 4),  // z
            ];
            return isHeads;
        }

        animateFlip(progress) {  // Animate a flip based on the target rotations and current progress.
            const easedProgress = 1 - Math.pow(1 - progress, 3);
            //this.coin.rotation.x = easedProgress * this.targetRotations[0];
            //this.coin.rotation.y = easedProgress * this.targetRotations[1];
            this.coin.rotation.z = easedProgress * this.targetRotations[2];
        }
    }

    const loader = new THREE.TextureLoader();
    let headTexture, tailTexture;
    let texturesLoaded = 0;
    let coinMaterials;

    function onTextureLoad() {
        texturesLoaded += 1;
        if (texturesLoaded === 2) {
            createMaterials();
        }
    }

    headTexture = loader.load('/blog/_test/heads.svg', onTextureLoad);
    tailTexture = loader.load('/blog/_test/tails.svg', onTextureLoad);

    [headTexture, tailTexture].forEach(tex => {
        tex.rotation = Math.PI / 2;
        tex.center.set(0.5, 0.5);
        //tex.anisotropy = scene.renderer.capabilities.getMaxAnisotropy();
        tex.minFilter = THREE.LinearFilter;
    });

    let coinArea1;
    let coinArea2;

    function createMaterials() {
        // Textured materials
        const headMaterial = new THREE.MeshStandardMaterial({ map: headTexture });
        const tailMaterial = new THREE.MeshStandardMaterial({ map: tailTexture });

        // Edge material
        const edgeMaterial = new THREE.MeshStandardMaterial({ color: 0x000000 });
        // Material order must match group indices: [top (heads), side, bottom (tails)]
        coinMaterials = [edgeMaterial, headMaterial, tailMaterial];

        setUpScenes();
    }

    function setUpScenes() {
        coinArea1 = new CoinArea(400, 200);
        coinArea1.addToContainer(document.getElementById('container1'));

        coinArea1.addCoin(new Coin(0));
        coinArea1.renderScene();

        coinArea2 = new CoinArea(400, 200, angle=7, z=100);
        coinArea2.addToContainer(document.getElementById('container2'));
        [-8.75, -6.25, -3.75, -1.25, 1.25, 3.75, 6.25, 8.75].forEach((x) => {
            [-3.75, -1.25, 1.25, 3.75].forEach((y) => {
                coinArea2.addCoin(new Coin(x, y, 1));
            })
        })
        coinArea2.renderScene();
    }

    function callback1() {
        document.getElementById("span1").innerHTML = "Oi.";
    }

    function interactive1(guess) {
        // Delete the two guess buttons.
        document.querySelectorAll("#interactive1 > button").forEach((el) => el.remove());
        coinArea1.flipCoins(callback = (result) => {
            result = result[0];
            let result_name = result ? "heads" : "tails";
            let guess_name = guess ? "heads" : "tails";
            let result_span = document.getElementById("span1_1");
            if (result === guess) result_span.innerHTML = `Good guess, you called ${result_name} correctly!`;
            else result_span.innerHTML = `Wooops, you called ${guess_name} but got ${result_name}...`;

            document.getElementById("span1_2").innerHTML = 100 * Number(result);
            document.getElementById("span1_3").innerHTML = 100 * Number(!result);

            // Pre-fill table from the next section.
            document.getElementById("interactive2_tally_heads").innerHTML = Number(result);
            document.getElementById("interactive2_tally_tails").innerHTML = Number(!result);
            document.getElementById("interactive2_per_heads").innerHTML = `${(100 * Number(result)).toFixed(2)}%`;
            document.getElementById("interactive2_per_tails").innerHTML = `${(100 * Number(!result)).toFixed(2)}%`;
            document.getElementById("interactive2_caption").innerHTML = `Tally after 1 flip:`;
        });
    }

    function interactive2(turns) {
        const _duration = Math.round(1000 / turns);
        let flips_left = turns;

        function triggerFlip() {
            if (flips_left <= 0) return;

            flips_left--;
            coinArea2.flipCoins(duration = _duration, callback = (results) => {
                const heads_tally_el = document.getElementById("interactive2_tally_heads");
                let heads_tally = parseInt(heads_tally_el.innerHTML) || 0;
                heads_tally = results.reduce(
                    (acc, result) => acc + result,
                    heads_tally
                )
                heads_tally_el.innerHTML = heads_tally;
                const tails_tally_el = document.getElementById("interactive2_tally_tails");
                let tails_tally = parseInt(tails_tally_el.innerHTML) || 0;
                tails_tally = results.reduce(
                    (acc, result) => acc + !result,
                    tails_tally
                )
                tails_tally_el.innerHTML = tails_tally;

                const total_flips = tails_tally + heads_tally;
                document.getElementById("interactive2_per_heads").innerHTML = `${(100 * heads_tally / total_flips).toFixed(2)}%`;
                document.getElementById("interactive2_per_tails").innerHTML = `${(100 * tails_tally / total_flips).toFixed(2)}%`;
                document.getElementById("interactive2_caption").innerHTML = `Tally after ${total_flips} flips:`;

                triggerFlip();
            });
        }
        triggerFlip();

    }
</script>


## Heads or tails?

The coin below is a fair coin and I want to flip it.
What do you call?
<span class="heads" /> or <span class="tails" />?


<div id="interactive1" style="text-align:center">
<div id="container1"></div>
<button class="btn" onclick="interactive1(true)">Heads</button>
<button class="btn" onclick="interactive1(false)">Tails</button>
</div>

<span id="span1_1">Flip the coin above.</span>


## Balanced coin flip results

What was the probability that you'd be able to guess the coin flip above correctly?
If the coin is fair, it's 50%, right?
That means that the result <span class="heads" /> is as likely to show up as <span class="tails" />.

But wait...
So far, <span id="span1_2">(flip the coin above!)</span>% of the flips resulted in <span class="heads" />, while <span id="span1_3">(flip the coin above!)</span>% of the flips resulted in <span class="tails" />.

That's because you only flipped the coin _once_.
If you flip the coin a whole lot more, the split of the results between <span class="heads" /> and <span class="tails" /> will converge to the expected 50% / 50% split:


<div id="interactive2" style="text-align:center">
<div id="container2"></div>
<button class="btn" onclick="interactive2(1)">Flip 32 coins</button>
<button class="btn" onclick="interactive2(10)">Flip 320 coins</button>
<button class="btn" onclick="interactive2(100)">Flip 3200 coins</button>
</div>

<span id="interactive2_caption">Tally after 0 flips:</span>

<table id="interactive2_tally">
    <thead><tr>
        <th>Heads</th>
        <th>Tails</th>
    </tr></thead>
    <tbody><tr>
        <td id="interactive2_tally_heads">0</td>
        <td id="interactive2_tally_tails">0</td>
    </tr></tbody>
    <tfoot><tr>
        <td id="interactive2_per_heads">0.00%</td>
        <td id="interactive2_per_tails">0.00%</td>
    </tr></tfoot>
</table>

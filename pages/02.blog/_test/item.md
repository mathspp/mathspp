Explore the counterintuitive world of probabilities you get into when you flip a coin over and over again, with interactive animations to make it easier to understand how everything works.

===

<script src="https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.min.js"></script>

<style>
    canvas {
        display: block;
    }

    button {
        margin-top: 10px;
        padding: 0.6em 1.2em;
        font-size: 1rem;
        cursor: pointer;
    }
</style>

<script>
    class CoinArea {
        constructor(width, height) {
            this.scene = new THREE.Scene();
            this.camera = new THREE.PerspectiveCamera(45, width / height, 0.1, 1000);
            this.camera.position.z = 10;

            this.renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
            this.renderer.setSize(width, height);

            this.ambient = new THREE.AmbientLight(0xffffff, 2);
            this.scene.add(this.ambient);

            this.coins = [];

            this.isAnimating = false;
            this.flipResults = null;
            this.animationStart = null;
        }

        addToContainer(container) {
            container.appendChild(this.renderer.domElement);
        }

        addCoin(coin) {
            this.coins.push(coin);
            this.scene.add(coin.coin);
        }

        flipCoins() {
            if (this.isAnimating) return;

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

            const duration = 1000;  // ms
            const progress = Math.min((time - this.animationStart) / duration, 1);
            this.coins.forEach((coin) => coin.animateFlip(progress));

            this.renderScene();

            if (progress < 1) requestAnimationFrame((time) => {
                this.animateFlips(time);
            });
            else this.animationCallback();
        }

        animationCallback() { // Called when the flip animation is done.
            console.log(`Flipping done, results were ${this.flipResults}.`)
            this.isAnimating = false;
            this.flipResults = null;
            this.animationStart = null;
        }

        renderScene() {
            this.renderer.render(this.scene, this.camera);
        }
    }

    class Coin {
        constructor(x = 0) {
            this.radius = 2;
            this.thickness = 0.3;
            this.segments = 64;

            this.geometry = new THREE.CylinderGeometry(this.radius, this.radius, this.thickness, this.segments, 1, false);
            this.coin = new THREE.Mesh(this.geometry, coinMaterials);
            this.coin.rotation.x = Math.PI / 2;
            this.coin.position.x = x;

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

        /*
        coinArea2 = new CoinArea(400, 200);
        coinArea2.addToContainer(document.getElementById('container2'));

        coinArea2.addCoin(new Coin(-5));
        coinArea2.addCoin(new Coin(0));
        coinArea2.addCoin(new Coin(5));
        coinArea2.renderScene();
        */
    }

</script>


## Heads or tails?

The coin below is a fair coin and I want to flip it.
What do you call?
Heads or tails?


<div style="text-align:center">
<div id="container1"></div>
<button class="btn" onclick="coinArea1.flipCoins()">Flip coin</button>
</div>

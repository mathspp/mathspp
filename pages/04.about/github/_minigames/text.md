---
image_align: right
process:
    twig: true
---

<style type="text/css">
    @keyframes slidy {
        0% { left: 0%; }
        20% { left: 0%; }
        25% { left: -100%; }
        45% { left: -100%; }
        50% { left: -200%; }
        70% { left: -200%; }
        75% { left: -300%; }
        95% { left: -300%; }
        100% { left: -400%; }
    }
    body { margin: 0; }
    div#slider {
        overflow: hidden;
        margin-top: -3rem;
        max-height: 30rem;
    }
    div#slider figure img { width: 20%; float: left; }
    div#slider figure {
        position: relative;
        width: 500%;
        margin: 0;
        left: 0;
        animation: 30s slidy infinite;
    }
</style>

<div id="slider">
    <figure>
    {% for image in page.media.images %}
        {{ image.html }}
    {% endfor %}
    </figure>
</div>
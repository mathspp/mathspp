---
process:
    twig: true
cache_enable: false

body_classes: "header-dark header-transparent"
content:
    items: self.children
    limit: 20
    order:
        by: date
        dir: desc
    pagination: true
hero_image: hero.webp
hero_classes: "parallax overlay-dark-gradient text-light" # see https://demo.getgrav.org/blog-skeleton/blog/hero-classes
---

{% include "forms/form.html.twig" with {form: forms( {route: "/insider"} )} %}

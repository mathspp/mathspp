---
process:
    twig: true
cache_enable: false
template: insider-archive

body_classes: "header-dark header-transparent"
content:
    items: '@self.children'
    limit: 20
    order:
        by: date
        dir: desc
    pagination: true
hero_image: hero.webp
hero_classes: "parallax overlay-dark-gradient text-light" # see https://demo.getgrav.org/blog-skeleton/blog/hero-classes
show_sidebar: false
---

# Become the smartest Python 🐍 developer in the room 🚀

## Get a _free_ weekly Python deep dive breaking down a complex Python topic

{% include "forms/form.html.twig" with {form: forms( {route: "/insider"} )} %}

---
title: 'Fast Python development with uv'
hero_classes: 'text-light hero-fullscreen overlay-dark-gradient'
hero_image: 'theme://images/common_hero.webp'

process:
    twig: true
cache_enable: false

form:
    name: enroll

    fields:
        honeypot:
          label: Honeypot
          type: honeypot

        email:
          display_label: false
          autocomplete: true
          placeholder: Your best email address
          type: email
          validate:
            required: true

        g-recaptcha-response:
          label: Captcha
          type: captcha
          recaptcha_not_validated: 'Captcha not valid!'

    buttons:
        submit:
          type: submit
          value: Enroll for free 🐍🚀

    process:
        captcha: true
        newsletter-subscriber:
          publication: insider
          utm_source: "email-course-uv"
          automations:
            - "aut_e5f88d3c-f6ea-46e6-b3b7-1b9da6c2b23c"
---

# The “Fast Python development **playbook**”

## How to supercharge your developer experience in just 4 days with uv

{% include "forms/form.html.twig" with {form: forms("enroll")} %}

<style>
form[name="enroll"] {
    max-width: 70%;
    margin: auto;
}

h2 {
    font-size: 70%;
}

.hero #to-start {
    display: none;
}

.container.grid-lg {
    max-width: max(70%, 976px);
}
</style>

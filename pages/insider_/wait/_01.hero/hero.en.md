---
title: 'mathspp insider 🐍🚀'
hero_classes: 'text-light hero-fullscreen overlay-dark-gradient'
hero_image: 'theme://images/common_hero.webp'

process:
    twig: true
cache_enable: false

form:
    name: test

    fields:
        honeypot:
          label: Honeypot
          type: honeypot

        emailtest:
          display_label: false
          autocomplete: true
          placeholder: Your best email address
          type: email
          validate:
            required: true
          default: '{{ uri.query("email") ?? "failed@example.com" }}'

        g-recaptcha-response:
          label: Captcha
          type: captcha
          recaptcha_not_validated: 'Captcha not valid!'

    buttons:
        submit:
          type: submit
          value: Subscribe for free 🐍🚀

    process:
        captcha: true
        redirect: '/_insider/you-are-in'
---

# Wait! 🤚 You're not in yet

{% include "forms/form.html.twig" with {form: forms("test")} %}

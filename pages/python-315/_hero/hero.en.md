---
title: "15 days of Python 3.15"
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
          value: "Learn about Python 3.15 🐍🚀"

    process:
        captcha: true
        newsletter-subscriber:
          publication: insider
          utm_source: "python-315-campaign"
          automations:
            - "aut_68fbf21d-5f08-41d8-a9eb-7e960ce07ac1"
        display: you-are-in
---

# 15 days of Python 3.15 🐍🚀

## Learn the new features that matter through examples

{% include "forms/form.html.twig" with {form: forms("enroll")} %}

<style>
form[name="enroll"] {
    max-width: 70%;
    margin: auto;
}

.hero #to-start {
    display: none;
}

.container.grid-lg {
    max-width: max(70%, 976px);
}
</style>

---
title: 'Python drops 🐍💧'
hero_classes: 'text-light hero-fullscreen overlay-dark-gradient'
hero_image: 'theme://images/common_hero.webp'

process:
    twig: true
cache_enable: false

form:
    name: subscribe

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
          value: Subscribe for free 🐍💧

    process:
        captcha: true
        newsletter-subscriber:
          publication: drops
        display: /drops/wait-one-more-thing
---

# Better Python in 2 minutes per day

## Get a _free_ daily drop 💧 of Python 🐍 knowledge

{% include "forms/form.html.twig" with {form: forms("subscribe")} %}

<style>
form[name="subscribe"] {
    max-width: 70%;
    margin: auto;
}

h2 {
    font-size: 70%;
}

.hero #to-start {
    display: none;
}
</style>

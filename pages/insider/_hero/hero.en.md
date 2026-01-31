---
title: 'mathspp insider 🐍🚀'
hero_classes: 'parallax text-light hero-fullscreen overlay-dark-gradient'
hero_image: 'theme://images/common_hero.webp'

process:
    twig: true
cache_enable: false

form:
    name: subscribe

    fields:
        publication_id:
          type: hidden
          default: pub_303dfe56-7a7e-4827-ac7c-6cc52785917a

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
          value: Subscribe 🐍🚀

    process:
        captcha: true
        newsletter-subscriber: true
        display: /insider/wait-one-more-thing
---

# Become the smartest Python 🐍 developer in the room 🚀

## Get a weekly Python deep dive breaking down a complex Python topic

{% include "forms/form.html.twig" with {form: forms("subscribe")} %}

Read by 10,000+ from Apple, Amazon, Microsoft, Bank of America, and more

<style>
form[name="subscribe"] {
    max-width: 70%;
    margin: auto;
}
</style>

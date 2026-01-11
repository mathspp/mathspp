---
title: Subscribe
process:
    twig: true
cache_enable: false
class: offset-box

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

## Improve with weekly Python 🐍 deep dives 🚀

Each issue unpacks a topic with analogies, diagrams, and code examples so you can write clearer, faster, and more idiomatic code.

{% include "forms/form.html.twig" with {form: forms("subscribe")} %}

<style>
form[name="subscribe"] {
    max-width: 70%;
    margin: auto;
}
</style>

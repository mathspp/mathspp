---
title: Subscribe

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
          value: Become smarter about Python

    process:
        #captcha: true
        newsletter-subscriber:
          publication: "drops"
        display: wait-one-more-thing
---

## Subscribe

---
title: Subscribe

form:
    name: subscribe

    fields:
        honeypot:
          label: Honeypot
          type: honeypot

        email:
          label: Your email
          autocomplete: true
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
          value: Submit

    process:
        captcha: true
        newsletter-subscriber: true
---

## Subscribe

---
title: Subscribe
publication_id: pub_fe58688a-209b-4a1b-b7c1-83c0c0e8fee5

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

---
title: Subscribe

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
          value: Submit

    process:
        captcha: true
        newsletter-subscriber: true
        display: wait-one-more-thing
---

## Join mathspp insider 🐍🚀

Enter your email address and I'll send you one Python deep dive every week.

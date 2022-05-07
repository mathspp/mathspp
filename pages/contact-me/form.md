---
title: Contact Form

form:
    name: contact

    fields:
        name:
          label: Name
          placeholder: Enter your name
          autocomplete: on
          type: text
          validate:
            required: true

        email:
          label: Email
          placeholder: Enter your email address
          type: email
          validate:
            required: true

        message:
          label: Message
          placeholder: Enter your message
          type: textarea
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
        reset:
          type: reset
          value: Reset

    process:
        captcha: true
        save:
            fileprefix: contact-
            dateformat: Ymd-His-u
            extension: txt
            body: "{% include 'forms/data.txt.twig' %}"
        email:
            -
                reply_to: "{{ form.value.email }}"
                from_name: "{{ form.value.name|e }}"
                subject: "[Site Contact Form] {{ form.value.name|e }}"
                body: "{{ form.value.message }}"
            -
                subject: "mathspp contact confirmation"
                body: "{{ form.value.message }}"
                to: "{{ form.value.email }}"
        message: Thank you for getting in touch!
        display: thank-you
---

# Contact form

Reach out to me!

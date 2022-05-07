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
        email:
            -
                reply_to: "{{ form.value.email }}"
                from_name: "{{ form.value.name|e }}"
                subject: "[Contact Form] {{ form.value.name|e }}"
                body: "{{ form.value.message }}"
            -
                subject: "Contact confirmation from mathspp.com"
                body: "Thanks for reaching out to me, I'll get back to you ASAP! <br />Your message:<blockquote>“{{ form.value.message }}”</blockquote>"
                to: "{{ form.value.email }}"
        display: thank-you
        reset: true
---

# Contact me

Use the form below to write me a message. This goes straight to my inbox 📩

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
        timestamp: label: timestamp
        email:
            -
                reply_to: "{{ form.value.email }}"
                from_name: "{{ form.value.name|e }}"
<<<<<<< HEAD
                subject: "[Contact Form] {{ form.value.name|e }} {{ form.value.timestamp }}"
                body: "{{ form.value.message }}"
            -
                subject: "Contact confirmation from mathspp.com"
                body: "Thanks for reaching out to me, I'll get back to you ASAP! <br />Your message:<blockquote>“{{ form.value.message }}”</blockquote>"
                to: "{{ form.value.email }}"
=======
                subject: "[Site Contact Form] {{ form.value.name|e }}"
                body: "{{ form.value.message }}"
            -
                subject: "mathspp contact confirmation"
                body: "{{ form.value.message }}"
                to: "{{ form.value.email }}"
        message: Thank you for getting in touch!
>>>>>>> 7c004ef10a650c1417467a74a2ffe435f66e957c
        display: thank-you
        reset: true
---

# Contact me

Use the form below to write me a message. This goes straight to my inbox 📩

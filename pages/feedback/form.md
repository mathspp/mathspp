---
title: Send me feedback!

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

        photo:
          label: Profile picture
          type: file
          multiple: false
          accept:
            - image/*

        message:
          label: Message
          placeholder: Enter your message
          rows: 5
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
            subject: "{{ form.value.subject }}"
            body: "{{ form.value.message }}"
          -
            subject: "Contact confirmation from mathspp.com ({{ form.value.subject }})"
            to: "{{ form.value.email }}"
            body:
              -
                content_type: "text/html"
                body: "Thanks for reaching out to me, I'll get back to you ASAP! <br />Your message:<blockquote>“{{ form.value.message }}”</blockquote><br />It is safe to delete this confirmation email. Thanks!"
              -
                content_type: "text/plain"
                body: "Thanks for reaching out to me, I'll get back to you ASAP!\n\nYour message: “{{ form.value.message }}”\nIt is safe to delete this confirmation email. Thanks!"
        display: thank-you
---

# Feedback

Send me feedback! Thanks!

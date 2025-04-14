---
class: container grid-lg
title: Contact Form

form:
    name: contact
    action: /training/advanced-iteration

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

        company:
          label: Company
          placeholder: Enter your company
          type: text
          validate:
            required: true

        subject:
          default: Inquiry about your advanced iteration training
          label: Subject
          placeholder: E-mail subject
          type: text
          validate:
            required: true

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
            from_name: "{{ form.value.name|e }} ({{ form.value.company|e }})"
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

## Contact me

If you want to schedule this course for your team, or if you have any questions you'd like me to answer, just [drop me a line to rodrigo@mathspp.com](mailto:rodrigo@mathspp.com?subject=Corporate%20training%20inquiry).
Alternatively, fill in the contact form below:

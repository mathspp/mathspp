---
title: Write a testimonial

form:
    name: testimonial

    fields:
        object:
          default: book-pydonts
          label: What is your testimonial about?
          type: select
          options:
            book: "Book"
            course: "Course / workshop / tutorial"
            talk: "Talk"
            newsletter: "Newsletter"
            blog: "Blog post"
            other: "Something else / nothing in particular."
          validate:
            required: true

        testimonial:
          label: Testimonial
          placeholder: |
            Write your testimonial here.
            What thing(s) did you enjoy? How did they help? How is your code / life better now?
            Keep it simple and honest :)
            Thanks!
          rows: 5
          type: textarea
          validate:
            required: true

        name:
          label: Your name
          autocomplete: on
          type: text
          validate:
            required: true

        honeypot:
          label: Honeypot
          type: honeypot

        email:
          label: Your email (won't be shared publicly)
          autocomplete: true
          help: "For the confirmation email, only."
          type: email
          validate:
            required: true

        social:
          label: Your social link (e.g., LinkedIn)
          type: url

        company_and_title:
          label: Your company and title
          type: text

        headshot:
          avoid_overwriting: true
          label: Your photo
          type: file
          help: "Makes the testimonial more personal."
          multiple: false
          destination: "user/data/testimonial"
          accept:
            - image/*

        permission:
          type: checkbox
          label: "My testimonial can be shared online without restrictions."
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
            subject: "Testimonial for {{ form.value.object }}"
            body: "{{ form.value.testimonial }}"
          -
            subject: "Testimonial confirmation."
            to: "{{ form.value.email }}"
            reply_to: "rodrigo@mathspp.com"
            body:
              -
                content_type: "text/plain"
                body: "Thank you for writing a testimonial. I will review it as soon as possible and add it to the testimonials page. If you didn't include your picture in the review you submitted, feel free to reply to this email and attach your picture there. Having a picture, even if it does not look professional, makes the testimonial much more personal – and therefore, valuable."
        save-review: true
        display: thank-you
---

# Write a testimonial

Use the form below to write a testimonial for my work.
This is very appreciated.

[Check the testimonial wall](/testimonials).

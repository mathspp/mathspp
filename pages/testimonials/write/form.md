---
title: Write a testimonial

form:
    name: testimonial

    fields:
        name:
          label: Name
          placeholder: Enter your name
          autocomplete: on
          type: text
          validate:
            required: true

        headshot:
          avoid_overwriting: true
          label: Your photo
          type: file
          help: "Makes testimonial more personal."
          multiple: false
          destination: "user/data/testimonial"
          accept:
            - image/*

        email:
          label: Email
          autocomplete: true
          placeholder: Enter your email address
          help: "For the confirmation email, only."
          type: email
          validate:
            required: true

        company:
          label: Company
          placeholder: Your company
          type: text

        role:
          label: Role
          placeholder: Your role at your company
          type: text

        object:
          default: book-pydonts
          label: What is your testimonial about
          type: select
          options:
            # Books section
            books:
              value: Books
              disabled: true
            book-pydonts: "Pydon'ts"
            book-list-comps: "Comprehending Comprehensions"
            book-little-itertools: "The little book of itertools"
            book-little-pygame: "The little book of pygame"
            book-little-pandas-and-matplotlib: "The little book of pandas & matplotlib"
            book-nnfwp: "Neural Networks Fundamentals with Python"
            book-puzzles: "Puzzles, Riddles & Problems"
            # Conference section
            conferences:
              value: Conferences
              disabled: true
            conference-talk: "talk"
            conference-tutorial: "tutorial/workshop"
            conference-lt: "lightning talk"
            # Misc section
            misc:
              value: Misc
              disabled: true
            webinar: "webinar"
            bootcamp: "problem-solving bootcamp"
            blog: "blog article"
            newsletter: "newsletter"
            social-media: "social media post(s)"
            other: "something else / nothing in particular"
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

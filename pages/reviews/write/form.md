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
          help: "Think of this as your profile picture from social media. I'll use it next to the testimonial to make it more personal."
          multiple: false
          destination: "self@"
          accept:
            - image/*

        email:
          label: Email
          placeholder: Enter your email address
          help: "This won't be displayed with your testimonial, it's just to email you a confirmation."
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
          label: What is your testimonial about
          type: select_optgroup
          options:
            - Books:
              book-pydonts: "book “Pydon'ts”"
              book-list-comps: "book “Comprehending Comprehensions”"
              book-little-itertools: "book “The little book of itertools”"
              book-little-pygame: "book “The little book of pygame”"
              book-little-pandas-and-matplotlib: "book “The little book of pandas & matplotlib”"
              book-nnfwp: "book “Neural Networks Fundamentals with Python”"
              book-puzzles: "book “Puzzles, Riddles & Problems"
            - Conferences:
              conference-talk: "talk"
              conference-tutorial: "tutorial/workshop"
              conference-lt: "lightning talk"
            - Misc:
              blog: "blog article"
              newsletter: "newsletter"
              social-media: "social media post(s)"
              other: "something else / nothing in particular"
          validate:
            required: true

        testimonial:
          label: Testimonial
          placeholder: "Please, write your testimonial here. Include the thing you're reviewing (a book? the newsletter? the blog?) and what you liked about it."
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

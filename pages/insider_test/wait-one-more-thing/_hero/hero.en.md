---
title: 'Wait! One more thing...'
hero_classes: 'parallax text-light hero-fullscreen overlay-dark-gradient'
hero_image: 'theme://images/common_hero.webp'

process:
    twig: true
cache_enable: false

form:
    name: sign-up-survey

    fields:
        publication_id:
          type: hidden
          default: pub_303dfe56-7a7e-4827-ac7c-6cc52785917a

        honeypot:
          label: Honeypot
          type: honeypot

        python-level:
          label: "Whats your current Python level?"
          display_label: true
          type: radio
          default: beginner
          options:
            beginner: "Beginner programmer (just starting out in Python and programming in general)"
            intermediate: "Intermediate (I'm productive with the language)"
            advanced: "Advanced (LOTS of experience)"
            experienced-beginner: "Experienced beginner (new to Python but with considerable experience in other languages)"

        extra-info:
          label: "Extra info"
          display_label: true
          type: text
          validate:
            required: true
          attributes:
            custom_field: "sign_up_survey.extra_info"
          maxlength: 255

        g-recaptcha-response:
          label: Captcha
          type: captcha
          recaptcha_not_validated: 'Captcha not valid!'

    buttons:
        submit:
          type: submit
          value: Subscribe for free 🐍🚀

    process:
        captcha: true
        beehiiv-custom-field-updater: true
        display: /insider_test/you-are-in
---

# Wait! 🤚 Your subscription isn't completed yet.

{% include "forms/form.html.twig" with {form: forms("sign-up-survey")} %}

<script>plausible("mathspp insider landing page signup");</script>

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
          label: "What's your current Python level?"
          display_label: true
          type: select
          default: beginner
          options:
            beginner: "Beginner programmer (just starting out in Python and programming in general)"
            intermediate: "Intermediate (I'm productive with the language)"
            advanced: "Advanced (LOTS of experience)"
            experienced-beginner: "Experienced beginner (new to Python but with considerable experience in other languages)"
          validate:
            required: true
          attributes:
            custom_field: "sign_up_survey.python_level"

        usage:
          label: "How do you use Python?"
          display_label: true
          type: select
          multiple: true
          options:
            professional: "I am a professional Python programmer"
            job: "I use Python to help me get my job done"
            hobby: "I'm a hobbyist Python programmer"
          validate:
            required: true
          attributes:
            custom_field: "sign_up_survey.python_use_cases"

        interests:
          label: "What topics are you interested in learning more about?"
          display_label: true
          type: checkboxes
          options:
            core: "Core Python syntax and principles"
            stdlib: "Standard library"
            llms: "Agentic coding / LLMs"
            tooling: "Tooling & productivity (e.g., uv)"
            typing: "Typing"
            web: "Web development"
            ds: "Data science"
            cs: "Computer science"
          validate:
            required: true
          attributes:
            custom_field: "sign_up_survey.interests"

        why-interests:
          label: "Why are you interested in the topics you picked above?"
          display_label: true
          type: text
          validate:
            required: true
          attributes:
            custom_field: "sign_up_survey.interests_explanation"
          maxlength: 999

        roadblock:
          label: "What's the #1 roadblock you hit when trying to grow your skills?"
          display_label: true
          type: select
          default: time
          options:
            time: "Not enough time to learn"
            llms: "Uncertainty because of LLMs"
            sticking: "Nothing sticks after I “learn”"
            path: "I don't have a clear path forward"
          validate:
            required: true
          attributes:
            custom_field: "sign_up_survey.biggest_roadblock"

        why-interests:
          label: "What's the most important thing you've been meaning to or are trying to learn?"
          display_label: true
          type: text
          validate:
            required: true
          attributes:
            custom_field: "sign_up_survey.biggest_learning_goal"
          maxlength: 999

        extra-info:
          label: "Is there anything else you'd like to share so I can help you achieve your Python objectives?"
          display_label: true
          type: text
          validate:
            required: true
          attributes:
            custom_field: "sign_up_survey.extra_info"
          maxlength: 999

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

---
class: offset-box

process:
    twig: true
cache_enable: false

form:
    name: sign-up-survey

    fields:
        honeypot:
          label: Honeypot
          type: honeypot

        python-level:
          label: "1. What's your current Python level?"
          display_label: true
          type: select
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
          label: "2. How do you use Python?"
          display_label: true
          type: checkboxes
          options:
            professional: "I am a professional Python programmer"
            job: "I use Python to help me get my job done"
            hobby: "I'm a hobbyist Python programmer"
          validate:
            required: true
          attributes:
            custom_field: "sign_up_survey.python_use_cases"

        interests:
          label: "3. What topics are you interested in learning more about?"
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
          label: "4. Why are you interested in the topics you picked above?"
          display_label: true
          type: textarea
          validate:
            required: true
          attributes:
            custom_field: "sign_up_survey.interests_explanation"
          minlength: 3
          maxlength: 999

        roadblock:
          label: "5. What's the #1 roadblock you hit when trying to grow your skills?"
          display_label: true
          type: select
          options:
            time: "Not enough time to learn"
            llms: "Uncertainty because of LLMs"
            sticking: "Nothing sticks after I “learn”"
            path: "I don't have a clear path forward"
          validate:
            required: true
          attributes:
            custom_field: "sign_up_survey.biggest_roadblock"

        biggest-learning-goal:
          label: "6. What's the most important thing you've been meaning to or are trying to learn?"
          display_label: true
          type: textarea
          validate:
            required: true
          attributes:
            custom_field: "sign_up_survey.biggest_learning_goal"
          minlength: 3
          maxlength: 999

        extra-info:
          label: "7. Is there anything else you'd like to share so I can help you achieve your Python objectives?"
          display_label: true
          type: textarea
          validate:
            required: true
          attributes:
            custom_field: "sign_up_survey.extra_info"
          minlength: 3
          maxlength: 999

        g-recaptcha-response:
          label: Captcha
          type: captcha
          recaptcha_not_validated: 'Captcha not valid!'

    buttons:
        submit:
          type: submit
          value: Complete subscription 🐍🚀

    process:
        captcha: true
        beehiiv-custom-field-updater:
          publication: "insider"
        email:
          -
            subject: "Survey response"
            body: "{% include 'forms/data.html.twig' %}"
        redirect: /insider/you-are-in
---

{% include "forms/form.html.twig" with {form: forms("sign-up-survey")} %}

<script>plausible("mathspp insider landing page signup");</script>

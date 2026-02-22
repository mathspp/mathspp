---
process:
    twig: true
---

<style>
/* Container for the two review cards */
.review-cards-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1rem;
  align-items: start;
}

/* Wide screens: show two cards side-by-side */
@media (min-width: 900px) {
  .review-cards-grid {
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
  }
}
</style>

<div class="review-cards-grid">

{% include 'partials/review-card.html.twig' with {
    review: {
    header: {
        star_rating: 5,
        name: 'Kiel Haymon',
    },
    content: '<p>Learning in an interactive manner is helpful and the most effective to me. Also being able to ask questions and get them resolved in real time is very beneficial.</p>',
    media: {
        images: [
        { url: '/user/pages/testimonials/20260217-2118-kiel-haymon/profile.webp' }
        ]
    }
    }
} %}

{% include 'partials/review-card.html.twig' with {
    review: {
    header: {
        star_rating: 5,
        name: 'Yoga',
    },
    content: '<p> I like your teaching style, and I think you make topics approachable and engaging. I learned things that were not necessarily the core point of the material by watching you work through rough code and answer questions. It is useful to hear questions from others in the course who have different experience levels or perspectives. It's also just more fun to work together in real time rather than on my own.</p>',
    media: {
        images: [
        { url: '/user/pages/testimonials/20260217-2119-colby-thrash/profile.webp' }
        ]
    }
    }
} %}

</div>


## Join the March cohort

<div style="display:flex; justify-content:center;">
<a href="https://mathspp.gumroad.com/l/intermediate-python-course?wanted=true&variant=Cohort%3A+2nd+March+%E2%80%94+3rd+April" class="btn cta-btn">Secure your spot!</a>
</div>

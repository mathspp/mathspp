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
        star_rating: 4,
        name: 'Youn Noh',
    },
    content: '<p>I’m especially excited to apply generators to better handle messy data and recover from errors while processing iterables. I also appreciated the deep dive into tools like uv, mypy, and publishing to PyPI — I’ve already switched from Anaconda to uv because of this course.</p>',
    media: {
        images: [
        { url: '/user/pages/testimonials/20260217-2118-youn-noh/profile.webp' }
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
    content: '<p>Decorators and generators were especially exciting, and I’m looking forward to applying them in my own work.</p>',
    media: {
        images: [
        { url: '/user/pages/testimonials/20260222-1615-yoga/profile.webp' }
        ]
    }
    }
} %}

</div>


## Join the March cohort

<div style="display:flex; justify-content:center;">
<a href="https://mathspp.gumroad.com/l/intermediate-python-course?wanted=true&variant=Cohort%3A+2nd+March+%E2%80%94+3rd+April" class="btn cta-btn">Secure your spot!</a>
</div>

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
    content: '<p>The most exciting part for me was typing, a very interesting module to explore further.</p>',
    media: {
        images: [
        { url: '/user/pages/testimonials/20251204-1151-soumen/profile.webp' }
        ]
    }
    }
} %}

{% include 'partials/review-card.html.twig' with {
    review: {
    header: {
        star_rating: 5,
        name: 'Michael Dahlberg',
    },
    content: '<p>I learned something I did not know on each day. [...] Modularity was something I knew about but I really learned some fantastic rules on how to apply it. [...] This was one of those rare classes that I came away feeling like I learned WAY more about any of the given topics than I thought I already knew .</p>',
    media: {
        images: [
        { url: '/user/pages/testimonials/20260217-2116-michael-dahlberg/profile.webp' }
        ]
    }
    }
} %}

</div>


## Join the March cohort

<div style="display:flex; justify-content:center;">
<a href="https://mathspp.gumroad.com/l/intermediate-python-course?wanted=true&variant=Cohort%3A+2nd+March+%E2%80%94+3rd+April" class="btn cta-btn">Secure your spot!</a>
</div>

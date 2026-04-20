---
process:
    twig: true
---

## From non-CS background to confident dev in 5 weeks

Join dozens of past students who already

 - got better job offers after the course
 - developed internal projects and tools that brought them more notoriety
 - simplified their workflows with the techniques learned during the course
 - are writing more efficient code with more confidence


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
        name: 'Marina, data scientist',
    },
    content: '<p>I come from a data science background <span>without a formal computer science education</span>, and this course helped me better understand how to write clearer, more structured code and follow functional programming principles. It challenged me to rethink how I approach coding and gave me a better foundation to improve.</p>',
    media: {
        images: [
        { url: '/user/pages/testimonials/20260217-2117-marina/profile.webp' }
        ]
    }
    }
} %}

{% include 'partials/review-card.html.twig' with {
    review: {
    header: {
        star_rating: 5,
        name: 'Colby Thrash, hydrologist',
    },
    content: '<p>I don’t have as much computer science background as it sounded like some others had, but I felt like I was able to follow the material. <span>It gave me more confidence in my programming skills</span>.</p>',
    media: {
        images: [
        { url: '/user/pages/testimonials/20260217-2119-colby-thrash/profile.webp' }
        ]
    }
    }
} %}

</div>

<div style="display:flex; justify-content:center;">
<a href="https://mathspp.gumroad.com/l/intermediate-python-course?wanted=true&variant=Cohort%3A+5th+May+%E2%80%93+4th+June" class="btn cta-btn">Secure your spot!</a>
</div>

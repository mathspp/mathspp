---
process:
    twig: true
---

## Turn your Python code into professional code

Join dozens of others who are writing more efficient code with more confidence.


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

/* Optional: keep cards visually consistent in a grid */
.review-cards-grid .review-card {
  height: 100%;
}
</style>

<div class="review-cards-grid">

{% include 'partials/review-card.html.twig' with {
    review: {
    header: {
        star_rating: 5,
        name: 'Marina',
    },
    content: '<p>I come from a data science background without a formal computer science education, and this course helped me better understand how to write clearer, more structured code and follow functional programming principles. It challenged me to rethink how I approach coding and gave me a better foundation to improve.</p>',
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
        name: 'Michael Dahlberg',
    },
    content: '<p>One of the most valuable things I learned wasn’t just specific tools, but a new way to think about Python — as a set of building blocks you can assemble to solve problems. It was absolutely worth the financial and time investment.</p>',
    media: {
        images: [
        { url: '/user/pages/testimonials/20260217-2116-michael-dahlberg/profile.webp' }
        ]
    }
    }
} %}

</div>



**Next cohort**:

| Start | End |
| | |
| 2nd March 2026 | 3rd April 2026 |

<div style="display:flex; justify-content:center;">
<a href="https://mathspp.gumroad.com/l/intermediate-python-course?wanted=true&variant=Cohort%3A+2nd+March+%E2%80%94+3rd+April" class="btn" style="margin-right: 1em;">Secure your spot!</a>
</div>

> “I learned more in one week than I could have learned in multiple weeks teaching myself.” — **Kiel**, past student

# 

> “One of the most useful things I learned wasn’t just what was taught, but how problems were approached.
> This was one of _the best learning experiences I’ve had_. It was absolutely worth the time and financial investment.” — **Mike**, past student

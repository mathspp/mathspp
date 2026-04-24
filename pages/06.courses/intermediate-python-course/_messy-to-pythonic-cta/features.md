---
process:
    twig: true
---

## Your code works but doesn't look <span class="accent">Pythonic</span>

Your code gets the job done.

But it feels messy, hard to extend, and painful to maintain.

You copy and paste code between files.

You're not sure when to use functions, classes, or modules.

You've heard terms like “clean architecture”, “design patterns”, and “Pythonic code”, but nobody has shown you how to apply them to real projects.

So every new feature feels like starting over.

## Learn to write code you're proud of

In this course, you'll learn how experienced developers structure real projects.

You'll understand how to break problems into clean, reusable pieces.

You'll write code that is easier to read, test, debug, and improve.

Not just code that works, but code that looks professional.

The kind of code that gives you confidence when you open it six months later.

And the kind of code that makes other developers take you seriously.

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
    content: '<p>I would like to write better code and follow the principles of functional programming... But often I just write typical <span>analytical spaghetti code</span>.</p>',
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
    content: '<p>One of the most useful things I learned was that Python can be thought of as a group of ‘building blocks’ that can be assembled in any manner to solve a problem. This is how I want to approach problems and program in Python!</p>',
    media: {
        images: [
        { url: '/user/pages/testimonials/20260217-2116-michael-dahlberg/profile.webp' }
        ]
    }
    }
} %}

</div>

<div style="display:flex; justify-content:center;">
<a href="https://app.cal.eu/mathspp/ipc-discovery" class="btn cta-btn">Apply for the cohort</a>
</div>

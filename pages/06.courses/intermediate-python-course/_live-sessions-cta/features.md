---
process:
    twig: true
---

## You feel stuck

You watch YouTube videos, read blog posts, and maybe even bought a course or two.

And make no progress.

## You're bleeding opportunities

If your skills stay the same, you're getting _worse_.

This cohort gets you back on track.

## Go from solo-struggles to realtime mentorship

You're missing a cohort of other students.

You need to exchange ideas with other devs with the same struggles as you.

You need to learn live, in realtime, asking questions and getting answers right then and there.


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
        name: 'Kiel Haymon, got a job after the cohort',
    },
    content: "<p><span>Learning in an interactive manner is the most effective to me</span>. Also being able to ask questions and get them resolved in real time is very beneficial.</p>",
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
    content: "<p>We're really fortunate to have you put on these sessions. <span>It's not always easy to find mentors that can communicate difficult things simply</span>. And it's not always easy to find mentors to meet you where you are at.</p>",
    media: {
        images: [
        { url: '/user/pages/testimonials/20260222-1615-yoga/profile.webp' }
        ]
    }
    }
} %}

</div>

<div style="display:flex; justify-content:center;">
<a href="https://app.cal.eu/mathspp/ipc-discovery" class="btn cta-btn">Apply for the cohort</a>
</div>

---
author: Jack Hopkins, Mart Bakler, and Akbir Khan
date: 18-04-2025 13:50
link: https://jackhopkins.github.io/factorio-learning-environment/
taxonomy:
    category: link
title: "Factorio Learning Environment"
via: https://www.linkedin.com/posts/shivanivirdi_i-have-a-bone-to-pick-with-all-these-llm-activity-7318873720716947457-PD9B/
---

This short paper introduces an LLM leaderboard based on the simulation/automation game [Factorio](https://www.factorio.com).
The authors created a programmatic interface to the game and then several LLMs were asked to play a simplified version of the game through that programmatic interface.

The LLMs were evaluated in two settings:

 1. open play – In open play, LLMs were tasked with building the largest factory possible.
 2. lab play – In lab play, LLMs are given a fixed time interval under which they're asked to automate the production of 24 distinct materials, from simple mines to utility science packs, which require the coordination of multiple other machines and components.

The paper shows that the models tested had very imbalanced scores, with Claude Sonnet 3.5 beating GPT-4o, Deepseek-v3, Gemini-2, Llama-3.3-7.0B, and GPT-4o-Mini, in both open play and lab play.

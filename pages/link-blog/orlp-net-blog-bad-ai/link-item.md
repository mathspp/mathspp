---
author: Orson Peters
date: 04-02-2025 14:15
link: https://orlp.net/blog/bad-ai/
taxonomy:
    category: link
title: "Why Bad AI Is Here to Stay"
# via:
---

In this article, Orson talks about how LLMs are likely here to stay and presents some scenarios in which LLMs are useful, despite the fact that they're bad.
Orson provides an equation that formalises the usefulness of an LLM in a given context and it's through this equation that Orson presents typical use cases for LLMs and determines whether they are useful for that use case or not.

The equation is gradually refined through the blog post and in its final form it provides a measure of the risk of using an LLM in a given scenario based on the cost of querying the LLM, the cost to verify the LLM answer, the probability that the answer is both correct and relevant, and the risk/cost of using another method if the answer of the LLM is bad:

$$
\text{Cost}_\text{LLM} = \text{Cost}(\text{query}) + \text{Cost}(\text{verify}) + (1 - P(\text{correct}\cap\text{relevant})) \cdot \text{Cost}(\text{bad})
$$

Although it's unlikely you'll be able to provide exact numbers for all the terms, this lets you look at different use cases with _some_ rigour, since you can estimate some values in some scenarios, that Orson presents and that I copy verbatim:

1. Inspiration – $\text{Cost}_\text{bad} \approx 0$
2. Creative – $P(\text{correct}) \approx 1$
3. Planning – $P(\text{correct}) = P(\text{relevant}) = 1$
4. Retrieval – $P(\text{correct}) \approx P(\text{relevant})$
5. Objective – $P(\text{relevant}) = 1$ when $\text{Cost}(\text{verify}) \approx 0$

The author then provides examples of queries that fall into each category.

---
date: 03-12-2024
event: "PyData Global 2024"
event_link: https://pydata.org/global2024/schedule/talk/WFBQR9/
main_reference: https://pola.rs/posts/understanding-polars-data-types/
resources: https://github.com/mathspp/talks/tree/main/20241203_pydata_global-understanding-polars-data-types
slides_pdf: https://github.com/mathspp/talks/blob/main/20241203_pydata_global-understanding-polars-data-types/slides.pdf
taxonomy:
    category: talks
    tags:
        - "PyData Global"
        - "remote"
        - "2024"
title: "Understanding Polars data types"
watch: https://www.youtube.com/watch?v=8HwfVVknhP4
youtube_embed: https://www.youtube.com/embed/8HwfVVknhP4
---

## Abstract

[Polars](https://github.com/pola-rs/polars) boasts 18 different data types, not including variants of numerical types.

Do we really need such a vast collection of data types?

What is the use case for each type?

What is the difference between `List` and `Array`? Or between `Categorical` and `Enum`? And why on Earth would I ever need a `Struct`?

This talk will clear up all of these questions and more, as we go through the data types that Polars provides and understand why we need each one of them.

## Description

[According to the documentation](https://docs.pola.rs/user-guide/concepts/data-types-and-structures/#appendix-full-data-types-table), Polars has 18 data types (excluding the varying precision of numerical data types).

The use cases for some data types are intuitively very clear.
For example, we all know when to use Booleans, integers, floating-point numbers, or strings.

Some pairs of data types are fairly easy to understand, but their distinctions can be fuzzy.
For example, when do you use `List` or `Array`?
When is `Categorical` better than `Enum` and vice-versa?

And some less common data types are poorly understood, like `Decimal`, `Object`, or `Struct`.

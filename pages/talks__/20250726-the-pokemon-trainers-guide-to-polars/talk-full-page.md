---
date: 26-07-2025
event: "PyCon Portugal 2025"
event_link: https://pretalx.evolutio.pt/pycon-portugal-2025/talk/HAD3VX/
resources: https://github.com/mathspp/talks/tree/main/20250726_pycon_portugal_the-pokemon-trainers-guide-to-polars
taxonomy:
    category: tutorials
    tags:
        - "PyCon Portugal"
        - "Portugal"
        - "2025"
title: "The Pokémon trainer's guide to Polars"
---

## Abstract

Polars is lightning fast, easy to use, dataframe library.
It's clean API and its contexts and expressions allow writing readable but performant data wrangling code.

Aimed at both data newbies and pandas experts (Polars `!=` pandas), in this hands-on tutorial we'll use data from the Pokémon franchise to explore the Polars API and the ideas that set it apart from pandas and other dataframe libraries.

## Description

**Set-up instructions**: install Polars (1.12+); jupyter notebooks are optional.

The Polars dataframe library leverages a couple of key concepts to allow you to write lightning-fast data manipulation code: contexts and expressions.

The tutorial is split in 4 sections of roughly 30 minutes:
1. The basics
2. Expression expansion
3. Data transformations
4. The lazy API

### 1. The basics

Early on in the tutorial we will introduce the concept of expression and explain that expressions simply represent computations, but do not compute anything.
Then, using Polars contexts (`select`, `group_by`, etc), Polars takes expressions and performs computations, producing a result that depends on the context used.
The first 30 minutes of the tutorial are spent exploring the four basic contexts that Polars provides:
1. `select`
2. `with_columns`
3. `filter`
4. `group_by`

### 2. Expression expansion

Effective use of the Polars API can only be attained if you become comfortable with expression expansion, a Polars feature that is highly intuitive but that requires some practising to recognise all of the opportunities where it could be used.
During this section, we explore how to select columns based on names, patterns, data types, and how to use the Polars column selectors `polars.selectors`.

### 3. Data transformations

During this section we spend some time playing with some general-purpose transformations and we see how the Polars API exposes them, namely pivots and unpivots, as well as several types of joins: equi-joins, the join asof, and a non-equi join.

### 4. The lazy API

Finally, we conclude the tutorial by going through one of the most important features of Polars, which is what enables Polars uses to process enormous amounts of data at impressive speeds: the lazy API.
During this section, we explore how the lazy API differs from the eager API, how to write queries with the lazy API and how to execute them, how to use streaming, and how to do profiling.

### Notes

I will present the tutorial in a notebook and the whole tutorial will be practical and the audience is supposed to follow along while I live-code.
In each section, there will be around 15 minutes for participants to work on exercises to practise the concepts introduced.

(During the whole tutorial I will have a script that continuously synchronises my live-coded demos with a GitHub repository so that everyone can pull my code locally if they get lost.)

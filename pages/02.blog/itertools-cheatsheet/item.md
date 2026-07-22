Cheatsheet with visual diagrams that explain how the iterables from `itertools` work.

===

This cheatsheet contains diagrams that explain how the iterables from the module `itertools` work in a visual way.

[Download this cheatsheet](https://gumroad.com/l/cheatsheet-itertools?classes=btn,btn-lg,btn-center)

![A4 itertools cheatsheet shown in light and dark themes.](_cheatsheets-light-front.webp?classes=dark-theme-only)

![A4 itertools cheatsheet shown in light and dark themes.](_cheatsheets-dark-front.webp?classes=light-theme-only)

[Download this cheatsheet](https://gumroad.com/l/itertools-uv?classes=btn,btn-lg,btn-center)

---

## Filtering

### `filterfalse`

![Visual diagram explaining how filterfalse works.](_filterfalse_dark_bg.webp?classes=dark-theme-only)
![Visual diagram explaining how filterfalse works.](_filterfalse_light_bg.webp?classes=light-theme-only)

### `takewhile` & `dropwhile`

![Visual diagram explaining how takewhile and dropwhile work.](_takewhile_dropwhile_dark_bg.webp?classes=dark-theme-only)
![Visual diagram explaining how takewhile and dropwhile work.](_takewhile_dropwhile_light_bg.webp?classes=light-theme-only)

### `compress`

![Visual diagram explaining how compress works.](_compress_dark_bg.webp?classes=dark-theme-only)
![Visual diagram explaining how compress works.](_compress_light_bg.webp?classes=light-theme-only)

## Reshaping

### `batched`

![Visual diagram explaining how batched works.](_batched_dark_bg.webp?classes=dark-theme-only)
![Visual diagram explaining how batched works.](_batched_light_bg.webp?classes=light-theme-only)

### `islice`

![Visual diagram explaining how islice works.](_islice_dark_bg.webp?classes=dark-theme-only)
![Visual diagram explaining how islice works.](_islice_light_bg.webp?classes=light-theme-only)

### `pairwise`

![Visual diagram explaining how pairwise works.](_pairwise_dark_bg.webp?classes=dark-theme-only)
![Visual diagram explaining how pairwise works.](_pairwise_light_bg.webp?classes=light-theme-only)

### `chain`

![Visual diagram explaining how chain works.](_chain_dark_bg.webp?classes=dark-theme-only)
![Visual diagram explaining how chain works.](_chain_light_bg.webp?classes=light-theme-only)

### `groupby`

![Visual diagram explaining how groupby works.](_groupby_dark_bg.webp?classes=dark-theme-only)
![Visual diagram explaining how groupby works.](_groupby_light_bg.webp?classes=light-theme-only)

## Infinite

### `repeat`

![Visual diagram explaining how repeat works.](_repeat_dark_bg.webp?classes=dark-theme-only)
![Visual diagram explaining how repeat works.](_repeat_light_bg.webp?classes=light-theme-only)

### `cycle`

![Visual diagram explaining how cycle works.](_cycle_dark_bg.webp?classes=dark-theme-only)
![Visual diagram explaining how cycle works.](_cycle_light_bg.webp?classes=light-theme-only)

### `count`

![Visual diagram explaining how count works.](_count_dark_bg.webp?classes=dark-theme-only)
![Visual diagram explaining how count works.](_count_light_bg.webp?classes=light-theme-only)

## Combinatorial

### `product`

![Visual diagram explaining how product works.](_product_dark_bg.webp?classes=dark-theme-only)
![Visual diagram explaining how product works.](_product_light_bg.webp?classes=light-theme-only)

When you want to compute the product of an iterable with itself two or more times, you can also use the argument `repeat`:

![Visual diagram explaining how product works.](_product_repeat_dark_bg.webp?classes=dark-theme-only)
![Visual diagram explaining how product works.](_product_repeat_light_bg.webp?classes=light-theme-only)

### `permutations`

![Visual diagram explaining how permutations work.](_permutations_dark_bg.webp?classes=dark-theme-only)
![Visual diagram explaining how permutations work.](_permutations_light_bg.webp?classes=light-theme-only)

### `combinations`

![Visual diagram explaining how combinations work.](_combinations_dark_bg.webp?classes=dark-theme-only)
![Visual diagram explaining how combinations work.](_combinations_light_bg.webp?classes=light-theme-only)

### `combinations_with_replacement`

![Visual diagram explaining how combinations_with_replacement works.](_combinations_with_replacement_dark_bg.webp?classes=dark-theme-only)
![Visual diagram explaining how combinations_with_replacement works.](_combinations_with_replacement_light_bg.webp?classes=light-theme-only)

## Complementary

### `zip_longest`

![Visual diagram explaining how zip_longest works.](_zip_longest_dark_bg.webp?classes=dark-theme-only)
![Visual diagram explaining how zip_longest works.](_zip_longest_light_bg.webp?classes=light-theme-only)

### `starmap`

![Visual diagram explaining how starmap works.](_starmap_dark_bg.webp?classes=dark-theme-only)
![Visual diagram explaining how starmap works.](_starmap_light_bg.webp?classes=light-theme-only)

### `accumulate`

![Visual diagram explaining how accumulate works.](_accumulate_dark_bg.webp?classes=dark-theme-only)
![Visual diagram explaining how accumulate works.](_accumulate_light_bg.webp?classes=light-theme-only)

By default, `accumulate` uses addition as its operation.
The magic in using `functools.reduce` and `itertools.accumulate` well is in realising the function that combines elements can be an arbitrary function of two arguments.
For example, you can imagine you're a painter and you're going to mix the colours in your palette:

![Visual diagram explaining how accumulate works.](_accumulate_mix_dark_bg.webp?classes=dark-theme-only)
![Visual diagram explaining how accumulate works.](_accumulate_mix_light_bg.webp?classes=light-theme-only)

---

[Download this cheatsheet](https://gumroad.com/l/cheatsheet-itertools?classes=btn,btn-lg,btn-center)

![A4 itertools cheatsheet shown in light and dark themes.](_cheatsheets-light-front.webp?classes=dark-theme-only)

![A4 itertools cheatsheet shown in light and dark themes.](_cheatsheets-dark-front.webp?classes=light-theme-only)

[Download this cheatsheet](https://gumroad.com/l/itertools-uv?classes=btn,btn-lg,btn-center)

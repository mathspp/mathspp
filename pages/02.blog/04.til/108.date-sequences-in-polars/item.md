Today I learned how to use the Polars function `pl.date_range` to create date sequences with calendar-aware intervals between dates.

===


## Date sequences in Polars

Polars provides a function `polars.date_range` that is able to produce date sequences with calendar-aware intervals.
For example, I'm writing this article on the 10th of December.
If I say I will publish it “in a month from now”, I'm talking about the 10th of January, which is 31 days away.
If today were the 10th of February and I said the same thing, then I'd be saying I would publish this article on the 10th of March, which would be 28 or 29 days away (depending on whether it's a leap year or not).

These “calendar-aware” intervals are supported by Polars and, in particular, the function `polars.date_range` supports them as well:

```py
import datetime as dt
import polars as pl

print(
    pl.date_range(
        start=dt.date(2024, 12, 10),
        end=dt.date(2025, 5, 1),
        interval="1mo",  # 1 month
        eager=True,  # Produce the sequence right away.
    )
)
```
```
shape: (5,)
Series: 'literal' [date]
[
    2024-12-10
    2025-01-10
    2025-02-10
    2025-03-10
    2025-04-10
]
```

As you can see, when Polars uses the 1 month intervals, we get a number of dates that fall on the 10th of December, January, etc.

At the time of writing, Polars supports 5 interval specifiers:

| Interval | Meaning |
| :- | :- |
| `d` | Day |
| `w` | Week |
| `mo`| Month |
| `q` | Quarter |
| `y` | Year |

These can be combined and used together.
For example, `"1mo2d"` means 1 month and 2 days:

```py
import datetime as dt
import polars as pl

print(
    pl.date_range(
        start=dt.date(2024, 12, 10),
        end=dt.date(2025, 5, 1),
        interval="1mo2d",  # 1 month and 2 days.
        eager=True,
    )
)
```
```
shape: (5,)
Series: 'literal' [date]
[
    2024-12-10
    2025-01-12
    2025-02-14
    2025-03-16
    2025-04-18
]
```

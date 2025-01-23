Today I learned that Polars allows non-strict vertical concatenation of dataframes with the parameter `how="vertical"`.

===


# Implicit casting in dataframe concatenation

Polars dataframes have an associated schema, a piece of metadata that describes the columns and their types:

```py
import polars as pl

close_family = pl.DataFrame(
    {
        "name": ["John", "Anne"],
        "age": [27, 35],
    }
)

print(close_family.schema)
# Schema({'name': String, 'age': Int64})
```

By default, Polars uses the type `pl.Int64` when a column contains integers.
However, since ages don't tend to get very big, and because they're never negative, it's enough to use the data type `pl.UInt8`:

```py
extended_family = pl.DataFrame(
    {
        "name": ["Rob", "Jessica"],
        "age": [47, 28],
    },
    schema_overrides={
        "age": pl.UInt8,
    },
)

print(extended_family.schema)
# Schema({'name': String, 'age': UInt8})
```

Now, if I try to use `pl.concat` to concatenate these two vertically, Polars complains because the columns `age` in both dataframes have different types:

```py
pl.concat([close_family, extended_family], how="vertical")
```
```
polars.exceptions.SchemaError: type UInt8 is incompatible with expected type Int64
```

Polars is very strict about data types (and rightfully so) and that is why it complains.
In many situations, you can ask Polars to be more lenient by specifying `strict=False` but `pl.concat` does not support this argument.
Instead, today I learned that it supports `how="relaxed"`[^1]:

```py
pl.concat([close_family, extended_family], how="vertical_relaxed")
```
```
shape: (4, 2)
┌─────────┬─────┐
│ name    ┆ age │
│ ---     ┆ --- │
│ str     ┆ i64 │
╞═════════╪═════╡
│ John    ┆ 27  │
│ Anne    ┆ 35  │
│ Rob     ┆ 47  │
│ Jessica ┆ 28  │
└─────────┴─────┘
```

I don't know for sure, but I'm guessing the reason we have `how="vertical_relaxed"` instead of `strict=False` is because the parameter `strict` is completely irrelevant for the other types of concatenation supported by `pl.concat`, so the Polars devs decided to fold that functionality into the parameter `how`.

[^1]: I was giving a Polars training and a participant taught me this. You learn a lot when you teach!

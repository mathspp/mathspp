Today I learned how to set different thousands separators using string formatting.

===


# Thousands separators

When doing string formatting with big integers, you may want to include thousands separators to make numbers easier to read.
You can add commas, underscores, or a locale-appropriate separator, using the specifiers `,`, `_`, or `n`, respectively:

```py
bignum = 123541241234

print(f"Big money ${x:,}")
# Big money $123,541,241,234


print(f"Big money ${x:_}")
# Big money $123_541_241_234


print(f"Big money ${x:n}")
# Big money $123541241234
```

When you specify that you are printing binary (`b`), octal (`o`), or hexadecimal digits (`x`/`X`), `_` can be used to insert underscores every four digits:

```py
bits = 0b10_0000_1111_0110

print(f"{bits:b}")   # 10000011110110
print(f"{bits:_b}")  # 10_0000_1111_0110
```

```py
hex_value = 0xfa35_de98

print(f"{hex_value:x}")   # fa35de98
print(f"{hex_value:_x}")  # fa35_de98
```

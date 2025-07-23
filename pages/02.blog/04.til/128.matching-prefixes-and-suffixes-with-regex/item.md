Today I learned how to use `\b` and `\B` to match prefixes and suffixes with regex.

===

## Matching prefixes and suffixes with regular expressions

The special characters `\b` and `\B` go hand in hand in regular expressions:

 - `\b` matches at word boundaries; and
 - `\B` matches inside words.

For these two characters, the default “word characters” are alphanumeric characters and the underscore.

By combining `\b` and `\B` at the beginning or end of a pattern, you get to match standalone words, prefixes, suffixes, and infixes!

The table below shows some examples of sentences that all contain the substring `"legal"` along the rows.
The columns show whether different patterns that use the special characters `\b` and `\B` would match against those sentences.

| | `r"legal"` | `r"\blegal\b"` | `r"\blegal\B"` | `r"\Blegal\b"` | `r"\Blegal\B"` |
| - | - | - | - | - | - |
| `"Criticism is legal."` | ✅ | ✅ | ❌ | ❌ | ❌ |
| `"He's legally blind."` | ✅ | ❌ | ✅ | ❌ | ❌ |
| `"Theft is illegal."` | ✅ | ❌ | ❌ | ✅ | ❌ |
| `"He obtained that illegally."` | ✅ | ❌ | ❌ | ❌ | ✅ |

This was the tip 97 I sent to [the Python drops 🐍💧](/drops) newsletter, so if you'd like to get a daily drop of Python knowledge, make sure to [sign-up now](/drops)!

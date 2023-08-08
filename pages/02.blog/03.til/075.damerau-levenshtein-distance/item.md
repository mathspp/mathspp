Today I learned about the Damerau-Levenshtein distance used on strings in the field of genetics.

===


# Damerau-Levenshtein distance

## Intuitive definition

The Damerau-Levenshtein distance is an algorithm that you can use to determine how similar two strings are to each other.
This algorithm works in quite an intuitive way, and I like to think of it like this:

 > The distance between two strings is the number of typos that it takes to go from one to the other.

For example, the DL (Damerau-Levenshtein) distance between the words "bald" and "ball" is 1 because with a single typo (replace the last `l` with a `d`) you can get from one word to the other.

So, the point of the DL distance is to determine what kind of typos are considered, and then we just use some recursion to count how many are needed!
In the DL distance, we consider the following kinds of typos:

 - if you added a letter by mistake, that's one typo (e.g., "ball" and "balll");
 - if you forgot a letter, that's one typo (e.g., "ball" and "bal"); and
 - if you swapped two letters, that's one typo (e.g., "ball" and "blal").

In case you know it, this is similar to the [Levenshtein distance](https://en.wikipedia.org/wiki/Levenshtein_distance), except here we also consider transpositions of characters (swapping two consecutive characters).

Here are some concrete examples, assuming `dl` computes the DL distance:

```pycon
>>> dl("ball", "balll")
1
>>> dl("ball", "bal")
1
>>> dl("ball", "blal")
1
>>> dl("hello", "halo")
2
>>> dl("bananas", "cnnaanas")
3
```


## Recursive definition

The DL distance can be defined recursively in a “straightforward way” once you realise how to translate the math into English (or whatever your native language is!).

This is the math definition that the Wikipedia article shows:

$$
d_{a, b}(i, j) = \min \begin{cases}
0, ~ i = j = 0\\
d_{a, b}(i - 1, j) + 1, ~ i > 0 \\
d_{a, b}(i, j - 1) + 1, ~ j > 0 \\
d_{a, b}(i - 1, j - 1) + 1_{a_i \neq b_j}, ~ i, j > 0 \\
d_{a, b}(i - 2, j - 2) + 1_{a_i \neq b_j}, ~ i, j > 1 ~ \wedge ~ a_i = b_{j-1} ~ \wedge ~ a_{i-1} = b_j
\end{cases}
$$

The $d_{a, b}$ is the function that computes the DL distance between two strings $a$ and $b$ and the $i$ and the $j$ control the length of the prefix we are looking at:
the function works recursively and you drop characters from the end of the strings $a$ and $b$ as you go down.

For example, when $i = 0$, that means you have 0 characters left of the string $a$ and when $j = 0$ that means you have 0 characters left of the string $b$.

The $1_{a_i \neq b_j}$ translates into `int(a[i] != b[j])`, which is 1 if the two characters are different and 0 if they are the same.

We can turn the math into code as seen below.


## Recursive implementation of the Damerau-Levenshtein distance in Python

```py
from functools import lru_cache

@lru_cache
def dl(a, b):
    edit_distances = []

    if len(a) == len(b) == 0:
        edit_distances.append(0)

    if len(a) > 0:
        edit_distances.append(dl(a[:-1], b) + 1)

    if len(b) > 0:
        edit_distances.append(dl(a, b[:-1]) + 1)

    if len(a) > 0 and len(b) > 0:
        edit_distances.append(dl(a[:-1], b[:-1]) + (a[-1] != b[-1]))

    if len(a) > 1 and len(b) > 1 and a[-1] == b[-2] and a[-2] == b[-1]:
        edit_distances.append(dl(a[:-2], b[:-2]) + (a[-1] != b[-1]))


    return min(edit_distances)
```

This code replaces each branch of the mathematical definition with an `if` statement, and we use `edit_distances` to collect all the possible (recursive) values to consider.
Finally, we compute the minimum value and return it!


That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe

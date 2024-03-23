How can you find the longest substring that contains only unique characters?

===

# Longest unique substring

In this article I'll share a coding challenge with you.
Then, I'll analyse different approaches to solving this problem, talking about the advantages and disadvantages of each one in terms of efficiency and code quality.


## Problem statement

! Given a string, find the longest substring that contains only unique characters.
! In case of a tie, return the first one.

Here are some examples to test your code with:

```py
def longest_unique_substring(string):
    ...

lus = longest_unique_substring

assert lus("") == ""
assert lus("abc") == "abc"
assert lus("abca") == "abc"
assert lus("abcad") == "bcad"
assert lus("ababababcda") == "abcd"
assert lus("aaaaaa") == "a"
```


## Naïve solution

The straightforward naïve solution is to use a double loop that checks every possible substring.
Something like the following:

```py
def longest_unique_substring(string):
    longest = ""
    for start in range(len(string)):
        for stop in range(start + 1, len(string) + 1):
            substring = string[start:stop]
            length = stop - start
            if len(set(substring)) == length and length > len(longest):
                longest = substring
    return longest
```

This solution employs a _brute-force_ technique because it checks _every single possible substring_.
If $n$ is the size of the input string, the time complexity of this solution is $O(n^2)$.

We can optimise this solution without changing the underlying approach (the brute-force one) by adding a couple of clever checks.
For example, if we've already found a unique substring of length `l`, we don't need to check the last `l` values of `start`:

```py
def longest_unique_substring(string):
    longest = ""
    len_longest = start = 0  # <--
    while start + len_longest < len(string):  # <--
        for stop in range(start + 1, len(string) + 1):
            substring = string[start:stop]
            length = stop - start
            if len(set(substring)) == length and length > len(longest):
                longest = substring
                len_longest = length  # <--
        start += 1
    return longest
```

Why can you skip the last values of `start`?
Suppose that for a given input we've found a substring of length 5 in the middle of the string.
Regardless of what the characters are, the last 5 characters of the input string cannot contain, inside themselves, a substring that has length 6 or more!
So, we don't need to inspect the last 5 characters of the string.

Another small change comes from reversing the inner loop.
For a given value of `start`, instead of starting with the smallest substring and going up until the end of the input string, we can start with the longest possible substring starting at `start`.
This is better because as soon as we find one unique substring starting at `start` we don't need to look for others, as they will be shorter (because `end` is decreasing):

```py
def longest_unique_substring(string):
    longest = ""
    len_longest = start = 0
    while start + len_longest < len(string):
        for stop in range(len(string), start, -1):  # <--
            substring = string[start:stop]
            length = stop - start
            if len(set(substring)) == length and length > len(longest):
                longest = substring
                len_longest = length
                break  # <-- Avoid looking at smaller values of `stop`.
        start += 1
    return longest
```

_Finally_, we can also say that we don't need to consider values of `end` that are too close to `start`, as those will produce substrings that are shorter than what the currently longest unique substring is.
Hence, we can make yet another change to the second loop:

```py
def longest_unique_substring(string):
    longest = ""
    len_longest = start = 0
    while start + len_longest < len(string):
        for stop in range(len(string), start + len_longest, -1):  # <--
            substring = string[start:stop]
            length = stop - start
            if len(set(substring)) == length and length > len(longest):
                longest = substring
                len_longest = length
                break
        start += 1
    return longest
```

This solution is faster than the original naïve version but it is still quadratic ($O(n^2)$) in the worst case.


## What's the best-case scenario?

In problems like this I often try to figure out what is the best-case scenario in terms of complexity of solutions.
For example, in this problem I'm dealing with a string and I _know_ I have to traverse the string at least once, otherwise I won't be able to know what characters the string has.
In other words, it's impossible to come up with an algorithm that is better than $O(n)$.

After concluding that a problem can't have a solution better than a given complexity, I always try to come up with a solution with the _optimal_ complexity.
In this case, we must think:
“Is there a solution that has $O(n)$ time complexity?”

I [posted this challenge on X / Twitter](https://x.com/mathsppblog/status/1771140279426314350) and at the time I thought I had come up with such a solution.
Now, as I was writing about it, I realised it was wrong! 🤣
Thankfully, people replied with some good linear solutions that we can go over here.


## A first linear solution

For the first optimal solution, we need to think about the following:
If `string[a:b]` has unique characters and `string[a:b + 1]` doesn't, because the character at position `k` is the same as the character at position `b`, then we know that the longest substring will _not_ start in the positions between `a` and `k`.

First, here's a diagram of what I'm describing:

```
|ABCDEFGHIJKLMNOPQ|F
a     k           b
 ^^^^^^
```

In the string above, the slice `string[a:b]` only contains unique characters (the letters from A to Q).
If we look at `string[a:b+1]`, the characters are no longer all unique because the F at the right end of the string is a repetition of the character at position `k`.
This means that we know for a fact that the longest substring will not start at any of the positions marked with `^`.
Give it some thought...

The reason the longest substring won't start at any of the positions marked with a `^` is because such a substring will only be unique up to the point `b`, and because it is starting after the point `a`, it will be shorter than the substring that goes from `a` to `b`.
This means that it is only worth to keep looking after the point `k`.

By using this observation, we can implement a solution that looks like this:

```py
def longest_unique_substring(string):
    longest = ""  # Longest substring found so far.
    substring = ""  # Current substring we're building.
    for char in string:
        if char in substring:
            k = substring.find(char)
            substring = substring[k + 1:]
        substring += char
        if len(substring) > len(longest):
            longest = substring
    return longest
```

The solution works by traversing the string once and building a unique substring.
This is the substring that goes from point `a` to point `b` in the explanation above.
When we find a character that's inside that substring, we give up on that substring and we drop all of the characters to the left of the repeated character.

From an algorithmic point of view, I think this solution is absolutely brilliant.

Before concluding, we'll just consider a slight improvement to this function that is related to the `.find` and the `substring[k + 1:]` operations.
Slicing is considered an expensive operation because it creates a copy of what's being sliced.
For that matter, we'll try to get rid of the slicing.
Along the way, we'll make a couple of adjustments that will also make the code a bit faster.


## Tracking unique characters with a set and a deque

If we put sets and `collections.deque` to good use, we can write a solution that's even faster.
(You can find a [primer on Python sets here](/blog/pydonts/set-and-frozenset) and [a `deque` tutorial here](/blog/python-deque-tutorial).)

Instead of checking for repeated characters with `char in substring`, which is linear in the size of `substring`, we'll use a set to make that operation be constant.
Also, to avoid slicing the substring, we'll keep track of the current substring in a `deque` and when we find a repeated character we just pop characters from the left of the `deque`.

All in all, the revamped solution looks like this:

```py
from collections import deque

def longest_unique_substring(string):
    longest = ""
    substring = deque()
    characters = set()
    for char in string:
        if char in characters:
            # Pop all characters up to the repeated one.
            while (popped := substring.popleft()) != char:
                characters.remove(popped)
        substring.append(char)
        characters.add(char)
        if len(substring) > len(longest):
            longest = "".join(substring)
    return longest
```

The issue, now, is that we're potentially doing too many string concatenations with `"".join(substring)`.
Instead of trying to update `longest` every time we add a character to `substring`, we can try to update the value of `longest` when the size of `substring` is about to get smaller when we remove characters:

```py
from collections import deque

def longest_unique_substring(string):
    longest = ""
    substring = deque()
    characters = set()
    for char in string:
        if len(substring) > len(longest):  # <--
            longest = "".join(substring)   # <--
        if char in characters:
            # Pop all characters up to the repeated one.
            while (popped := substring.popleft()) != char:
                characters.remove(popped)
        substring.append(char)
        characters.add(char)
    return longest
```

However, this solution is wrong...
And that's because we also need to try and update `longest` when the loop ends, in case the longest unique substring is a trailing substring of the input string:

```py
from collections import deque

def longest_unique_substring(string):
    longest = ""
    substring = deque()
    characters = set()
    for char in string:
        if len(substring) > len(longest):
            longest = "".join(substring)
        if char in characters:
            # Pop all characters up to the repeated one.
            while (popped := substring.popleft()) != char:
                characters.remove(popped)
        substring.append(char)
        characters.add(char)
    if len(substring) > len(longest):  # <--
        longest = "".join(substring)   # <--
    return longest
```

This wraps it up!
At the time of writing, this is the best solution I'm aware of.
If you come up with a better solution or if you'd like me to add a different solution to this analysis, feel free to write a comment below.

This approach of solving a single coding challenge and then presenting and analysing different solutions is the core idea of my [problem-solving bootcamp](/pythonbootcamp).
The next edition starts in April, so be sure to [check it out](/pythonbootcamp) and sign up if you want to become a better programmer that writes efficient and idiomatic Python code.

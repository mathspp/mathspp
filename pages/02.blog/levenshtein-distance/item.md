This article explains what the Levenshtein distance is and shows a recursive implementation, an iterative implementation, and a dynamic programming implementation of this algorithm."

===

# What is the Levenshtein distance?

You can think of the Levenshtein distance between two strings as the number of typos it would take to turn one string into the other.
For example, the Levenshtein distance between "house" and "host" is two because you need two typos to turn "house" into "host":

 > "house" → "hose" → "host"

Strictly speaking, the Levenshtein distance is a measure of similarity between two strings:
the larger the value of the Levenshtein distance between two strings, the less similar they are considered.


# How does the Levenshtein distance work?


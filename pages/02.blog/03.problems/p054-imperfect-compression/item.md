Can you show that perfect compression is impossible?

===

![](thumbnail.png "Photo by Rui Matayoshi on Unsplash.")


# Problem statement

Compression is great: it is what lets you take that giant folder you have and reduce its size to save some memory on your laptop.
Of course, you only do these compressions happily because you know you don't lose information when you compress things.
The data is just... compressed!

For compression to be useful, it has to be bidirectional: you must be able to recover the original data from the compressed version.
This is only possible if two different pieces of data never get compressed into the same thing.
(In mathematical terms, we say that the compression must be injective.)

Now, on top of that, we are interested in compression that actually works, right?
That is, in compression that reduces the size of things.
Right?

Right!
Now, the challenge is for you to show that no compression mechanism is perfect.
In other words, show that if a compression mechanism is bidirectional and it manages
to take some pieces of data and transform them into something smaller,
then, there are pieces of data that will become **larger** by the action of the compression mechanism.

If it makes it easier for you,
we can suppose that the data we are talking about are just sequences of letters.
So, we are talking about compression mechanisms that take sequences of letters and try to build smaller
sequences of letters, the compression.
For example, maybe the sequence `aaaaaa` gets compressed into `Aaab`,
but maybe the mechanism fails on `AAAAAA` because it “compresses” it into `arghfewtoen`.

!!! Give it some thought!

If you need any clarification whatsoever, feel free to ask in the comment section below.


# Solvers

Congratulations to the ones that solved this problem correctly and, in particular, to the ones
who sent me their correct solutions:

 - David H., Taiwan;

Know how to solve this?
Join the list of solvers by [emailing me][email] your solution!


# Solution

Let's assume that there _is_ a perfect compression algorithm.
For the empty sequence, what does this algorithm do?
It has to compress the empty sequence into the empty sequence,
because there is no shorter sequence to compress the sequence into.

Now, let's think about sequences of length 1.
None of those can be compressed into the only sequence of length 0,
because there is already a sequence compressed into that (itself).
Thus, all sequences of length 1 must map to other sequences of length 1.
Of course they don't map to sequences of length 2 or greater,
otherwise the _compression_ would actually make the sequences larger.

Therefore, the sequences of length 1 all map to each other,
and no two sequences can map to the same one,
so the compression algorithm really only works as a shuffling of the sequences...

Now, we can just repeat this train of thought indefinitely:
for the sequences of length 2,
none of them can map to sequences of length 0 or 1,
because those are all taken already.
Thus, all sequences of length 2 must map to each other.

By using induction, we can show that this happens for all lengths:
all sequences of length $n$ are mapped within each other,
because all shorter sequences are already taken up.

In practice, this shows that the compression algorithm is not useful,
because it doesn't really _compress_ any sequence at all!
Therefore, there can't be a perfect compression algorithm that is also useful.


[Don't forget to subscribe to the newsletter][subscribe] to get bi-weekly
problems sent straight to your inbox.

[email]: mailto:rodrigo@mathspp.com?subject=Solution%20to%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: /subscribe

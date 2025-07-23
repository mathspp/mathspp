---
date: 06-05-2022
metadata:
    author: Rodrigo Girão Serrão
    description: "Let me show you a real life example of what recursion is and how it works."
    og:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
    twitter:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
slug: finding-a-receipt-recursively
taxonomy:
    category: twitter thread
    tag:
      - programming
      - python
      - recursion
title: "Finding a receipt recursively"
---

<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

Let me show you a real life example of what recursion is and how it works.

===

Recently I tweeted a thread that explained recursion.
In case you missed it, I included it here 👇

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">I have gotten multiple requests to explain recursion.<br><br>Recursion is a maths/computer science concept and it goes beyond Python 🐍, or JavaScript, or whatever language you use.<br><br>Let me explain recursion with an example 👇</p>&mdash; Rodrigo 🐍📝 (@mathsppblog) <a href="https://twitter.com/mathsppblog/status/1518288648961155074?ref_src=twsrc%5Etfw">April 24, 2022</a></blockquote>

But that was mostly a joke.
In this article, I'll try to explain recursion with a real life example.

Let's go 🚀

When people think of recursion, people think of functions that call themselves.
After all, recursion is about self-reference.
Something that is recursive is something that depends on itself.
Therefore, a recursive function will call itself.

But how? And why?

Recursion is useful in _certain_ types of problems.
Recursion is mostly useful when:

 1. you have a big problem;
 2. that big problem can be split into smaller subproblems; and
 3. the smaller subproblems look like the big problem.

But what kind of problem looks like that?

The other day, I had to look for a receipt for something I had purchased.
I didn't remember the address of the store and I needed to go there again.
So, I took my stack of receipts and started looking for it...
But the stack was big and I was in a hurry!
So, what did I do?

Easy:

 1. I split the stack of receipts in half;
 2. I went looking for my girlfriend; and
 3. I gave her half of the receipts and asked for help with those.

The other half, I kept for myself and started looking for the receipt there.
But I was in a hurry!
So, what did I do?

Easy:

 1. I split the stack of receipts in half;
 2. I went looking for my brother; and
 3. I gave him half of the receipts and asked for help with those.

The other half, I kept for myself, and started looking for the receipt there.
But I was in a hurry!
So, what did I do?

Can you guess what I did?
I split the stack in half again and asked someone else to help me.
Then, I took the stack I kept for myself and I looked for the receipt in there.

And this is the key idea!

I started the example with this sentence:

 > “So, I took my stack of receipts and started looking for it...”

And just now, after asking for help a couple of times, I wrote this:

 > “Then, I took the stack I kept for myself and I looked for the receipt in there.”

Right?

But these look like the same task, don't they?
So, why did I ask for help? Was it even worth it?
Yes it was!

Why?

Because now I have a much smaller stack to look through!
So, I have a subproblem (a smaller problem) that looks pretty much like the original problem.

Task: find a specific receipt in a stack of receipts.

Strategy:

 1. split stack in two halves;
 2. give one half to someone and ask for help; and
 3. look for the specific receipt in my stack of receipts.

Notice how the 3rd bullet point of the strategy matches the task.
So, this is a big part of recursion.
If a problem you have can be split into smaller problems, and the smaller problems still look like the original...
That's a good candidate for recursion.
The part where you split the problem into smaller ones is the recursive part.

Why?

Because it's when you start with one problem:
Look for a receipt in a stack of receipts.
And you create multiple problems:
Look for a receipt in the two stacks of receipts.

The other ingredient missing tells you when to stop splitting the stack in half.
In the world of recursion, we call it the base case.
Say I had plenty of family members around and I kept splitting the stack in half.
I split, and split, and split, and suddenly I look at my stack: it's a single receipt.
Well, that's easy to handle, right?
I don't need to split the stack in half again...
I barely even have a _stack_, it's just a single receipt!
I just look at the receipt and check if it's the one I'm looking for.
If it is, good. I found it!
If it's not, I'll wait for my family members to report back to me.

Is this making any sense?
Can you see what I'm getting at?
Let's take this receipt example and translate it to Python 🐍.

My function takes a stack of receipts (a list of receipts) and another receipt and looks for it.
Here it is, with lots of comments:

```py
## Write a function that looks for a receipt.
## Returns True if it finds it, False otherwise.
def find_receipt(stack_of_receipts, receipt):
    # Do we have a stack so small, we can do everything by ourselves?
    if len(stack_of_receipts) == 1:
        # The stack really is small!
        # Is this the receipt we are looking for?
        return stack_of_receipts[0] == receipt

    # The stack is big, so we really need help.
    their_stack_half = stack_of_receipts[:len(stack_of_receipts) // 2]  # First half
    my_stack_half = stack_of_receipts[len(stack_of_receipts) // 2:]  # Second half

    # Ask a family member to look for the receipt in their stack.
    they_found = find_receipt(their_stack_half, receipt)
    # Look for the receipt in my half:
    i_found = find_receipt(my_stack_half, receipt)

    # Did any of us find the receipt?
    return they_found or i_found
```

Does this function work?
Well, we can try it out...

```py
>>> receipts = ["supermarket", "book 1", "book 2", "Python course", "pizza"]
>>> find_receipt(receipts, "something else") 
False
>>> find_receipt(receipts, "book 2")
True
>>> find_receipt(receipts, "supermarket")
True
>>> find_receipt(receipts, "pizza")
True
```

And it does seem like it is working!
Here you go, a recursive function that we built together!
I really like to think that the recursive part is asking someone for help.
Does that analogy make sense to you?

This was a tiny toy example of recursion.
There are many other things to be said about recursion, but I hope this gave you an overview of the concept.

If you enjoyed this thread, follow me [@mathsppblog][mathsppblog]!
Also, [retweet this thread](https://twitter.com/intent/tweet?text=https://twitter.com/mathsppblog/status/1522683659362385922) so others learn too! 😉


## Conclusion

If a problem can be split into subproblems and the subproblems are similar to the original problem...
Then recursion might be appropriate!

Also:

 - Recursion happens when you solve the subproblems.
 - Recursion needs a base case that you write first in the code.


!!!! This article was generated automatically from [this thread](https://twitter.com/mathsppblog/status/1519381134408396800) I published on Twitter [@mathsppblog][mathsppblog].
!!!! Then it was edited lightly.

[mathsppblog]: https://twitter.com/mathsppblog

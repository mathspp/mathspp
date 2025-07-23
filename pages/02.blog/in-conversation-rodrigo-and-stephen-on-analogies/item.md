A conversation between Rodrigo Girão Serrão and Stephen Gruppetta on analogies in programming.

===

!!! This article was a collaboration with [Stephen Gruppetta](https://x.com/s_gruppetta_ct) from [The Python Coding Place](https://thepythoncodingplace.com).
!!! You can also read this article on [Stephen's substack](https://thepythoncodingstack.substack.com/p/in-conversation-rodrigo-and-stephen-on-analogies).


## Discussing Analogies

*Rodrigo and Stephen love to use analogies. They had a virtual chat about this topic. Here's that conversation*

**[Rodrigo]** Stephen and I do a lot of teaching, and both of us also teach Python to people who are entirely new to Python _and_ to programming.

This means that we often need to teach how to _write_ Python code but also how to _think_ in a way that is amenable to programming.

For example, explaining the concept of a function to someone who has never programmed before and who isn't mathematically inclined can be very challenging!

That's why I like resorting to analogies to explain programming concepts and I find myself going back to a couple of analogies frequently.

What about you, Stephen?

How do you feel about analogies and do you have any favourites?



**[Stephen]** There's no disagreement from me on the usefulness of analogies. I need them when I'm learning something myself and I'm convinced they're helpful for everyone who's learning a technical and abstract subject such as programming.

I've been doing a lot of thinking about analogies and other techniques I can use when writing technical articles, and I'm sure I'll be bringing up some of these ideas in this discussion.

But you asked me about my favourite analogy. So let me make sure I don't evade your question.

One of the first analogies I use when teaching is the box analogy to represent a variable. I often find myself telling students that whenever they see the equals sign to assign data, Python brings an empty cardboard box and sticks a label on the box. This label is the _variable name_. Whatever comes to the right of the equals is the object that goes into the box.

**[Rodrigo]** Ah, of course you'd start with the box analogy!

**[Stephen]** It's a classic!

And I like it also as it's great to be able to use the word "object" both in its standard English meaning–the object that goes in the box–and the Python meaning of the word, which is more specific. And this analogy conveys the idea of storing data so we can use it later in the program.

We can also have fun with assigning several names to the same object:

```python
>>> my_name = "Stephen"
>>> author = my_name
```

These are simple lines of code, but there's a lot happening behind the scenes. Explaining that the second line puts a second label on the *same* box helps students picture what's happening. There's only one object, but there are two labels on the box. So you can use either name to refer to the box's contents.

**[Rodrigo]** One thing that I like about analogies is that you can try to extend them!

For example, I had never thought about using the box/label analogy to explain that the assignment you showed just adds a second label to the same box.

When working with strings, it may be difficult to see this in action because strings are immutable, but the typical list example always blows the minds of my students:

```py
## Create a box called "first_list" and store three numbers there.
>>> first_list = [1, 2, 3]

## Create a second box called "second_list" with the same elements.
>>> second_list = first_list

## Modify the contents of the first box:
>>> first_list.append(4)
>>> first_list[0] = 999

## What?! The second box also changed?
>>> second_list
[999, 2, 3, 4]
```

**[Stephen]** That's such a great party trick: *The Vanishing Item* trick, now you see it, now you don't!

**[Rodrigo]** By using your idea of putting a second label on the same box, it becomes "obvious" that `second_list` actually points to the same list that `first_list` points to. (In reality, there are no obvious things when you are teaching, but that's another story...)

I'll probably use this extension in a class I'm teaching now.

Which allows me to segue to something I really wanted to bring up!

Whenever I teach programming to beginners, I always have this internal debate about whether I should bother them with programming style standards, like leaving spaces around operators or writing lists cleanly as `[1, 2, 3, 4]` instead of `[   1,         2,3,4]`.

I always end up mentioning this, but I don't bother them too much with it...

However, with the box analogy, I have the perfect excuse to tell my students about the importance of naming variables correctly!

If you pack a bunch of books in a box and then just label the box with "BOX", in a couple of months you'll forget what's inside the box.

**[Stephen]** We've all done that at some point when moving house!

**[Rodrigo]** I'm ashamed to admit I do it way too often! I have a box in my office and I have no idea what's inside because I labelled it – you guessed it – as "stuff"...

But what's even worse, and this is a crime I haven't committed, if you pack a bunch of different things and label all your boxes with "BOX1", "BOX2", "BOX3", etc., then you'll have a really hard time finding the things that you need when you need them!

Hence the importance of having good variable names!

With this approach, I can typically get my students to use better variable names than just `variable`, `string`, or `integer`!


**[Stephen]** I also teach Python coding to children and we use the `turtle` module a lot. If I had a penny (or cent) for each time I see `turtle1` and `turtle2` and so on, I'd be able to retire early!

There was a time when I would distinguish the _type_ of box depending on the data type when teaching. A list could be one of those boxes with built-in dividers to store paper, say. And a dictionary could be a box file with labelled dividers. However, I no longer do this for a couple of reasons.

Firstly, it makes the analogy more complex, and it requires the learner to work harder to think of what type of box we're dealing with. And I think that for an analogy to work, it needs to be easy for the reader to visualise. If they have to work hard to understand the analogy, it may not serve its purpose.

Secondly, I didn't like the fact that this extension to the box analogy combines the "storage box" with the "item" stored in the box. I now prefer to keep them separate:

- The item stored _in_ the box is the object (in the Python sense of the word)
- The box then represents the variable name

Recently, I was thinking about the components that make up a good analogy. It's not always that easy to come up with effective analogies. The situation you use must be one that most people easily understand, and it should be a reasonably good match to the concepts you're describing. I mused a bit more about this in [Whizzing Through Wormholes](https://breakingtherules.substack.com/p/whizzing-through-wormholes-ep-2) a few weeks ago.

But no analogy is perfect. And when it comes to extending analogies, there will be a point when they'll break. I love when students have thought about the problem in detail and then break your analogy–it's a sign they've understood it. I had some students point out a scenario similar to this one:

```python
>>> the_rs_team = ["Rodrigo", "Stephen"]  # I chose two random names, of course!
>>> another_team = ["Fred", "Mary"]
>>> one_more_team = ["Bob", "Kate"]

>>> first_game = [the_rs_team, another_team]
>>> second_game = [the rs_team, one_more_team]
```

The object `the_rs_team`, which is a list, is contained in both `first_game` and `second_game`. The box analogy tells us there's a box labelled `first_game` and it contains two items, one of which is `the_rs_team`. But `second_game` is another box and it also contains `the_rs_team`. How can the same item be in two boxes at once (unless we're considering the quantum world)?

The analogy _could_ lead someone to think there are two copies of the list `the_rs_team`, one in each box, but that's not the case.

I have found myself telling students that what we really have in the box is a sheet of paper telling us where to find the item in the store room. But this is also the point when it's best to abandon the analogy, I think! Knowing when to quit is important with analogies. I'll often use my [room analogy, which I wrote about recently](https://thepythoncodingstack.substack.com/p/monty-and-the-white-room-python-analogy), and that's another example of an analogy with many layers and extensions, but which eventually breaks down if you go too deep.

But how about you Rodrigo, what's *your* favourite analogy?

**[Rodrigo]** That's interesting, I never considered using the box analogy to explain something like the list of lists example you showed.

When I talk about this topic, I usually use a different analogy related to libraries and whether you're carrying a book around or a piece of paper that tells you the aisle/bookshelf a book is in.
But this is not my favourite analogy.

My favourite analogy of all is actually a maths analogy which doesn't have a lot to do with programming at first sight.

It has to do with what we call a "parametrisation" in maths and it is an "advanced" mathematical concept.
However, I've been successful in explaining the gist of it to people with no mathematical background at all, which proves that analogies can be really, really useful.

I'm not sure if we're interested in going down that road but I touched on that analogy in an article I wrote recently about [creating animations from first principles](/blog/animations-from-first-principles-in-5-minutes), so maybe we can leave it at that.

**[Stephen]** We could go on for a long time talking about analogies. I know you and I both share a fondness for the "coffee machine" analogy for functions, too. But perhaps we can talk about that and some other analogies on another day so that our readers can get on with their day for now!

How about our readers? What's your favourite programming analogy?


!!! This article was a collaboration with [Stephen Gruppetta](https://x.com/s_gruppetta_ct) from [The Python Coding Place](https://thepythoncodingplace.com).
!!! You can also read this article on [Stephen's substack](https://thepythoncodingstack.substack.com/p/in-conversation-rodrigo-and-stephen-on-analogies).

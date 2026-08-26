Learn the fundamental principles behind OOP and how they connect to the syntax of Python.

===

## Introduction

Welcome!
This article will teach you the core ideas behind **object-oriented programming**, commonly known as **OOP**.
This is the article I wish I read many years ago, when I was first learning about OOP in Python.

If you're new to OOP, this article will explain why OOP exists, how it works, and how to work with OOP in Python.
If you think you already know OOP, this article will change the way you think about programming and Python.

## Code is about real-world things

Let me tell you about a programming project I had to do in college where you had to write a library management service.
We were expected to work in pairs and I was paired with my good friend Tito.

Tito and I sat down and started going through the problem statement, figuring out what we needed to implement.
We got to a point where Tito turned to me and said:

 > “It's not obvious to me what's the best way to **represent a *book*** in our program.
 > Maybe you can start implementing the search functionality and I'll think about this for a while.”

The *search functionality* was a set of functions that the problem statement required us to implement:

 - `find_by_title(catalog, search_term)`: returns a sublist with the books whose title contains the given search term
 - `find_by_genre(catalog, genre)`: returns a sublist with the books of the given genre
 - `find_by_author(catalog, author)`: returns a sublist with the books written by the given author

I nodded, but then I thought about it for a second.
If I don't know *anything* about how to work with books, there's no way I can implement these three functions.
Tito agreed with me and told me he'd provide me with these functions:

 - `book_title(book)`: returns the title of the given book
 - `book_genre(book)`: returns the genre of the given book
 - `book_author(book)`: returns the author of the given book

He told me to think of these functions as auxiliary functions that he would implement.
I didn't have them *yet*, but I could write my search functions *trusting* he'd implement them correctly.

!!! In OOP, you have entities with associated data (books with authors, titles, and genres) and a set of functions to operate on those entities (the functions `find_by_xxx`).

Now, think about it for a second.
Can *you* implement the functions `find_by_title`, `find_by_genre`, and `find_by_author`, using the auxiliary functions that Tito will implement?
How would you go about it?

I worked on it for a bit, and eventually used [a list comprehension](/blog/pydonts/list-comprehensions-101) to define the function `find_by_title`:

```python
def find_by_title(catalog, search_term):
    search_term = search_term.casefold()
    return [
        book
        for book in catalog
        if search_term in book_title(book).casefold()
    ]
```

The function `find_by_title` goes through the list of books called `catalog` with a loop and uses the auxiliary function `book_title` to retrieve the title.
It then uses [`casefold` to perform a case-insensitive search](/blog/how-to-work-with-case-insensitive-strings).

Something worth noting here is that the loop uses the variable name `book` for the elements of the list `catalog`, even though I don't know what these “books” look like!
But if Tito does his job well, the piece of code `book_title(book)` will return the book title and my code will work.

I also implemented the other two functions following the same pattern:

```python
def find_by_genre(catalog, genre):
    return [
        book
        for book in catalog
        if genre == book_genre(book)
    ]

def find_by_author(catalog, author):
    return [
        book
        for book in catalog
        if author == book_author(book)
    ]
```

I finished my part of the assignment and I pinged Tito to see whether he'd made any progress.

This assignment, the way Tito and I split our work up, and the way I wrote my part, all highlight the same thing: **code is about real-world things**.
We're writing a library management system, so we need to be able to _think about books_, authors, and other related entities.

When we're reading the code for our library management system, we want to be able to _reason about the high-level, real-world entities_ that our code is about, like the books.
That's why Tito and I decided to create an **abstraction** that represents a _book_.
This simplifies my part of the code greatly.

To let the rest of the program use the abstraction, we just need a few functions that expose useful operations.
For example, Tito created the three functions `book_xxx` because he realised I'd need to access book information.
These three functions _hide the low-level details_ of how a book is implemented.

!!! The core idea of OOP is that you create high-level abstractions for the _entities_ that you care about to make it easier to work with them.

## The O stands for Objects

Tito worked on his representation of a book for a while and then he came back.
He started by saying “hey Rodrigo, I realised we also need a function `book_initialise(author, title, genre)` that accepts three strings and creates the representation I came up with”.
Then, he showed me his four functions:

```python
def book_initialise(author, title, genre):
    la = list(map(ord, author))
    lt = list(map(ord, title))
    lg = list(map(ord, genre))
    return ((len(la), len(la) + len(lt)), la + lt + lg)

def book_author(book):
    return "".join(map(chr, book[1][:book[0][0]]))

def book_title(book):
    return "".join(map(chr, book[1][book[0][0]:book[0][1]]))

def book_genre(book):
    return "".join(map(chr, book[1][book[0][1]:]))
```

I was a bit surprised by Tito's code, but the truth is that it worked just fine:

```python
book = book_initialise("Charles Dickens", "Oliver Twist", "novel")
print(book_author(book))  # Charles Dickens
print(book_title(book))   # Oliver Twist
print(book_genre(book))   # novel
```

Still flabbergasted, I looked at Tito and wrote one more `print`:

```python
print(book)
# ((15, 27), [67, 104, 97, 114, 108, 101, 115, 32, 68, 105, 99, 107, 101, 110, 115, 79, 108, 105, 118, 101, 114, 32, 84, 119, 105, 115, 116, 110, 111, 118, 101, 108])
```

“Tito, what the heck is this?
When I print this book, all I see is numbers!”

“That's because I thought it made sense to represent a book as a sequence of Unicode code points stored along with the cumulative lengths of the individual fields.
But you don't have to worry about it, since the auxiliary functions `book_xxx` are the ones that represent the interface that users should interact with.”

And while the internal representation of a book looked a bit weird, Tito was right about one thing.
Since his auxiliary functions `book_xxx` were working, so were the `find_xxx` functions I wrote earlier:

```python
catalog = [
    book_initialise("Charles Dickens", "A Christmas Carol", "novella"),
    book_initialise("Charles Dickens", "Oliver Twist", "novel"),
    book_initialise("Charles Dickens", "Great Expectations", "novel"),
]

for book in find_by_title(catalog, "ol"):
    print(book_title(book))
# A Christmas Carol
# Oliver Twist
```

Once we finished working on books, we started working on **authors**.
As it turns out, it's not enough to have a string with the author's name.
An author depends on three pieces of information:

 1. first name
 2. last name
 3. birth year

On top of that, there are a number of functions associated with authors:

 - `author_initialise(first_name, last_name, birth_year)`: builds the representation of an author based on the data provided
 - `author_name(author)`: returns the full name of the author
 - `author_birth_year(author)`: returns the author's birth year
 - `author_age(author)`: returns the current age of the author

The funky book representation with Unicode code points and lengths is a bit contrived, so you can pick something more natural for an author.
You can use a 3-element tuple to represent an author, as shown by the function `author_initialise` defined below:

```py
def author_initialise(first_name, last_name, birth_year):
    return (first_name, last_name, birth_year)
```

If you think about the function `author_initialise` as a function that _just creates a tuple with its arguments_, the function will look quite useless.
But that's not the power of the function `author_initialise`.
This function is useful because it accepts three arguments and _returns an author_.
How the author is represented is an implementation detail.

At this point, it's clear that it's very important to be able to work with the **objects** that you create with your `xxx_initialise` functions.

!!! The concrete values of the high-level abstractions you work with in OOP are called **objects**.

After implementing `author_initialise`, all other functions follow:

```py
def author_name(author):
    return f"{author[0]} {author[1]}

def author_birth_year(author):
    return author[2]

def author_age(author):
    # Gross simplification, but bear with me:
    return 2026 - author_birth_year(author)
```

Note that the first three functions have to access the internal representation of the author to be able to extract the most basic pieces of information.
If you change how an author is represented, you have to change the functions `author_name` and `author_birth_year`.
On the other hand, the function `author_age` builds on the most basic functions, and thus `author_age` is _independent_ of the internal representation of an author.

But now, as your programs grow and you start creating collections of functions to represent and work with different types of entities, like books and authors, you'll be riddling your programs with various functions with prefixes to help you organise them.
The book-related functions are all called `book_xxx`, the author-related functions are all called `author_xxx`, and functions for other types of entities will likely follow a similar pattern.

Many languages provide syntactic features that make it easier to work with OOP and these objects.
You're now going to learn what features Python provides and you'll start by learning how to organise object-related functions.

## Organising functions with namespaces

By now, it should be clear that it's useful to have functions to represent and manipulate real-world entities in your code.
If you imagine for a second that OOP doesn't exist yet, you can see the value in structuring your programs around these objects, the representations of real-world entities like books and authors.

So far, the only way to organise your functions was by giving them a shared prefix, such as `author_xxx`.
But this prefix only creates a loose grouping, so the Python language provides a better way to do that.

If you use the keyword `class`, you can create a Python **class**.
And the reason that's relevant is because a class provides a **namespace** for your functions.
As the term implies, a **namespace** is a **space** for your **names**.
Functions have names, so you can put them inside a dedicated namespace to group them together.

How do you group functions under the namespace of a class?

Take the functions for the author as an example.
To create a namespace for the functions related to authors, you're going to

1. use the keyword `class`
2. follow it with a class name (for example, `Author`)
3. add a colon to indicate the start of the class definition
4. indent the collection of names inside the class

In code, this means you just have to take your four original `author` functions and precede them with this line of code:

```py
class Author:
    # The five author functions go here.
```

In Python, it's common to use [PascalCase](https://en.wikipedia.org/wiki/Camel_case#Description) for class names, just to help differentiate them from other types of variables.
That's why you're using the name `Author` and not `author`.
Since Python uses indentation, you must indent the functions after using the keyword `class`:

```py
class Author:
    def author_initialise(first_name, last_name, birth_year):
        return (first_name, last_name, birth_year)

    def author_name(author):
        return f"{author[0]} {author[1]}"

    def author_birth_year(author):
        return author[2]

    def author_age(author):
        # Gross simplification, but bear with me:
        return 2026 - author_birth_year(author)
```

This piece of code creates the class `Author`, and the indentation means the functions defined under it are part of the `Author` class namespace.
The class provides _a natural home for all of your author-related functions_, making it easier to:

 - determine where to add a new author-related function, if you create one;
 - find all author-related functions to see what's available; and
 - access the functions to use them.

To use a function inside a class namespace, you use the class name, followed by a dot (`.`), followed by the name you want to access.
For example, to create an object to represent Charles Dickens and then print his name you can write the following:

```py
charles = Author.author_initialise("Charles", "Dickens", 1812)
print(Author.author_name(charles))  # Charles Dickens
```

Looking at the two lines of code above shows that there's some naming redundancy.
Since the class `Author` already provides a clear umbrella for all author-related functions, the functions themselves can drop the `author_` prefix from their names.
This clears up the implementation of `Author` a bit.
The functions themselves don't change, but their names do:

```py
class Author:
    def initialise(first_name, last_name, birth_year): ...

    def name(author): ...

    def birth_year(author): ...

    def age(author): ...
```

The calls to these functions must be updated to reflect the new, shorter name:

```py
charles = Author.initialise("Charles", "Dickens", 1812)
print(Author.name(charles))  # Charles Dickens
```

This code is only slightly shorter, but removing the redundancy makes it clearer.

You also need to update the function `age` to use the function `birth_year` through the namespace:

```py
class Author:
    # ...

    def author_age(author):
        return 2026 - Author.birth_year(author)
```

Up until this point, there is only one advantage to using the keyword `class`: the class becomes the _obvious home_ for all your author-related functions.
But this is **a key advantage** that enables everything that you're going to learn about next.

## Using standard representations for objects

Earlier in this article, you represented books with sequences of code points and you represented authors with tuples.
While you and I agree that representing a book as the sequence of code points for the concatenation of the strings associated with the book, along with the cumulative lengths of the concatenated fields[^1], is absolutely brilliant, not everyone will agree.
You might be persuaded to change the representation of a book to a tuple as well, especially since an author is no longer just a name string, but an _object_ in and of itself.

[^1]: Try saying that fast, three times in a row.

But using tuples to represent objects opens another can of worms, because all tuples look alike.
You're allowed to look at a tuple and say “this tuple represents the author Charles Dickens”, but that's a convention that you might forget about or that other parts of the code may never honour.

Instead of having to use a tuple or some other collection, Python provides a way of creating a standard representation for each type of object.
These standard representations are plain and highly flexible, but they're also different from one another so you'll never mistake a book for an author.

Since the class `Author` already contains everything that is author-related, wouldn't it be wonderful if you could automatically use the class to create dedicated **author objects**?
And that's what Python does if you call the class `Author` as if it were a function:

```py
author = Author()
print(author)  # <__main__.Author object at 0x10898ef90>
```

If you call `Author()` and print it, you'll see a lot of gibberish but you'll also see the words “Author object”.
When you create a class, Python allows you to create **objects** that are tightly connected to that class and its namespace.

You can think of the object `author` as a plain value that is suitable to represent an author.
But when you run the code `Author()`, the object created doesn't know anything about any particular author.
You still have to customise your author with the relevant data, just like when you created the three-element tuple with the first name, last name, and birth year.

To be able to customise your author, you need to tweak your function `Author.initialise`.
The function no longer needs to return a tuple.
Instead, it _accepts an author as an argument_ and then _attaches the data to the author_, as the code shows:

```py
class Author:
    def initialise(
        author,  # <-- new argument
        first_name,
        last_name,
        birth_year,
    ):
        author.first_name = first_name
        author.last_name = last_name
        author.birth_year = birth_year
        return author
```

Using the dot (`.`) to access functions from a class namespace was a brilliant idea, so you can use the same notation to attach data directly to an author.
In a way, it's as if the object `author` was acting as a namespace for its own data.

Here's how you can create an author with your new trick:

```py
charles = Author()
charles = Author.initialise(charles, "Charles", "Dickens", 1812)
```

The first line of code creates the plain object for a new author, and then the second line customises it with the relevant data.
It is very common for objects to have associated data that is fundamental to that object.
Because “data associated with my object” is quite a mouthful, you can use the term **attribute** instead.

!!! An **attribute** is a piece of data associated with an object that you access with the dot notation.

The function `Author.initialise`, defined above, creates three attributes: `author.first_name`, `author.last_name`, and `author.birth_year`.

There's just one final tweak that you must make to your function `Author.initialise`.
When you create an attribute, you're _mutating_ the original plain object, which means you don't need to return anything from the function since the object that's passed in will automatically reflect the changes you make:

```py
class Author:
    def initialise(
        author,  # <-- new argument
        first_name,
        last_name,
        birth_year,
    ):
        author.first_name = first_name
        author.last_name = last_name
        author.birth_year = birth_year
        # !!! No return!
```

To verify that this works, you can create an author, initialise it with its attributes, and then print an attribute outside of the function `Author.initialise`.
To do so, use the dot notation to access the attribute:

```py
charles = Author()
Author.initialise(charles, "Charles", "Dickens", 1812)
print(charles.birth_year)  # 1812
```

But now that you have **attributes** and the dot notation to access them, the implementation of your namespace `Author` can be simplified.
The function `Author.birth_year` became redundant and the functions `Author.name` and `Author.age` should use the attributes directly:

```py
class Author:
    def initialise(author, first_name, last_name, birth_year):
        author.first_name = first_name
        author.last_name = last_name
        author.birth_year = birth_year

    def name(author):
        return f"{author.first_name} {author.last_name}"

    # def birth_year(author): removed!

    def age(author):
        return 2026 - author.birth_year
```

Having attributes also means that the code that uses them becomes easier to read.
Take the function `name`, for example: the f-string `f"{author.first_name} {author.last_name}"` is much clearer than `f"{author[1]} {author[2]}"`.
Additionally, using named attributes makes the code less prone to bugs.
If the names of the attributes change, the code stops working and you get an exception telling you that you tried using an attribute that doesn't exist.
With the tuple, there are _many_[^2] different ways in which you can introduce bugs to your code that make a valid f-string that returns the wrong value.
In fact, I bet you didn't even notice that the f-string is wrong, since it should've been `author[0]` (index zero) and `author[1]` (index one) instead of `author[1]` and `author[2]`.

[^2]: Off the top of my head I can easily think of four or five.

## Simplifying the use of the class namespace

You've come a long way, but Python provides more niceties for working in an OOP context.
These niceties are independent of the core OOP idea, representing real-world entities, associating data with them, and manipulating them through a set of functions.

Take a look at the snippet of code that follows, which creates an object that represents Charles Dickens and then uses some of the functions associated with the namespace of `Author`:

```py
charles = Author()
Author.initialise(charles, "Charles", "Dickens", 1812)
print(Author.name(charles))  # Charles Dickens
print(Author.age(charles))  # 214
```

If you can look past the fact that Charles Dickens can't be 214 years old, you may be able to notice something else.
The four lines of code shown above are filled with redundancy:

 1. On line 1, you set the variable `charles` to be an object that represents _an author_ by using the namespace of `Author`.
 2. On line 2, you want to pass the variable `charles` (an author) to a function from the namespace of `Author`.
 3. On line 3, you want to pass the variable `charles` to another function from the namespace of `Author`.
 4. On line 4, you want to pass the variable `charles` to _yet_ another function from the namespace of `Author`.

Every single line is tied to the namespace of `Author`.
But as soon as the variable `charles` is set to be an object created by `Author()`, both you and Python know that `charles` is related to the class `Author`.
Thus, to make it easier to work with the functions from the namespace of the class `Author`, _you can access them directly from the object itself_.

You use the exact same dot notation, but you don't have to prefix every function call with `Author` since it's already implied that that's the class namespace you care about.
Instead, you use the object itself.
But it'd be a bit silly if you had to write the object twice:

```py
print(charles.name(charles))  # A bit silly...
```

That's why Python allows you to _omit_ the object from the function arguments _when calling the function_:

```py
charles = Author()
charles.initialise("Charles", "Dickens", 1812)
print(charles.name())  # Charles Dickens
print(charles.age())  # 214
```

Removing the extra reference to the class `Author` allows you to simplify your code, making it easier to read and process.

The functions that you access directly from objects are the object's **methods**, and these **methods** are the ones that you define inside your class `Author`.

!!! **Methods** are the functions defined inside a class that you access directly from an object of that class.

Note that the implementation of the class `Author` _remains exactly the same_.
In particular, the functions defined inside `Author` _still take `author` as the first argument_.
The only thing that changes is that you no longer need to _explicitly_ pass that argument when calling the function:

```py
# The implementation of Author remains THE SAME.
class Author:
    #              vvvvvv still listed here
    def initialise(author, first_name, last_name, birth_year):
        author.first_name = first_name
        author.last_name = last_name
        author.birth_year = birth_year

    # ...

charles = Author()
#                 v not passed explicitly here
charles.initialise("Charles", "Dickens", 1812)
```

Using the dot notation to access methods from an object make the parameter counts look inconsistent.
`Author.initialise` is defined with four parameters, but the call `charles.initialise` accepts only three arguments because Python supplies `charles` as the first argument.

But wait, there's more.

## Initialising objects automatically

Consider the two lines of code you've been using over and over again to create objects that represent Charles Dickens:

```py
charles = Author()
charles.initialise("Charles", "Dickens", 1812)
```

These two lines of code always go hand in hand.
When you create an object to represent an author, you'll want to customise it with the attributes associated with that object.
So, wouldn't it be nice if you could combine the two operations?

Python has a mechanism that allows you to create an object and immediately customise it with the data that you care about.
In Python, you can pass the arguments for `initialise` directly to the class `Author`:

```py
charles = Author("Charles", "Dickens", 1812)
```

But when you do so, how does Python know which arguments go to which attributes?
It _doesn't_.
Instead, Python runs your function `initialise` automatically.
Well, almost.

When you use a class to create an object, Python will _look_ for an initialiser function with a very specific name: `__init__`.
If there is a function inside the class `Author` that is called `__init__`, Python will call it for you.
Thankfully, you already defined an initialiser function, so you just have to rename it:

```py
class Author:
    #   vvvvvvvv used to be called `initialise`
    def __init__(author, first_name, last_name, birth_year):
        author.first_name = first_name
        author.last_name = last_name
        author.birth_year = birth_year

    def name(author):
        return f"{author.first_name} {author.last_name}"

    def age(author):
        return 2026 - author.birth_year
```

By renaming your function `Author.initialise` to `Author.__init__`, you can now create and customise author objects in a single go:

```py
charles = Author("Charles", "Dickens", 1812)
```

Everything else remains the same, including how you can access attributes or use methods:

```py
print(charles.birth_year)  # 1812
print(charles.name())  # Charles Dickens
```

Note that the name `__init__` has nothing special about it.
It's Python that _chose_ to use `__init__` for the name of the function that gets called automatically to initialise objects.
It could've picked `initialise`, `init`, `customise`, or anything else.

OOP is at the core of Python, and Python has many more special methods that allow you to create more flexible objects.
Now, imagine you're the creator of Python.
You want to create a consistent naming convention for all of these [special methods](/blog/pydonts/dunder-methods) _and_ you also have to avoid using names that other developers could naturally pick for their own methods, to avoid any accidental name clashes.
As a solution, you decide to surround every special method name with two underscores, hence `__init__`.

The train of thought that took you up to this point reveals two interesting things about the method `__init__`:

 1. `__init__` is a regular method, just like any other, which means you _can_ call it directly if you want.
 2. The method `__init__` doesn't _create_ the object; it just customises it.

The only thing that's different about `Author.__init__`, when compared to methods like `Author.name`, `Author.age`, or your old `Author.initialise`, is that Python has rules to call `Author.__init__` automatically in certain situations.

## The first argument is `self`

You've been exposed to the core ideas of OOP and how they could've evolved naturally into the syntax that Python provides for you to write OOP code.
All that's left is learning about a Python convention _so common_ it's almost a rule.
When you define functions inside a class, the first parameter should be called `self`.

If you recall, the class `Author` used the name `author` for the first parameter of each function.
If you created a class `Book`, you'd use the name `book` for the first parameter of each function in that class.
Python streamlines all of this.
Instead of using an arbitrary name, you use `self`.
Following Python's conventions, your class `Author` now becomes:

```py
class Author:
    def __init__(self, first_name, last_name, birth_year):
        self.first_name = first_name
        self.last_name = last_name
        self.birth_year = birth_year

    def name(self):
        return f"{self.first_name} {self.last_name}"

    def age(self):
        return 2026 - self.birth_year
```

If the first parameter of every function is called `self`, then you must also update the bodies of the functions to use the dot notation on the argument `self` instead of the argument `author`.

You can think of the name “self” as referring to “the object that belongs to the class itself”.

## Summary

By going through this article, you should've learned a couple of key things about OOP and how to use OOP in Python:

 - OOP is, first and foremost, about creating **objects**: high-level abstractions that make it easier to reason about your code
 - objects in OOP have associated data and functions to operate on the data
 - the data associated with an object is called an **attribute** in Python
 - a **method** is a function defined inside a class that's accessed directly from an object of that class

If you'd like to learn more advanced OOP Python features, you may want to learn about [dunder methods](/blog/pydonts/dunder-methods), [properties](/blog/pydonts/properties), or [descriptors](/blog/pydonts/describing-descriptors).

If you or your team would like to improve your OOP fluency, reach out.
I offer [corporate Python trainings](/trainings).

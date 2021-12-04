When you call a function in Python and give it some arguments...
Are they passed by value? No!
By reference? No!
They're passed by assignment.

===

<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

![Python snippet containing the code `x is y`.](thumbnail.png)

(If you are new here and have no idea what a Pydon't is, you may want to read the
[Pydon't Manifesto][manifesto].)


# Introduction

Many traditional programming languages employ either one of two models
when passing arguments to functions:

 - some languages use the pass-by-value model; and
 - most of the others use the pass-by-reference model.

Having said that, it is important to know the model that Python uses,
because that influences the way your code behaves.

In this Pydon't, you will:

 - see that Python doesn't use the pass-by-value nor the pass-by-reference models;
 - understand that Python uses a pass-by-assignment model;
 - learn about the built-in function `id`;
 - create a better understanding for the Python object model;
 - realise that every object has 3 very important properties that define it;
 - understand the difference between mutable and immutable objects;
 - learn the difference between shallow and deep copies; and
 - learn how to use the module `copy` to do both types of object copies.

<!--v-->
!!! You can now get your **free** copy of the ebook “Pydon'ts – Write beautiful Python code” [on Gumroad][gumroad-pydonts].
<!--^-->


# Is Python pass-by-value?

In the pass-by-value model, when you call a function with a set of arguments,
the data is copied into the function.
This means that you can modify the arguments however you please and
that you won't be able to alter the state of the program
outside the function.
This is not what Python does, Python does not use the pass-by-value model.

Looking at the snippet of code that follows,
it might look like Python uses pass-by-value:

```py
def foo(x):
    x = 4

a = 3
foo(a)
print(a)
# 3
```

This looks like the pass-by-value model because we gave it a 3,
changed it to a 4,
and the change wasn't reflected on the outside
(`a` is still 3).

But, in fact, Python is not _copying_ the data into the function.

To prove this, I'll show you a different function:

```py
def clearly_not_pass_by_value(my_list):
    my_list[0] = 42

l = [1, 2, 3]
clearly_not_pass_by_value(l)
print(l)
# [42, 2, 3]
```

As we can see, the list `l`, that was defined outside of the function,
changed after calling the function `clearly_not_pass_by_value`.
Hence, Python does not use a pass-by-value model.


# Is Python pass-by-reference?

In a true pass-by-reference model,
the called function gets access to the variables of the callee!
Sometimes, it can _look_ like that's what Python does,
but Python does not use the pass-by-reference model.

I'll do my best to explain why that's not what Python does:

```py
def not_pass_by_reference(my_list):
    my_list = [42, 73, 0]

l = [1, 2, 3]
not_pass_by_reference(l)
print(l)
# [1, 2, 3]
```

If Python used a pass-by-reference model,
the function would've managed to completely change the value of `l`
outside the function, but that's not what happened, as we can see.

Let me show you an actual pass-by-reference situation.

Here's some Pascal code:

```pas
program callByReference;
var
    x: integer;

procedure foo(var a: integer);
{ create a procedure called `foo` }
begin
    a := 6            { assign 6 to `a` }
end;

begin
    x := 2;           { assign 2 to `x` }
    writeln(x);       { print `x` }
    foo(x);           { call `foo` with `x` }
    writeln(x);       { print `x` }
end.
```

Look at the last lines of that code:

 - we assign `2` to `x` with `x := 2`;
 - we print `x`;
 - we call `foo` with `x` as argument; and
 - we print `x` again.

What's the output of this program?

I imagine that most of you won't have a Pascal interpreter lying around,
so you can just go to tio.run and
[run this code online](https://tio.run/##lY8xEoIwEEX7nGJLKLSgsCBj4xE8AWtYMDMYmCVKHMaz4wYdoLBxy7/vbX467A02u6oz09RxWzPeQILm9DxTRUzOkFYPZAUyIQfrPNXEWimhDZV3JqjaNhEEcFmnWo1gmNATIKxkvEwlFGIU8FIXqq2bLyPkRzjAZkbAvre1k9S3UGDkyZXy8GqFaGX6l5XNVohWzAe2nhqXhFQvZMfSdsPEb6z7DxMLf@sO1l//uChd99P0Bg)

If you run this, you'll see that the output is

```
2
6
```

which can be rather surprising,
if the majority of your programming experience is in Python!

The procedure `foo` effectively received the variable `x`
and changed the value that it contained.
After `foo` was done, the variable `x` (that lives outside `foo`)
had a different value.
You can't do anything like this in Python.


# Python object model

To really understand the way Python behaves when calling functions,
it's best if we first understand what Python objects are,
and how to characterise them.


## The three characteristics of objects

In Python, _everything_ is an object, and each object is characterised
by three things:

 - its identity (an integer that uniquely identifies the object,
 much like social security numbers identify people);
 - a type (that identifies the operations you can do with your object); and
 - the object's content.

Here is an object and its three characteristics:

```py
>>> id(obj)
2698212637504       # the identity of `obj`
>>> type(obj)
<class 'list'>      # the type of `obj`
>>> obj
[1, 2, 3]           # the contents of `obj`
```

As we can see above, `id` is the built-in function you use to query the identity of an object,
and `type` is the built-in function you use to query the type of an object.


## (Im)mutability

The (im)mutability of an object depends on its type.
In other words, (im)mutability is a characteristic of types,
not of specific objects!

But what _exactly_ does it mean for an object to be mutable?
Or for an object to be immutable?

Recall that an object is characterised by its identity, its type, and its contents.
A type is mutable if you can change the contents of its objects without changing its
identity and its type.

Lists are a great example of a mutable data type.
Why?
Because lists are _containers_: you can put things inside lists
and you can remove stuff from inside those same lists.

Below, you can see how the contents of the list `obj` change
as we make method calls, but the identity of the list remains the same:

```py
>>> obj = []
>>> id(obj)
2287844221184
>>> obj.append(0); obj.extend([1, 2, 3]); obj
[42, 0, 1, 2, 3]
>>> id(obj)
2287844221184
>>> obj.pop(0); obj.pop(0); obj.pop(); obj
42
0
3
[1, 2]
>>> id(obj)
2287844221184
```

However, when dealing with immutable objects, it's a completely different story.
If we check an English dictionary, this is what we get for the definition of “immutable”:

 > adjective: immutable – unchanging over time or unable to be changed.

Immutable objects' contents never change.
Take a string as an example:

```py
>>> obj = "Hello, world!"
```

Strings are a good example for this discussion because, sometimes,
they can _look_ mutable.
But they are not!

A very good indicator that an object is immutable is when all its methods return something.
This is unlike list's `.append` method, for example!
If you use `.append` on a list, you get no return value.
On the other hand, whatever method you use on a string, the result is returned to you:

```py
>>> [].append(0)    # No return.
>>> obj.upper()     # A string is returned.
'HELLO, WORLD!"
```

Notice how `obj` wasn't updated automatically to `"HELLO, WORLD!"`.
Instead, the new string was created and returned to you.

Another great hint at the fact that strings are immutable is that you cannot
assign to its indices:

```py
>>> obj[0]
'H'
>>> obj[0] = "h"
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
TypeError: 'str' object does not support item assignment
```

This shows that, when a string is created, it remains the same.
It can be used to build _other_ strings, but the string itself always. stays.
unchanged.

As a reference,
`int`, `float`, `bool`, `str`, `tuple`, and `complex` are the most common types of immutable objects;
`list`, `set`, and `dict` are the most common types of mutable objects.


## Variable names as labels

Another important thing to understand is that a variable name has very little to do with the object itself.

In fact, the name `obj` was just a label that I decided to attach to the
object that has identity 2698212637504, has the list type,
and contents 1, 2, 3.

Just like I attached the label `obj` to that object,
I can attach many more names to it:

```py
>>> foo = bar = baz = obj
```

Again, these names are just labels.
Labels that I decided to stick to the _same_ object.
How can we know it's the same object?
Well, all their “social security numbers” (the ids) match,
so they must be the same object:

```py
>>> id(foo)
2698212637504
>>> id(bar)
2698212637504
>>> id(baz)
2698212637504
>>> id(obj)
2698212637504
```

Therefore, we conclude that `foo`, `bar`, `baz`, and `obj`,
are variable names that all refer to the same object.


## The operator `is`

This is exactly what the operator `is` does:
it checks if the two objects are the _same_.

For two objects to be the same,
they must have the same identity:

```py
>>> foo is obj
True
>>> bar is foo
True
>>> obj is foo
True
```

It is _not_ enough to have the same type and contents!
We can create a new list with contents `[1, 2, 3]`
and that will _not_ be the same _object_ as `obj`:

```py
>>> obj is [1, 2, 3]
False
```

Think of it in terms of perfect twins.
When two siblings are perfect twins, they look identical.
However, they _are_ different people!


## `is not`

Just as a side note, but an important one,
you should be aware of the operator `is not`.

Generally speaking, when you want to negate a condition,
you put a `not` in front of it:

```py
n = 5
if not isinstance(n, str):
    print("n is not a string.")
# n is not a string.
```

So, if you wanted to check if two variables point to different objects,
you could be tempted to write

```py
if not a is b:
    print("`a` and `b` are different objets.")
```

However, Python has the operator `is not`, which is much more similar
to a proper English sentence, which I think is really cool!

Therefore, the example above should actually be written

```py
if a is not b:
    print("`a` and `b` are different objects.")
```

Python does a similar thing for the `in` operator, providing a `not in` operator as well...
How cool is that?!


## Assignment as nicknaming

If we keep pushing this metaphor forward,
assigning variables is just like giving a new nickname to someone.

My friends from middle school call me “Rojer”.
My friends from college call me “Girão”.
People I am not close to call me by my first name – “Rodrigo”.
However, regardless of what they call me, _I_ am still _me_, right?

If one day I decide to change my haircut,
everyone will see the new haircut, regardless of what they call me!

In a similar fashion, if I modify the _contents_ of an object,
I can use whatever nickname I prefer to see that those changes happened.
For example, we can change the middle element
of the list we have been playing around with:

```py
>>> foo[1] = 42
>>> bar
[1, 42, 3]
>>> baz
[1, 42, 3]
>>> obj
[1, 42, 3]
```

We used the nickname `foo` to modify the middle element,
but that change is visible from all other nicknames as well.

Why?

Because they all pointed at the _same_ list object.


# Python is pass-by-assignment

Having laid out all of this, we are now ready to understand how Python
passes arguments to functions.

When we call a function, each of the parameters of the function is assigned
to the object they were passed in.
In essence, each parameter now becomes a _new_ nickname to the objects
that were given in.


## Immutable arguments

If we pass in immutable arguments,
then we have _no_ way of modifying the arguments themselves.
After all, that's what immutable means: “doesn't change”.

That is why it can look like Python uses the pass-by-value model.
Because the only way in which we can have the parameter hold something
else is by assigning it to a completely different thing.
When we do that, we are reusing the same nickname for a different object:

```py
def foo(bar):
    bar = 3
    return bar

foo(5)
```

In the example above, when we call `foo` with the argument `5`,
it's as if we were doing `bar = 5` at the beginning of the function.

Immediately after that, we have `bar = 3`.
This means “take the nickname "bar" and point it at the integer `3`”.
Python doesn't care that `bar`, as a nickname (as a variable name)
had already been used.
It is now pointing at that `3`!




## Mutable arguments

On the other hand, mutable arguments _can_ be changed.
We can modify their internal contents.
A prime example of a mutable object is a list:
its elements can change (and so can its length).

That is why it can look like Python uses a pass-by-reference model.
However, when we change the _contents_ of an object,
we didn't change the identity of the object itself.
Similarly, when you change your haircut or your clothes,
your social security number does _not_ change:

```py
>>> l = [42, 73, 0]
>>> id(l)
3098903876352
>>> l[0] = -1
>>> l.append(37)
>>> id(l)
3098903876352
```

Do you understand what I'm trying to say?
If not, drop a comment below and I'll try to help.


## Beware when calling functions

This goes to show you should be careful when defining your functions.
If your function expects mutable arguments, you should do one of the two:

 - do not mutate the argument in any way whatsoever; or
 - document explicitly that the argument may be mutated.

Personally, I prefer to go with the first approach:
to not mutate the argument;
but there are times and places for the second approach.

Sometimes, you do need to take the argument as the basis for some kind
of transformation, which would mean you would want to mutate the argument.
In those cases, you might think about doing a copy of the argument
(discussed in the next section), but making that copy can be resource intensive.
In those cases, mutating the argument might be the only sensible choice.


# Making copies

## Shallow vs deep copies

“Copying an object” means creating a second object that has a different identity
(therefore, is a _different_ object) but that has the same contents.
Generally speaking, we copy one object so that we can work with it and mutate it,
while also preserving the first object.

When copying objects, there are a couple of nuances that should be discussed.

### Copying immutable objects

The first thing that needs to be said is that, for immutable objects,
it does not make sense to talk about copies.

“Copies” only make sense for mutable objects.
If your object is immutable, and if you want to preserve a reference to it,
you can just do a second assignment and work on it:

```py
string = "Hello, world!"
string_ = string
# Do stuff with `string_` now...
```

Or, sometimes, you can just call methods and other functions directly
on the original, because the original is not going anywhere:

```py
string = "Hello, world!"
print(string.lower())
# After calling `.lower`, `string` is still "Hello, world!"
```

So, we only need to worry about mutable objects.

### Shallow copy

Many mutable objects can contain, themselves, mutable objects.
Because of that, two types of copies exist:

 - shallow copies; and
 - deep copies.

The difference lies in what happens to the mutable objects inside the mutable objects.

Lists and dictionaries have a method `.copy` that returns a shallow copy
of the corresponding object.

Let's look at an example with a list:

```py
>>> sublist = []
>>> outer_list = [42, 73, sublist]
>>> copy_list = outer_list.copy()
```

First, we create a list inside a list, and we copy the outer list.
Now, because it is a _copy_, the copied list isn't the same object as the
original outer list:

```py
>>> copy_list is outer_list
False
```

But if they are not the same object, then we can modify the contents
of one of the lists, and the other won't reflect the change:

```py
>>> copy_list[0] = 0
>>> outer_list
[42, 73, []]
```

That's what we saw: we changed the first element of the `copy_list`,
and the `outer_list` remained unchanged.

Now, we try to modify the contents of `sublist`,
and that's when the fun begins!

```py
>>> sublist.append(999)
>>> copy_list
[0, 73, [999]]
>>> outer_list
[42, 73, [999]]
```

When we modify the contents of `sublist`, both the `outer_list`
and the `copy_list` reflect those changes...

But wasn't the copy supposed to give me a second list that I could
change without affecting the first one?
Yes!
And that is what happened!

In fact, modifying the contents of `sublist` doesn't _really_ modify
the contents of neither `copy_list` nor `outer_list`:
after all, the third element of both was pointing at a list object,
and it still is!
It's the (inner) contents of the object to which we are pointing that changed.

Sometimes, we don't want this to happen:
sometimes, we don't want mutable objects to share inner mutable objects.

### Common shallow copy techniques

When working with lists, it is common to use slicing to produce a shallow
copy of a list:

```py
>>> outer_list = [42, 73, []]
>>> shallow_copy = outer_list[::]
>>> outer_list[2].append(999)
>>> shallow_copy
[42, 73, [999]]
```

Using the built-in function for the respective type, on the object itself,
also builds shallow copies.
This works for lists and dictionaries, and is likely to work for other
mutable types.

Here is an example with a list inside a list:

```py
>>> outer_list = [42, 73, []]
>>> shallow_copy = list(outer_list)
>>> shallow_copy[2].append(999)
>>> outer_list
[42, 73, [999]]
```

And here is an example with a list inside a dictionary:

```py
>>> outer_dict = {42: 73, "list": []}
>>> shallow_copy = dict(outer_dict)
>>> outer_dict["list"].append(999)
>>> shallow_copy
{42: 73, 'list': [999]}
```

### Deep copy

When you want to copy an object “thoroughly”,
and you don't want the copy to share references to inner objects,
you need to do a “deep copy” of your object.
You can think of a deep copy as a recursive algorithm.

You copy the elements of the first level and, whenever you find a mutable element on the first level,
you recurse down and copy the contents of those elements.

To show this idea, here is a simple recursive implementation of a
deep copy for lists that contain other lists:

```py
def list_deepcopy(l):
    return [
        elem if not isinstance(elem, list) else list_deepcopy(elem)
        for elem in l
    ]
```

We can use this function to copy the previous `outer_list` and see
what happens:

```py
>>> sublist = []
>>> outer_list = [42, 73, sublist]
>>> copy_list = list_deepcopy(outer_list)
>>> sublist.append(73)
>>> copy_list
[42, 73, []]
>>> outer_list
[42, 73, [73]]
```

As you can see here, modifying the contents of `sublist` only affected
`outer_list` indirectly; it didn't affect `copy_list`.

Sadly, the `list_deepcopy` method I implemented isn't very robust,
nor versatile, but the Python Standard Library has got us covered!


## The module `copy` and the method `deepcopy`

The module [`copy`][copy] is exactly what we need.
The module provides two useful functions:

 - `copy.copy` for shallow copies; and
 - `copy.deepcopy` for deep copies.

And that's it!
And, what is more, the method `copy.deepcopy` is smart enough to handle
issues that might arise with circular definitions, for example!
That is, when an object contains another that contains the first one:
a naïve recursive implementation of a deep copy algorithm would enter
an infinite loop!

If you write your own custom objects and you want to specify how shallow
and deep copies of those should be made,
you only need to implement `__copy__` and `__deepcopy__`, respectively!

It's a great module, in my opinion.


# Examples in code

Now that we have gone deep into the theory – pun intended –,
it is time to show you some actual code that plays with these concepts.


## Mutable default arguments

Let's start with a Twitter favourite:

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">Python 🐍 is an incredible language but sometimes appears to have quirks 🤪<br><br>For example, one thing that often confuses beginners is why you shouldn&#39;t use lists as default values 👇<br><br>Here is a thread 👇🧵 that will help you understand this 💯 <a href="https://t.co/HVhPjS2PSH">pic.twitter.com/HVhPjS2PSH</a></p>&mdash; Rodrigo 🐍📝 (@mathsppblog) <a href="https://twitter.com/mathsppblog/status/1445327870218444804?ref_src=twsrc%5Etfw">October 5, 2021</a></blockquote>

Apparently, it's a bad idea to use mutable objects as default arguments.
Here is a snippet showing you why:

```py
def my_append(elem, l=[]):
    l.append(elem)
    return l
```

The function above appends an element to a list and,
if no list is given, appends it to an empty list by default.

Great, let's put this function to good use:

```py
>>> my_append(1)
[1]
>>> my_append(1, [42, 73])
[42, 73, 1]
>>> my_append(3)
[1, 3]
```

We use it once with `1`, and we get a list with the `1` inside.
Then, we use it to append a `1` to another list we had.
And finally, we use it to append a `3` to an empty list...
Except that's not what happens!

As it turns out, when we define a function,
the default arguments are created and stored in a special place:

```py
>>> my_append.__defaults__
([1, 3],)
```

What this means is that the default argument is _always_ the _same_ object.
Therefore, because it is a mutable object, its contents can change over time.
That is why, in the code above, `__defaults__` shows a list with two items already.

If we redefine the function, then its `__defaults__` shows an empty list:

```py
>>> def my_append(elem, l=[]):
...     l.append(elem)
...     return l
...
>>> my_append.__defaults__
([],)
```

This is why, in general, mutable objects shouldn't be used as default arguments.

The standard practice, in these cases, is to use `None` and then
[use Boolean short-circuiting][pydont-short-circuiting] to assign
the default value:

```py
def my_append(elem, l=None):
    lst = l or []
    lst.append(elem)
    return lst
```

With this implementation, the function now works as expected:

```py
>>> my_append(1)
[1]
>>> my_append(3)
[3]
>>> my_append(3, [42, 73])
[42, 73, 3]
```


## `is not None`

Searching through the Python Standard Library shows that
the `is not` operator is used a bit over 5,000 times.
That's a lot.

And, by far and large, that operator is almost always followed by `None`.
In fact, `is not None` appears 3169 times in the standard library!

`x is not None` does exactly what it's written:
it checks if `x` is `None` or not.

Here is a simple example usage of that, from the [`argparse`][argparse] module to create command line interfaces:

```py
# From Lib/argparse.py from Python 3.9
class HelpFormatter(object):
    # ...

    class _Section(object):
        # ...

        def format_help(self):
            # format the indented section
            if self.parent is not None:
                self.formatter._indent()
            # ...
```

Even without a great deal of context, we can see what is happening:
when displaying command help for a given section,
we may want to indent it (or not) to show hierarchical dependencies.

If a section's `parent` is `None`, then that section has no parent,
and there is no need to indent.
In other words, if a section's parent `is not None`, then we want to indent it.
Notice how my English matches the code exactly!


## Deep copy of the system environment

The method `copy.deepcopy` is used a couple of times in the standard library,
and here I'd like to show an example usage where a dictionary is copied.

The module [`os`][os] provides the attribute `environ`,
similar to a dictionary,
that contains the environment variables that are defined.

Here are a couple of examples from my (Windows) machine:

```py
>>> os.environ["lang"]
'en_US.UTF-8'
>>> os.environ["appdata"]
'C:\\Users\\rodri\\AppData\\Roaming'
>>> os.environ["systemdrive"]
'C:'
# Use list(os.environ.keys()) for a list of your environment variables.
```

The module [`http.server`][http-server] provides some classes
for basic HTTP servers.

One of those classes, `CGIHTTPRequestHandler`,
implements a HTTP server that can also run CGI scripts and,
in its `run_cgi` method, it needs to set a bunch of environment variables.

These environment variables are set to give the necessary context for the
CGI script that is going to be ran.
However, we don't want to actually modify the current environment!

So, what we do is create a deep copy of the environment,
and then we modify it to our heart's content!
After we are done, we tell Python to execute the CGI script,
and we provide the altered environment as an argument.

The exact way in which this is done may not be trivial to understand.
I, for one, don't think I could explain it to you.
But that doesn't mean we can't infer parts of it:

Here is the code:

```py
# From Lib/http/server.py in Python 3.9
class CGIHTTPRequestHandler(SimpleHTTPRequestHandler):
    # ...

    def run_cgi(self):
        # ...
        env = copy.deepcopy(os.environ)
        env['SERVER_SOFTWARE'] = self.version_string()
        env['SERVER_NAME'] = self.server.server_name
        env['GATEWAY_INTERFACE'] = 'CGI/1.1'
        # and many more `env` assignments!

        # ...

        else: 
            # Non-Unix -- use subprocess
            # ...
            p = subprocess.Popen(cmdline,
                                 stdin=subprocess.PIPE,
                                 stdout=subprocess.PIPE,
                                 stderr=subprocess.PIPE,
                                 env = env
                                 )
```

As we can see, we copied the environment and defined some variables.
Finally, we created a new subprocess that gets the modified environment.


# Conclusion

Here's the main takeaway of this Pydon't, for you, on a silver platter:

 > “*Python uses a pass-by-assignment model,
 and understanding it requires you to realise all objects are characterised
 by an identity number, their type, and their contents.*”

This Pydon't showed you that:

 - Python doesn't use the pass-by-value model, nor the pass-by-reference one;
 - Python uses a pass-by-assignment model (using “nicknames”);
 - each object is characterised by
   - its identity;
   - its type; and
   - its contents.
 - the `id` function is used to query an object's identifier;
 - the `type` function is used to query an object's type;
 - the type of an object determines whether it is mutable or immutable;
 - shallow copies copy the reference of nested mutable objects;
 - deep copies perform copies that allow one object, and its inner elements,
 to be changed without ever affecting the copy;
 - `copy.copy` and `copy.deepcopy` can be used to perform shallow/deep copies; and
 - you can implement `__copy__` and `__deepcopy__` if you want your own objects to be copiable.


## See also

If you prefer video content, you can check [this YouTube video][jacob-yt-video],
which was inspired by this article.


<!-- v -->
If you liked this Pydon't be sure to leave a reaction below and share this with your friends and fellow Pythonistas.
Also, [subscribe to the newsletter][subscribe] so you don't miss
a single Pydon't!
<!-- ^ -->

[subscribe]: https://mathspp.com/subscribe
[manifesto]: /blog/pydonts/pydont-manifesto
[gumroad-pydonts]: https://gum.co/pydonts
[pydont-short-circuiting]: https://mathspp.com/blog/pydonts/boolean-short-circuiting#define-default-values
[copy]: https://docs.python.org/3/library/copy.html
[argparse]: https://docs.python.org/3/library/argparse.html
[os]: https://docs.python.org/3/library/os.html
[http-server]: https://docs.python.org/3/library/http.server.html
[jacob-yt-video]: https://www.youtube.com/watch?v=D3IffJOwDY0

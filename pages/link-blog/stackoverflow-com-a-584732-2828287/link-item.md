---
# author:
date: 27-01-2025 21:40
link: https://stackoverflow.com/a/584732/2828287
taxonomy:
    category: link
title: "What is an example of the Liskov Substitution Principle? - Stack Overflow"
# via:
---

When working with typed Python code I get mypy complaining about the Liskov Substitution Principle quite frequently.
I end up rereading an explanation of it and it looks absolutely obvious to me.
So, I don't get how/why I keep messing it up.

A cat is an animal.
Therefore, if you have the class `Animal` and want to create a class `Cat`, you have to make sure that _wherever_ you can use a class `Animal`, you can replace that with `Cat` and the code should still work.
For example, here's an easy way to break the LSP:

```py
class Animal:
    def eat(self, food: Animal) -> None:
        pass


class Fish(Animal):
    ...


class Cat(Animal):
    def eat(self, food: Fish) -> None:  # <--
        pass
```

This breaks the LSP because the current definition of `Animal.eat` lets me write something like `Animal().eat(Cat())`, and a cat is an animal, so wherever I can use a generic animal I should also be able to write a cat.
However, `Cat().eat(Cat())` doesn't work because `Cat.eat` expects fish.
Therefore, I conclude that there's something fishy with `Animal.eat` / `Cat.eat`.

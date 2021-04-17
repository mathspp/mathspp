# [Brainetry] and [Roj]

These repositories contain each an interpreter for a programming language.

[Brainetry] is a programming language I created, inspired by the `brainf*ck` and `Poetic` programming languages.

The example `Brainetry` program I included below should be self-explanatory:

```
This program runs forever.
Not only it runs forever but it also
prints all natural numbers starting from one.
What a simple task.
I did it in just five lines of code.
```

The [Roj] programming language was born [because I was writing a Pascal interpreter][roj-post] but then the blog post series I was following didn't have the remaining posts and so I wrapped it up earlier. A `Roj` program example is given below:

```
$ Calculates the factorial of a given number $
result = 1;
readint n; $ Ask for user input $
while n > 0 do
    result = result * n;
    n = n - 1;
end;
out result;
```

[Brainetry]: https://github.com/RojerGS/Brainetry
[Roj]: https://github.com/RojerGS/Roj
[roj-post]: ../../blog/creating-programming-language-from-scratch

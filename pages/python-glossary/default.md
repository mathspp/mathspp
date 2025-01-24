# Python Glossary

## A

annotation
: a syntactic feature of Python that lets you add arbitrary metadata to variables, function parameters, and function return values. Often used for type hinting. See also: type hints.

anonymous function
: a function expression defined by the keyword `lambda`.

attribute
: a variable that is associated with an object. Typically, you access attributes with dot notation: `person.name` accesses the attribute `name` on the object `person`. See also: class attribute.


## B

base class
: the base class, or base classes, of a class `C`, are all the classes that the class `C` inherits from.

bytecode
: lower-level representation of Python programs that the Python interpreter executes to run your programs. When you run a program, your code is first translated (compiled) into bytecode. See the module `dis` for more information.


## C

callable
: an object that behaves like a function, in the sense that you can use parenthesis `()` to trigger some behaviour of that object. Functions and classes are the most common callables. You can create your own callable objects by defining `__call__` in the class.

class
: a piece of code that defines a “blueprint” to create objects of that type. The class defines the information each individual instance holds (the attributes) and the operations you can perform on them (the methods). See also: attribute, method.

class attribute
: an attribute that is defined directly on the class and not on the instances.

currying
: a technique from functional programming in which arguments can be passed to functions one at a time. In a sense, it’s the ability to pre-apply some arguments of a function, creating another function that expects the remaining arguments.


## D

decorator
: generally, a higher-order function that adds useful functionality to a function, when that functionality is unrelated to the original purpose of the function. For example, `functools.cache` is a decorator that adds caching — a useful functionality— to functions. A decorator can be an arbitrary callable, and not just a function, and it can be applied to classes as well as functions.

docstring
: short for “documentation string”. A triple-quoted string that provides documentation for an object of interest. Docstrings are defined as regular triple-quoted strings that Python interprets as docstrings when placed immediately below a definition, e.g., immediately below the signature of a function.


## E

expression
: a piece of code that evaluates to a concrete value. Not to be confused with a statement. Examples of expressions include arithmetic operations, comparisons, literals of the built-in types, conditional expressions, and list comprehensions. Examples of statements, which are not expressions, include assignments, conditional statements (`if`/`elif`/`else`), and function or class definitions with `def`/`class`. See also: statement.


## F

functional programming
: a programming paradigm that favours the use of pure functions, composability, stateless programs, higher-order functions, and more. Other ideas typically associated with functional programming include recursion, point-free programming, and currying.


## G

global (variable)
: a variable or value that is defined in the outer scope of a file, and is therefore accessible from within all functions in that file. Usage of global variables is typically frowned upon unless when to define global constants, which are then defined in all uppercase variables, e.g., `DEFAULT_SEARCH_ENGINE = "google"`.


## L

literal  {#literal}
: a piece of code that spells out a value of a built-in type. For example, `"Hello, world!"` is a string literal, `12.34` is a float literal, and `[True, {}]` is a list literal with two other literals inside.

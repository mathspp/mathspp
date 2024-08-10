Today I learned how to read the bytecode from a file of compiled Python bytecode (`.pyc`).

===


# How to read bytecode from a `.pyc` file

If you have a `.pyc` file, you can use the modules `dis` and `marshal` from the standard library to get the corresponding bytecode:

```py
import dis
import marshal

with open(path_to_pyc_file, "rb") as f:
    _ = f.read(16)  # Header is 16 bytes in 3.6+.
    # _ = f.read(8)  # Header is 8 bytes in <3.6.
    loaded = marshal.load(f)

dis.dis(loaded)
```


# Example

Suppose that you have a file `fibonacci.py` with the following code:

```py
def fibonacci(num):
    """Computes terms of the Fibonacci sequence."""
    if num <= 1:
        return 1
    return fibonacci(num - 1) + fibonacci(num - 2)
```

If you import your function `fibonacci` into the REPL or from another file, Python will compile the bytecode and write it to a `.pyc` file.

The quickest way to force Python to compile and dump the bytecode in a file is with this command:

```bash
❯ python -c "import fibonacci"
```

This will create a folder `__pycache__` (if it doesn't exist yet) and write the bytecode to a file.
Because I'm running Python 3.11 at the time of writing, the file that I got was `fibonacci.cpython-311.pyc`.

Now, to get the bytecode back from that file, I run the code from above, but I specify the path to the file `fibonacci.cpython-311.pyc`:

```py
import dis
import marshal

with open("__pycache__/fibonacci.cpython-311.pyc", "rb") as f:
    _ = f.read(16)  # Header is 16 bytes in 3.6+.
    loaded = marshal.load(f)

dis.dis(loaded)
```

If you run the code above, you get the bytecode associated with the `.pyc` file you opened:

```txt
  0           0 RESUME                   0

  1           2 LOAD_CONST               0 (<code object fibonacci at 0x100f997d0, file "/Users/rodrigogs/Documents/tmp/fibonacci.py", line 1>)
              4 MAKE_FUNCTION            0
              6 STORE_NAME               0 (fibonacci)
              8 LOAD_CONST               1 (None)
             10 RETURN_VALUE

Disassembly of <code object fibonacci at 0x100f997d0, file "/Users/rodrigogs/Documents/tmp/fibonacci.py", line 1>:
  1           0 RESUME                   0

  3           2 LOAD_FAST                0 (num)
              4 LOAD_CONST               1 (1)
              6 COMPARE_OP               1 (<=)
             12 POP_JUMP_FORWARD_IF_FALSE     2 (to 18)

  4          14 LOAD_CONST               1 (1)
             16 RETURN_VALUE

  5     >>   18 LOAD_GLOBAL              1 (NULL + fibonacci)
             30 LOAD_FAST                0 (num)
             32 LOAD_CONST               1 (1)
             34 BINARY_OP               10 (-)
             38 PRECALL                  1
             42 CALL                     1
             52 LOAD_GLOBAL              1 (NULL + fibonacci)
             64 LOAD_FAST                0 (num)
             66 LOAD_CONST               2 (2)
             68 BINARY_OP               10 (-)
             72 PRECALL                  1
             76 CALL                     1
             86 BINARY_OP                0 (+)
             90 RETURN_VALUE
```

That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe

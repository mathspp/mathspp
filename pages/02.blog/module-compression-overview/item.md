A high-level overview of how to use the module `compression`, new in Python 3.14.

===


This article will teach you how to use the package `compression`, **new in Python 3.14**.
You will learn how to

 - compress and decompress data directly;
 - how to write and read from compressed files;
 - how to compress and decompress data incrementally;
 - you'll also learn what compression algorithms are available to you and which ones are available in Python versions earlier than 3.14.


## Compression modules available

The package `compression` makes five compression modules available to you:

 1. `bz2` – comprehensive interface for compressing and decompressing data using the bzip2 compression algorithm;
 2. `gzip` – adds support for working with `gzip` files through a simple interface to compress and decompress files like the GNU programs `gzip` and `gunzip` would;
 3. `lzma` – interface for compressing and decompressing data using the LZMA compression algorithm;
 4. `zlib` – interface for the `zlib` library (lower-level than `gzip`); and
 5. `zstd` – interface for compressing and decompressing data using the Zstandard compression algorithm.


## Importing the modules

The first four modules (`bz2`, `gzip`, `lzma`, and `zlib`) were already available in earlier Python 3 versions as standalone modules.
This means you can import these modules directly in earlier versions of Python:

```pycon
# Python 3.12
>>> import bz2, gzip, lzma, zlib
>>> # No exception raised.
```

In Python 3.14, they continue to be importable directly and through the package `compression`:

```pycon
# Python 3.14
>>> import bz2, gzip, lzma, zlib
>>> # No exception raised.

>>> from compression import bz2, gzip, lzma, zlib
>>> # No exception raised.
```

The package `compression.zstd` is new in Python 3.14 and can only be imported as `compression.zstd`:

```pycon
# Python 3.14
>>> import zstd
# ModuleNotFoundError: No module named 'zstd'
>>> from compression import zstd  # ✅
>>> # No exception raised.
```

When possible (for example, in new programs), it is recommended that you import any of the five compression modules through the package `compression`.


## Basic interface

At the most basic level, all five modules provide the functions `compress` and `decompress`.
These functions can be given bytes-like objects to perform one-shot compression/decompression, as the snippet of code below shows:

```py
from compression import zstd

data = ("Hello, world!" * 1000).encode()
compressed = zstd.compress(data)
print(compressed)
# b'(\xb5/\xfd`\xc81\xad\x00\x00hHello, world!\x01\x00\xb8\x12\xf8\xf9\x05'
print(zstd.decompress(compressed) == data)
# True
```

Using the same toy data (the string `"Hello, world!"` repeated 1000 times) we can use the function `compress` of each module to determine how well they compress this data.
The table below shows the ratio of the compressed data to the original data, which means a smaller number is better:

| module |  ratio | check |
| - | - | - |
|    bz2 | 0.0058 |     ✅ |
|   gzip | 0.0060 |     ✅ |
|   lzma | 0.0098 |     ✅ |
|   zlib | 0.0051 |     ✅ |
|   zstd | 0.0024 |     ✅ |

The table below shows that, for this toy example, `zstd` compressed the data at least twice as effectively as any other compression algorithm.

This table was produced by the following snippet of Python 3.14 code, which also proves that all five modules provide the functions `compress` and `decompress`:

```py
from compression import bz2, gzip, lzma, zlib, zstd

# Pretty table header:
print(f"{'module':>7} | {'ratio':>6} | {'check':>5}")

# Toy data to compress/decompress:
data = ("Hello, world!" * 1000).encode()

for module in (bz2, gzip, lzma, zlib, zstd):
    compressed = module.compress(data)  # <-- compress data
    ratio = len(compressed) / len(data)
    decompressed = module.decompress(compressed)  # <-- decompress data

    # Print a pretty table:
    roundtrip_sanity_check = "✅" if data == decompressed else "❌"
    _, _, module_name = module.__name__.partition(".")
    print(f"{module_name:>7} | {ratio:>6.4f} | {roundtrip_sanity_check:>5}")
```


## Working with compressed files

Sometimes, you want to work with compressed data stored in files, and for that, you can use the module-specific function `open`, which most of the modules provide.
The function `open` in the compression modules is very similar to the built-in function `open`, but the compression-aware version allows you to work directly with compressed files.


### Availability

The compression-aware function `open` is only available in four of the compression modules:

 1. `bz2`
 2. `gzip`
 3. `lzma`
 4. `zstd`

In other words, the module `zlib` does not provide a function `zlib.open`.


### Writing a compressed file

To write a compressed file, just use the function `open` as a context manager and write to the file like you would normally.
Since you're using the compression-aware version of `open`, the data will be automatically compressed for you.

The example below uses the module `zstd` to open/create a new file `compressed_by_zstd` that writes the string `"Hello, world!"` repeated a thousand times to that file:

```py
from compression import zstd

with zstd.open("compressed_by_zstd", "wt") as f:
    f.write("Hello, world!" * 1000)
```

Note that you're opening the file with the mode set to `"wt"`, where the `"w"` stands for “write” and the `"t"` stands for “text mode”.

! For the **built-in** function `open`, the default behaviour is to open files in text mode.
! That's why you can often get away with using `"w"` or `"r"` to write and read text, respectively.
! When using the functions `open` from the compression modules, the default is to open the file in binary mode, not in text mode.

By the way, if you check the size of the file you just created (for example, with the command `stat -f "%z"`), you'll see that it's only 33 bytes:

```bash
bash % stat -f "%z" compressed_by_zstd
33
```

That's definitely much shorter than writing the string `"Hello, world!"` a thousand times!


### Reading a compressed file

Now that you have the file `compressed_by_zstd`, you can read it back by opening the file with `zstd.open` and reading the contents in:

```py
from compression import zstd

with zstd.open("compressed_by_zstd", "rt") as f:
    contents = f.read()

assert contents == "Hello, world!" * 1000
```

If you run this code you will see no assertion errors, which means you were able to open the compressed file and read the data back in.

Alternatively, you could open the file in binary mode and then decode the data yourself:

```py
from compression import zstd

with zstd.open("compressed_by_zstd", "r") as f:
    binary_contents = f.read()

assert binary_contents.decode() == "Hello, world!" * 1000
```

This highlights that `zstd.open` – and the functions `open` from the other compression modules – use binary mode by default.


## Incremental compression and decompression

Four of the five compression modules support incremental compression and decompression through dedicated classes:

 1. `bz2` – `BZ2Compressor` and `BZ2Decompressor`
 2. `lzma` – `LZMACompressor` and `LZMADecompressor`
 3. `zlib` – `Compress` and `Decompress`
 4. `zstd` – `ZstdCompressor` and `ZstdDecompressor`

These classes have _similar_ interfaces, although they are not exactly the same.
Below, you can find an example using the compression module `zstd` and its classes `ZstdCompressor` and `ZstdDecompressor`.


### Compressing data incrementally

To show how to compress data incrementally, you'll use a generator that produces strings to model a stream of incoming data:

```py
def incoming_data_stream():
    strings = [b"Hello, world!", b"Goodbye!", b"Here we go again..."]
    for n in range(80, 100):
        for string in strings:
            yield string * n
```

To compress data incrementally you have to start by instantiating the class `ZstdCompressor`.
Then, you pass chunks of data to the compressor by calling the method `.compress`.
This returns a `bytes` object with compressed data, and this compressed data must be concatenated with the results from previous calls to `.compress`.
When you are done, you call the method `.flush`, which may return a final piece of compressed data.

Here's what this process looks like in code:

```py
from compression import zstd

compressor = zstd.ZstdCompressor()

result = b""
bytes_processed = 0
for chunk in incoming_data_stream():
    # `chunk` is a new batch of data.
    bytes_processed += len(chunk)
    # We compress this batch.
    result += compressor.compress(chunk)
result += compressor.flush()

ratio = len(result) / bytes_processed
print(f"{100 * ratio:>.2f}%")  # 0.56%
```

!! When you call `.compress`, the compressed data that is returned doesn't necessarily represent the compression of everything you passed in, which is why there might be some data left when you call `.flush` at the end.

This compressed data could be written to a file, perhaps:

```py
with open("compressed.zstd", "wb") as f:
    f.write(result)
```

! Most of the compression modules provide tools that are more appropriate and convenient when working with files.
! Here, we just use the file as the source of chunks of data so you can see how the (de)compression objects work.


### Decompressing data incrementally

The process to decompress data incrementally is similar.
You instantiate the class `ZstdDecompressor` and then pass in chunks of compressed data to its method `.decompress`, which returns the decompressed bytes.
When you're done, you don't need to call a specific method.

The snippet below reads the file `compressed.zstd`, written in the previous section, in chunks, decompressing it as you read it:

```py
decompressor = zstd.ZstdDecompressor()

result = b""
with open("compressed.zstd", "rb") as f:
    while (chunk := f.read(256)):
        result += decompressor.decompress(chunk)

assert result == b"".join(incoming_data_stream())
print("Incremental decompression completed successfully!")
```

The `assert` ensures that the decompressed data matches the original data from the incoming data stream.
If you see the printed message, incremental decompression worked just fine!

! Most of the compression modules provide tools that are more appropriate and convenient when working with files, as you saw above.
! Here, we just use the file as the source of chunks of data so you can see how the (de)compression objects work.


## Advanced compression options, scenarios, and more

This article covered the simplest and most straightforward uses of the module `compression` and its five modules:

 - One-shot compression and decompression with the functions `compress` and `decompress`.
 - Writing compressed files and reading from compressed files with the module-specific function `open`.
 - Compressing and decompressing data incrementally with dedicated classes.

The compression modules you explored also support more advanced scenarios, often offering fine-grained control over the (de)compression process through additional arguments or specialised functions and classes.
Check the documentation to learn more!

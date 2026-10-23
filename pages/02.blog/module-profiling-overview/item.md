Learn about the new module profiling that was introduced in Python 3.15 to organise the built-in profiling tools.

===

The standard library module `profiling` organises the built-in profiling tools that Python has into a single namespace.
Introduced in Python 3.15, it gives access to two different profilers: a sampling profiler called Tachyon, and a tracing profiler.

This article will present an overview of those two profilers.

## 15 days of Python 3.15

To [celebrate the release of Python 3.15](/python-315), over the next 15 business days I'll be writing about a new 3.15 feature every day.
Explained clearly and with examples so you don't have to sift through the changelog.

Subscribe to receive this free email series:

{% include "forms/form.html.twig" with {form: forms("enroll")} %}

If you want, you can also [check the schedule](/python-315) of the upcoming emails.

## Historical note

Before Python 3.15, Python had a module in the standard library called `profile`.
The module `profile` was a pure-Python profiler that was of little practical use.
This module was deprecated in Python 3.15 and is scheduled for removal in Python 3.17.

In addition to the pure-Python module `profile`, Python 3.14 and earlier included a tracing profiler called cProfile.
This profiler now lives under `profiling.tracing` and it's one of the tools you'll learn about in this article.

## Understanding the two Python profilers

The Python standard library provides two profilers under the module `profiling`:

 1. `profiling.sampling`: a statistical profiler that uses high-frequency sampling to profile your code with virtually zero overhead. This profiler is commonly known as Tachyon.
 2. `profiling.tracing`: a tracing profiler that instruments your code to profile your code deterministically but with some overhead. This profiler used to be called cProfile.

The key difference between the two profilers lies in the method that each profiler uses to measure runtime statistics about your code.

Before understanding the differences between the two profilers, it's important to understand what a profiler is supposed to do.

Profiling your code is the act of gathering statistics regarding how much time each part of your code takes to run, and how often each part of your code runs.
These statistics are often used to identify **performance bottlenecks** in your code — parts of the code that have a high impact in the total time your program takes to run.

A profiler is a tool that can be used to profile code to collect those statistics.

### What's a sampling profiler?

A sampling profiler is a profiler that collects information about your code by taking repeated snapshots with a tiny interval between each snapshot.
By repeatedly snapshotting the call stack of your program, a sampling profiler is able to estimate how much time each part of your code takes to run.

For example, if your program runs in 10 seconds, if the sampling profiler took 10,000 snapshots, and if your function `foobar` was running in 750 of those snapshots (7.5% of the total number of samples), a sampling profiler will estimate that the function `foobar` ran for a total of 0.75 seconds, or 750 milliseconds.

Python's sampling profiler, called `Tachyon`, lives in the module `profiling.sampling`.

### What's a tracing profiler?

A tracing profiler is a profiler that instruments your code to determine exactly how much time each function call takes and how many times each function is called.
Its results are deterministic and exact, since the profiler hooks into your code to figure out what's happening at every instant.

Since a tracing profiler provides exact results and the sampling profiler only provides estimates, using the tracing profiler sounds like the best option, but you'll learn that that's not always the case.

Python provides a tracing profiler under the module `profiling.tracing`.

## The differences between the two Python profilers

The table below provides a high-level overview of the differences between the two profilers that the Python standard library provides:

| | Sampling profiler | Tracing profiler |
| :- | :- | :- |
| Nature of the results | Statistical estimate | Exact values |
| Overhead introduced | Virtually zero | Moderate |
| Usable as a CLI | ✅ | ✅ |
| Programmatic use | ❌ | ✅ |
| Profile a program end-to-end | ✅ | ✅ |
| Attach to and profile a running program | ✅ | ❌ |
| Profiling capabilities | Advanced | Basic |

The high frequency with which Tachyon samples your code means it will provide very accurate results.
Since [Tachyon is a more modern profiler and provides better capabilities](/blog/tachyon-sampling-profiling-in-python), Tachyon should be your default profiler choice.

You should switch to the tracing profiler when you:

 - **need exact call counts**: only the tracing profiler can give you _exact_ call counts
 - **need to use the profiler programmatically**: both profilers have a CLI but only the tracing profiler can be used programmatically
 - **want to measure small performance differences**: if you're measuring small performance differences (1%–2%), the statistical nature of Tachyon might not pick those differences up
 - **want to measure micro-benchmarks**: if you want to benchmark small pieces of Python code (e.g., an expression), you'll want to use `timeit` instead of any of the profilers

## How to profile a program from the command line

At its simplest form, both profilers can be used from the command line to run a Python program and profile it.
The module `profiling.tracing` accepts the program name as the argument:

```bash
% python -m profiling.tracing myscript.py
```

Since [Tachyon provides a more complete CLI](/blog/tachyon-sampling-profiling-in-python#using-the-tachyon-cli), to run a program and profile it you must use the Tachyon command `run`:

```bash
#                              vvv
% python -m profiling.sampling run myscript.py
```

Upon completion, profiling statistics are printed on the screen.
Instead of printing, you can write the statistics to a file by using the option `-o output_file` _before_ the program name:

```bash
# Tracing profiler:
% python -m profiling.tracing -o stats.prof myscript.py

# Sampling profiler:
% python -m profiling.sampling -o stats.prof myscript.py
```

The statistics can be later read and processed by the module `pstats`:

```py
import pstats

statistics = pstats.Stats("stats.prof")
statistics.print_stats()
```

The tracing profiler can only produce statistics in the format compatible with `pstats`.
[Tachyon supports other output formats](/blog/tachyon-sampling-profiling-in-python#output-formats) like [flamegraphs](/blog/tachyon-sampling-profiling-in-python#flamegraph) and [heatmaps](/blog/tachyon-sampling-profiling-in-python#line-by-line-heatmap).

## Advanced profiling

There are a number of features that are supported by Python's profilers but that we didn't cover:

 - profiling code programmatically with the tracing profiler
 - manipulating the statistics generated by the profilers with the module `pstats`
 - using Tachyon for:
    - [attaching to a running Python process to profile it](/blog/tachyon-sampling-profiling-in-python#profiling-running-programs-with-attach-and-dump)
    - profiling concurrent programs that use multiple threads and/or asynchronous programming
    - using the GIL mode to pick only the samples where the GIL was being held
    - ...

You can [read this more complete introduction to Tachyon on my blog](/blog/tachyon-sampling-profiling-in-python), or check the documentation of the respective modules.

## Python 3.15 brings much more to the table

From the two new built-ins `frozendict` and `sentinel`, to lazy imports, package startup configuration files, typing improvements, or more colour everywhere, Python 3.15 has a lot more to offer.
If you want to learn more about what's new in Python 3.15, [take a look at the “15 days of Python 3.15” series](/python-315) I'm running.
For 15 days, I'll explain a new Python 3.15 feature every day.

Get the free 3.15 email series:

{% include "forms/form.html.twig" with {form: forms("enroll_bottom")} %}

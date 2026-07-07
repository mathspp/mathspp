Improve the capabilities of your agent by providing it with better tools.

===

## Introduction

This tutorial builds on [the coding agent you implemented in the tutorial “Write a coding agent from first principles”](/blog/write-a-coding-agent-from-first-principles).
In this tutorial, you'll take your agent and improve its capabilities by implementing the text edit and bash command tools that Anthropic provides.

## Why use Anthropic's tools?

In the previous tutorial you implemented a coding agent that has a few tools that it can use to read, write, and execute, code.
The tool `"bash"` can be used to execute arbitrary commands and the tools `"read"`, `"write"`, `"replace"`, and `"insert"`, can be used to edit files.

As it turns out, these tools are so universally useful that Anthropic trained its models on specific schema definitions for these tools.
The tools still run on the client side, so you'll still get tool use blocks in the API responses, but you don't have to define the schema for the tool.
You just specify the tools by [their Anthropic types and names](https://platform.claude.com/docs/en/agents-and-tools/tool-use/tool-reference#anthropic-provided-tools), and the LLMs will happily request tool uses.

Anthropic trains their models on a number of useful tools but you'll focus your attention on two tools that emulate the functionality you already have:

 1. **Text editor tool**: this tool replaces the four tools you defined to read, write, replace, and insert, text in text files
 2. **Bash tool**: this tool provides a persistent bash session that can run bash commands

By replacing your tools with Anthropic's, the agent will be able to make better tool calls consistently, since Anthropic trains their models on their specific tool schemas.

## The native text editor tool

To define support for Anthropic's text editor tool you need to add it to your list of tools.
The name of the tool is `"str_replace_based_edit_tool"` and its type is `"text_editor_20250728"`.
(The type carries [a versioning suffix](https://platform.claude.com/docs/en/agents-and-tools/tool-use/tool-reference#tool-versioning) that may influence the tool's behaviour, so make sure you use the right date suffix.)

Since you'll be using Anthropic's text editor tool, you can delete the functions `read`, `write`, `replace`, and `insert`, and the corresponding dictionaries that go in the list `TOOLS`.
Instead, add the dictionary that specifies the Anthropic tool:

```py
# ...
TOOLS = [
    {
        "type": "text_editor_20250728",
        "name": "str_replace_based_edit_tool",
    }
]

# Bash tool defined and added later.
```

For organisation purposes, you'll define the text editor tool and the bash tool in their own submodules, so create the folder `tools` and then create the file `tools/str_replace_based_edit_tool.py` under `src/agent`.
In there, you'll define the code to handle the tool call.

The [text editor tool](https://platform.claude.com/docs/en/agents-and-tools/tool-use/text-editor-tool) is a 4-in-1 tool that allows you to _view_, _replace_, _create_, and _insert_, text.
To disambiguate the action you want to do, the tool use request includes a _command_:

```py
# Example tool use dictionary:
{
  "type": "tool_use",
  "id": "toolu_01A09q90qw90lq917835lq9",
  "name": "str_replace_based_edit_tool",
  "input": {
    "command": "view",  # <--
    # ...
  }
}
```

You'll use the key `"command"` from the input to determine which of the four actions to run.
Thus, you'll define the tool `str_replace_based_edit_tool` as a function that dispatches to the appropriate function based on the input command:

```py
# In src/agent/tools/str_replace_based_edit_tool.py

def str_replace_based_edit_tool(input_dict):
    command_functions = {
        # "create": create,
        # "str_replace": str_replace,
        # "view": view,
        # "insert": insert,
    }
    function = command_functions.get(input_dict["command"])
    if function is None:
        return True, f"Unknown function {input_dict["command"]}"
    try:
        return function(input_dict)
    except Exception as e:
        return True, f"Command {input_dict["command"]} failed with {e}."
```

Remember that you need to return a Boolean indicating whether there's an error and the string result.
That's why you return `True` and a string message if you can't recognise the command or if running `function(input_dict)` raises an exception.
What's left is implementing the functions `view`, `str_replace`, `create`, and `insert`.

Note that `str_replace_based_edit_tool` is implemented with a general `try: ... except:` block around the call to `function`.
This is to simplify the code inside each function.
If there's any exceptions — for example if you try to read a file that you don't have permission to read — then those are all handled in a single place.
Otherwise, your four functions would be riddled with exception handling code.

In the main agent file, `src/agent/__init__.py`, you can already write the code that handles this tool:

```py
# In src/agent/__init__.py

# ...

content_dictionaries = []
tool_results = []
for block in response.content:
    content_dictionaries.append(block.to_dict())
    if block.type == "text":
        print(block.text)

    elif block.type == "tool_use":
        print(f"Using tool {block.name} with input = {block.input}.")
        if block.name == "str_replace_based_edit_tool":  # <--
            is_error, result = str_replace_based_edit_tool(block.input)  # <--
```

As you implement the four functions, you can already test them out by triggering tool calls within the agent.

! The four commands will be implemented in the file `src/agent/tools/str_replace_based_edit_tool.py`.

### Command `create`

The command `create` is the simplest of the four commands.
You take a file path and some text and you're supposed to create a text file with that content.

If the tool use wants to use the command `create`, you'll get a tool use that looks like this:

```py
{
    "type": "tool_use",
    "id": "toolu_01A09q90qw90lq917835lq9",
    "name": "str_replace_based_edit_tool",
    "input": {
        "command": "create",
        "path": "test_primes.py",
        "file_text": "import unittest\nimport primes\n\nclass TestPrimes(unittest.TestCase):\n..."
    }
}
```

For this command, and the others that follow, the function that implements the tool is going to work with the dictionary `"input"`.
The file path is in the key `"path"` and the string contents are in the key `"file_text"`.

!!!! This tutorial [has an accompanying GitHub repo](https://github.com/mathspp/coding-agent-tutorial).
!!!! At each exercise, you'll be linked to a point in the history of the repo that contains all the code _up to that exercise_.

!!! **Exercise**: define a function `view` that accepts the dictionary `"input"` and implements the command `create`.
!!! Work on this for up to 10 minutes and then keep reading.
!!! [Companion repo checkpoint](https://github.com/mathspp/coding-agent-tutorial/tree/part-2-before-create-exercise).

To implement this function, you can use the module `pathlib` to create an object `pathlib.Path` and then use the method `.write_text` to write the text you're given into the file:

```py
from pathlib import Path

def create(input_dict):
    path = Path(input_dict["path"])
    content = input_dict["file_text"]
    path.write_text(content)
    return False, "File created successfully."
```

If you wish, you can check whether the file you're _creating_ already exists or not and return a custom error message if it already exists:

```py
def create(input_dict):
    path = Path(input_dict["path"])
    if path.exists():
        return True, f"File {path} already exists."
    content = input_dict["file_text"]
    path.write_text(content)
    return False, "File created successfully."
```

After you've implemented the function `create`, you should uncomment it in the dictionary inside `str_replace_based_edit_tool`:

```py
def str_replace_based_edit_tool(input_dict):
    command_functions = {
        "create": create,  # <-- now available
        # "str_replace": str_replace,
        # "view": view,
        # "insert": insert,
    }
    # ...
```

Then, run your agent and try to trigger this tool by asking it to create a new file with some random content.
The new file(s) should be created successfully.

### Command `str_replace`

The command `str_replace` can be used to make text replacements.
You're given an old string and its new replacement and you just have to make the replacement directly.

An example tool use block for the command `str_replace` looks like this:

```py
{
    "type": "tool_use",
    "id": "toolu_01A09q90qw90lq917835lq9",
    "name": "str_replace_based_edit_tool",
    "input": {
        "command": "str_replace",
        "path": "primes.py",
        "old_str": "for num in range(2, limit + 1)",
        "new_str": "for num in range(2, limit + 1):"
    }
}
```

!!! **Exercise**: implement the function `str_replace` that accepts the dictionary `"input"` and handles the tool use.
!!! Spend up to 10 minutes on this and then keep reading.
!!! [Companion repo checkpoint](https://github.com/mathspp/coding-agent-tutorial/tree/part-2-before-replace-exercise).

The string built-in type provides the method `replace` that is the most appropriate tool for this task.
If you're thinking about using the module `re` for regex replacements, you're overthinking this.
The LLM will give you a literal string you're supposed to replace, so you do not want to bring regular expressions into the mix.

The function `str_replace` only needs to read the file, make the replacement, and write the new contents:

```py
def str_replace(input_dict):
    path = Path(input_dict["path"])
    old = input_dict["old_str"]
    new = input_dict["new_str"]

    file_contents = path.read_text()
    new_file_contents = file_contents.replace(old, new)
    path.write_text(new_file_contents)

    return False, "Replacement successful."
```

For extra safety, the documentation recommends you ensure the old replacement text only occurs once.
If it occurs zero times or more than once you should return an appropriate error message.
Hence, you can improve the function `str_replace` to count the old replacement first:

```py
def str_replace(input_dict):
    path = Path(input_dict["path"])
    old = input_dict["old_str"]
    new = input_dict["new_str"]

    file_contents = path.read_text()
    count = file_contents.count(old)
    if count != 1:
        return (
            True,
            f"Replacement text shows up {count} times."
            + " No replacements were made."
        )
    new_file_contents = file_contents.replace(old, new)
    if new_file_contents == file_contents:
        return True, "There was nothing to replace."
    path.write_text(new_file_contents)

    return False, "Replacement successful."
```

After implementing the function `str_replace` you can uncomment it to try to use it:

```py
def str_replace_based_edit_tool(input_dict):
    command_functions = {
        "create": create,
        "str_replace": str_replace,  # <-- now available
        # "view": view,
        # "insert": insert,
    }
    # ...
```

### Command `view`

The command `view` can be used to view the contents of a text file or the contents of a directory, so you need to handle both scenarios in your function.
You have to distinguish which case you're in based on the path you're given.

An example tool use block for the command `view` looks like this:

```py
{
    "type": "tool_use",
    "id": "toolu_01A09q90qw90lq917835lq9",
    "name": "str_replace_based_edit_tool",
    "input": {
        "command": "view",
        "path": "primes.py"
    }
}
```

The documentation mentions that you may also get an optional `"view_range"` key when viewing the contents of a text file.
If that's the case, you only want to look at a portion of the file.
The view range is a list of two integers with the indices for the lines to view from and to view to, but the indices are 1-based.
So, if you have `"view_range": [1, 3]` you want to view lines 1, 2, and 3, which means you want something like `lines[0:3]`.
The special value `-1` might be used to indicate you want to view to the end of the file.

An example tool use block that _includes_ the optional view range looks like this:

```py
{
    "type": "tool_use",
    "id": "toolu_01A09q90qw90lq917835lq9",
    "name": "str_replace_based_edit_tool",
    "input": {
        "command": "view",
        "path": "primes.py",
        "view_range": [3, -1]
    }
}
```

!!! **Exercise**: implement the function `view` that accepts the dictionary `"input"` and handles the tool use.
!!! Spend up to 10 minutes on this and then keep reading.
!!! [Companion repo checkpoint](https://github.com/mathspp/coding-agent-tutorial/tree/part-2-before-view-exercise).

My suggestion is that you start by _not_ handling the optional key `"view_range"`, which means your code for the function `view` can look more or less like this:

```py
def view(input_dict):
    path = Path(input_dict["path"])
    if path.is_file():
        content = path.read_text()
        return False, content

    return False, "\n".join(map(str, path.iterdir()))
```

This version can already list the contents of directories and of text files.
For example, you can already ask it to solve the riddle in the file `riddle.txt` that you created during the first part of this tutorial.

To handle the optional key `"view_range"`, you need to be careful about handling the indices for slicing correctly, as well as handling the special case for `-1`.
A slice of the form `[idx:]` slices until the end of the list, and so does `[idx:None]`.
Usually, you omit the value `None`, but in this case it's helpful to know about it:

```py
def view(input_dict):
    path = Path(input_dict["path"])
    if path.is_file():
        content = path.read_text()
        if "view_range" not in input_dict:
            return False, content

        from_, to_ = input_dict["view_range"]
        to_ = None if to_ == -1 else to_  # <--
        lines = content.splitlines(keepends=True)
        return False, "".join(lines[from_ - 1:to_])

    return False, "\n".join(map(str, path.iterdir()))
```

The magic lies in deciding you'll always slice the list of lines from the file.
The only thing you need to do is to figure out what are the _correct_ indices for slicing.
The starting index is going to be the first line you want to include minus 1, hence the `from_ - 1`.
The ending index matches the last line you want to include.
Except if you got the special value `-1`.

The value `-1` in a slice like `lines[3:-1]` means you'll slice from the third element and you'll exclude the last:

```pycon
>>> nums = list("abcdefgh")
>>> print(nums)
['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h']
>>> print(nums[3:-1])
['d', 'e', 'f', 'g']
```

So, you replace it with the special value `None` so that it means “until the end”.

Another detail that's relevant is that you're using the string method `splitlines` to split text into its lines, and you're keeping the newline characters with `keepends=True`.
You do it to preserve the exact newline characters and to make sure you don't drop trailing newlines.
This trick will also be relevant for the command `insert`.

After implementing `view` you can uncomment it:

```py
def str_replace_based_edit_tool(input_dict):
    command_functions = {
        "create": create,
        "str_replace": str_replace,
        "view": view,  # <-- now available
        # "insert": insert,
    }
    # ...
```

### Command `insert`

The command `insert` is used to insert new text at a given line index in the given file.
The command inserts _at the beginning_ of the line specified.

Suppose you have a file `primes.py` that looks like this:

```py
"""
A module for working with prime numbers.
"""

def is_prime(n):
    ...
```

Now, suppose you get the following tool use block for the command `insert`:

```py
{
    "type": "tool_use",
    "id": "toolu_01A09q90qw90lq917835lq9",
    "name": "str_replace_based_edit_tool",
    "input": {
        "command": "insert",
        "path": "primes.py",
        "insert_line": 2,
        "insert_text": "\nBy Rodrigo Girão Serrão\nAll rights reserved (2026)\n"
    }
}
```

Note that the value for `"insert_text"` is a string that _may_ contain newline characters.
It's _not_ a list of lines.

If the command runs successfully, the file would become

```py
"""
A module for working with prime numbers.

By Rodrigo Girão Serrão.
All rights reserved (2026)
"""

def is_prime(n):
    ...
```

In implementing this command, you need to be careful about the possible _trailing newline characters_ in the text to be inserted.
If the text of the tool use block above ended with `"... reserved (2026)"`, _without_ the `"\n"`, the modified file would be different:

```py
"""
A module for working with prime numbers.

By Rodrigo Girão Serrão.
All rights reserved (2026)"""

def is_prime(n):
    ...
```

!!! **Exercise**: implement the function `insert` that accepts the dictionary `"input"` and handles the tool use.
!!! Spend up to 15 minutes on this and then keep reading.
!!! [Companion repo checkpoint](https://github.com/mathspp/coding-agent-tutorial/tree/part-2-before-insert-exercise).

The first step is splitting both the text file and the text to be inserted into lines with the string method `.splitlines(keepends=True)`.
You want `keepends=True` for the same reasons mentioned when you implemented the command `view`.
The function `insert` starts like this:

```py
def insert(input_dict):
    path = Path(input_dict["path"])
    insert_index = input_dict["insert_line"]
    text_lines = input_dict["insert_text"].splitlines(keepends=True)
    file_lines = path.read_text().splitlines(keepends=True)
```

Regardless of whether the text to insert was a single line or multiple lines, `text_lines` is always a list of lines.
(Possibly a list with a single line.)
To insert the new text lines in the right location, you need to slice the file lines.
Then, you put everything together with `"".join(...)`:

```py
def insert(input_dict):
    path = Path(input_dict["path"])
    insert_index = input_dict["insert_line"]
    text_lines = input_dict["insert_text"].splitlines(keepends=True)
    file_lines = path.read_text().splitlines(keepends=True)

    new_lines = file_lines[:insert_index] + text_lines + file_lines[insert_index:]
    path.write_text("".join(new_lines))
    return False, "Text successfully inserted."
```

After implementing `insert` you can uncomment it:

```py
def str_replace_based_edit_tool(input_dict):
    command_functions = {
        "create": create,
        "str_replace": str_replace,
        "view": view,
        "insert": insert,  # <-- now available
    }
    # ...
```

This concludes implementing your text editing commands.

### Restricting access to the current working directory

The text editor tool interacts with your filesystem so you may want to put some guardrails in place.
For instance, you may want to restrict editing access so that the agent can only edit files within the current working directory.

!!! **Exercise**: [write a decorator](/blog/pydonts/decorators) `only_within_cwd` that you're going to apply to the functions `create`, `str_replace`, and `insert`.
!!! The decorator will look at the value `input_dict["path"]` to determine if it's within the current working directory.
!!! If it's not, return an error message saying the agent doesn't have access to that directory.
!!! If you don't know how to create decorators, write the check explicitly inside one of the functions and then copy and paste it into the other functions.
!!! Work on this for up to 20 minutes and then keep reading.
!!! [Companion repo checkpoint](https://github.com/mathspp/coding-agent-tutorial/tree/part-2-before-workspace-exercise).

If `path` is an arbitrary path and `cwd` is the current working directory, you can check if `path` is _under_ `cwd` by writing `cwd in path.parents`.
Using that, the check inside a function would look like this:

```py
def create(input_dict):
    path = Path(input_dict["path"])
    cwd = Path().resolve()

    if cwd not in path.expanduser().resolve().parents:
        return (
            True,
            f"Can't access {path} because it's outside the "
            + "current working directory."
        )

    # ...
```

To avoid having repeated checkers across the functions, and to make it easier to see what functions are restricted, you can factor that implementation out into a decorator:

```py
def only_within_cwd(function):
    def restricted_function(input_dict):
        path = Path(input_dict["path"]).expanduser().resolve()
        if Path().resolve() not in path.parents:
            return (
                True,
                f"Can't access {path} because it's outside "
                + "the current working directory."
            )
        return function(input_dict)

    return restricted_function
```

Now, you just apply the decorator to the functions you wish to restrict:

```py
# ...

@only_within_cwd
def create(input_dict):
    ...

@only_within_cwd
def str_replace(input_dict):
    ...

@only_within_cwd
def insert(input_dict):
    ...
```

It's up to you to decide whether you want to restrict the command `view` or not.

### Creating backups before edits

Another thing worth considering is the creation of backups before the agent makes file edits.
In other words, before the agent makes an insertion or a string replacement, create a copy of the target file.

!!! **Exercise**: [write a decorator](/blog/pydonts/decorators) `make_path_backup` that you're going to apply to the functions `str_replace` and `insert`.
!!! The decorator will create a copy of the file pointed to by `input_dict["path"]`.
!!! The copy should be timestamped with the current date and time.
!!! If you don't know how to create decorators, write the backup logic explicitly inside one of the functions and then copy and paste it into the other function.
!!! Work on this for up to 20 minutes and then keep reading.
!!! [Companion repo checkpoint](https://github.com/mathspp/coding-agent-tutorial/tree/part-2-before-backup-exercise).

Using the module `datetime` (as `dt`), you can create a timestamp with `dt.datetime.now().strftime("%y%m%d-%H%M%S")`.
You can use that timestamp, together with the method `.with_name` from path objects, and the function `shutil.copy2`, to make the backup.
If you define this logic directly inside the editing functions, it could look like this:

```py
import datetime as dt
from shutil import copy2

@only_within_cwd
def str_replace(input_dict):
    path = Path(input_dict["path"])

    timestamp = dt.datetime.now().strftime("%y%m%d-%H%M%S")
    backup_path = path.with_name(f"{path.name}.{timestamp}.backup")
    copy2(path, backup_path)

    # ...
```

To avoid having duplicate backup logic, you can factor this out into a decorator:

```py
import datetime as dt
from shutil import copy2

def make_path_backup(function):
    def wrapper(input_dict):
        path = Path(input_dict["path"]).expanduser().resolve()
        timestamp = dt.datetime.now().strftime("%y%m%d-%H%M%S")
        backup_path = path.with_name(f"{path.name}.{timestamp}.backup")
        copy2(path, backup_path)
        return function(input_dict)

    return wrapper
```

Now, you need to apply this decorator to the functions `str_replace` and `insert`.

!!! **Exercise**: the order in which decorators are applied _is_ relevant.
!!! Figure out the correct order in which `@make_path_backup` and `@only_within_cwd` must be applied.
!!! Work on this for up to 10 minutes and then keep reading.
!!! [Companion repo checkpoint](https://github.com/mathspp/coding-agent-tutorial/tree/part-2-before-decorator-order-exercise).

For the functions `str_replace` and `insert` you want the two actions to happen in the correct order:

 1. First you check if the path is within the current working directory
 2. Only then do you create the backup right before the edit

For this to happen, the decorator `only_within_cwd` must be applied _first_:

```py
# ...

@only_within_cwd
@make_path_backup
def str_replace(input_dict):
    ...

@only_within_cwd
@make_path_backup
def insert(input_dict):
    ...
```

If you swap the order and you try to edit a file outside the current working directory, the edit will fail _but_ you'll be left with a random backup of that file in that directory.

## The native bash tool

The [bash tool that Anthropic provides](https://platform.claude.com/docs/en/agents-and-tools/tool-use/bash-tool) is supposed to enable Claude to execute shell commands in a _persistent_ bash session, so it's slightly different from the bash tool you implemented in the first part of this tutorial.

If you're going to implement Anthropic's bash tool, you can delete the function `bash` you had defined and the schema definition you were appending to the list `TOOLS`.
Instead, you'll add a simpler dictionary that specifies that you support Anthropic's tool, which has the name `bash` and the type `"bash_20250124"`:

```py
TOOLS = [
    ...,  # str_replace_based_edit_tool
    {
        "type": "bash_20250124",
        "name": "bash",
    },
]
```

The main difficulty with implementing this tool will be in adding persistence to the bash session.
If you didn't need persistence, the code from the first implementation would be enough.

To implement a bash session with persistence, you're going to create a class `BashSession` with the following methods:

 - `__init__()`: uses `subprocess.Popen` to open a pipe to a bash subprocess and starts a daemon thread that reads output from the subprocess's standard output and puts it into a queue
 - `run(command, timeout)`: sends the given command string to the bash subprocess and then tries to read the output of running that command before timing out
 - `close()`: shuts everything down as gracefully as possible

!!! **Exercise**: implement the class `BashSession` described above.
!!! Work on this for up to 30 minutes and then keep reading.
!!! [Companion repo checkpoint](https://github.com/mathspp/coding-agent-tutorial/tree/part-2-before-bash-session-exercise).

! **Hint**: when running a command in `BashSession.run`, send `"echo __DONE__\n"` to the bash subprocess _after_ the original command.
! When reading output, you know you're done if you see the string `"__DONE__\n"`.

The implementation of the class `BashSession` will happen in the file `tools/bash.py`.

### Implementing a persistent bash session

To implement the persistent bash session, you'll start by using the module `subprocess` to start a subprocess that's running bash:

```py
process = subprocess.Popen(
    ["/bin/bash"],
    # ...
)
```

The string `/bin/bash` points to the executable you want the subprocess to be running.

To be able to send commands into the subprocess and to read their output, you're going to set the standard input and the standard output of the subprocess to be two pipes:

```py
process = subprocess.Popen(
    ["/bin/bash"],
    stdin=subprocess.PIPE,
    stdout=subprocess.PIPE,
    stderr=subprocess.STDOUT,
    # ...
)
```

Additionally, you're setting the subprocess's standard error stream to be the same as the standard output.
Redirecting stderr to stdout makes your life simpler because you only have to handle one output stream.
The price you pay is that your output becomes less descriptive since becomes harder to distinguish errors from regular output.

Finally, you're going to set the input and output streams to be in text mode with `text=True` and you'll make them line-buffered by using `bufsize=1`:

```py
process = subprocess.Popen(
    ["/bin/bash"],
    stdin=subprocess.PIPE,
    stdout=subprocess.PIPE,
    stderr=subprocess.STDOUT,
    text=True,
    bufsize=1,
)
```

This is the call to `subprocess.Popen` that starts the bash subprocess.
You're going to start this subprocess in `BashSession.__init__`, along with a daemon thread that reads from `process.stdout` and puts the output lines into a queue:

```py
# tools/bash.py
import queue
import subprocess
import threading

class BashSession:
    def __init__(self):
        self.process = subprocess.Popen(
            ["/bin/bash"],
            stdin=subprocess.PIPE,
            stdout=subprocess.PIPE,
            stderr=subprocess.STDOUT,
            text=True,
            bufsize=1,
        )
        self.output_queue = queue.Queue()
        self.stdout_reader = threading.Thread(
            target=self._read_stdout,
            daemon=True,
        )
        self.stdout_reader.start()

    def _read_stdout(self):
        for line in self.process.stdout:
            self.output_queue.put(line)
```

You use a thread to read _from_ `self.process.stdout` _into_ `self.output_queue` because reading from the standard ouptut of the subprocess might block for an arbitrary amount of time and it's easier to control timeouts when reading from a queue.
You see this in action in the method `run`, that in its essence is just six lines of code:

```py
class BashSession:
    def __init__(self): ...
    def _read_stdout(self): ...

    def run(self, command):
        self.process.stdin.write(command + "\n")
        self.process.stdin.write("echo __DONE__\n")

        output = []
        while not output or output[-1] != "__DONE__\n":
            output.append(self.output_queue.get())

        return False, "".join(output[:-1])
```

The expression `self.output_queue.get()` pops a line of output from the queue, but only if there is content in the queue.
If there is not, the method call blocks and waits until there is output available.
For output to become available in the queue, it must be put there by the method `_read_stdout`, that is running the thread that you started in `BashSession.__init__`.
In turn, this only happens once the command you're running produces output in the bash subprocess.

You have no idea how long this whole process can take and you don't want to wait for it forever.
That's why you specify a timeout as a second argument to the method `run`:

```py
# ...
import time

class BashSession:
    def __init__(self): ...
    def _read_stdout(self): ...

    def run(self, command, timeout):
        self.process.stdin.write(command + "\n")
        self.process.stdin.write("echo __DONE__\n")

        output = []
        start = time.monotonic()
        while not output or output[-1] != "__DONE__\n":
            already_elapsed = time.monotonic() - start
            remaining = timeout - already_elapsed
            if remaining < 0:
                return True, f"Command timed out after {timeout} seconds."

            try:
                output.append(self.output_queue.get(timeout=timeout - already_elapsed))
            except queue.Empty:
                return True, f"Command timed out after {timeout} seconds."

        return False, "".join(output[:-1])
```

The method call `self.output_queue.get(timeout=...)` accepts a timeout argument which is taken from the second argument to the method `run`.
This timeout is computed as `timeout - already_elapsed`, which is the total time you allow for the command to run from minus the total time you _already_ spent since the start of the method `run`.

Finally, you need a method `close` that shuts the subprocess down:

```py
import queue
import subprocess
import threading
import time


class BashSession:
    def __init__(self): ...
    def _read_stdout(self): ...
    def run(self, command, timeout): ...

    def close(self):
        self.process.stdin.write("exit\n")
        try:
            self.process.wait(timeout=1)
        except subprocess.TimeoutExpired:
            self.process.terminate()
            self.process.wait()
```

### Handling bash tool use requests

Anthropic's models might request to use the bash tool in two different ways:

 - **Restart**: the tool use block might have the key-value pair `"restart": true`, in which case you're supposed to restart the bash session
 - **Command**: the block might have the key `"command"`, in which case its value is a command you're supposed to run

!!! **Exercise**: using the class `BashSession` you implemented earlier, handle the bash tool use requests.
!!! Work on this for 10 minutes maximum and then keep reading.
!!! [Companion repo checkpoint](https://github.com/mathspp/coding-agent-tutorial/tree/part-2-before-bash-handling-exercise).

An instance of the class `BashSession` has an associated subprocess running, so there's no point in creating the instance before it's needed.
Instead, you set a variable `bash_session` to `None` and instantiate the class `BashSession` only when required:

```py
from .tools.bash import BashSession  # <--
# ...

def main():
    client = Anthropic()
    bash_session = None  # <--
    # ...
    while True:
        # ...
        for block in response.content:
            # ...
            elif block.type == "tool_use":
                print(f"Using tool {block.name} with input = {block.input}.")
                # ...
                elif block.name == "bash":  # <--
                    if bash_session is None:
                        bash_session = BashSession()

                    is_error, result = False, ""
                    if block.input.get("restart"):
                        bash_session.close()
                        bash_session = BashSession()
                        is_error, result = False, "Bash session restarted."
                    if block.input.get("command"):
                        is_error, result = bash_session.run(block.input["command"], 10)
                    else:
                        is_error, result = True, f"Unknown tool {block.name}."
                # ...
```

Similarly, restarting the session is equivalent to closing the one that's running and creating a new one, which is how you handle the action `"restart"`.
It is up to you to set a timeout that you're comfortable here, but 10 seconds should be more than enough to start with.

At this point you can try to trigger your bash tool by asking what's your current working directory, for example.
The agent will likely try to run the command `pwd`.

### Truncating very large outputs

As a safety measure, the Claude Docs recommend that you truncate very large outputs to avoid any token issues.
This can be done by ensuring you don't return too many lines of output from the method `BashSession.run`.
Additionally, you can also ensure that the total length of the output string that the agent puts in the context also stays within a reasonable limit.

!!! **Exercise**: limit the amount of output that you have to work with after running a bash command by restricting the total number of output lines.
!!! Work on this for 10 minutes and then keep reading.
!!! [Companion repo checkpoint](https://github.com/mathspp/coding-agent-tutorial/tree/part-2-before-truncation-exercise).

To limit the number of lines of output returned by the bash session you can't just stop getting output from the bash subprocess if you hit the limit.
Instead, you have to get all lines of output until you find the marker that says you're done and _then_ you truncate your output.
Otherwise, if you run a command with a very long output, the output would get truncated and the second half of the output of a command would pass as the output of a second command.

If the output gets truncated, it's a good idea to include a line saying it's being truncated:

```py
class BashSession:
    MAX_OUTPUT_LINES = 1000
    # ...

    def _truncate_output(self, output):
        if (total_len := len(output)) > self.MAX_OUTPUT_LINES:
            output = output[:self.MAX_OUTPUT_LINES]
            output.append(
                f"(Showing {self.MAX_OUTPUT_LINES} of "
                + f"{total_len} total lines for brevity.)\n"
            )
        return "".join(output)

    def run(self, command, timeout):
        # ...
        return False, self._truncate_output(output[:-1])
```

This tells the model that it didn't receive the full output, which may be relevant.

### Mitigating the security risks

By implementing a bash tool you're giving your agent full control over your computer.
You want to make sure your agent doesn't run any dangerous commands, so you need to put some guardrails in place.

!!! **Exercise**: add a guardrail that asks the user for permission every time the agent wants to run a command.
!!! Give the user the option to mark the command as safe to run always, so that that command is automatically allowed during the same chat session.
!!! Work on this for 10 minutes and then keep reading.
!!! [Companion repo checkpoint](https://github.com/mathspp/coding-agent-tutorial/tree/part-2-before-approval-exercise).

You're starting to develop a few things around the concept of a persistent bash session, so you can separate the core bash session — responsible for running commands and getting their output — from everything else around it that's related to the bash tool.
This means taking a step back and factoring the method `_truncate_output` out of the class `BashSession`:

```py
...

class BashSession:
    def __init__(self): ...
    def _read_stdout(self): ...

    def run(self, command, timeout):
        ...
        return False, output[:-1]  # <--

    def close(self): ...
```

For simplicity, you can also refactor the method `run` so that it returns a list with output lines instead of a single string.
With that in mind, you can create the class `BashToolManager` that wraps the bash session:

```py
class BashToolManager:
    MAX_OUTPUT_LINES = 1000

    def __init__(self):
        self.bash_session = None

    def _truncate_output(self, output):
        if (total_len := len(output)) <= self.MAX_OUTPUT_LINES:
            return "".join(output)

        lines = output[: self.MAX_OUTPUT_LINES]
        lines.append(
            f"(Showing {self.MAX_OUTPUT_LINES} of "
            + f"{total_len} total lines for brevity.)\n"
        )
        return "".join(lines)

    def run(self, command, timeout):
        if self.bash_session is None:
            self.bash_session = BashSession()
        is_error, output_lines = self.bash_session.run(command, timeout)
        return is_error, self._truncate_output(output_lines)

    def restart(self):
        if self.bash_session is not None:
            self.close()
        self.bash_session = BashSession()

    def close(self):
        if self.bash_session is not None:
            self.bash_session.close()
```

Note the class `BashToolManager` has an attribute `bash_session` that might be `None`, and that is checked on each interaction.
Since the manager does this work, you can simplify your main agentic loop:

```py
from .tools.bash import BashToolManager  # <--
# ...

def main():
    client = Anthropic()
    bash_session = BashToolManager()  # <--
    # ...
    while True:
        # ...
        for block in response.content:
            # ...
            elif block.type == "tool_use":
                print(f"Using tool {block.name} with input = {block.input}.")
                # ...
                elif block.name == "bash":  # <--
                    is_error, result = False, ""
                    if block.input.get("restart"):
                        bash_tool_manager.restart()
                        is_error, result = False, "Bash session restarted."
                    if block.input.get("command"):
                        is_error, result = bash_tool_manager.run(
                            block.input["command"], 10
                        )
                # ...
```

With this refactor, your main loop doesn't interact with the bash session directly.
Instead, it uses methods on the manager.

Now, you can implement the security check in the bash tool manager, along with the ability to allow equal commands to be reran without asking for approval again.
For simplicity, you can start by implementing approval logic for _full commands_:

```py
class BashToolManager:
    ...
    def __init__(self):
        ...
        self._approved_commands = set()

    def _get_approval(self, command):  # <--
        if command in self._approved_commands:
            return True

        print(f"The agent wants to run the command {command!r}.")
        print("Allow always (a), allow this time (y), or don't allow (n)?")
        permission = input("(a/y/n) >>> ").strip().casefold()
        if permission.startswith("a"):
            self._approved_commands.add(command)
            return True

        return permission.startswith("y")

    def run(self, command, timeout):
        if self.bash_session is None:
            self.bash_session = BashSession()

        if not self._get_approval(command):  # <--
            return True, "User didn't authorise running the command."

        is_error, output_lines = self.bash_session.run(command, timeout)
        return is_error, self._truncate_output(output_lines)
```

The method `_get_approval` runs before a command runs and checks if the command has been approved before.
If a command hasn't been approved yet, the user is prompted to determine whether that specific command can be approved always, only this time, or if it's cancelled.
If the user doesn't allow running a command, the agent is informed of that so it can try running an alternative command.

As a potential improvement, you may want to add the ability to pre-approve commands based on their executable and not necessarily on the full list of arguments and options used.
For example, if the agent wants to run `ls -al`, you may want to pre-approve all commands that start with `ls`.

! If you modify your command approval policy, be careful about command pipes and chained commands.
! The command `ls` is an innocent command but you don't want to pre-approve the command `ls` and, by mistake, allow the agent to run a command like `ls && python -c 'print("Malicious code here")'`.

## Conclusion and next steps

In this tutorial, you improved your agent by:

 - leveraging Anthropic's text editor tool
 - adding support for Anthropic's bash session tool
 - giving the agent access to a _stateful_ bash session
 - setting security guardrails around the bash tool

In doing so, your agent became much more robust and consistent because it can use powerful tools the models were specifically trained on.

Sitting at roughly 300 lines of code (100 for the agentic loop and command management, 100 for the text editor tool, and 100 for the bash session and tool manager), you can get the full agent code [from this GitHub repository](https://github.com/mathspp/coding-agent-tutorial) and this tutorial stops [at the tag “part 2 complete”](https://github.com/mathspp/coding-agent-tutorial/tree/part-2-complete).

To keep exploring the world of coding agents, here are possible follow-up tasks:

 1. implement [response streaming](https://platform.claude.com/docs/en/cli-sdks-libraries/sdks/python#streaming-responses) to allow the agent to provide longer responses and to use more tools
 2. improve the agent UI, for example by using `rich` to add colours to the output or by using `textual` to add a TUI to the agent
 3. add user commands that allow you to manage the agent during a session, like the ability to reset the context, switch model during the conversation, or change the token limit for responses
 4. wrap the agent in a CLI — a small tweak that enables the usage of subagents further down the line

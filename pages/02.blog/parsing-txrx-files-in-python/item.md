In this article I show how I wrote a parser to parse TXRX files into a more manageable object in Python.

===


# Parsing TXRX files in Python

In the [Python community](https://skool.com/python) that I am creating, a friend of mine mentioned he was trying to write a parser for TXRX files.
I like parsers, so I offered to help with that task and this article is a short overview of what I did and how I did it.


## TXRX files

First and foremost, I have no idea what TXRX files are.
My friend sent me [one sample TXRX file](sample.txrx) for me to play around with, and I will include it here for you to take a look at the data as well:

<details>
<summary>Sample TXRX file contents</summary>

<pre><code class="language-txrx">begin_&lt;points&gt; BS
project_id 1
active
vertical_line no
pattern_shown no
cube_size 2.50000
CVxLength 10.00000
CVyLength 10.00000
CVzLength 10.00000
AutoPatternScale
ShowDescription yes
CVsVisible no
CVsThickness 3
begin_&lt;location&gt; 
begin_&lt;reference&gt; 
cartesian
longitude -0.000000000000000
latitude 0.000000000000000
visible no
terrain
end_&lt;reference&gt;
nVertices 1
-33.000000000000000 11.000000000000000 32.000000000000000
end_&lt;location&gt;
pattern_show_arrow no
pattern_show_as_sphere no
generate_p2p yes
use_apg_acceleration no
is_transmitter yes
is_receiver yes
begin_&lt;transmitter&gt; 
begin_&lt;pattern&gt; 
antenna 1
waveform 1
rotation_x 0.00000
rotation_y 0.00000
rotation_z 0.00000
end_&lt;pattern&gt;
power 0.00000
end_&lt;transmitter&gt;
begin_&lt;receiver&gt; 
begin_&lt;pattern&gt; 
antenna 1
waveform 1
rotation_x 0.00000
rotation_y 0.00000
rotation_z 0.00000
end_&lt;pattern&gt;
NoiseFigure 3.00000
end_&lt;receiver&gt;
powerDistribution Uniform 10.00000 10.00000 inactive nosampling 10
end_&lt;points&gt;
begin_&lt;grid&gt; ue_grid
project_id 2
active
vertical_line no
pattern_shown no
CVxLength 10.00000
CVyLength 10.00000
CVzLength 10.00000
AutoPatternScale
ShowDescription yes
CVsVisible no
CVsThickness 3
begin_&lt;location&gt; 
begin_&lt;reference&gt; 
cartesian
longitude -0.000000000000000
latitude -0.000000000000000
visible no
terrain
end_&lt;reference&gt;
side1 180.00000
side2 120.00000
spacing 2.00000
nVertices 1
-90.000000000000000 -60.000000000000000 1.500000000000000
end_&lt;location&gt;
pattern_show_arrow no
pattern_show_as_sphere no
generate_p2p no
use_apg_acceleration yes
is_transmitter no
is_receiver yes
begin_&lt;receiver&gt; 
begin_&lt;pattern&gt; 
antenna 1
waveform 1
rotation_x 0.00000
rotation_y 0.00000
rotation_z 0.00000
end_&lt;pattern&gt;
NoiseFigure 3.00000
end_&lt;receiver&gt;
powerDistribution Uniform 10.00000 10.00000 inactive nosampling 10
end_&lt;grid&gt;
</code>
</pre>
</details>


The most important thing that I noticed is that there seem to be opening and closings tags that look like `begin_<...>` / `end_<...>` and in between those you get lines of values, and possibly more nested tags.

Here is an excerpt of the TXRX file that shows this structure.
To make it easier to visualise, I will indent the lines within opening / closing tags:

```txrx
begin_<points> BS
    project_id 1
    AutoPatternScale
    CVsThickness 3
    begin_<location>
        begin_<reference>
            longitude -0.000000000000000
            latitude 0.000000000000000
            terrain
        end_<reference>
        -33.000000000000000 11.000000000000000 32.000000000000000
    end_<location>
    generate_p2p yes
    use_apg_acceleration no
    begin_<transmitter> 
        begin_<pattern> 
            rotation_x 0.00000
        end_<pattern>
        power 0.00000
    end_<transmitter>
    begin_<receiver> 
        begin_<pattern> 
            waveform 1
            rotation_z 0.00000
        end_<pattern>
        NoiseFigure 3.00000
    end_<receiver>
    powerDistribution Uniform 10.00000 10.00000 inactive nosampling 10
end_<points>
```


## TXRX format grammar

With this in mind, the first thing I did was write a pseudo [BNF grammar](https://en.wikipedia.org/wiki/Backus–Naur_form) that represents the format of the data.
That's because, with the grammar in place, writing the parser is much easier.

This is what I came up with:

```bnf
document := node* EOF
node := BEGIN_TAG TAG_NAME? values END_TAG NL
values := (node | line_value)*
line_value := (STR | "yes" | "no" | INT | FLOAT)+ NL
```


## Tokenizing the file

To make parsing easier, I also decided to write a tiny tokenizer.
The function `tokenize_file` accepts the path to a TXRX field and creates an iterator that produces whitespace-separated tokens, together with a “newline token” at the end of each line.
For this file format, processing one “token” at a time is almost the same as processing a line at a time, but the token approach is more easily extensible, so I went with it:

```py
NL_TOKEN = "\n"


def tokenize_file(path):
    """Breaks a TXRX file into whitespace-separated tokens."""

    with open(path, "r") as f:
        for line in f:
            yield from line.split()
            yield NL_TOKEN


if __name__ == "__main__":
    print(list(tokenize_file("sample.txrx")))
```

If you run that code, you get a big list of tokens:

```
['begin_<points>', 'BS', '\n', 'project_id', '1', '\n', 'active', '\n', 'vertical_line', 'no', '\n', ...]
```


## Creating a peekable iterator

The next step is to define a helper class to manipulate the stream of tokens.
The generator is something we can only advance by using the built-in `next` but when parsing it is often useful to peek at the next token (if there is one) to check what it will look like.
That's why I defined the class `peekable`:

```py
# ...

class peekable:
    """Makes it possible to peek at the next value of an iterator."""

    def __init__(self, iterator):
        self._iterator = iterator
        # Unique sentinel used as flag.
        self._sentinel = object()
        self._next = self._sentinel

    def peek(self):
        """Peeks at the next value of the iterator, if any."""
        if self._next is self._sentinel:
            self._next = next(self._iterator)
        return self._next

    def has_values(self):
        """Check if the iterator has any values left."""
        if self._next is self._sentinel:
            try:
                self._next = next(self._iterator)
            except StopIteration:
                pass
        return self._next is not self._sentinel

    def __iter__(self):
        """Implement the iterator protocol for `peekable`."""
        return self

    def __next__(self):
        """Implement the iterator protocol for `peekable`."""
        if (next_value := self._next) is not self._sentinel:
            self._next = self._sentinel
            return next_value
        return next(self._iterator)


if __name__ == "__main__":
    tokens = peekable(tokenize_file("sample.txrx"))
    # Peeking doesn't advance the tokenizer.
    print(tokens.peek(), tokens.peek(), tokens.peek())  # begin_<points> begin_<points> begin_<points>
    print(next(tokens))  # begin_<points>
    print(tokens.has_values())  # True
    for value in tokens:  # Exhaust the tokens.
        pass
    print(tokens.has_values())  # False
```


## Implementing the parser

### Parser skeleton

Now that we have the tokenizer and the required helper class we can implement the parser.
To implement a parser from the grammar we defined, we are going to create a function for each of the grammar rules:

```py
# ...
def parse_document(tokens):
    """Parse a TXRX document."""
    raise NotImplementedError()


def parse_node(tokens):
    """Parse a begin_<...> / end_<...> node.

    Returns the node name and the node.
    """
    raise NotImplementedError()


def parse_values(tokens):
    """Parse the lines of values within a node.

    Returns a list of line values.
    """
    raise NotImplementedError()


def parse_line_value(tokens):
    """Parse a line with a single-line value.

    Returns a tuple with all the values in that line.
    """
    raise NotImplementedError()
```

On top of that, we define some regular expressions to help us identify different types of values (integers, floats, the strings `"yes"` and `"no"`, and the opening/closing tags for nodes).
We also define an auxiliary function `eat`, which takes the stream of tokens and a string and makes sure that the next token is what I expect it to be:

```py
import re

RE_BOOL_TRUE = re.compile(r"yes")
RE_BOOL_FALSE = re.compile(r"no")
RE_BEGIN_NODE = re.compile(r"begin_<(?P<node_name>\S*)>")
RE_END_NODE = re.compile(r"end_<(?P<node_name>\S*)>")
RE_INT = re.compile(r"-?\d+")
RE_FLOAT = re.compile(r"-?\d+[.]\d+")
RE_LABEL = re.compile(r"\S+")

# ...

def eat(tokens, expected):
    """Ensures the next token is what's expected."""
    if (tok := next(tokens)) != expected:
        raise RuntimeError(f"Expected token {expected!r}, got {tok!r}.")

# ...
```


### Parsing result

We will be parsing the TXRX files mostly into dictionaries, lists, and tuples.
A node will be parsed into a dictionary with the following structure:

```py
node = {
    "name": None,
    "values": {...},
    "labels": [...],
    "data": [...],
}
```

- The key `name` will map to the optional name that sometimes appears in front of the tag `begin_<...>`.
- The key `values` will map to a dictionary of all the values within the node that start with a label and then have the value(s).
This includes other nodes and lines like `rotation_x 0.000000` or `is_transmitter no`.
- The key `labels` will list all of the single-string lines like `AutoPatternScale`.
- The key `data` will hold tuples of lines of values that do not start with a label, like the line `-33.000000000000000 11.000000000000000 32.000000000000000`.


### Parsing the document

To parse the grammar rule `document := node* EOF` we just need to write a loop that calls `parse_node` repeatedly.
I'm assuming the top-level document is _only_ composed of nodes (and no data) and that nodes have unique names.
If that is not the case, the code would need to be tweaked to collect all of the nodes with the same name in a list, for example.

If I were familiar with the format TXRX, this probably wouldn't be an issue because I would know the format spec.
Since I don't, I will bake these assumptions into my code and I will make sure to raise an error if one of the assumptions is broken:

```py
def parse_document(tokens):
    """Parse a TXRX document."""
    if not isinstance(tokens, peekable):
        tokens = peekable(tokens)

    document = {}
    while tokens.has_values():
        tok = tokens.peek()
        if not RE_BEGIN_NODE.match(tok):
            raise RuntimeError(f"Non node {tok!r} at the top-level of the document.")

        node_name, node = parse_node(tokens)
        if node_name in document:
            raise RuntimeError(f"Node with duplicate name {node_name} found.")
        document[node_name] = node
    return document
```


### Parsing a node

To parse the grammar rule `node := node := BEGIN_TAG TAG_NAME? values END_TAG NL` we have to parse a series of tokens in sequence:

```py
def parse_node(tokens):
    """Parse a begin_<...> / end_<...> node.

    Returns the node name and the node.
    """
    node = {
        "name": None,
        "values": {},
        "labels": [],
        "data": [],
    }
    begin_tag = next(tokens)
    begin_match = RE_BEGIN_NODE.match(begin_tag)
    node_name = begin_match.group("node_name")

    # Is there a name?
    if tokens.peek() != NL_TOKEN:
        node["name"] = next(tokens)

    eat(tokens, NL_TOKEN)

    # Parse the values and put them in the node dictionary.
    for value in parse_values(tokens):
        # What does the value look like?
        match value:
            case (str(label),):  # Is it a single label?
                node["labels"].append(label)
            case (str(label), value):  # Is it a label / value pair?
                node["values"][label] = value
            case str(label), *rest:  # Is it a label followed by 2+ values?
                node["values"][label] = rest
            case _:  # Is it data without a label?
                node["data"].append(value)

    # Parse the closing tag and newline.
    eat(tokens, f"end_<{node_name}>")
    eat(tokens, NL_TOKEN)

    return node_name, node
```


This is the longer function because it is parsing the most complex rule while also putting all of the data in the correct keys.


### Parsing the values

To parse the values rule `values := (node | line_value)*` we just need to disambiguate between a node and a generic line of values and collect everything in a list.

```py
def parse_values(tokens):
    """Parse the lines of values within a node.

    Returns a list of line values.
    """
    lines = []

    while tokens.has_values():
        tok = tokens.peek()

        if RE_END_NODE.match(tok):
            return lines
        elif RE_BEGIN_NODE.match(tok):
            lines.append(parse_node(tokens))
        else:
            lines.append(parse_line_value(tokens))

    return lines
```


### Parsing a line of values

Finally, we parse the grammar rule `line_value := (STR | "yes" | "no" | INT | FLOAT)+ NL` by consuming all of the tokens until we reach the newline token and by making sure we do the appropriate conversions, for example by converting the `"yes"` / `"no"` to the Boolean values `True` / `False`:

```py
def parse_line_value(tokens):
    """Parse a line with a single-line value.

    Returns a tuple with all the values in that line.
    """
    values = []

    while tokens.has_values() and tokens.peek() != NL_TOKEN:
        tok = next(tokens)
        if RE_BOOL_TRUE.match(tok):
            values.append(True)
        elif RE_BOOL_FALSE.match(tok):
            values.append(False)
        elif RE_FLOAT.match(tok):
            values.append(float(tok))
        elif RE_INT.match(tok):
            values.append(int(tok))
        else:
            values.append(tok)
    eat(tokens, NL_TOKEN)
    return tuple(values)
```


## Running the parser

We can run the parser on the file `sample.txrx` to check that it works.
First, we modify the bottom of the file:

```py
if __name__ == "__main__":
    tokens = peekable(tokenize_file("sample.txrx"))
    document = parse_document(tokens)
```

Now, if you run your file with `python -i parser.py` it will run and then it will drop you in the REPL with the functions you've defined, the globals, and also the variable `document`:

```pycon
❯ python -i parser.py
>>> document["points"]
{'name': 'BS', 'values': {}, 'labels': [...], 'data': []}
>>> document["points"]["name"]
'BS'
>>> document["points"]["values"]["location"]
{'name': None, 'values': {'reference': {'name': None, 'values': {'longitude': -0.0, 'latitude': 0.0, 'visible': False}, 'labels': ['cartesian', 'terrain'], 'data': []}, 'nVertices': 1}, 'labels': [], 'data': [(-33.0, 11.0, 32.0)]}
>>> document["points"]["values"]["location"]["values"]["reference"]["values"]["\
longitude"]
-0.0
```

One thing that we can see from this interaction is that it is quite cumbersome to access nested nodes because we need to access the key `"values"` at every level.
To simplify this, we can introduce an auxiliary dataclass to represent a node.


## Creating a node dataclass

The dataclass `Node` makes it more convenient to work with the data:

```py
@dataclass
class Node:
    """Node to represent a section delimited by begin_<...> / end_<...>.

    Provides the attributes name, values, labels, and data.

    - name is the optional name in front of the tag begin_<...>;
    - values is a dictionary mapping labels to values;
    - labels is a list of labels; and
    - data is a list of tuples with unlabeled data.

    The dictionary `values` can be accessed directly by keying the node instance
    itself, e.g., node["xyz"] is equivalent to node["values"]["xyz"].
    """

    name: str | None = None
    values: dict = field(default_factory=dict)
    labels: list = field(default_factory=list)
    data: list = field(default_factory=list)

    def __getitem__(self, key):
        """Dispatch `node[key]` to `node.values[key]`."""
        return self.values.__getitem__(key)

    def __setitem__(self, key, value):
        """Dispatch `node[key] = value` to `node.values[key] = value`."""
        return self.values.__setitem__(key, value)

    def __delitem__(self, key):
        """Dispatch `del node[key]` to `del node.values[key]`."""
        return self.values.__delitem__(key)
```

After implementing `Node`, we need to update the function `parse_node`:

```py
def parse_node(tokens):
    """Parse a begin_<...> / end_<...> node.

    Returns the node name and the node.
    """
    node = Node()
    begin_tag = next(tokens)
    begin_match = RE_BEGIN_NODE.match(begin_tag)
    node_name = begin_match.group("node_name")

    if tokens.peek() != NL_TOKEN:
        node.name = next(tokens)  # <--

    eat(tokens, NL_TOKEN)

    for value in parse_values(tokens):
        match value:
            case (str(label),):
                node.labels.append(label)  # <--
            case (str(label), value):
                node[label] = value  # <--
            case str(label), *rest:
                node[label] = rest  # <--
            case _:
                node.data.append(value)  # <--

    # ...
```

Now it is much more convenient to access nested data:

```pycon
# Before:
# document["points"]["values"]["location"]["values"]["reference"]["values"]["longitude"]

# Now:
>>> document["points"]["location"]["reference"]["longitude"]
-0.0
```


## Full parser code

For your convenience, you can find the full code below.

<details>
<summary>File <code>parser.py</code></summary>

<pre><code class="language-py">"""Parses a TXRX file into a Python object that can be easily queried.

The pseudo-grammar for a TXRX file looks like this:

document := node* EOF
node := BEGIN_TAG TAG_NAME? values END_TAG NL
values := (node | line_value)*
line_value := (STR | "yes" | "no" | INT | FLOAT)+ NL
"""

from dataclasses import dataclass, field
import re

RE_BOOL_TRUE = re.compile(r"yes")
RE_BOOL_FALSE = re.compile(r"no")
RE_BEGIN_NODE = re.compile(r"begin_&lt;(?P&lt;node_name&gt;\S*)&gt;")
RE_END_NODE = re.compile(r"end_&lt;(?P&lt;node_name&gt;\S*)&gt;")
RE_INT = re.compile(r"-?\d+")
RE_FLOAT = re.compile(r"-?\d+[.]\d+")
RE_LABEL = re.compile(r"\S+")

NL_TOKEN = "\n"


def tokenize_file(path):
    """Breaks a TXRX file into whitespace-separated tokens."""

    with open(path, "r") as f:
        for line in f:
            yield from line.split()
            yield NL_TOKEN


class peekable:
    """Makes it possible to peek at the next value of an iterator."""

    def __init__(self, iterator):
        self._iterator = iterator
        # Unique sentinel used as flag.
        self._sentinel = object()
        self._next = self._sentinel

    def peek(self):
        """Peeks at the next value of the iterator, if any."""
        if self._next is self._sentinel:
            self._next = next(self._iterator)
        return self._next

    def has_values(self):
        """Check if the iterator has any values left."""
        if self._next is self._sentinel:
            try:
                self._next = next(self._iterator)
            except StopIteration:
                pass
        return self._next is not self._sentinel

    def __iter__(self):
        """Implement the iterator protocol for `peekable`."""
        return self

    def __next__(self):
        """Implement the iterator protocol for `peekable`."""
        if (next_value := self._next) is not self._sentinel:
            self._next = self._sentinel
            return next_value
        return next(self._iterator)


@dataclass
class Node:
    """Node to represent a section delimited by begin_&lt;...&gt; / end_&lt;...&gt;.

    Provides the attributes name, values, labels, and data.

    - name is the optional name in front of the tag begin_&lt;...&gt;;
    - values is a dictionary mapping labels to values;
    - labels is a list of labels; and
    - data is a list of tuples with unlabeled data.

    The dictionary `values` can be accessed directly by keying the node instance
    itself, e.g., node["xyz"] is equivalent to node["values"]["xyz"].
    """

    name: str | None = None
    values: dict = field(default_factory=dict)
    labels: list = field(default_factory=list)
    data: list = field(default_factory=list)

    def __getitem__(self, key):
        """Dispatch `node[key]` to `node.values[key]`."""
        return self.values.__getitem__(key)

    def __setitem__(self, key, value):
        """Dispatch `node[key] = value` to `node.values[key] = value`."""
        return self.values.__setitem__(key, value)

    def __delitem__(self, key):
        """Dispatch `del node[key]` to `del node.values[key]`."""
        return self.values.__delitem__(key)


def eat(tokens, expected):
    """Ensures the next token is what's expected."""
    if (tok := next(tokens)) != expected:
        raise RuntimeError(f"Expected token {expected!r}, got {tok!r}.")


def parse_document(tokens):
    """Parse a TXRX document."""
    if not isinstance(tokens, peekable):
        tokens = peekable(tokens)

    document = {}
    while tokens.has_values():
        tok = tokens.peek()
        if not RE_BEGIN_NODE.match(tok):
            raise RuntimeError(f"Non node {tok!r} at the top-level of the document.")

        node_name, node = parse_node(tokens)
        if node_name in document:
            raise RuntimeError(f"Node with duplicate name {node_name} found.")
        document[node_name] = node
    return document


def parse_node(tokens):
    """Parse a begin_&lt;...&gt; / end_&lt;...&gt; node.

    Returns the node name and the node.
    """
    node = Node()
    begin_tag = next(tokens)
    begin_match = RE_BEGIN_NODE.match(begin_tag)
    node_name = begin_match.group("node_name")

    # Is there a name?
    if tokens.peek() != NL_TOKEN:
        node.name = next(tokens)

    eat(tokens, NL_TOKEN)

    # Parse the values and put them in the node dictionary.
    for value in parse_values(tokens):
        # What does the value look like?
        match value:
            case (str(label),):  # Is it a single label?
                node.labels.append(label)
            case (str(label), value):  # Is it a label / value pair?
                node[label] = value
            case str(label), *rest:  # Is it a label followed by 2+ values?
                node[label] = rest
            case _:  # Is it data without a label?
                node.data.append(value)

    # Parse the closing tag and newline.
    eat(tokens, f"end_&lt;{node_name}&gt;")
    eat(tokens, NL_TOKEN)

    return node_name, node


def parse_values(tokens):
    """Parse the lines of values within a node.

    Returns a list of line values.
    """
    lines = []

    while tokens.has_values():
        tok = tokens.peek()

        if RE_END_NODE.match(tok):
            return lines
        elif RE_BEGIN_NODE.match(tok):
            lines.append(parse_node(tokens))
        else:
            lines.append(parse_line_value(tokens))

    return lines


def parse_line_value(tokens):
    """Parse a line with a single-line value.

    Returns a tuple with all the values in that line.
    """
    values = []

    while tokens.has_values() and tokens.peek() != NL_TOKEN:
        tok = next(tokens)
        if RE_BOOL_TRUE.match(tok):
            values.append(True)
        elif RE_BOOL_FALSE.match(tok):
            values.append(False)
        elif RE_FLOAT.match(tok):
            values.append(float(tok))
        elif RE_INT.match(tok):
            values.append(int(tok))
        else:
            values.append(tok)
    eat(tokens, NL_TOKEN)
    return tuple(values)


if __name__ == "__main__":
    tokens = tokenize_file("sample.txrx")
    document = parse_document(tokens)
</code></pre>

</details>

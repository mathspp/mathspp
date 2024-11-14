"""Parses a TXRX file into a Python object that can be easily queried.

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
RE_BEGIN_NODE = re.compile(r"begin_<(?P<node_name>\S*)>")
RE_END_NODE = re.compile(r"end_<(?P<node_name>\S*)>")
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
    """Parse a begin_<...> / end_<...> node.

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
    eat(tokens, f"end_<{node_name}>")
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
    tokens = peekable(tokenize_file("sample.txrx"))
    document = parse_document(tokens)

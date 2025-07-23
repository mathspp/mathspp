Today I learned about the piece table data structure.

===


## Piece table

A piece table is a data structure commonly used in text editors.
It is similar to a linked list, but instead of each node containing one single item (a character), each node contains a span of the data (a substring).

So, instead of a structure that would look like

 > H > e > l > l > o > _ > w > o > r > l > d

you could have something like

 > Hel > lo worl > d

In the context of a text editor, the idea is that these spans of contiguous data represent spans of text that the user hasn't edited yet.
Then, whenever the user adds or removes text, we add or remove corresponding spans.

According to the sources I found, text editors may typically use other bits and bobs together with this data structure to handle the text and user input.
Here, I provide a minimal toy implementation that modifies the piece table through insertions, deletions, and replacements, of arbitrary text.

My class `PieceTable` implements three methods:

 1. `insert(idx, text)` – inserts the string `text` at index `idx`, so that the first character of `text` ends up at the index `idx`;
 2. `delete(begin, end)` – deletes the substring from indices `begin` up to `end`, including the beginning and excluding the end (like a slice `obj[begin:end]`); and
 3. `replace(begin, end, replacement)` – replaces the text between indices `begin` and `end` with `replacemente`, regardless of the length of `replacement`.

Here are some examples:

```py
pt = PieceTable("hey world")  # Initial data.
pt.replace(5, 7, "!!!")  # "hey w!!!ld"
pt.delete(5, 8)  # "hey wld"
pt.insert(5, "or")  # "hey world"
pt.delete(2, 6)  # "herld"
pt.insert(0, "A new word: ")  # "A new word: herld"
pt.insert(15, "a")  # "A new word: herald"
pt.delete(0, 0) # "A new word: herald"
pt.replace(1, 5, "n existing")  # "An existing word: herald"
```


### Python implementation of a piece table

I provide a toy implementation below.
To reduce the amount of work I had to do, I decided to implement `insert` and `delete` at the expense of `replace`.
All I needed to do was realise that

 - `insert(idx, text) == replace(idx, idx, text)`; and
 - `delete(begin, end) == replace(begin, end, "")`.

Additionally, to make the code a bit simpler, I added a “sentinel node” that is always present at the beginning of the piece table and that holds no data whatsoever.
This made it simpler to insert text at the beginning.

So, here is the code:

```py
from __future__ import annotations

from dataclasses import dataclass
from typing import Generator


@dataclass
class _Node:
    next: "_Node" | None
    data: str
    start_idx: int


@dataclass
class _SentinelNode(_Node):
    next: _Node | None
    data: str = ""
    start_idx: int = 0


class PieceTable:
    _pieces: = _SentinelNode(None)

    def __init__(self, initial_data):
        if initial_data is not None:
            self._pieces = _SentinelNode(_Node(None, initial_data, 0))

    def insert(self, start_idx, data):
        return self.replace(start_idx, start_idx, data)

    def delete(self, start_idx, end_idx):
        return self.replace(start_idx, end_idx, "")

    def replace(self, start_idx, end_idx, data):
        # Go up until we find the node within which the `start_idx` is contained.
        prev_node = None
        node = self._pieces
        while node and start_idx > node.start_idx + len(node.data):
            prev_node = node
            node = prev_node.next

        # If we have no node, append a new node to the end and be done.
        if not node:
            assert prev_node is not None
            prev_node.next = _Node(None, data, prev_node.start_idx + len(data))
            return

        """
        At this stage, we split `node`:
        node:
            start_idx = 0
            data = "hello world"
            h e y _ w o r l d
            0 1 2 3 4 5 6 7 8
        start_idx = 5, data = "!!!"
        result:
            h e y _ w | o r l d
            0 1 2 3 4   5 6 7 8
        """
        # If needed, split this node at the start_idx.
        if start_idx < node.start_idx + len(node.data):
            offset = start_idx - node.start_idx
            prev_data, next_data = node.data[:offset], node.data[offset:]
            new_node = _Node(node.next, next_data, node.start_idx + offset)
            node.next = new_node
            node.data = prev_data

        anchor_node = node
        prev_node = anchor_node
        node = prev_node.next
        # At this point, `anchor_node` is the last node that isn't influenced.
        # Now, we need to go forward looking for the nodes that will get deleted
        # up until we reach the end node.
        to_delete[_Node] = []
        while node and end_idx >= node.start_idx + len(node.data):
            to_delete.append(node)
            prev_node = node
            node = prev_node.next

        """
        At this stage, we split `node`:
        node:
            start_idx = 5
            data = "orld"
            o r l d
            5 6 7 8
        end_idx = 7, data = "!!!"
        result:
            o r | l d
            5 6   7 8
        """
        # If needed, split this node at `end_idx`.
        if node and end_idx < node.start_idx + len(node.data):
            offset = end_idx - node.start_idx
            prev_data, next_data = node.data[:offset], node.data[offset:]
            new_node = _Node(node.next, next_data, node.start_idx + offset)
            node.next = new_node
            node.data = prev_data
            to_delete.append(node)

        # Delete everything that needs to be deleted but keep track of the first node
        # at the right end of the nodes to be deleted.
        first_node_at_right = to_delete[-1].next if to_delete else anchor_node.next
        del to_delete
        if data:
            new_data_node = _Node(
                first_node_at_right, data, anchor_node.start_idx + len(anchor_node.data)
            )
        else:
            new_data_node = first_node_at_right
        anchor_node.next = new_data_node

        if first_node_at_right is not None:
            # Make sure we update the `start_idx` of the remaining nodes.
            prev_node, next_node = new_data_node, first_node_at_right
            while next_node:
                next_node.start_idx = prev_node.start_idx + len(prev_node.data)
                prev_node = next_node
                next_node = prev_node.next

    def __iter__(self):
        node = self._pieces
        while node:
            yield node
            node = node.next

    def __repr__(self):
        return f"PieceTable<{self.data}>"

    @property
    def data(self):
        return "".join(node.data for node in self)


if __name__ == "__main__":
    pt = PieceTable("hey world")
    pt.replace(5, 7, "!!!")
    assert pt.data == "hey w!!!ld"
    pt.delete(5, 8)
    assert pt.data == "hey wld"
    pt.insert(5, "or")
    assert pt.data == "hey world"
    pt.delete(2, 6)
    assert pt.data == "herld"
    pt.insert(0, "A new word: ")
    assert pt.data == "A new word: herld"
    pt.insert(15, "a")
    assert pt.data == "A new word: herald"
    pt.delete(0, 0)
    assert pt.data == "A new word: herald"
    pt.replace(1, 5, "n existing")
    assert pt.data == "An existing word: herald"
```

Today I learned that `collections.deque` is implemented as a doubly-linked list of blocks.

===

## `collections.deque`

I've written about the data structure `deque` from the module `collections` extensively.
In particular, I wrote a [`deque` tutorial with plenty of practical example use cases of `deque`](/blog/python-deque-tutorial).

Today, after some discussion during a cohort I was teaching, a student [sent a link to the `collections.deque` source code](https://github.com/python/cpython/blob/d948eaa366029bc358dbe9cf32d545c3ad30c502/Modules/_collectionsmodule.c#L82-L127) where a comment explains some of the lower-level details of how a `deque` is implemented.

## Double-ended queue

The name “deque” stands for Double-Ended QUEue and that's because a deque is a doubly-linked list.
When you learn about that, you might think that, under the hood, a `deque` is essentially a collection of nodes that link to the next and to the previous:

```py
class Node:
    prev_node: Node | None
    value: object
    next_node: Node | None

class deque:
    first_node: Node | None
    ...
```

But that's not the case.
Apparently, a `deque` is implemented in a more optimised way, where each “node” is actually a block that can hold up to a certain number of elements.
At the level of C, this means you need to manipulate memory less often, which makes the `deque` faster.

## Pseudo-implementation of `deque` in Python

The Python code below is a pseudo-implementation of `deque` that mimics more or less the underlying block mechanism that's in place for Python 3.15 (and that _has been in place for decades_).
The Python code below is stripped of all of the memory operations and other lower-level details and instead focuses on the mechanics of managing blocks:

```py
from dataclasses import dataclass, field


BLOCKLEN = 64
CENTRE = (BLOCKLEN - 1) // 2


def new_empty_block_data() -> list[object]:
    return [None for _ in range(BLOCKLEN)]


@dataclass
class Block:
    left_link: Block | None = None
    data: list[object] = field(default_factory=new_empty_block_data)
    right_link: Block | None = None


@dataclass(init=False)
class deque:
    left_block: Block
    right_block: Block
    left_index: int
    right_index: int
    maxlen: int

    def __init__(self, maxlen: int | None = None) -> None:
        self.left_block = self.right_block = Block()
        self.left_index = CENTRE + 1
        self.right_index = CENTRE
        if maxlen is None:
            maxlen = -1
        self.maxlen = maxlen
```

The two classes `Block` and `deque` set the structure for the `deque`.
The attributes `left_block` and `right_block` point, respectively, to the leftmost block and the rightmost block, and the first item of a deque `d` is always found at `d.left_block[d.left_index]` while the last item is at `d.right_block[d.right_index]`.

With this in mind, adding or removing elements from a deque is a matter of managing the blocks and the indices correctly.
Below, you can find the pseudo-implementation of `append` and `pop`:

```py
@dataclass
class deque:
    ...

    def append(self, item: object) -> None:
        # If there's no space, create a new block.
        if self.right_index == BLOCKLEN - 1:
            new_block = Block()
            self.right_block.right_link = new_block
            new_block.left_link = self.right_block
            self.right_block = new_block
            self.right_index = -1

        self.right_index += 1
        self.right_block[self.right_index] = item

        if self.maxlen > -1 and len(self) > self.maxlen:
            self.popleft()

    def pop(self) -> object:
        item = self.right_block[self.right_index]
        self.right_index -= 1

        # Did we just empty this block?
        if self.right_index < 0:
            # Are there blocks to the left of the rightmost block?
            if self.right_block.left_link is not None:
                new_right = self.right_block
                # Disconnect the two:
                self.right_block.left_link = None
                new_right.right_link = None
                self.right_block = new_right
                self.right_index = BLOCKLEN - 1
            # If not, the deque is empty. Recentre the last block.
            else:
                self.left_index = CENTRE + 1
                self.right_index = CENTRE

        return item
```

For `popleft` and `appendleft`, you'd do the symmetrical.

!!! Remember that the real implementation has to do more work than what's shown here.

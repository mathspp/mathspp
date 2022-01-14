Today I learned about Spouge's formula to approximate the factorial.

===

![The Python import statement that allows us to use the Python module `selectors`.](thumbnail.png)


# `selectors`

Following up on some of [my recent efforts to learn socket programming][sockets-for-dummies],
I learned about the Python module `selectors`.
This module is very helpful when you need to manage multiple socket connections
and you don't want to spawn a new thread to handle each socket separately.

The relevance of this module arises from the fact that, if nothing else is done,
calling the method `recv` method of a client socket – or the method `accept` of a server socket – blocks.
For example, for the server socket, this means that the call to `accept` won't return while no connection is accepted.

Try pasting this code in a script and run it:

```py
# server.py
import socket

server = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
server.bind(("localhost", 7342))
server.listen()
print("About to accept:")
server.accept()
print("Call to `accept` returned.")
```

If you run this with `python server.py`, the message `"About to accept:"` gets printed,
but not the message ``"Call to `accept` returned."``.
Why?
Because there is no connection to be accept, and so the call to `accept` blocks,
waiting for a connection.

Thus, we can't _just_ call the method `recv` on the different clients we have,
because if one doesn't have anything for us, we will block!
A common way to deal with this is by spawning a thread for each client,
and do the blocking calls in those separate threads.

Another way to deal with this is with the module `selectors`.

Here is my intuitive understanding of what the module `selectors` does:
you take all the sockets that you care about, and you put them in a big bag of sockets.
Then, the module `selectors` can take a look at the bag and give you the sockets
that are ready to be read from/written to; you just have to ask.

For example, here is how you can use the module `selectors` to only
try accepting a connection to the server socket when one is ready:

```py
# server.py

import selectors
import socket

# Set up the server
server = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
server.bind(("localhost", 7342))
server.listen()

# Set up the selectors "bag of sockets"
selector = selectors.DefaultSelector()
selector.register(server, selectors.EVENT_READ)

while True:
    events = selector.select()
    for key, _ in events:
        sock = key.fileobj
        print("About to accept.")
        client, _ = sock.accept()
        print("Accepted.")
```

If you paste this code in a file `server.py` and run it with `python server.py`,
you will see that it hangs once more, but it didn't hang in the call to the method `accept`.

If you open a new REPL, create a client socket, and connect to the server,
you will see that the two messages `"About to accept."` and `"Accepted."` get printed back to back.

The GIF below shows that the two messages get printed pretty much at the same time,
proving that the code is not hanging because of the call to `accept`:

![GIF that proves that the Python module `selectors` allows multiplexing between sockets.](_selectors_accept_doesnt_block.gif)


# Registering with a callback

There are many, _many_ details I didn't cover about the module `selectors` and the things that are actually happening in that code.
You can take a look [at the documentation][selectors-docs] for more information.

However, there is one more thing I'd like to briefly mention here,
and that is the fact that the method `register` can accept a third argument,
an arbitrary object representing some data that you can retrieve later.

In other words, when you register a socket,
you can associate some arbitrary data with that socket.
Later on, when that socket is returned from the call to the method `select`,
you can access that data you registered the socket with.
A pattern that seems pretty helpful, and that is also seen in the `selectors` documentation,
is to register a socket with a callback that will then handle that socket's events.

Here is an example, associating the server socket with a callback that accepts connections:

```py
# server.py

import selectors
import socket


def accept_connection(server):
    print("About to accept.")
    server.accept()
    print("Accepted.")

# Set up the server
server = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
server.bind(("localhost", 7342))
server.listen()

# Set up the selectors "bag of sockets"
selector = selectors.DefaultSelector()
selector.register(server, selectors.EVENT_READ, accept_connection)

while True:
    events = selector.select()
    for key, _ in events:
        sock = key.fileobj
        callback = key.data
        callback(sock)
```


That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe
[sockets-for-dummies]: /blog/sockets-for-dummies
[selectors-docs]: https://docs.python.org/3/library/selectors

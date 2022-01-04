Today I learned the basics of socket programming (in Python).

===

<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

![A tunnel, alluding to my intuitive understanding of what a socket is.](thumbnail.png)


# Sockets

When the day started, I had _very little knowledge_ about sockets.
My intuitive understanding was that they were like tunnels that allowed
different programs to communicate between themselves.

Now that the day is ending, I still don't know _much_ about sockets,
but I did spend the day reading about them and experimenting with them.

I have been documenting the process publicly:

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">Today, I&#39;m spending the day learning in public about socket programming (in Python 🐍).<br><br>My starting point is this:<br><br>“I think sockets are like tunnels/roads that allow different programs to talk to each other directly.”<br><br>This 🧵 will evolve as I learn and experiment 👇</p>&mdash; Rodrigo 🐍📝 (@mathsppblog) <a href="https://twitter.com/mathsppblog/status/1478321570892320768?ref_src=twsrc%5Etfw">January 4, 2022</a></blockquote>

This helps me make sure I learn as much as possible.
It also helps, because others chime in with interesting suggestions quite often.

So, “today I learned about sockets”.

I'll show you how you can create two sockets that communicate with each other.

In order to do this, let's open two Python terminals,
and put them side by side.

I'll walk you through the things you have to do,
just make sure to write each piece of code in the correct REPL:

 - one will be the “server side”; and
 - the other will be the “client side”.

To create the server, we

 - create a new socket;
 - bind it to the localhost and a random port number (I like 7342);
 - start listening on that host and port; and
 - accept an incoming connection:

```py
# (SERVER)
>>> import socket
>>> server = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
>>> server.bind(("localhost", 7342))  # 1
>>> server.listen()  # 2
>>> client, address = server.accept()  # 3
```

At this point, the code hangs because we are waiting for a client to connect.

To create a client, we do the exact same first step, but then we connect to the host and port:

```py
# (CLIENT)
>>> import socket
>>> client = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
>>> client.connect(("localhost", 7342))  # 4
```

At this point, the server side is no longer hanging, because a client connected
and the connection was automatically accepted.

Now, either side can send the first message.
If this were a communication through an established protocol,
we would know the socket that is expected to send the first message.
For example, on the web, with the browsers,
browsers are expected to send the first message (the request) to the server.
Only then the server replies.

For our toy experiment, let's have the client send the first (and only)
message with some data, which the server will give back in reverse.

Sending the data means calling the method `.send`:

```py
# (CLIENT)
>>> client.send(b"Hello, world!")  # 5
13
```

Now that we sent some data, the server can receive it and handle it.
In our case, we receive the data, and then return it reversed.

After we reverse the data and send it back, we close the socket.
In our toy example, we assume that the client can only use the socket once,
which means that the server has no reason to keep the socket open after returning
the reversed data.

Here is the code that implements the steps described:

```py
# (SERVER)
>>> data = client.recv(1024)
>>> print(data.decode("utf8"))
Hello, world!
>>> response = bytes(reversed(data))
>>> client.send(response)
13
>>> client.close()
```

While this is happening, the client side is waiting to receive a message back,
which we can receive and print:

```py
# (CLIENT)
>>> back = client.recv(1024)
>>> print(back.decode("utf8"))
!dlrow ,olleH
```

After this, we can see that the connection on the server side was indeed closed.
This is signalled by the fact that calling the method `.recv` once more returns an empty bytes sequence.
This is how sockets signal that the other end was closed;
if the server side wasn't closed but the server hadn't sent any (more) data yet,
the method call would just hang and wait for data to be received.

So, here is our verification that the server side was indeed closed,
after which we close our end of the connection too:

```py
# (CLIENT)
>>> client.recv(1024)
b''
>>> client.close()
```

That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe
[pydont-negative-indexing]: /blog/pydonts/sequence-indexing#negative-indices
[docs-bitwise-invert]: https://docs.python.org/3/reference/expressions.html#unary-arithmetic-and-bitwise-operations

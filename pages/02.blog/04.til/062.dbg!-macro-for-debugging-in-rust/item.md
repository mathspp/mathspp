Today I learned why I should use the `dbg!` macro instead of the `println!` macro for debugging in Rust.

===

## What is the `dbg!` macro in Rust?

The `dgb!` macro is a macro that is similar to `println!` but that is designed specifically for debugging.
Not only does it print the values you pass it, but it also adds a tag with the name of the file and the line from where the macro was called.

Here is an example of using the `dbg!` macro:

```rust
fn main() {
    dbg!("hello, world!");
}
```

If you run your program with `cargo run` now, this is what you get:

```bash
❯ cargo run
   Compiling borrowing v0.1.0 (/Users/rodrigogs/Documents/rust/borrowing)
    Finished dev [unoptimized + debuginfo] target(s) in 0.10s
     Running `target/debug/borrowing`
[src/main.rs:2] "hello, world" = "hello, world"
```

Notice the last line of the output, that shows the argument to the macro, along with the line of code from where the macro was called.
It may look redundant to have `"hello, world" = "hello, world"` displayed there, but that is because the macro will show the expression that was used as its argument _and_ it will show the value of that expression.

Here is another example program:

```rust
fn main() {
    dbg!(3 + 3);
}
```

This shows:

```bash
❯ cargo run
## ...
[src/main.rs:2] 3 + 3 = 6
```

Or this program:

```rust
fn main() {
    let x = 3;
    dbg!(x);
}
```

Shows:

```bash
❯ cargo run
## ...
[src/main.rs:3] x = 3
```

So, this is how you use the `dbg!` macro in Rust!


That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe

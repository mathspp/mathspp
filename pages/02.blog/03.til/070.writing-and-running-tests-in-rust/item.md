Today I learned how to write and run tests in the Rust programming language.

===

# Writing a test in Rust

I'm attending [a Rust tutorial at EuroPython 2023](https://ep2023.europython.eu/session/write-your-first-web-api-with-rust) and I just learned how to write and run tests in Rust.
Turns out it is quite straightforward!

! I'm a Rust beginner!
! I don't know if there are other ways of writing tests, possibly more advanced and/or more flexible.
! What I'm showing is just a simple way of creating tests.

Suppose you have a function that determines whether or not a given age corresponds to an adult age in Europe (or, at least, most European countries):

```rust
fn is_adult(age: u8) -> bool {
    age >= 18
}
```

In order to test this function, you create test functions (like you do in `pytest`, for example).
In `pytest`, a function is a test function whenever its name starts with `test_`.
In Rust, if you add a `#[test]` at the top of the function definition, that function is now a test function.

Then, similarly to how the keyword `assert` works in Python, you can use the macro `assert!` to check for the test result in Rust.

Here are a couple of tests for the function `is_adult`, above:

```rust
#[test]
fn is_adult_is_true_for_adults() {
    assert!(is_adult(18));
    assert!(is_adult(19));
    assert!(is_adult(123));
}

#[test]
fn is_adult_is_false_for_kids() {
    assert!( !is_adult(1) );
    assert!( !is_adult(17) );
}
```

The macro `assert!` expects a Boolean value and it will fail if the Boolean is `false`.

I know that there is, _at least_, one other assertion macro in Rust.
`assert_eq!` takes two values, compares them, and if they differ, the test fails.

Your whole file could look like this:

```rust
fn is_adult(age: u8) -> bool {
    age >= 18
}

fn main() {
    println!("What's up?");
}

#[test]
fn is_adult_is_true_for_adults() {
    assert!(is_adult(18));
    assert!(is_adult(19));
    assert!(is_adult(123));
}

#[test]
fn is_adult_is_false_for_kids() {
    assert!( !is_adult(1) );
    assert!( !is_adult(17) );
}
```


# How to run your Rust tests

To run your Rust tests, you just use the command `cargo test`.
That's it!
If you do, you should get output like this:

![Command line output of running Rust tests with the command `cargo test`.](_test_output.webp "Output of running Rust tests.")


That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe

Today I learned how to setup a Rust project that can be called from Python with PyO3 and maturin through uv.

===

When you follow the [PyO3 getting started guide](https://pyo3.rs/v0.29.2/getting-started.html) to create a simple Rust project that can be called from Python, the instructions you get assume you'll use a global Python installation to create a virtual environment and to install maturin into it.
You can use `uv` through maturin, but if your project also has a Rust binary, things may break.

When you run a command like `cargo run`, cargo will see the dependency on PyO3 and it will then look for a Python installation.
If you have no global Python installations — because you do everything through uv — or if your global installations aren't setup exactly like a vanilla, default installation, PyO3 might fail.

The fix is simple.
In `.cargo/config.toml` add the environment variable `PYO3_PYTHON` that points to the Python inside your virtual environment:

```toml
# .cargo/config.toml
[env]
PYO3_PYTHON = { value = ".venv/bin/python", relative = true }
```

## How to set up a Rust + Python project with PyO3 and maturin through uv

Here are all the steps to set up a Rust project that can be compiled into a binary executable and that can also be used from within Python:

```bash
% cargo new calculator
% cd calculator
```

Create the file `lib.rs`:

```rs
// lib.rs
pub fn add(a: i32, b: i32) -> i32 {
    a + b
}

#[pyo3::pymodule]
mod calculator {
    use pyo3::prelude::*;

    #[pyfunction]
    fn add(a: i32, b: i32) -> PyResult<i32> {
        Ok(crate::add(a, b))
    }
}
```

And update the file `main.rs` to depend on your calculator:

```rs
use calculator::add;

fn main() {
    println!("{}", add(1, 2));
}
```

If you run `cargo run`, you should get the result `3`:

```bash
% cargo run
3
```

Add the PyO3 dependency from the Rust side:

```bash
% cargo add pyo3 -F abi3-py38
```

Update `Cargo.toml` to configure your crate type so it can be compiled for the Rust binary and for the Python bridge:

```toml
# Cargo.toml
# ...

[lib]
name = "calculator"
crate-type = ["cdylib", "rlib"]
```

Now, create a minimal `pyproject.toml`:

```toml
# pyproject.toml

[project]
name = "calculator"
version = "0.1.0"

[build-system]
requires = ["maturin>=1.0,<2.0"]
build-backend = "maturin"
```

Add the dependency on maturin and run it:

```bash
% uv add maturin
% uv run maturin develop
# ...
✏️ Setting installed package as editable
🛠 Installed calculator-0.1.0
```

Run Python with `uv run python` and test your package:

```pycon
>>> from calculator import add
>>> add(3, 4)
7
```

At this point you're happy that you can use your Rust code from Python and may not realise that `cargo run` may no longer work, complaining about Python frameworks, not finding whatever it needs to link, or other weird errors.

Configure cargo to use the Python installation from the virtual environment by adding the file `.cargo/config.toml`:

```toml
# .cargo/config.toml
[env]
PYO3_PYTHON = { value = ".venv/bin/python", relative = true }
```

Try running `cargo run` again and note that everything _still_ works:

```bash
% cargo run
3
```

## Fixing issues with PyO3 + maturin in environments with conda

I also found that this fix with `.cargo/config.toml` was very effective among my students, many of which had all sorts of funky Python environments set up through conda.

If this is your case, go ahead and get uv.
Make sure you don't have _any_ virtual environments activated.
Follow these steps to set up maturin and add the relevant configuration to `.cargo/config.toml`.

Then run `cargo clean`.
**This step is important**.
I've had students do the configuration setup and still run into issues because they had cached stuff that was already in the wrong state.

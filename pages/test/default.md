This is just a test.

[ui-tabs]
[ui-tab title="`file.py`"]

This is my first tab with some code.

```py
def fibonacci(n: int) -> int:
    if n <= 1:
        return 1
    else:
        return fibonacci(n - 1) + fibonacci(n - 2)
```

[/ui-tab]
[ui-tab title="Second _tab_."]

Another tab here.

[/ui-tab]
[/ui-tabs]

Third paragraph.

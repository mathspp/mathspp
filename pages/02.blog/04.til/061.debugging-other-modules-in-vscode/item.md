Today I learned how to use the VS Code debugger in code from “other” modules.

===

# Debugging other modules in VS Code

Sometimes you may want to use the VS Code debugger and also trace into code from “other” modules, 3rd party modules you are using.
To do this, you need to disable the option `justMyCode`, which you must set to `false`:

```json
"justMyCode": false
```

However, you need to do this in the correct place _and_ then you need to run your debugger correctly.

Here are the steps you need to follow:

 - open the “Run and Debug” extension;
 - near the top right of the extension sidebar, hit the cog to open the settings;
 - a JSON file with your settings next to a `"launch"` section with an option `"configurations"` that is a list (which may or may not be empty)[^1];
 - you need to add the following JSON to your configurations:

```json
{
    "name": "Python: Current File but not just my code",
    "type": "python",
    "request": "launch",
    "program": "${file}",
    "console": "integratedTerminal",
    "justMyCode": false
}
```

 - set the `"name"` to something you can identify;
 - run the debugger with these options by changing the dropdown in the debug sidebar.

[^1]: the file opened may be `settings.json` – in which case the list `"configurations"` is inside the dictionary `"launch"` – or it may be the file `launch.json` – in which case the list `"configurations"` is at the top level of the JSON. Either way, the JSON shown above goes _inside_ the list `"configurations"`.


That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe

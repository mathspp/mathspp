Learn keyboard shortcuts that will make you a more efficient and productive Python programmer with VS Code.

===


# Introduction

As a Python programmer, I spend my day inside my IDE.
After all, it's my job!

This article will share some VS Code keyboard shortcuts that save me **hours** every week and make me a much more productive Python programmer.
I will explain what each shortcut does and I will show a short video example.

(I will show the specific keyboard shortcuts for VS Code, but these actions are common and your IDE is likely to have a similar shortcut!)


# Open command palette

All the shortcuts that will be shown are bound to a specific action and the shortcuts can always be edited to better suit your needs.
If you forget a specific shortcut, you can open the command palette (which also shows **all** commands available) and you can type the name of the command you want to see the shortcut for.

To open the command palette, use:

 - <key>Cmd</key> + <key>Shift</key> + <key>P</key> on Mac;
 - <key>Ctrl</key> + <key>Shift</key> + <key>P</key> elsewhere.


# Edit multiple occurrences of the same word

To edit multiple occurrences of the same word (or of the same selection of text), use:

 - <key>Cmd</key> + <key>D</key> on Mac;
 - <key>Ctrl</key> + <key>D</key> elsewhere.

Each time you press the shortcut, you select the next occurrence of the word.
When you have selected as many occurrences as you want, start typing to edit them!

<iframe width="560" height="315" src="https://www.youtube.com/embed/HTiy2f-GD2E" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>

You can also use <key>Ctrl</key> + <key>Shift</key> + <key>L</key> to select all occurrences at once.
(Use <key>Cmd</key> instead of <key>Ctrl</key> on a Mac.)


# Jump to the definition of any symbol

If you want to jump to the definition of something (for example, a variable, an attribute, a function, etc), you can use:

 - <key>Cmd</key> + <key>Shift</key> + <key>O</key> on Mac;
 - <key>Ctrl</key> + <key>Shift</key> + <key>O</key> elsewhere.

When you press this combination, you will see a dropdown with all the symbols in the current file.
You can start typing the name of the thing you want to find and select it to jump to its definition.

This is useful when you are in a big file and you want to find a specific function, for example.

<iframe width="560" height="315" src="https://www.youtube.com/embed/AEUeU2tnS9c" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>


# Jump to the definition of the current symbol

Similarly, if you want to jump to the definition of a symbol when you are in a place where it is used, you can hold <key>Ctrl</key> (or <key>Cmd</key> on Mac) and click the symbol.
This will jump to the definition site of that symbol.
Conversely, if you click a symbol in its definition site, it will open a pane with all the usages of that symbol that VS Code can find.

! Note that this pane is not guaranteed to list **all** usages; for example, this can fail on highly dynamic code that uses `getattr`, `hasattr`, and `setattr`.

<iframe width="560" height="315" src="https://www.youtube.com/embed/a8X6ydwdt-0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>


# Rename a symbol

To rename a symbol, like a variable, a class, a function, a method, etc, you can press <key>F2</key> on top of the symbol.
This will rename all occurrences of the symbol that VS Code can find in the current project.

! Note that it is not guaranteed that this will rename **all** occurrences; for example, this can fail on highly dynamic code that uses `getattr`, `hasattr`, and `setattr`.

You can also preview the change before making it.

<iframe width="560" height="315" src="https://www.youtube.com/embed/_yZBMCzsgpQ" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>


# Jump to a specific line

To jump to a specific line number, you can use <key>Ctrl</key> + <key>G</key> (for example, after seeing a traceback with a specific line number).

<iframe width="560" height="315" src="https://www.youtube.com/embed/aRO0XuVHHpI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>


# Jump to previous position of the cursor

After jumping around a lot, you may need to go back to where you _were_ and you may not remember.
To go back to your previous cursor position, use:

 - <key>Ctrl</key> + <key>-</key> on Mac;
 - <key>Ctrl</key> + <key>Alt</key> + <key>-</key> elsewhere.

<iframe width="560" height="315" src="https://www.youtube.com/embed/aRO0XuVHHpI" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>


# Conclusion

These are some shortcuts I use very often.
These shortcuts can be edited and configured to better suit your taste.

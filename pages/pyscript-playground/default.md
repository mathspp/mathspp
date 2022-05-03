<script defer src="https://pyscript.net/unstable/pyscript.min.js"></script>

# PyScript Playground

## Try PyScript Online

Below, you can find a PyScript REPL that lets you try PyScript online.
Press <kbd>Shift</kbd>+<kbd>Enter</kbd> to evaluate a cell.

<input type="button" value="Button 1" id="btn1" >
<br />

The button above has id `"btn1"` and the button below has id `"btn2"`.

<input type="button" value="Button 2" id="btn2" >
<br />

<div id="pre-repl"></div>

<div>
    <py-repl id="my-repl">
div_pre = Element("pre-repl")
div_pre.write("This is a div!")
div_post = Element("post-repl")
div_post.write("Hey!")
div_post.write("How you doin'?", append=True)
    </py-repl>
</div>

<div id="post-repl"></div>

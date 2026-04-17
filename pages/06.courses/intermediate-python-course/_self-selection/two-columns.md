<style>
.fa-check-list {
    list-style: none;
}

.fa-check-list li {
    position: relative;
}

.fa-check-list li::before {
    font-family: "Font Awesome 5 Free";
    font-weight: 400; /* 400 = Regular (far) */
    position: relative;
    left: -.4em;
    top: 0.15em;

    font-size: 1rem;
}

.fa-check-list.check li::before {
    content: "\f058"; /* Unicode for fa-check-circle */
    color: var(--gr);
}

.fa-check-list.cross li::before {
    content: "\f057"; /* Unicode for fa-times-circle */
    color: var(--re);
}
</style>

## This is for you if you're a

<ul class="fa-check-list check">
  <li>Scientist, data engineer, or another type of professional who uses Python as a tool to get their job done</li>
  <li>Coder who works in multi-language environments and that wishes to write better, Pythonic code</li>
  <li>QA/testing engineer, or similar, who wishes to transition into developer roles</li>
  <li>Self-taught programmer who never learned proper engineering best practices</li>
  <li>Aspiring developer who wants to strengthen their fundamentals before job interviews</li>
  <li>Using LLMs to help you write code but you don't understand what they're doing</li>
</ul>

===

## This is NOT for you if you're

<ul class="fa-check-list cross">
  <li>A beginner programmer who is still learning basic Python syntax</li>
  <li>A very advanced developer (e.g., writing frameworks, playing with CPython internals)</li>
  <li>Looking for a purely self-paced course</li>
  <li>Expecting results without putting in any work during the 5 weeks</li>
  <li>Using LLMs and don't care about the code they're producing</li>
</ul>

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

## This is for you

<ul class="fa-check-list check">
  <li>Scientists, data engineers, and other professionals, who use Python as a tool to get their jobs done</li>
  <li>Coders who work in multi-language environments and that wish to write better, Pythonic code</li>
  <li>QA/testing engineers, or similar, who wish to transition into developer roles</li>
  <li>Self-taught programmers who never learned proper engineering best practices</li>
  <li>Aspiring developers who want to strengthen their fundamentals before job interviews</li>
  <li>You're using LLMs to help you write code but you don't understand what they're doing</li>
</ul>

===

## This is NOT for you

<ul class="fa-check-list cross">
  <li>Beginner programmers who are still learning basic Python syntax</li>
  <li>Very advanced developers (e.g., writing frameworks, playing with CPython internals)</li>
  <li>Looking for a purely self-paced course</li>
  <li>Expecting results without putting in any work during the 5 weeks</li>
  <li>You're using LLMs and are happy not understanding what they're doing</li>
</ul>

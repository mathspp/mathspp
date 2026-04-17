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

## Who this is for

<ul class="fa-check-list check">
  <li>Professionals who use Python as a tool to get their jobs done</li>
  <li>Coders who work in multi-language environments and wish to write better, Pythonic code</li>
  <li>QA/testing engineers, or similar, who wish to transition into developer roles</li>
  <li>Hardcore hobbyists who want to take the next step</li>
</ul>

===

## Who this is not for

<ul class="fa-check-list cross">
  <li>Beginner programmers</li>
</ul>

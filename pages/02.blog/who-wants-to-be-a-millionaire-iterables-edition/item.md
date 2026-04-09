Play this short quiz to test your Python knowledge!

===

<script src="/user/themes/myquark/js/quiz.js"></script>
<link rel="stylesheet" href="/user/themes/myquark/css/quiz-custom.css">


At PyCon Lithuania 2026 I did a lightning talk where I presented a “Who wants to be a millionaire?” Python quiz, themed around iterables.
There's a whole performance during the lightning talk which was recorded and will be eventually linked to from here.
This article includes only the four questions, the options presented, and a basic system that allows you to check whether you got it right or not.

## Question 1

This is an easy one to get you started.

<div class="quiz-question" data-correct="a">
  <div class="question-text"><p>What is the output of the following Python program?</p></div>
  <pre><code class="language-py hljs language-python">print("Hello, world!")</code></pre>
  <ul class="choices">
    <li data-option="a">Hello, world!</li>
    <li data-option="b">Hello world!</li>
    <li data-option="c">Hello world</li>
    <li data-option="d">Hello world!!</li>
  </ul>
  <p class="feedback"></p>
</div>


## Question 2


<div class="quiz-question" data-correct="a">
  <div class="question-text"><p>What is the output of the following Python program?</p></div>
  <pre><code class="language-py hljs language-python">squares = (x ** 2 for x in range(3))
print(type(squares))</code></pre>
  <ul class="choices">
    <li data-option="a"><code>&lt;class 'generator'&gt;</code></li>
    <li data-option="b"><code>&lt;class 'gen_expr'&gt;</code></li>
    <li data-option="c"><code>&lt;class 'list'&gt;</code></li>
    <li data-option="d"><code>&lt;class 'tuple'&gt;</code></li>
  </ul>
  <p class="feedback"></p>
</div>


## Question 3

<div class="quiz-question" data-correct="a">
  <div class="question-text"><p>Out of the 20, how many objects in `itertools` are iterables?</p></div>
  <ul class="choices">
    <li data-option="a">19</li>
    <li data-option="b">20</li>
    <li data-option="c">1</li>
    <li data-option="d">0</li>
  </ul>
  <p class="feedback"></p>
</div>


## Question 4

The final question is pretty much impossible unless you've been doing Python for quite a while...

<div class="quiz-question" data-correct="d">
  <div class="question-text"><p>What is the output of the following Python program?</p></div>
  <pre><code class="language-py hljs language-python">from itertools import *

print(sum(chain.from_iterable(chain(*next(
islice(permutations(islice(batched(pairwise(
count()),5),3,9)),15,None)))))</code></pre>
  <ul class="choices">
    <li data-option="a">1800</li>
    <li data-option="b">0</li>
    <li data-option="c">🇱🇹❤️🐍</li>
    <li data-option="d"><code>SyntaxError</code></li>
  </ul>
  <p class="feedback"></p>
</div>

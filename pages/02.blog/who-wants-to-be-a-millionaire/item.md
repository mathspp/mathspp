Play this short Python quiz to test your Python knowledge!

===

<script>
document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll('.quiz-question').forEach(q => {
    const correct = q.dataset.correct;
    const choices = q.querySelectorAll('li');
    const feedback = q.querySelector('.feedback');

    choices.forEach(choice => {
      choice.addEventListener('click', () => {
        if (q.classList.contains('answered')) return;

        const selected = choice.dataset.option;

        q.classList.add('answered');

        if (selected === correct) {
          choice.classList.add('correct');
          feedback.textContent = '✅ Correct!';
        } else {
          choice.classList.add('wrong');
          feedback.textContent = '❌ Wrong. Correct answer: ' +
            q.querySelector(`li[data-option="${correct}"]`).textContent;
          q.querySelector(`li[data-option="${correct}"]`).classList.add('correct');
        }
      });
    });
  });
});
</script>


<style>
.quiz-question {
  --bg: #f9f9f9;
  --fg: #222;
  --accent: var(--bl);
  --correct: var(--gr);
  --wrong: var(--re);

  background: var(--bg);
  color: var(--fg);
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 1rem;
  margin: 1rem 0;
  font-family: system-ui, sans-serif;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

.quiz-question .question-text {
  font-size: 1.1rem;
  margin-bottom: 0.5rem;
}

.quiz-question ul.choices {
  list-style: none;
  padding: 0;
  margin: 0;
}

.quiz-question ul.choices li {
  background: white;
  border: 1px solid #ccc;
  border-radius: 6px;
  padding: 0.6rem 1rem;
  margin-bottom: 0.5rem;
  cursor: pointer;
  transition: background 0.3s, border 0.3s;
}

.quiz-question ul.choices li:hover {
  background: #eef7ff;
  border-color: var(--accent);
}

.quiz-question ul.choices li.correct {
  background: var(--correct);
  color: white;
  border-color: var(--correct);
}

.quiz-question ul.choices li.wrong {
  background: var(--wrong);
  color: white;
  border-color: var(--wrong);
}

.quiz-question .feedback {
  margin-top: 0.75rem;
  font-weight: bold;
  opacity: 0;
  transform: translateY(-10px);
  transition: opacity 0.4s ease, transform 0.4s ease;
}

.quiz-question.answered .feedback {
  opacity: 1;
  transform: translateY(0);
}
</style>


# Who wants to be a millionaire?

At EuroPython 2024 I did a lightning talk where I presented a “Who wants to be a millionaire?” Python quiz.
[There was a whole performance going on during the lightning talk](https://www.youtube.com/watch?v=6SckLDU_X6M), but the questions themselves were very interesting and I'm sharing them here so that you can test your Python knowledge!

## Question 1

This is an easy one to get you started.

<div class="quiz-question" data-correct="a">
  <div class="question-text">What's the output of <code>print("Hello, world!")</code></div>
  <ul class="choices">
    <li data-option="a">Hello, world!</li>
    <li data-option="b">Hello world!</li>
    <li data-option="c">Hello world</li>
    <li data-option="d">Hello world!!</li>
  </ul>
  <div class="feedback"></div>
</div>


## Question 2


<div class="quiz-question" data-correct="c">
  <div class="question-text">How many soft keywords does Python 3.13 have?</div>
  <ul class="choices">
    <li data-option="a">0</li>
    <li data-option="b">2</li>
    <li data-option="c">4</li>
    <li data-option="d">8</li>
  </ul>
  <div class="feedback"></div>
</div>


## Question 3

<div class="quiz-question" data-correct="a">
  <div class="question-text">How many times does the Python 3.13 glossary use the word “dunder”?</div>
  <ul class="choices">
    <li data-option="a">0</li>
    <li data-option="b">2</li>
    <li data-option="c">15</li>
    <li data-option="d">42</li>
  </ul>
  <div class="feedback"></div>
</div>


## Question 4

The final question is pretty much impossible unless you've been doing Python for quite a while...

<div class="quiz-question" data-correct="d">
  <div class="question-text">What does the following Python 2 code output?</div>
  <div><pre><code class="language-py hljs language-python">print`3&lt;3`[~3]</code></pre>
  <ul class="choices">
    <li data-option="a"><code>SyntaxError</code></li>
    <li data-option="b">42</li>
    <li data-option="c">3</li>
    <li data-option="d">a</li>
  </ul>
  <div class="feedback"></div>
</div>

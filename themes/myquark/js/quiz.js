document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('.quiz-question').forEach(q => {
        const correct = q.dataset.correct;
        const choices = q.querySelectorAll('ul.choices > li');
        const feedback = q.querySelector('.feedback');

        choices.forEach(choice => {
            choice.addEventListener('click', () => {
                if (q.classList.contains('answered')) return;

                const selected = choice.dataset.option;

                q.classList.add('answered');

                if (selected === correct) {
                    choice.classList.add('correct');
                    feedback.textContent = 'Correct!';
                } else {
                    choice.classList.add('wrong');
                    feedback.textContent = 'Wrong. Correct answer: ' +
                        q.querySelector(`li[data-option="${correct}"]`).textContent;
                    q.querySelector(`li[data-option="${correct}"]`).classList.add('correct');
                }
            });
        });
    });
});

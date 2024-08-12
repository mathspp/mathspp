---
title: "Neural networks fundamentals with Python – student-teacher"
---

In this article of the [NNFwP series][series] we'll do the
“student-teacher” experiment with two neural networks,
where one network will learn directly from the other.

===

![A nice image with blue and purple lights.](thumbnail.png "Original photo by JJ Ying on Unsplash.")


# Purpose of this article

The purpose of this article is to try and perform an experiment
that is called the “student-teacher” experiment,
in which we use one large neural network to train another smaller network directly,
instead of using conventional training data.

In the field of machine learning,
this is also commonly referred to as “knowledge distillation”.
The “student-teacher” experiment we will do in this article
will be smaller than similar experiments you might find done in the field,
but the principles will be more or less the same;
our experiment will only be smaller because we are dealing with a small and simple problem,
the MNIST classification problem, and because the networks we are using are fairly small.

If you want to learn more about “student-teacher” experiments/knowledge distillation,
maybe take a look at [this survey][student-teacher-survey].

!!! The code for this article, and for the all articles of the series,
!!! can be found in [this GitHub repository][gh-nnfwp].
!!! This article will build upon [v1.2][gh-nnfwp-v1_2] of that code.


# The layout of the experiment

Let me explain to you how the “student-teacher” experiment works,
and why we bother doing it.

As you have seen, neural networks are composed of several layers
that are stacked together.
The number of layers is variable, and so is the shape of each layer.
As it turns out, for more complex tasks, like speech recognition,
or object recognition, or text translation, among others,
we usually have networks much larger than the network we used for
the MNIST dataset.

However, a network being larger brings a couple of downsides with it,
namely the computation power it takes to run it and the memory usage
of having it loaded in memory.
To counteract these issues, researchers looked for a way of “compressing”
these large networks, and they came up with a really interesting method.
They observed that, if you trained a smaller network right from the get-go,
the smaller network wouldn't be as good as the larger one.
_But_, if they started by training the larger one, _then_ they could train
the smaller one by teaching it to _mimic_ the larger one.
They realised that the smaller network
would work as well as the original one, or sometimes even better!

Here are the detailed steps of how this experiment works:

 1. create, train, and test, a large network in the conventional way – this will be the teacher;
 2. create a smaller network – this will be the student;
 3. train the student as follows:
    1. traverse the training data;
    2. feed the input `x` to the teacher and obtain output `o`; and
    3. train the student on the input data `x` and use the teacher output `o` as the target.
 4. test the student.

Let us do this experiment together, with the MNIST data.


# Benchmarking the student

Before we begin, let me share a thought with you.
Critical thinking is _very_ important.
Of course you have different levels of trust towards different people –
and I'll be honest, I would like to develop a high level of trust with you –
but trusting in someone doesn't invalidate the need for you to keep your brain working.
Everyone makes mistakes, everyone can be misinformed, etc.,
so it is really important that you always judge everything critically
and try to find out by yourself if things you are being told really are like they tell you.

For example, I just told you that training a small network that is
as good as the larger networks is hard.
It doesn't hurt if you go and look that up in the literature
(and that will confirm what I said),
but you could also try to train a small network for yourself and see what happens.

This initial step is also important because it will allow us to create a benchmark
for the performance of small networks on this task.
On the one hand, we know that our “large” network can reach 90% accuracy consistently,
so we would like to be able to train a small network that also reaches 90% accuracy.
On the other hand, we want to know what is the level of accuracy that a small network
achieves if it is trained in the conventional way,
so that we can now how better (or how worse) the student network is,
when compared with a network of the same size but that was training with the conventional method.

Let's start!


## Creating the student

Let's go ahead and copy our `examples/mnist.py` file into `examples/mnist_small.py`,
where we will have pretty much the same code, but with a very small network.
In fact, let us use the smallest network possible...
What would that be?

Well, the input images have 784 pixels and the output has 10 classes,
so the smallest network we can have is a network with a single layer
that has 784 inputs and 10 outputs!
Let's build a network with that shape.
For the activation function, let's stick with the `LeakyReLU`
(we'll try the `Sigmoid` in a second) and let's also use the `CrossEntropyLoss`,
because we've seen [previously][nnfwp-subtleties] that that's a more suitable loss function:

```py
# In examples/mnist_small.py

if __name__ == "__main__":
    layers = [
        Layer(784, 10, LeakyReLU()),
    ]
    net = NeuralNetwork(layers, CrossEntropyLoss(), 0.001)
```

Because we are using the `CrossEntropyLoss`, don't forget to make sure that the `train`
function is adapted accordingly!
(For the `MSELoss`, we wanted to use column vectors as target outputs,
but for the `CrossEntropyLoss` we only need to give it information about the correct digit.)

```py
# In examples/mnist_small.py

def train(net, train_data):
    for i, train_row in enumerate(train_data):
        if not i%1000:
            print(i)

        net.train(to_col(train_row[1:]), train_row[0])
```

The remainder of the code can be left as-is.
I ran the `examples/mnist_small.py` file and got this output:

```bash
 > python examples/mnist_small.py
NNFwP\examples\mnistdata\mnist_test.csv...
Done.
# [...]
Accuracy is 8.39%       # <- initial accuracy of the random net
NNFwP\examples\mnistdata\mnist_train.csv...
Done.
0
1000
2000
3000
NNFwP\nn.py:71: RuntimeWarning: overflow encountered in exp
  d = np.exp(values)/np.sum(np.exp(values))
NNFwP\nn.py:71: RuntimeWarning: invalid value encountered in true_divide
  d = np.exp(values)/np.sum(np.exp(values))
# [...]
Accuracy is 9.80%       # <- final accuracy after training
```


## Runtime warnings

I didn't expect a perfect net, but I trained the network with the whole dataset,
and I only got 9.8% accuracy..?
That looks too bad, right?
What is more, there were two runtime warnings issued by NumPy.

These runtime warnings are warnings that NumPy issues when it encounters
numerical problems that it finds serious, but that are not worth halting problem execution for.
In general, these warnings only show up once per type of warning.
This means that, although the “overflow encountered in exp” warning
was only printed once in the console,
the underlying problem might have been encountered multiple times during program execution.

In our case, like for most of the `RuntimeWarning`s you will encounter,
we get them when the calculations get crazy,
for example when we are getting results that are _way_ too large:

```py
>>> import numpy
>>> r = numpy.exp(1000)
<stdin>:1: RuntimeWarning: overflow encountered in exp
>>> r
inf
```

Because the result is too large for NumPy to represent,
we get this `inf` back, representing infinity.

Immediately after that, we get another `RuntimeWarning` in the _same_ line of code,
saying we got an invalid value when using `true_divide`.
`true_divide` here refers to the division we are performing with `/`.
The problem is that both on the left and on the right of `/` we have infinite values,
and NumPy doesn't know how to divide one by the other
(because it's mathematically undetermined).

If we use the session to try and get the same `RuntimeWarning` by dividing `r` with `r`,
we get a slightly different warning:

```py
>>> r/r
<stdin>:1: RuntimeWarning: invalid value encountered in double_scalars
nan
```

In order to get the original warning concerning `true_divide`,
we need to make sure one of the operands of `/` is an array:

```py
>>> r/numpy.array([1, 2, r])
<stdin>:1: RuntimeWarning: invalid value encountered in true_divide
array([inf, inf, nan])
```

The `nan` stands for “not a number”,
and NumPy gives this answer because it simply _cannot_ calculate the answer.

So, all in all, we can see we have a problem.
What is it?


## Rescaling the input

The problem is that we are giving numbers that are too large as the network input.
We were able to get away with it in the first MNIST experiment because we were lucky, essentially.
And that's ok.
What matters is that now we are aware of this problem that we can easily fix.

There are several ways in which one can prevent this type of issues,
one of them being the rescaling of the input.
We know that all our inputs are column vectors of length 784
with integers that range from 0 to 255,
because they represent greyscale values.
Instead of feeding column vectors with such large integers,
we can rescale the input so that it's a column vector with floats from 0 to 1.
In order to do that, we just need to divide every input column by 255.

!!! If you don't think 255 is a large integer,
!!! consider what happens when the output layer produces a number like 255.
!!! The loss function will then exponentiate those numbers,
!!! and that will give a _really_ large number:
!!!
!!! ```py
!!! >>> numpy.exp(255) 
!!! 5.5602316477276757e+110
!!! ```
!!!
!!! On the other hand, if we keep input numbers between 0 and 1,
!!! then the output is more likely to stay within that order of magnitude
!!! instead of blowing up.

Numerical instabilities can be a pain to deal with but,
in our case, it isn't too hard to fix the problem.
All we have to do is tweak the `test` and `train` functions
to divide the input vectors by 255:

```py
# In examples/mnist_small.py

def test(net, test_data):
    correct = 0
    for i, test_row in enumerate(test_data):
        if not i%1000:
            print(i)

        t = test_row[0]
        x = to_col(test_row[1:])/255    # <-- divide by 255 here
        out = net.forward_pass(x)
        guess = np.argmax(out)
        if t == guess:
            correct += 1

    return correct/test_data.shape[0]

def train(net, train_data):
    for i, train_row in enumerate(train_data):
        if not i%1000:
            print(i)

        net.train(to_col(train_row[1:])/255, train_row[0])      # <-- divide by 255 here
```

## Re-running the student

After fixing these issues,
we can run our code again and see what is the accuracy that this
network achieves.

I ran my code a couple of times and got results around 90%.
What this means is that training a small network directly,
for the MNIST problem, doesn't appear to be that difficult.
In fact, this 90% accuracy is more or less on par with the accuracy of our larger model.
As we proceed into the “student-teacher” experiment,
let us see if we can improve this or, at least, create a student with the same level of accuracy.


# The experiment

## Step 1 – the teacher network

Now that we have had a go at creating a small network
and have established the benchmark accuracy for the student network,
we can start our “student-teacher” experiment.

Let us take the contents of `examples/mnist_small.py`
and copy them to the `examples/teacher_student.py` file.

Now, at this point you might be scratching your head and wondering
why we are copying and pasting so much code –
it is a very reasonable concern that will be addressed in the near future!

Assuming you have the `examples/teacher_student.py` file in place,
with the `train` and `test` functions using the rescaled inputs,
then all we need to do to complete the first step
is to create the full teacher network and train it:

```py
# In examples/teacher_student.py

# [...]

if __name__ == "__main__":
    layers = [
        Layer(784, 16, LeakyReLU()),
        Layer(16, 16, LeakyReLU()),
        Layer(16, 10, LeakyReLU()),
    ]
    teacher = NeuralNetwork(layers, CrossEntropyLoss(), 0.03)

    test_data = load_data(TEST_FILE, delimiter=",", dtype=int)
    accuracy = test(teacher, test_data)
    print(f"Accuracy is {100*accuracy:.2f}%")     # Expected to be around 10%

    train_data = load_data(TRAIN_FILE, delimiter=",", dtype=int)
    train(teacher, train_data)

    accuracy = test(teacher, test_data)
    print(f"Accuracy is {100*accuracy:.2f}%")
```

With this architecture, learning rate, and rescaled inputs,
I tend to get an accuracy around 85% or slightly higher.

!!! Notice that the learning rate I used now was 0.03,
!!! which I was only able to get to because I made lots of experiments
!!! before writing _this_.
!!! I include here this value because it is likely to work for you,
!!! but be mindful that in the real world, all these numbers have to
!!! be chosen in some way.
!!! Usually through a first guess and then tuning through repeated experimentation.


## Step 2 – create the student

Creating the student just entails initialising a new network,
much like we have done above for the benchmarking.
What is interesting to note here, is that the student network
will use the `MSELoss` function, because we want to teach the student network
to mimic the behaviour of the teacher.
In other words, we want the student's output to be as similar to the teacher's
as possible, and the `MSELoss` is great at measuring similarity between vectors.
In fact, the mean squared error loss function measures distances between vectors,
and we want them to be close to each other.

What we will do now is define a function that will accept a teacher,
some training data, and some students, and will train all the students with the given teacher.
This will let us try different types of students without having to wait for too long
for each experiment.

Here are some students to begin with:


```py
# In examples/teacher_student.py

# [...]

if __name__ == "__main__":
    layers = [
        Layer(784, 16, LeakyReLU()),
        Layer(16, 16, LeakyReLU()),
        Layer(16, 10, LeakyReLU()),
    ]
    teacher = NeuralNetwork(layers, CrossEntropyLoss(), 0.03)
    students = [
        NeuralNetwork([Layer(784, 10, LeakyReLU())], MSELoss(), 0.001),
        NeuralNetwork([Layer(784, 10, LeakyReLU())], MSELoss(), 0.003),
        NeuralNetwork([Layer(784, 10, LeakyReLU())], MSELoss(), 0.01),
        NeuralNetwork([Layer(784, 10, LeakyReLU())], MSELoss(), 0.03),
        NeuralNetwork([Layer(784, 10, LeakyReLU())], MSELoss(), 0.1),
        NeuralNetwork([Layer(784, 10, LeakyReLU())], MSELoss(), 0.3),
    ]

    # [...]
```

We start by creating six students where the only difference is the learning rate.
We will start by looking for a learning rate that looks promising.

By the way, don't forget to import all of the loss functions that you need!


## Step 3 – train the student

We have create a teacher, that we know how to train,
and we have a number of students that are eager to learn,
we are only left with training the students.
If you recall the beginning of the article, here are the steps we need to follow:

 3. train the student as follows:
    1. traverse the training data;
    2. feed the input `x` to the teacher and obtain output `o`; and
    3. train the student on the input data `x` and use the teacher output `o` as the target.

This training process is not what is implemented in the `train` function,
so let us create another function to help us with this training:

```py
# In examples/teacher_student.py

def train_students(teacher, students, train_data):
    for i, train_row in enumerate(train_data):
        if not i%1000:
            print(i)

        x = to_col(train_row[1:])/255
        out = teacher.forward_pass(x)
        for student in students:
            student.train(x, out)
```

Now we just need to call this function after we train the teacher model.


## Step 4 – test the students

Finally, all we have to do is test all our students after we train them:

```py
# In examples/teacher_student.py

if __name__ == "__main__":
    # [...]

    print("Training students.")
    train_students(teacher, students, train_data)
    print("Testing students.")
    accuracies = [100*test(student, test_data) for student in students]
    print(accuracies)
    print(f"Teacher accuracy had been {100*accuracy:.2f}%")
```

At this point, here is what the main `if` looks like:

```py
if __name__ == "__main__":
    layers = [
        Layer(784, 16, LeakyReLU()),
        Layer(16, 16, LeakyReLU()),
        Layer(16, 10, LeakyReLU()),
    ]
    teacher = NeuralNetwork(layers, CrossEntropyLoss(), 0.03)
    students = [
        NeuralNetwork([Layer(784, 10, LeakyReLU())], MSELoss(), 0.001),
        NeuralNetwork([Layer(784, 10, LeakyReLU())], MSELoss(), 0.003),
        NeuralNetwork([Layer(784, 10, LeakyReLU())], MSELoss(), 0.01),
        NeuralNetwork([Layer(784, 10, LeakyReLU())], MSELoss(), 0.03),
        NeuralNetwork([Layer(784, 10, LeakyReLU())], MSELoss(), 0.1),
        NeuralNetwork([Layer(784, 10, LeakyReLU())], MSELoss(), 0.3),
    ]

    test_data = load_data(TEST_FILE, delimiter=",", dtype=int)
    accuracy = test(teacher, test_data)
    print(f"Accuracy is {100*accuracy:.2f}%")     # Expected to be around 10%

    train_data = load_data(TRAIN_FILE, delimiter=",", dtype=int)
    train(teacher, train_data)

    accuracy = test(teacher, test_data)
    print(f"Accuracy is {100*accuracy:.2f}%")

    print("Training students.")
    train_students(teacher, students, train_data)
    print("Testing students.")
    accuracies = [100*test(student, test_data) for student in students]
    print(accuracies)
    print(f"Teacher accuracy had been {100*accuracy:.2f}%")
```

Make sure that you also defined the function `train_students`,
and you are ready to run your experiment!


## First run

If you are _anything_ like me, the first couple of times you run your file,
you have to fix typos and missing imports, etc.
After those were sorted out, here is what my code printed in the end:

```bash
 > python examples/teacher_student.py
# [...]
[82.0, 82.72, 82.62, 82.28999999999999, 69.39999999999999, 36.55]
Teacher accuracy had been 86.69%
```

Looking at these results, we see that the first four students have accuracies of 82%,
and the last two students have lower accuracies.
This already gives you an indication about which learning rates (the learning rates
used by the last two students) lead to poorer students.

As far as the first four students are concerned, this is a really good start!
We had two final objectives in mind and we already achieved one of them:
we already managed to use a teacher to train smaller networks with good accuracy.

The other objective, that we have yet to achieve,
is to be able to find a student that beats the benchmark of around 90%.

In order to do that, we can try a couple of different things...
which is funny, because it looks like we don't have much room to tweak the students.
Here are some of the things you can try:

 - experiment with other values for the learning rate between 0.001 and 0.03;
 - try changing the `LeakyReLU` parameter from the default value;
 - train the students twice or more;

I played around with these alternatives a bit, and I got close to beating the benchmark,
but alas, I couldn't do it.
Can you?
Now it is totally up to you to decide how long you want to be looking for a student
that beats the benchmark, but consider reading a bit further,
as later we'll introduce some improvements that will make running
repeated experiments less painful.

Finally, do not feel demotivated if you can't find a student that beats the benchmark,
recall that the “student-teacher” experiments tends to work much better when the original
benchmark is much worse, and our initial benchmark was pretty good!
Looking for a better example of where to apply this experiment will be in the final projects list!

Yes, there will be a final projects list!
A list of possible paths you can take after finishing reading the neural networks fundamentals.
I understand that it is difficult to know what to learn or do next,
so I'll do my best to help you and guide you.

By the way, congratulations on running your first machine learning research experiment!

You can find all the code for this series in [this GitHub repository][gh-nnfwp] and
the code that corresponds to the end of this article is available [under the tag v1.3][gh-nnfwp-v1_3],
in particular the files [`examples/mnist_small.py`][gh-nnfwp-mnist_small]
and [`examples/teacher_student.py`][gh-nnfwp-teacher_student].


# The series

These are all the articles in this series:

<ol>
{% for article in taxonomy.findTaxonomy({"tag": ["nnfwp"]}).order("date") %}
    <li><a href="{{ article.url }}">{{ article.title }}</a></li>
{% endfor %}
</ol>



[series]: /blog/tag:nnfwp
[nnfwp-subtleties]: /blog/neural-networks-fundamentals-with-python-subtleties
[gh-nnfwp]: https://github.com/mathspp/NNFwP
[gh-nnfwp-v1_2]: https://github.com/mathspp/NNFwP/tree/v1.2
[gh-nnfwp-v1_3]: https://github.com/mathspp/NNFwP/tree/v1.3
[gh-nnfwp-mnist_small]: https://github.com/mathspp/nnfwp/blob/v1.3/examples/mnist_small.py
[gh-nnfwp-teacher_student]: https://github.com/mathspp/nnfwp/blob/v1.3/examples/teacher_student.py
[student-teacher-survey]: https://arxiv.org/abs/2006.05525

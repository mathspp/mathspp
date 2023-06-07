---
title: "Neural networks fundamentals with Python – regression vs classification"
---

In this article of the [NNFwP series][series] we will understand
what are the differences between regression and classification problems.

===

![A nice image with blue and purple lights.](_thumbnail.png "Original photo by JJ Ying on Unsplash.")


# Purpose of this article

The purpose of this article is to explain what _regression_ and _classification_
problems are in ML, and why they are different.
In order to make this easier to understand, the discussion will revolve around
the things we have been doing already.

We will also see how these differences impact the code we write.

!!! The code for this article, and for the all articles of the series,
!!! can be found in [this GitHub repository][gh-nnfwp].


# Understand the problem

As I start writing down these words, I am reminded of something a friend of mine said all the time.
Whenever we were talking about machine learning and about using it to solve problems,
he'd always find a way to mention “priors”...
We'll circle back to this in just a second!

I am sure you heard this before, but in case you haven't, let me tell you.
One of the most important steps in solving a problem, whatever type of problem it might be,
is understanding the problem.
In fact, this is so important, that I can almost philosophically state that there's only
two steps to solving a problem:

 1. really, truly, deeply, understand the problem;
 2. solve the problem.

If you really, really, really, really, really understand the problem,
then there is nothing preventing you from solving it.

When my friend talks about “priors”,
he is talking about all of those fragment of knowledge that you/your team might
have about the problem that you want to tackle,
about the domain that your problem is in,
that might help you in solving the problem.

As a silly example, if you want to find out what is your favourite dish
in a specific restaurant, what you could do is order _all_ the different
dishes (probably not in one sitting!) and try them out.

However, if you notice that the menu has a specific dish that you really don't like,
then you don't need to order that one, right?
You can factor in that previous knowledge that you have about yourself – that prior –
and only order the other dishes.

For machine learning problems/tasks, it is no different.
You need to understand what it is that you are trying to achieve,
you need to understand the data you are working with,
you need to understand what is the final _thing_ you want to produce
as output, etc.

In this journey of understanding, it helps if you are aware of two big classes
of machine learning problems that you might be faced with.

These are the _regression_ problems and the _classification_ problems,
and their main difference lies in _what_ you are trying to achieve.

Being aware of this difference, and being able to tell one class of problems
apart from the other, will improve your understanding of the problems
you are faced with and will help you pick the right tools for the job.
As you will understand now, some tools are only suitable for one type of problem.


# Regression problems

Do you know how tall I am?
Probably not.
How tall am I?
Just take a guess and say it out loud, to force you to really try and guess.

Ok.

Now, how many different guesses could you have made?
Is it true that I can only have one out of three different heights?
Are all people either 1.7m, 1.8m, or 1.9m tall?
Or are there people who are 1.59m, others are 1.79m, etc?

There is a whole _spectrum_ of heights that people can have, correct?

Similarly, if I ask you to guess the temperature that is outside right now,
are there only 3 correct answers?
No, there is a wide _spectrum_ of possible temperatures,
and you have to try and pick a temperature that is _close_ to the correct one.

Just one more thing.
I'm thinking of a number.
Can you guess it?
How many possible answers are there?
There are infinite possible answers!

I could be thinking of 0, 1, -3, 17, 3.14, or even -12356616123.3114!

(By the way, my number was actually 3.28, did you get it?)

Here was another example of a question where your answer
fell within a spectrum of all possible answers.

Now, instead of asking these (weird) questions to you,
I could ask them to machine learning models, right?
There's no harm in that.

If your model is producing an answer that falls within a spectrum,
then that's because your model is performing a regression.
It is taking information in (in case there is any),
and it is trying to use that information to figure out,
within that spectrum of possible answers,
what's the (most) correct answer.

This is probably the characteristic that identifies regression problems better.

There is another thing that is also typical of regression problems.
When you give an answer to a regression problem,
it “makes sense” to say whether the answer was “close” or not.

For example, if you guessed that I was thinking of the number 3.279,
then that was really close!
Much closer than if you guessed 10, or 100, or 1000.

These characteristics of regression problems will be even easier to understand
if you contrast them with the characteristics of classification problems,
that I will present shortly.


# Classification problems

Do you know what colour my eyes are?
Can you guess?
What options are there for you to choose from?

If we ignore those really odd cases that pop up every once in a while,
_most_ people have either brown, green, or blue eyes, to put it simply.

Try to guess the colour of my eyes.

Did you guess blue?
If so, that's correct.
If you guessed anything else, that's wrong.
And that's it, your guess was either correct or wrong.

Will it rain tomorrow?
You can't know for sure, but you can try to guess, correct?
How many different answers can you give?
Well, I asked a yes-or-no question, so the only possible
answers are “yes” and “no”.
And then, tomorrow it will either rain or it won't,
so you will either guess it correctly or incorrectly.

When you are asked a question for which there is a fixed number of possible answers,
then that corresponds to a classification task.
You have to take any information you might have at your disposal and then
give your best shot at figuring out the correct answer.
Then, your answer will either be correct or it won't.


# Weird phrases dictionary

If you hear other people talk about regression and classification problems,
or if you read on this subject, your are bound to find terms and names for concepts
that might be new to you.

I'll go over some of those terms and concepts and explain them
in terms of what you already know.


## Labels and classes

When talking about classification problems, you might hear people talk about
labels or classes.
What are those?

Labels, or classes, are just another name to the group of possible answers for a given task.
If you are creating a machine learning model that looks at pictures of dogs and cats
and determines if the picture corresponds to, either a dog, or a cat,
then the labels for that problem will be just two: “dog” and “cat”.

In that context, you might also hear about the “true label” or the “true class”.
The “true label” of some input is the correct answer that the model should give for a particular input.
In the “cats vs dogs” example, the true label for a picture of a dog is “dog” and the true label
for a picture of a cat is “cat”.


## Discrete labels versus continuous quantities

When differentiating between regression problems and classification problems,
people always discuss the inherent differences in what type of output we are trying to produce.

Classification problems are such that there is only a given set of labels,
and you want your machine learning model to be able to take some input and conclude what the appropriate
label for that input is.
Because the set of labels is fixed and finite, people sometimes talk about a “discrete set of labels”
or a “discrete output”.
“Discrete” is a mathematical term that can be applied to finite sets, such as the set of labels for your task.

On the other hand, I mentioned that regression problems are such that the output could fall within
a wide spectrum of possibilities.
For example, my exact height could be _any_ number within the range of Human heights.
Because there is a spectrum, and because my height could be _anywhere_ on that spectrum,
we say that my height is a continuous quantity.
We can also say that the model is trying to predict a continuous quantity.


## Function approximation, curve fitting, and the like

If you read an article about regression you will probably find an image such as the following:

![White graph with a series of red points and a blue straight line representing the linear regression of those red points.](regression.png "Linear regression applied to a set of points.")

What is the relationship between regression and the image above?

Well, the red points above represent the data,
and their position in that image represent the relationship between the input
(on the horizontal axis) and the output (on the vertical axis).

For example, we could have recorded the number of hours it rained in several days,
and then also keep track of how many hours the pavement took to dry.
The top-right red point shows that after raining for 4 hours,
the pavement took 9 hours to dry.

Now we could create a model that answers this question:
“how many hours will the pavement take to dry after raining for a given
number of hours?”.
This is a regression problem, because the only answers aren't just
1 hour, 2 hours, 3 hours, and 4 hours.
For one, it could take 100 hours for all we know...
But also 1 hour and 23 minutes, or 2 hours, 15 minutes, and 42 seconds.

But for this task, maybe we don't need a very sophisticated model, like a neural network.
Maybe we just need a blue line that creates a simple formula that we can use.
And that's what the blue line in the image above represents:
it represents the straight line that best approximates the red points.

When we have a graph like the above and we try to find a function that fits
the data, we often say that we are doing “curve fitting”.
When the “curve” we are fitting is actually a straight line,
we call it “linear regression”.
All these are also called “function approximations”,
because we are trying to approximate the function that answers the question
that we posed earlier.


# Assessing the output

Because regression and classification problems produce two inherently different types of output,
the ways in which you assess the quality of the output are also different.
This can also depend on the type of model you are using to solve your problems,
so we will focus on our specific case, which is that of a neural network.

## Accuracy

The accuracy of a model is the percentage of times the model gets the correct answer right.
For example, we created some neural networks in the beginning that had around 90% accuracy
in the MNIST classification problem.

What does that mean?
It means that if I show 10 images to the neural network, it will be able to identify the digit
that is written in 9 of those images, and fail the 10th, on average.

Talking about the accuracy of a model only makes sense for classification tasks,
because it is only in classification tasks that we can say that the answer
is _correct_ or _wrong_.

For regression tasks, we talk about something else.


## Residues

In regression tasks we can only say if the prediction was _close_ to the answer or not.
Why is that?
Because in regression tasks, the output is _continuous_, it's a spectrum,
and there are an infinite amount of possible values in a spectrum.
This means that it is practically impossible for the model to predict the _exact_ answer.

Having said that, let's pretend that the _exact_ temperature outside my house,
right now, as I write these words, is 25.34 degrees Celsius.
Your task is to create a neural network that guesses the temperature based
on the time of day, day of the year, and yesterday's temperature.
Let's pretend that your model predicted that it is 25.3 degrees Celsius outside.

Which of the two following scenarios makes more sense to you:

 - I stamp your model's prediction with a big fat “WRONG”,
 the same way I would if the model had said it's -100 outside; or
 - I bow down to your model and applaud your work,
 given that the model only missed by 0.04 degrees Celsius.

Which situation makes more sense to you?

To me, it makes sense to distinguish good answers from bad answers.
Your model saying it's -100 degrees outside is ridiculous,
whereas it saying it's 20 degrees is decent,
and it saying it's 25.3 degrees is outstanding.

So, if we don't use the accuracy to assess the performance of models on regression tasks,
what do we do?
In general, we use measures that take into account the error of the network,
or what is often dubbed as _residue_.
In our example above, the residue was only 0.04, because the real temperature was 25.34
and the model guessed 25.3, so $25.34 - 25.3 = 0.04$.

After calculating the residue, we can get really creative and come up with all sorts of performance measures.
Perhaps one that you could come up with yourself is the average residue:
you apply your model to all the data you have, you compute all the residues, and then you average them out.

Another measure that we have seen already is similar to that, but it squares the residues before averaging them...
Does this sound familiar?
It's what the _mean squared error_ is, the function we implemented in `MSELoss`!
The _errors_ are the residues, that we then square and finally average out.
Wait, so the mean squared error is a measure used in regression tasks,
and yet we used it in our machine learning model for our classification task..?

Yes we did, and that's why we also addressed that issue afterwards,
when we introduced the activation function `Sigmoid` and the
loss function `CrossEntropyLoss`.


# Practical differences

Now that we are aware of the theoretical differences between regression and classification
tasks, it is time we look at the practical differences that impact our code.

For our neural networks experiments,
the differences show themselves mostly in the way we handle the training of the network
and in the way we assess the network.
That's because those are the steps that deal with the output of the network,
and from what I laid out above, the most obvious differences between regression and classification
revolve around the output.

## Regression tasks

For a regression task, your training data should consist of a set of inputs
and a set of corresponding column vector outputs.

Your network should be set up in such a way that the number of inputs
matches the input size of the data and the number of outputs matches the output size of the data.

You should pick a loss function that is appropriate for regression tasks,
such as the mean squared error.
These loss functions are typically based off of ideas regarding “distance”,
in that the loss function measures the distance between your output
and the target answer.

To train the network, simply iterate over the training data and feed the data to the network,
where the inputs are column vectors and the target outputs are also column vectors.

In order to assess the performance of the network,
you need to compute the loss of the network over your testing data,
and then average it out.

## Classification tasks

For a classification task, your training data should consist of a set
of inputs and a set of corresponding labels.

Your network should be set up in such a way that the number of inputs
matches the input size of the data and the number of outputs matches the number of different labels.
Each position of the output vector should be assigned to a single label.

You should pick a loss function that is appropriate for classification tasks,
such as the cross entropy loss.

To train the network, simply iterate over the training data and feed the data to the network,
where the inputs are column vectors and the target outputs are _label indices_!
It is important that you work with label indices here, and not actual labels.
So, if your labels are “cat” and “dog”, encode them as 0 and 1, for example.

In order to assess the performance of the network,
you need to feed the test data to the network, and for each data point,
figure out what is the position of the output vector that has a higher value.
Then, compare that index with the index of the correct label to check
if the network guessed correctly.
Count the number of correct guesses and the total number of attempts and compute the accuracy.

Now that we know about the practical differences that distinguish
regression tasks from classification tasks,
we can simplify our lives by implementing drivers that train and test
neural networks for the two types of tasks.
That is one of the things we will be doing in the next article.


<!-- v -->
# The series

These are all the articles in this series:

<ol>
{% for article in taxonomy.findTaxonomy({"tag": ["nnfwp"]}).order("date") %}
    <li><a href="{{ article.url }}">{{ article.title }}</a></li>
{% endfor %}
</ol>
<!-- ^ -->


[series]: /blog/tag:nnfwp
[gh-nnfwp]: https://github.com/mathspp/NNFwP
[gh-nnfwp-v1_3]: https://github.com/mathspp/NNFwP/tree/v1.3
[numba]: http://numba.pydata.org/
[numba-gh]: https://github.com/numba/numba
[numba-install]: https://numba.readthedocs.io/en/stable/user/installing.html

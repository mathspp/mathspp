This post gives the source code for a small Haskell program that finds if a formula is a tautology.

===


# Verifying tautologies with Haskell

A [tautology](https://en.wikipedia.org/wiki/Tautology_(logic)) is a formula that is true regardless of the logical value we attribute to the variables it contains. For example, the propositional formula $P \vee \neg P$ is **always** true, regardless of $P$ being true or false. But not every tautology is as simple as the one I just showed. For example, showing that

$$
(P \implies Q) \implies [(Q \implies R) \implies (P \implies R)]
$$

is a tautology requires a bit more thought. But thinking is too tiring, so let us write a Haskell program that takes a formula and decides if the formula is a tautology or not! (By the way, said program is available [in my GitHub](https://github.com/rodrigogiraoserrao/projects/blob/master/misc/tautologies.hs)...) First, we need to be able to represent a proposition, and we will create a data type just for that. We will call our type "Prop," from proposition, from propositional logic.

<script src="https://gist.github.com/rodrigogiraoserrao/4b3f30b5e77584a354e1037ef7d1566e.js"></script>

where we provided data constructors for all sorts of propositional formulas. The idea is that now the formula $P \vee \neg P$ is represented as `Or (Var "P") (Neg (Var "P"))`.

Now we have a way of representing formulas. What we are left with is having a way of evaluating those formulas to check if they are tautologies... Propositions will have variables, so we will need to collect the variables of a proposition, generate all possible combinations of values to attribute to all the variables, and then evaluate the logical value of the proposition with the given attribution. Notice that most of our functions will make use of the structure of the data `Prop` in order to do some sort of "recursion," but on the structure of the proposition. For example, if you have a proposition `p = Or p1 p2`, then the variables in $p$ are the variables in $p_1$ concatenated with the variables in $p_2$.

<script src="https://gist.github.com/rodrigogiraoserrao/f064060e9524cc3ed41b2ac3e50f5950.js"></script>

Now that we have all variables, we can generate all the possible combinations of values they can take. If we only have one variable, that is $T, F$. If we have two variables, that's $TT$, $TF$, $FT$, $FF$. If we have three variables, ... I define the function that generates all possible combinations of true/false values and then put them together with our variables.

<script src="https://gist.github.com/rodrigogiraoserrao/c327f8ae1e58aa547f9616087dc3e001.js"></script>

And now that we can define attributions for our variables, we can write a function that takes a proposition and an attribution, and evaluates the proposition according to the attribution! Our function will have the signature `Prop -> Attribution -> Maybe Bool` because we cannot know for sure that the attribution that is passed in contains all the variables present in the proposition.

<script src="https://gist.github.com/rodrigogiraoserrao/7d0aee9510ff02dcac955c56851a6f32.js"></script>

We are very close to the finish line! Given a proposition $p$, how do we check if it is a tautology? Well, we should generate all of the possible attributions for it, evaluate each one of them, and check if all the attributions made the proposition true! And that is exactly what we do in the next few lines:

<script src="https://gist.github.com/rodrigogiraoserrao/7b6de795c82b970c64ead27f1f57be2e.js"></script>

Now that our program is written down, we can actually check some tautologies!

We will check the tautology I presented in the beginning and three more tautologies: the definitions of "and," "or," and "equivalent" in terms of negation and implication. That is, $P \vee Q$, $P \wedge Q$, and $P \iff Q$ can be written down only using negations and implications, and I will use this program to check that!

<script src="https://gist.github.com/rodrigogiraoserrao/1abe28c6e601bcf20a1c40cf12c72f56.js"></script>

And obviously, this will print "True" four times.

On my GitHub page, you can find [this program](https://github.com/rodrigogiraoserrao/projects/blob/master/misc/tautologies.hs) with two extra functions, `falsify` and `truthify`, that try to find an attribution that makes the given proposition evaluate to false/true.

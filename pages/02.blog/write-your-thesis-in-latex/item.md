This reference article teaches you the things you'll need to write your thesis or report with LaTeX.

===

!["The thumbnail image of a blog article that provides a working introduction to LaTeX, as well as a reference to common things you may want to do with LaTeX when writing a report, an article, a masters thesis, or a PhD thesis."](thumbnail.webp)

## Introduction

I wrote this terse article as a reference for my “Introduction to LaTeX” workshop, where I teach the basic building blocks of LaTeX to students who want to use LaTeX for their reports, articles, and thesis.

This reference article will tell you about:

 - [setting the type of document you are creating with `\documentclass`](#creating-a-new-latex-document);
 - [environments in LaTeX](#begin-and-end);
 - [the anatomy of a LaTeX document](#the-anatomy-of-a-latex-document);
 - [using comments to leave notes that aren't shown in the final PDF output](#latex-comments);
 - [writing text in LaTeX](#line-changes-and-paragraphs);
 - [sectioning your document](#sectioning-your-document);
 - [adding a table of contents](#add-a-table-of-contents);
 - [using packages to add further functionality to your documents](#adding-automatic-links-to-references-with-hyperref);
 - [enabling internal links to cross-references, figures, equations, etc](#adding-automatic-links-to-references-with-hyperref);
 - [how to cross-reference sections, tables, and figures](#creating-references-to-your-content), and [equations](#referencing-your-equations);
 - [styling your text](#styling-your-text);
 - [adding a bibliography and citing it](#add-a-bibliography);
 - [adding and using a glossary](#add-a-glossary);
 - [splitting your document into multiple files for easier management](#multi-file-documents);
 - [typesetting mathematical equations](#mathematical-formulas-and-equations);
 - [inserting images into your document](#inserting-images-into-your-document) (and how to [tell LaTeX where to place your image](#controlling-image-positioning));
 - [inserting tables and tabular data](#creating-latex-tables);
 - [inserting listings of code](#inserting-listings-of-code);
 - adding a table of:
   - [figures](#listoffigures);
   - [tables](#listoftables);
   - [listings](#lstlistoflistings);

If this is your first time working with LaTeX, I recommend using an online service like [Overleaf].
Overleaf is a service that you can use for free to create LaTeX documents online.
It also has a comprehensive learning centre with many articles from which you can learn and it allows working on a project collaboratively with others.
But, above all, you can use Overleaf without having to install anything in your computer, so I think it is a friendly way to get started.


## Creating a new LaTeX document

To get started, we create a new LaTeX document in Overleaf.
When you hit the “New Project” button, it will show you a dropdown with a bunch of templates for you to choose from.
Because this is the first time we are working with LaTeX, I always suggest picking the “Blank” template, so that we can build our understanding from the ground up.

Overleaf will open a new page with three columns.
The middle column is your LaTeX document and the right column is the PDF preview of what you are typing.
Overleaf will also generate a couple of things for you, but we will delete all of them for now.

Delete everything in the middle column and type this out by hand:

```tex
\documentclass{article}

\begin{document}

\end{document}
```

These three lines **must** be present for you to have a valid LaTeX document.

### `documentclass`

The very first line – `\documentclass{article}` – is a _command_ that tells LaTeX what type of document you are creating.
There are various built-in options, but `article` is always a safe choice for a simple document.

### `begin` and `end`

Next, we see two lines that are related: `\begin{document}` and `\end{document}`.
The `\begin` and `\end` commands are used to create an _environment_.
An _environment_ is an area in your file where the things you write are treated in a special way.

For example, the environment `document` defines the area of your file where you can write the text contents of your document.
So, we need the two commands `\begin{document}` and `\end{document}` to tell LaTeX where our content goes.

Later, in this article, you will also learn about environments that let you add equations, formulas, images, tables, and more.

### Your first LaTeX compilation

Now, **between** the `begin` and `end` commands, go ahead and write a sentence.
For example, write “Houses are buildings where people live.”.

Your document should look like this:

```tex
\documentclass{article}

\begin{document}

Houses are buildings where people live.

\end{document}
```

Next, hit the green button “Recompile” and see Overleaf update the right column with a preview of your _compiled_ document.
The _compilation_ process is the process through which LaTeX reads your document, processes everything you have written, and creates a PDF with the desired output.

### Commands in LaTeX

_Commands_, in LaTeX, are things you write that start with the backslash `\` and have a special meaning to LaTeX.
For example,

 - the command `\documentclass` tells LaTeX which type of document we want to create;
 - the command `\begin` tells LaTeX that we are starting a new environment; and
 - the command `\end` tells LaTeX that we are closing, or ending, an environment that is currently open.

Some commands also accept arguments.
For example, the commands `\begin` and `\end` accept an argument, which is the name of the environment we are starting/ending.

Thus, the line `\begin{document}` is a LaTeX command (`begin`) with the argument `document`.

!!! If you are familiar with programming, this is similar to functions and arguments.


## The anatomy of a LaTeX document

### Document body

The document body, which is the region delimited by `\begin{document}` and `\end{document}`, is the region where you write your content and it is where you work most of the time.

### Document header

The header of a LaTeX document is everything that comes between the `\documentclass` and the body.
The command `\documentclass` must at the very start of the document, and you can give additional instructions to LaTeX by writing more commands before the body begins.

Shortly, we will see what type of commands it makes sense to put in the header.

Meanwhile, change your LaTeX document to look like this:

```tex
\documentclass{article}
% The header starts here.

% The header ends here.
\begin{document}
% The body starts here.

Houses are buildings where people live.

% The body ends here.
\end{document}
```

After you make this change, recompile your document.
Notice that **nothing** changed in the PDF output.

### LaTeX comments

The character `%` has a special meaning in LaTeX: it creates a comment.
Because LaTeX relies on commands and environments to let the user create complex output, sometimes it is useful to use comments to leave helpful notes that facilitate interpreting the LaTeX document.

Therefore, lines that start with `%` are completely ignored by LaTeX and you can write what you please in them.


## Sectioning your document

There are three commands that let you create sections, subsections, and sub-subsections in your document.
These commands are, in no particular order, `\subsection`, `\section`, and `\subsubsection`.
I will let you figure out which is which!

Each of these commands accepts a single argument, which is the name of the ((sub-)sub)section you are creating.

### Create a section with `\section`

The command `\section` can be used to create a section.
The example below creates two sections:

```tex
\documentclass{article}
% The header starts here.

% The header ends here.
\begin{document}
% The body starts here.

\section{Introduction}

Houses are buildings where people live.

\section{Divisions}

% The body ends here.
\end{document}
```

Create those two sections and recompile your document.

### Create a subsection with `\subsection`

The command `\subsection` can be used to create a subsection.
Go ahead and create two subsections, one named “Private rooms” and the other named “Common rooms”, both of which are after the “Divisions” section header.

To do this, you would modify your LaTeX document like so:

```tex
\documentclass{article}
% The header starts here.

% The header ends here.
\begin{document}
% The body starts here.

\section{Introduction}

Houses are buildings where people live.

\section{Divisions}

\subsection{Private rooms}

\subsection{Common rooms}

% The body ends here.
\end{document}
```

Notice that LaTeX numbers your (sub)sections automatically.
Go ahead and reorder the two subsections, recompile your document, and notice how the numbering is updated.

Now, fix the order again, so that “Private rooms” shows up first.

### Create a sub-subsection with `\subsubsection`

The command `\subsubsection` can be used to create a sub-subsection.
Go ahead and create five sub-subsections.

 - The sub-subsections “Bathroom” and “Bedroom” should be under “Private rooms”.
 - The sub-subsections “Kitchen”, “Living room”, and “Dining room”, should be under “Common rooms”.

To do this, your LaTeX document should look like so:

```tex
\documentclass{article}
% The header starts here.

% The header ends here.
\begin{document}
% The body starts here.

\section{Introduction}

Houses are buildings where people live.

\section{Divisions}

\subsection{Private rooms}

\subsubsection{Bathroom}
\subsubsection{Bedroom}

\subsection{Common rooms}

\subsubsection{Kitchen}
\subsubsection{Living room}
\subsubsection{Dining room}

% The body ends here.
\end{document}
```

Sub-subsections are likely to be the most fine-grained sectioning you will need for your document.
(Hint, the next level is **not** `\subsubsubsection`!)


## Line changes and paragraphs

Although we have several sections in our document, we have a single paragraph of text.
Go to each section, subsection, and sub-subsection and make sure to write a sentence about that section title.

For example, you could write this:

```tex
% ...
\begin{document}
% The body starts here.

\section{Introduction}

Houses are buildings where people live.

\section{Divisions}

A house is composed of a variable number of divisions.

\subsection{Private rooms}

Private rooms typically serve a single person at a time.

\subsubsection{Bathroom}

A bathroom is where people use the toilet.

\subsubsection{Bedroom}

A bedroom is where a person sleeps.

\subsection{Common rooms}

Common rooms are used by multiple people at the same time.

\subsubsection{Kitchen}

The kitchen is where food is prepared.

\subsubsection{Living room}

The living room is where people socialise.

\subsubsection{Dining room}

The dining room is where people have their meals together.

% The body ends here.
\end{document}
```

### Line changes for new sentences

!!! When writing a paragraph, I suggest you write each sentence on a single line.
!!! LaTeX will recognise this as a single paragraph and it makes it easier for you to find the sentence you are looking for in a big document.

So, if I wanted to expand on my introductory paragraph, I could write it like so:

```tex
% ...
\section{Introduction}

Houses are buildings where people live.
The people that live inside a house usually make up a family, although friends can also share a house.
Sometimes, strangers also share a house, especially if rooms in that house are rented separately.
```

Writing each sentence in its own line does **not** make LaTeX write sentences in their own line.
Instead, LaTeX will collect everything into a single paragraph.


### Creating a new paragraph

If you want to start a new paragraph, leave an **empty** line between the last sentence of the old paragraph and the new sentence of the next paragraph.
The example below will compile into two paragraphs:

```tex
% ...
\section{Introduction}

Houses are buildings where people live.
The people that live inside a house usually make up a family, although friends can also share a house.
Sometimes, strangers also share a house, especially if rooms in that house are rented separately.

A house is typically a standalone building.
A building that contains multiple stories where multiple people live is usually said to contain multiple apartments.
```


### Spacing in the LaTeX document

!!! As your LaTeX document grows, I suggest you keep some blank lines around key commands to make it easier for you to navigate your document.

As such, I typically recommend leaving two blank lines before and one line after each sectioning command.
Thus, my suggested layout for our current document is as such:

```tex
% ...
\section{Introduction}

Houses are buildings where people live.
The people that live inside a house usually make up a family, although friends can also share a house.
Sometimes, strangers also share a house, especially if rooms in that house are rented separately.

A house is typically a standalone building.
A building that contains multiple stories where multiple people live is usually said to contain multiple apartments.


\section{Divisions}

A house is composed of a variable number of divisions.


\subsection{Private rooms}

Private rooms typically serve a single person at a time.


\subsubsection{Bathroom}

A bathroom is where people use the toilet.


\subsubsection{Bedroom}

A bedroom is where a person sleeps.


\subsection{Common rooms}

Common rooms are used by multiple people at the same time.


\subsubsection{Kitchen}

The kitchen is where food is prepared.


\subsubsection{Living room}

The living room is where people socialise.


\subsubsection{Dining room}

The dining room is where people have their meals together.

% The body ends here.
\end{document}
```

For such a short document, this may not make a huge difference, but you will thank me later when you have a full article or report written in LaTeX.

!!! The two-before-one-after “rule” is a guideline I found to work for me.
!!! You may prefer a different number of lines or you may want to handle things in an entirely different way.
!!! That is absolutely up to you!

## Add a table of contents

### `\tableofcontents`

Creating a table of contents is as simple as adding the command `\tableofcontents` in the same place where you want the table of contents to show up.
Usually, this is right after the document begins:

```tex
% ...
\begin{document}
% The body starts here.

\tableofcontents  % Add a table of contents right here.

\section{Introduction}
% ...
```

### Table of contents on its own page

If you want the table of contents to appear on its own page, without the document contents immediately after, you can use the command `\clearpage` to clear the remainder of the page, like so:

```tex
% ...
\begin{document}
% The body starts here.

\tableofcontents  % Add a table of contents right here.
\clearpage  % Clear the remainder of the page so that the ToC shows up alone.

\section{Introduction}
% ...
```

!!! The command `\clearpage` works in any part of the document and it will clear the remainder of the page.
!!! Thus, the content that comes next will always be at the start of a new page.

[Learn more about the table of contents here](https://www.overleaf.com/learn/latex/Table_of_contents).


## Creating references to your content

Throughout your document you may want to reference things you have already written, or things that you will show later.
For example, you may want to point users to a future section or to a previous image.
This can be done with references.

To create a reference, you need two things:

 1. a label; and
 2. a reference command.


### Adding a label with `\label`

A label is created with the command `\label{...}` and accepts one argument: the name of the label.
Think of it like an actual label you would put in a box for storage: you want the label to reflect what's in the box.
Similarly, the label argument should be a name that reflects what you are pointing to.

For example, when I am adding labels to sections, the name starts with `sec:` and then I put the name of the section in there.

Here is how I would add labels to the two subsections under the section “Divisions”:

```tex
% ...
\subsection{Private rooms}
\label{sec:private rooms}  % Add a label for future reference.

Private rooms typically serve a single person at a time.

\subsubsection{Bathroom}

A bathroom is where people use the toilet.

\subsubsection{Bedroom}

A bedroom is where a person sleeps.

\subsection{Common rooms}
\label{sec:common rooms}  % Add a label for future reference.
% ...
```

Labels can be used after anything that LaTeX adds an automatic number to, so we can already use them with sections, subsections, and sub-subsections.


### Reference a label with `\ref`

After you added a label, you can point to it with the command `\ref`, which accepts as argument the name of the label you want to add.

For example, add a paragraph to the section “Divisions” saying that you will cover private rooms in the subsection `\ref{sec:private rooms}` and common rooms in the subsection `\ref{sec:common rooms}`, like so:

```tex
% ...
\section{Divisions}

A house is composed of a variable number of divisions.
These divisions can be of various types.
We will cover private rooms in subsection \ref{sec:private rooms} and common rooms in subsection \ref{sec:common rooms}.
% Use \ref to add references to labels.

% ...
```

Recompile the document and notice that LaTeX automatically inserts the correct number of the section you are talking about.
That is why you should use labels and references instead of writing the numbers out yourself.


## Adding automatic links to references with `hyperref`

To add automatic links to your document, you can use the package `hyperref`.
To “use a package” means going into the header of your document and using the command `\usepackage` to load the package.

Loading a package is like importing a module in Python, in that it lets you add more functionality and more commands to your document.

Just by loading the package `hyperref`, your references become clickable.

Add this to your document, recompile, and click an item in the table of contents:

```tex
\documentclass{article}
% The header starts here.

\usepackage{hyperref}  % This makes references clickable!

% ...
```

The links will be surrounded by a red box.
This is customisable, but you can also easily turn it off with:

```tex
\documentclass{article}
% The header starts here.

\usepackage[hidelinks]{hyperref}  % This makes references clickable!
%          ^^^^^^^^^^^ Option that turns off the boxes around the links.
```

The `[hidelinks]` is an _option_ that lets the package to not add boxes around the links.


## Styling your text

### Bold

To write text in boldface, use the command `\textbf` and give it as argument the text you want to be in bold.


### Italic

To write text in italic use the command `\textit` and give it as argument the text you want to be italicised.


### Coloured text

To write text in a different colour use the command `\textcolor` and give it as arguments the colour and the text.
For this, you need the package `xcolor` which provides the command `\textcolor`, as well as a series of predefined colours.

### Example of a styled sentence

The example sentence contains all styles we just mentioned:

```tex
\documentclass{article}
% The header starts here.

\usepackage[hidelinks]{hyperref}  % This makes references clickable!
%          ^^^^^^^^^^^ Option that turns off the boxes around the links.

\usepackage{xcolor}  % Add colour to text.

% ...

A house is \textbf{typically} a \textit{standalone building}.
A building that \textcolor{red}{contains multiple stories} where \textcolor{blue}{multiple people} live is usually said to contain multiple apartments.

% ...
```


## Add a bibliography

When writing a scientific document, you will need to add a bibliography.


### Create the bibliography file

There are many advanced and complex bibliography management systems.
We will stick to a simple one.

On the left column, create a new file and call it `my_bibliography.bib`.


### Populate the bibliography

The bibliography file is populated with the references to the articles, books, papers, and other resources, that you want to cite.

The file is a collection of entries that look like this:

```tex
@article{label_name,
    author = "Author name",
    title = "Article title",
    journal = "Journal where article was published",
    year = 2023,
    volume = "73",
    number = "2",
    pages = "103--110"
}
```

The `@article` says it is an article item (it could be a `@book`, etc).
The `label_name` is a label name that you will use to cite the work from your document.
The things that follow are pairs that contain more information about the item you want to cite.
You can read more about [the BibTeX format here](https://www.bibtex.com/g/bibtex-format/).

Many article and book repositories have functionality to automatically generate the BibTeX entry for a given article, report, book, etc.

For example, I went to Google Scholar and searched for "house".
Then, I clicked the button “Cite” and then “BibTeX”.
It generated this for me:

```tex
@article{hauser1988house,
  title={The house of quality},
  author={Hauser, John R and Clausing, Don and others},
  year={1988},
  publisher={Harvard Business Review Vol. 66, May-June 1988}
}
```

Put that in your `my_bibliography.bib` file.


### Inserting the bibliography

To insert the bibliography in your document, use the command `\bibliograph` and use as argument the name of your file (without the `.bib` extension).

Like so:

```tex
% ...

\subsubsection{Dining room}

The dining room is where people have their meals together.

\bibliography{my_bibliography}  % Insert the bibliography here.

% The body ends here.
\end{document}
```

Recompile your document, and behold!
Nothing happens!
That is because the bibliography will only show the items that you **actively** cited in your document.

!!! I am pretty sure this is the standard behaviour in scientific publications, but I am also sure you can customise this behaviour and show all of the items in the bibliography.

### Citing an item with `\cite`

To cite an item from your bibliography, just use the command `\cite` with the citation key as the argument.
The citation key is the label name of that bibliographic item, and it should be the first thing inside the curly braces `{}` in your item.

For example, in

```tex
@article{hauser1988house,
  title={The house of quality},
  author={Hauser, John R and Clausing, Don and others},
  year={1988},
  publisher={Harvard Business Review Vol. 66, May-June 1988}
}
```

the citation key is `hauser1988house`.

To cite this work in the document, write `\cite{hauser1988house}`.
For example, like so:

```tex
% ...
\section{Introduction}

% Add a citation to the bibliography.
We base our work on the contributions of \cite{hauser1988house}.
% ...
```

Recompile, and behold!
Still, nothing happens!


### Setting the style of the bibliography

To display a bibliography, you also have to set its style.
There are many styles available, but we will go with the plain one for this article:

```tex
% ...

\bibliographystyle{plain}  % Set the style of the bibliography.
\bibliography{my_bibliography}  % Insert the bibliography here.

% The body ends here.
\end{document}
```

Now, the bibliography should show its only item.

[Learn more about managing a bibliography here](https://www.overleaf.com/learn/latex/Bibliography_management_with_bibtex).


## Add a glossary

Similarly, you can add a glossary if you intend on using many acronyms or unfamiliar terms.


### Import the package and prepare the glossary

To use a glossary in your document, you need to add the package `glossaries` to the header and use the command `\makeglossaries` to tell LaTeX you will define glossary entries.

```tex
\documentclass{article}
% The header starts here.

\usepackage[hidelinks]{hyperref}  % This makes references clickable!
%          ^^^^^^^^^^^ Option that turns off the boxes around the links.

\usepackage{glossaries}  % Supports adding a glossary.
\makeglossaries  % Prepares for defining the glossary entries.

% ...
```


### Add acronym entries

To add glossary acronym entries, use the command `\newacronym` that takes **three** arguments:

 1. the label name of that glossary acronym;
 2. the acronym; and
 3. the expanded version of the acronym.

Here are three examples you can add to the header, immediately under `\makeglossaries`:

```tex
% ...

\makeglossaries  % Prepares for defining the glossary entries.

\newacronym{tmi}{TMI}{too much information}
\newacronym{fyi}{FYI}{for your information}
\newacronym{tldr}{TL;DR:}{too long, didn't read summary}
```


### Add glossary entries

Similarly, to add a glossary entry, just use the command `\newglossaryentry` that takes two arguments:

 1. the label name of that glossary acronym; and
 2. the `name` and `description` informations about that glossary entry.

Here is an example you can add under the acronyms you already defined.

```tex
% ...

\makeglossaries  % Prepares for defining the glossary entries.

\newacronym{tmi}{TMI}{too much information}
\newacronym{fyi}{FYI}{for your information}
\newacronym{tldr}{TL;DR:}{too long, didn't read summary}

\newglossaryentry{house}{
    name = "house",
    description = "A building where people live."
}
```


### Use entries

To use glossary entries, the simplest command is `\gls` which takes the label name of the entry.
`\Gls` is like `\gls`, but will capitalise the first letter of the entry.
Then, `\glspl` and `\Glspl` are useful if you want to use a glossary entry in the plural form.

For example, change the first paragraph of the “Introduction” to this:

```tex
% ...

\section{Introduction}

% Add a citation to the bibliography.
We base our work on the contributions of \cite{hauser1988house}.
\Gls{fyi}, this was \gls{tmi}.
The \gls{tldr} is that a \gls{house} is cool.

% ...
```


### Print the glossary

Finally, to print the glossary, we can use the command `\printglossaries`, for example at the end of the document:

```tex
% ...

\printglossaries  % Print the glossaries.

\bibliographystyle{plain}  % Set the style of the bibliography.
\bibliography{my_bibliography}  % Insert the bibliography here.

% The body ends here.
\end{document}
```


### Customising the glossary

The glossary can be customised further to your preferences.
You can [read more about the glossary here](https://www.overleaf.com/learn/latex/Glossaries).


## Multi-file documents

When creating large documents, it can be helpful to split your sections into different files.
For that, go ahead and create two files:

 1. `introduction.tex`; and
 2. `divisions.tex`.

Copy and paste each section into its file:

```tex
% introduction.tex:
\section{Introduction}

% Add a citation to the bibliography.
We base our work on the contributions of \cite{hauser1988house}.
\Gls{fyi}, this was \gls{tmi}.
The \gls{tldr} is that a \gls{house} is cool.

Houses are buildings where people live.
The people that live inside a house usually make up a family, although friends can also share a house.
Sometimes, strangers also share a house, especially if rooms in that house are rented separately.

A house is typically a standalone building.
A building that contains multiple stories where multiple people live is usually said to contain multiple apartments.
```

```tex
% divisions.tex:
\section{Divisions}

A house is composed of a variable number of divisions.
These divisions can be of various types.
We will cover private rooms in subsection \ref{sec:private rooms} and common rooms in subsection \ref{sec:common rooms}.
% Use \ref to add references to labels.

\subsection{Private rooms}
\label{sec:private rooms}  % Add a label for future reference.

Private rooms typically serve a single person at a time.

\subsubsection{Bathroom}

A bathroom is where people use the toilet.

\subsubsection{Bedroom}

A bedroom is where a person sleeps.

\subsection{Common rooms}
\label{sec:common rooms}  % Add a label for future reference.

Common rooms are used by multiple people at the same time.

\subsubsection{Kitchen}

The kitchen is where food is prepared.

\subsubsection{Living room}

The living room is where people socialise.

\subsubsection{Dining room}

The dining room is where people have their meals together.
```

Now, delete the sections from your file `main.tex` and write this instead:

```tex
\begin{document}
% The body starts here.

\tableofcontents  % Add a table of contents right here.
\clearpage  % Clear the remainder of the page so that the ToC shows up alone.

\input{introduction}  % Include what's in introduction.tex

\input{divisions}  % Include what's in divisions.tex

\printglossaries  % Print the glossaries.

\bibliographystyle{plain}  % Set the style of the bibliography.
\bibliography{my_bibliography}  % Insert the bibliography here.

% The body ends here.
\end{document}
```


## Mathematical formulas and equations

To add maths inline with your text, use a dollar sign before and a dollar sign after the numbers/formulas.
Here is an example:

```tex
% ...

\input{divisions}


\section{Maths}

If an apartment holds $4$ people, a building with $12$ apartments holds $4 \times 12 = 48$ people.

% ...
```

Use something like [Detexify](http://detexify.kirelabs.org/classify.html) to learn about the commands needed to print some symbols.
The results also show what packages you might need for those symbols.


### Superscript

As a general piece of advice, many symbols expect superscript content or content on top of that, and you can do that with `^`.


### Subscript

Similarly, you can put content under a symbol, or in subscript, with `_`.

The summation symbol is a good example of a symbol that takes superscript and subscript elements:

```tex
% ...

\input{divisions}  % Include what's in divisions.tex

\section{Maths}

If an apartment holds $4$ people, a building with $12$ apartments holds $4 \times 12 = 48$ people.  % Adding maths inline.
You can also write it as $\sum_{i=1}^{4} 12 = 18$, in a convoluted way.
```

Notice that we use the curly braces `{}` to group together the things that go into subscript and the things that go into superscript, otherwise LaTeX would only put a single character in each place.


### Equations

To create a full equation that is displayed in the centre of the text, use the environment `equation`:

```tex
% ...

\section{Maths}

If an apartment holds $4$ people, a building with $12$ apartments holds $4 \times 12 = 48$ people.  % Adding maths inline.

\begin{equation}  % Add a centred, numbered equation.
    4 \times 12 = 12 + 12 + 12 + 12 = 48
\end{equation}

% ...
```


### Multi-line, aligned equations

Use the environment `align` for multi-line equations with alignment:

```tex

% ...
\section{Maths}

If an apartment holds $4$ people, a building with $12$ apartments holds $4 \times 12 = 48$ people.  % Adding maths inline.

\begin{equation}  % Add a centred, numbered equation.
    4 \times 12 = 12 + 12 + 12 + 12 = 48
\end{equation}

\begin{align}  % Multi-line, aligned.
    4 \times 12 &= 12 + 12 + 12 + 12 \\ % & sets the alignment and \\ changes line.
    &= 48
\end{align}

% ...
```

The symbol `&` determines where the vertical alignment goes and the double backslash `\\` tells LaTeX where to change lines.

You cannot use `\\` inside a regular environment `equation` to change aligns.


### Build your equations online

You can use an online LaTeX equation editor like [this one](https://latex.codecogs.com/eqneditor/editor.php) to edit your equations by using a friendlier interface, while you are still getting used to the commands.


### Referencing your equations

Most mathematical environments (like `equation` and `align`) will give a number to your equation.
If you use `\label`, those can be referenced with the command `\eqref`.
`\eqref` is just like `\ref`, but it styles references in a slightly different way to show that you are referencing an equation:

```tex
% ...

You can also write it as $\sum_{i=1}^{4} 12 = 18$, in a convoluted way.
This is written out explicitly in equation \eqref{eq:42}.

\begin{equation}  % Add a centred, numbered equation.
\label{eq:42}
    4 \times 12 = 12 + 12 + 12 + 12 = 48
\end{equation}

% ...
```


## Inserting images into your document

### Uploading the image

The first thing you need to do if you want to insert an image into your LaTeX document is to tell Overleaf about it.
This means you need to upload the image into Overleaf.

On the left, Overleaf has an “Upload” button that you can use.
If you don't have a picture you would like to upload, you can go ahead and use [the thumbnail](./thumbnail.webp) from this article.

!!! When uploading images, I recommend uploading them into a folder specifically for images.
!!! For example, I uploaded my `thumbnail.webp` into a new folder called `images`.
!!! This will make it easier to organise everything in your project.


### The `graphicx` package

The next thing you need is to use the package that is appropriate for images: `graphicx`.

! Mind you, it is `graphicx` with an `X` at the end, and not `graphics` with an `S`.

Here is my updated `main.tex`:

```tex
\documentclass{article}
% The header starts here.

\usepackage[hidelinks]{hyperref}  % This makes references clickable!
%          ^^^^^^^^^^^ Option that turns off the boxes around the links.

\usepackage{glossaries}  % Supports adding a glossary.
\makeglossaries  % Prepares for defining the glossary entries.

\usepackage{graphicx}  % Allows inserting images into the PDF.

% ...
```


### The `figure` environment

Next, go to where you want to add your image and create the environment `figure`.

I created the environment in a brand new section:

```tex
% ...

\section{Maths}

% ...

\section{Images}

\begin{figure}  % <- start the environment to add a figure.

\end{figure}

\printglossaries  % Print the glossaries.

% ...
```

If you are using Overleaf, it might have filled in some lines for you.
We'll go over those now, but we will write them by ourselves.


### The command `includegraphics`

There is a command, called `includegraphics`, that is responsible for inserting the image into the document.
We can use that command inside the environment `figure`.
The argument to the command is the path to the figure, which is the folder the picture is in, followed by the name of the file, separated by a forward slash:

```tex
% ...

\begin{figure}
    \includegraphics{images/thumbnail.webp}
\end{figure}

% ...
```

When you type the opening curly brace `{` Overleaf will suggest image files so you just have to select the correct one.

Also, the command `\includegraphics` is indentend because that is my personal preference when writing LaTeX inside nested environments.
You are free to align the command `\includegraphics` (and the commands that follow) on the left.
However, it is my personal recommendation that you indent LaTeX code inside other environments.


### Adjusting the size of your image

There are many ways in which you can adjust the size of your image.
The two most common ones are `scale` and `width`.
These options can go inside the square brackets `[]` after the name of the command (`includegraphics`) and before the curly braces.


#### Scale

For example, this code would insert the image with 10% the size:

```tex
% ...

\begin{figure}
    \includegraphics[scale=0.1]{images/thumbnail.webp}
\end{figure}

% ...
```


#### Width

Alternatively, you can specify the width you want the image to have.
You can specify this as a value in centimetres `cm`, millimetres `mm`, `em`, pixels `px`, among other units.
You can also specify a size relative to the width of the line of your document, which is `\linewidth`.

Thus, the code below inserts the image and rescales it so that its width becomes _half_ of the line width (while preserving the aspect ratio of the image):

```tex
% ...

\begin{figure}
    \includegraphics[width=0.5\linewidth]{images/thumbnail.webp}
\end{figure}

% ...
```

Notice that `\linewidth` is _not_ the horizontal length of the page but rather the length of the region where LaTeX will write (so, that's the length of the page minus the margins).


### Centring your image

After rescaling your image, you can centre it with the command `\centering`:

```tex
% ...

\begin{figure}
    \centering
    \includegraphics[width=0.5\linewidth]{images/thumbnail.webp}
\end{figure}

% ...
```


### Adding a caption to your image

The next thing you need to do is use the command `\caption` to add a caption to your image.
The command `\caption` is what will _assign a figure number_ to your image and it is what will allow you to reference your image later.

Here is a simple caption:

```tex
% ...

\begin{figure}
    \centering
    \includegraphics[width=0.5\linewidth]{images/thumbnail.webp}
    \caption{Thumbnail image of a blog post.}
\end{figure}

% ...
```

The command `\caption` must come after the included image.


### Adding a label to your image

After adding a caption you can add a label that _must_ come _after_ the caption:

```tex
% ...

\begin{figure}
    \centering
    \includegraphics[width=0.5\linewidth]{images/thumbnail.webp}
    \caption{Thumbnail image of a blog post.}
    \label{fig:thumbnail}
\end{figure}

Just take a look at Figure \ref{fig:thumbnail}, isn't that a lovely figure?

% ...
```

!!! As recommended earlier, consider adding a common prefix to all of your image labels.
!!! I usually use `fig:`.
!!! Additionally, I tend to use the image name as the label itself.
!!! This forces me to give decent names to my images and makes it easier to create the label because I don't have to waste time thinking “what would be a good name for this label?”.


## Controlling image positioning

In LaTeX, environments like figures and tables (which you will learn about next) tend to “float” away from the place you put them in.
This is intentional and this functionality aims to waste as little whitespace as possible.

This is something you can learn to live with, as it is typically a good thing, but sometimes you may want to control the positioning of your figures and tables.


### The package `float`

If you want to tell LaTeX where to place your figures, you will want to use the package `float`.
You can remember this is the package to use because you want to prevent your figures from “floating” away.

```tex
% ...

\usepackage{graphicx}  % Allows inserting images into the PDF.

\usepackage{float}  % Control figure placement.

% ...
```


### Place the image HERE!

If you want LaTeX to place the image _exactly_ where you added it, you can use the option `H` (for Here) in the environment `figure`.
This option goes after the command `\begin{figure}[H]`:

```tex
% ...

\begin{figure}[H]
    \centering
    \includegraphics[width=0.5\linewidth]{images/thumbnail.webp}
    \caption{Thumbnail image of a blog post.}
    \label{fig:thumbnail}
\end{figure}

% ...
```

You can [learn more about image (and table) placement here](https://www.overleaf.com/learn/latex/Positioning_images_and_tables).


## Creating LaTeX tables

To create LaTeX tables, my suggested approach is that you use an online service like [this one](https://www.tablesgenerator.com) to create your tables in a graphical editor and then just copy and paste the LaTeX code that that service generates.

This is an example table generated by the service I linked:

```tex
% ...

\section{Tables}

\begin{table}[]
    \begin{tabular}{ll}
        \textbf{Room} & \textbf{Type} \\
        Bedroom       & Private       \\
        Bathroom      & Private       \\
        Living room   & Common
    \end{tabular}
\end{table}

% ...
```

You can [learn more about tables here](https://www.overleaf.com/learn/latex/Tables) but I explain the basics below.


### The environments `table` and `tabular`

The environment `table` is akin to the environment `figure` but for tables.
The environment `tabular` delimits the area where you write the data for the table.


### Specifying the size of your table

When you create a table the only thing you need to do is tell the `tabular` environment how many columns your table has.
You do that via an argument to the environment `tabular`.

See the `{ll}` in the table above?

Each letter corresponds to a column and the letter specifies how the column is aligned:

 - `l` stands for “left”;
 - `c` stands for “centre”; and
 - `r` stands for “right”.

As for the number of rows, you do not have to specify them upfront.


### Separating items in your table

Inside your environment `tabular` you use the symbol `&` to separate items in the same row and you use the double backslash `\\` to move to a new row.


### Centring a table

Just like a `figure` environment, `table` can also be centred:

```tex
% ...

\begin{table}
    \centering
    \begin{tabular}{ll}
        \textbf{Room} & \textbf{Type} \\
        Bedroom       & Private       \\
        Bathroom      & Private       \\
        Living room   & Common       
    \end{tabular}
\end{table}

% ...
```


### Captioning and referencing tables

A table can and should be captioned with the command `\caption`, which means you can then add a label to your table to enable cross-referencing it:

```tex
% ...

\begin{table}
    \centering
    \begin{tabular}{ll}
        \textbf{Room} & \textbf{Type} \\
        Bedroom       & Private       \\
        Bathroom      & Private       \\
        Living room   & Common       
    \end{tabular}
    \caption{A table with types of rooms.}
    \label{tab:types of rooms}
\end{table}

See Table \ref{tab:types of rooms} to check some types of rooms.

% ...
```


### Awesome tables with `booktabs`

Once you are comfortable with creating a couple of tables I suggest that you look up the package `booktabs`.
With very little effort you can create professional-looking tables.
In particular, I am a big fan of [this quick guide](https://people.inf.ethz.ch/markusp/teaching/guides/guide-tables.pdf).


## Inserting listings of code

Again, there are many resources out there that teach you about handling code inside your LaTeX file, like [this one](https://www.overleaf.com/learn/latex/Code_listing).
I want to show you the most common case (or what _should_ be the most common case): including code from a file into your LaTeX document.

! Copying code from a file and pasting it in your LaTeX project is a bad idea because you will _probably_ change your code and then _forget to update LaTeX_.
! If we link the LaTeX project that that code file, you can update your code all you want and your report/thesis/document will stay up to date.


### The package `listings`

The package we will be using for this is `listings`:

```tex
% ...

\usepackage{float}  % Control figure placement.

\usepackage{listings}  % Include source code in LaTeX.

% ...
```


### The command `\lstinputlisting`

The command `\lstinputlisting` expects a path to your code file and will import it into your project.
For Overleaf, we will need to update the code file.
If you are using LaTeX in your machine you just have to specify the path correctly.
It helps if the LaTeX project and the code are somewhere “close” to each other.

Assuming I uploaded a file called `mycode.py` into the folder `code` in my Overleaf project, I can write this:

```tex
% ...

\section{Code}

\lstinputlisting[language=Python]{code/mycode.py}

% ...
```

The option `language` is used to tell `listings` what language the code is written in, which LaTeX uses to add some very basic syntax highlighting.

If you want to try it for yourself, create the file `mycode.py` inside the folder `code` and paste this into that file:

```py
from functools import cache

@cache
def fibonacci(n: int) -> int:
    if n <= 1:
        return 1
    return fibonacci(n - 1) + fibonacci(n - 2)
```


### Captioning your listing

To add a caption to your listing, use the option `caption`:

```tex
% ...

\section{Code}

\lstinputlisting[language=Python, caption={A Fibonacci function with a cache.}]{code/mycode.py}

% ...
```

We write the caption inside `{}` otherwise LaTeX will think the caption is just the first word you write.


### Labelling and referencing your listings

Similarly, the option `label` can be used to specify the label you can use to refer to a specific listing of code:

```tex
% ...

\section{Code}

\lstinputlisting[language=Python, caption={A Fibonacci function with a cache.}, label={lst:mycode}]{code/mycode.py}

I show some code in the Listing \ref{lst:mycode}.

% ...
```


## Adding reference tables

On top of a table of contents (which lists all sections, subsections, and sub-subsections), you may want to add a reference table of

 - figures;
 - tables.
 - listings; or

Those all have aptly named commands:


### `\listoffigures`

The command `\listoffigures` will generate a list with all your figures.


### `\listoftables`

The command `\listoftables` will generate a list with all your tables.


### `\lstlistoflistings`

The command `\lstlistoflistings` (from the package `listings`) will generate a list with all your code listings.


## Conclusion

This was the reference article I use for my LaTeX workshops.
Typically, I don't have time to go through _every single section_ of this article.
I might also talk more about specific sections, depending on my audience.

Either way, I hope this was at least a bit helpful for you!

If you want to check it, [this is the Overleaf document that implements all of these introductory features](https://www.overleaf.com/read/pwxnphrhwjbr) as I presented them here.
Feel free to copy it and use it as a template for your LaTeX documents.


[Overleaf]: https://overleaf.com

import string

_PUNCTUATION_TABLE = str.maketrans("", "", string.punctuation)

def remove_punctuation(s):
    """Removes ASCII punctuation signs from the given string.

    Example:
        >>> remove_punctuation("Hello, world!")
        'Hello world'
    """
    return s.translate(_PUNCTUATION_TABLE)

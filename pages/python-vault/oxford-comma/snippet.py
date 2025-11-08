def oxford_comma(strings):
    """Creates a Human-readable enumeration of strings using the Oxford comma.
    
    Examples:
        >>> oxford_comma([])
        ''
        >>> oxford_comma(["like", "comment"])
        'like and comment'
        >>> oxford_comma(["like", "comment", "subscribe"])
        'like, comment, and subscribe'
    """
    prefix = ", ".join(strings[:-1])
    ox_comma = "," if len(strings) >= 3 else ""
    and_ = " and " if len(strings) >= 2 else ""
    last = strings[-1] if strings else ""
    return prefix + ox_comma + and_ + last

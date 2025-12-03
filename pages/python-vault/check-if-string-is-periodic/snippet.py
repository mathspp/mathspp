def is_periodic(string):
    """Check if this string can be formed by repeating one of its prefixes.
    
    Examples:
        >>> is_periodic("ababab")
        True
        >>> is_periodic("aba")
        False
    """
    return string in (string + string)[1:-1]

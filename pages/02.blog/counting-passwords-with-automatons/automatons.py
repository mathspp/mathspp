import string, time


class Automaton:
    """Class that represents a finite state automaton."""

    def __init__(self, state_transitions, terminal_states):
        """Initialises a finite state automaton.

        `state_transitions` is a dictionary representing the state transitions
        and `terminal_states` is a container that holds all the terminal states.
        """

        self._state_transitions = state_transitions
        self._terminal_states = terminal_states
        self._count_terminal_paths_cache = {}

    def is_terminal(self, state):
        """Returns whether `state` is a terminal state for the automaton or not."""
        return state in self._terminal_states

    def count_terminal_paths(self, state):
        """Counts how many paths go from `state` to any terminal state."""
        if state not in self._count_terminal_paths_cache:
            acc = int(self.is_terminal(state))
            for actions, next_state in self._state_transitions.get(state, []):
                acc += len(actions) * self.count_terminal_paths(next_state)
            self._count_terminal_paths_cache[state] = acc
        return self._count_terminal_paths_cache[state]


def generate_next_pwd_states(s):
    return [s[:i] + (num + 1,) + s[i + 1 :] for i, num in enumerate(s)]


def generate_state_transitions(classes, max_length):
    queue = [(0,) * len(classes)]
    state_transitions = {}

    while queue:
        state, *queue = queue
        if sum(state) < max_length:
            next_states = generate_next_pwd_states(state)
        else:
            next_states = []
        state_transitions[state] = list(zip(classes, next_states))
        for state_ in next_states:
            if state_ not in queue:
                queue.append(state_)

    return state_transitions


def gather_terminal_states(state_transitions, is_valid_pwd):
    return [s for s in state_transitions if is_valid_pwd(s)]


if __name__ == "__main__":
    # Configure the password:
    classes = [
        string.ascii_uppercase,
        string.ascii_lowercase,
        string.digits,
    ]
    MIN_LENGTH = 8
    MAX_LENGTH = 10
    is_valid_pwd = lambda s: MIN_LENGTH <= sum(s) <= MAX_LENGTH and all(s)

    state_transitions = generate_state_transitions(classes, MAX_LENGTH)
    terminal_states = gather_terminal_states(state_transitions, is_valid_pwd)

    automaton = Automaton(state_transitions, terminal_states)
    start = time.perf_counter()
    print(automaton.count_terminal_paths((0,) * len(classes)))
    elapsed = time.perf_counter() - start
    print(f"Counted in {elapsed:.6f}s.")

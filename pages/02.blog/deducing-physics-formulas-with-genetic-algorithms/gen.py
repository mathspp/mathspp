import operator
import random


operators = [
    operator.add,
    operator.sub,
    operator.mul,
    operator.truediv,
]


op_as_str = {
    operator.add: "+",
    operator.sub: "-",
    operator.mul: "*",
    operator.truediv: "/",
}


operands = [
    "x0",
    "v0",
    "a",
    "t",
    1,
]


def generate_fake_data():
    """Create a dictionary with fake data according to the formula we're deducing."""
    data = {}
    data["x0"] = random.uniform(-10, 10)
    data["v0"] = random.uniform(-10, 10)
    data["a"] = random.uniform(-10, 10)
    data["t"] = random.uniform(-10, 10)
    data["result"] = (
        0.5 * data["a"] * data["t"] ** 2 + data["v0"] * data["t"] + data["x0"]
    )
    # We add a bit of noise to simulate experimental errors:
    data["result"] += random.normalvariate(0, 0.1)
    return data


class Formula:
    """Base class for formulas represented as trees."""

    def __init__(self):
        self._fitness_cache = None

    @classmethod
    def new_formula(self, operator_prob=0.5):
        """Generates a random formula."""
        if random.random() < operator_prob:
            return Operator.new_operator(operator_prob / 2)
        else:
            return Operand.new_operand()

    def fitness(self, data_list):
        if self._fitness_cache is None:
            squared_errors = [
                (data["result"] - self.eval(data)) ** 2 for data in data_list
            ]
            self._fitness_cache = sum(squared_errors) / len(squared_errors)

        return self._fitness_cache

    def eval(self, data):
        """Evaluates the formula on the given data dictionary."""
        raise NotImplementedError()

    def copy(self):
        """Creates a copy of the given formula."""
        raise NotImplementedError()

    def mutate(self, mutation_rate=0.05):
        """Mutates the given formula."""
        raise NotImplementedError()


class Operator(Formula):
    """Base class for binary operators in formulas."""

    def __init__(self, op, left, right):
        super().__init__()
        self.op = op
        self.left = left
        self.right = right

    @classmethod
    def new_operator(cls, operator_prob):
        """Generates a random operator."""
        op = random.choice(operators)
        left = Formula.new_formula(operator_prob)
        right = Formula.new_formula(operator_prob)
        return cls(op, left, right)

    def __repr__(self):
        return f"({self.left} {op_as_str[self.op]} {self.right})"

    def eval(self, data):
        """Evaluates the formula on the given data dictionary."""
        left = self.left.eval(data)
        right = self.right.eval(data)
        if self.op == operator.truediv and right == 0:
            return float("inf")
        return self.op(left, right)

    def copy(self):
        """Creates a copy of the given operator."""
        return Operator(self.op, self.left.copy(), self.right.copy())

    def mutate(self, mutation_rate=0.05):
        """Mutates the given formula."""
        mutated = False
        if random.random() < mutation_rate:
            self.op = random.choice(operators)
            mutated = True
        mutated = mutated or self.left.mutate(mutation_rate)
        mutated = mutated or self.right.mutate(mutation_rate)
        if mutated:
            self._fitness_cache = None
        return mutated


class Operand(Formula):
    """Base class for operands in formulas."""

    def __init__(self, value):
        super().__init__()
        self.value = value

    @classmethod
    def new_operand(cls):
        """Generates a random operand."""
        return cls(random.choice(operands))

    def __repr__(self):
        if isinstance(self.value, str):
            return self.value
        else:
            return f"{self.value:.2f}"

    def eval(self, data):
        """Evaluates the formula on the given data dictionary."""
        if isinstance(self.value, str):
            return data[self.value]
        return self.value

    def copy(self):
        """Creates a copy of the given operand."""
        return Operand(self.value)

    def mutate(self, mutation_rate=0.05):
        """Mutates the given formula."""
        if random.random() < mutation_rate:
            self._fitness_cache = None
            if isinstance(self.value, str):
                self.value = random.choice(operands)
            else:
                self.value = random.expovariate(1 / self.value)
            return True
        return False


def initialise_population(n, operator_prob=0.5):
    """Create an initial population of `n` random formulas."""
    return [Formula.new_formula(operator_prob) for _ in range(n)]


def reproduce(f1, f2):
    """Combines two formulas to produce a new one."""
    # If we have two operands, put them under an operator.
    # Do this rarely (2% of the time) for other types of formulas as well.
    if (isinstance(f1, Operand) and isinstance(f2, Operand)) or random.random() < 0.02:
        op = random.choice(operators)
        return Operator(op, f1.copy(), f2.copy())

    # If we have one operator and one operand, put the operand in one of the branches
    # of the operator.
    # If we have two operators, put one branch of f1 into one of the branches of f2.
    # (We assume f1 and f2 were shuffled.)
    if isinstance(f1, Operand):
        branch = f1.copy()
        child = f2.copy()
    elif isinstance(f2, Operand):
        branch = f2.copy()
        child = f1.copy()
    else:
        branch_side = random.choice(["left", "right"])
        branch = getattr(f1, branch_side).copy()
        child = f2.copy()
    child_side = random.choice(["left", "right"])
    setattr(child, child_side, branch)
    return child


if __name__ == "__main__":
    from functools import partial
    from itertools import pairwise  # Python 3.10+

    POPULATION_SIZE = 10_000
    DATA_SIZE = 200
    TO_REPRODUCE = 5_000
    TOP_SAVED = 20
    GENERATIONS = 70

    population = [Formula.new_formula() for _ in range(POPULATION_SIZE)]
    data = [generate_fake_data() for _ in range(DATA_SIZE)]
    fitness_from_data = partial(Formula.fitness, data_list=data)

    for gen in range(GENERATIONS):
        # Sort population by fitness.
        sorted_pop = sorted(population, key=fitness_from_data)
        best_f = sorted_pop[0]
        print(f"{gen}. {best_f} : {best_f.fitness(data)}")

        # Save a copy of the absolute best formulas.
        top = [f.copy() for f in sorted_pop[:TOP_SAVED]]

        # The fittest are more likely to reproduce.
        parents = random.choices(
            sorted_pop, weights=range(len(sorted_pop), 0, -1), k=TO_REPRODUCE
        )
        children = [reproduce(f1, f2) for f1, f2 in pairwise(parents)]

        # The fittest survive for the next generation with possible mutations.
        fittest = sorted_pop[: POPULATION_SIZE // 2]
        for f in fittest:
            f.mutate(0.1)

        population = top + fittest + children

    sorted_pop = sorted(population, key=fitness_from_data)
    best_f = sorted_pop[0]
    print(f"{best_f} : {best_f.fitness(data)}")

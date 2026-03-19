# Refactoring Patterns

This directory contains PHP implementations of Martin Fowler's refactoring patterns with practical examples demonstrating before and after states.

## Overview

Each class demonstrates a specific refactoring pattern with:
- Clear documentation explaining the pattern
- Practical code examples showing before and after states
- Comments explaining when to use the pattern
- A demonstration method showing the pattern in action

## Patterns Included

### 1. Extract Function (`ExtractFunction.php`)

**Purpose**: Take a fragment of code that can be grouped together and turn it into a function whose name explains the purpose.

**When to use**:
- When you have a code fragment that can be grouped together
- When you need to use the same code fragment in multiple places
- When a function is too long and does multiple things
- When you need to add comments to explain what code is doing

**Benefits**:
- Improves code readability by giving meaningful names to code fragments
- Reduces code duplication by extracting reusable logic
- Makes code easier to understand and maintain
- Facilitates testing by isolating specific behaviors

**Reference**: [Extract Function - Refactoring.com](https://refactoring.com/catalog/extractFunction.html)

### 2. Inline Function (`InlineFunction.php`)

**Purpose**: Replace a function call with the function's body when the body is as clear as the name.

**When to use**:
- When a function body is as clear as its name
- When you have too many small functions that don't add value
- When the indirection is more confusing than helpful
- When preparing for a different refactoring
- When functions are only called from one place and don't provide reusability

**Benefits**:
- Eliminates unnecessary indirection
- Reduces code complexity when functions are too granular
- Improves performance by removing function call overhead
- Makes code more straightforward when function names don't add clarity

**Warning**: Don't inline when:
- The function is used in multiple places (would create duplication)
- The function provides meaningful abstraction
- The function is overridden in subclasses (polymorphism)

**Reference**: [Inline Function - Refactoring.com](https://refactoring.com/catalog/inlineFunction.html)

### 3. Introduce Parameter Object (`IntroduceParameterObject.php`)

**Purpose**: Group parameters that naturally go together into a single object.

**When to use**:
- When you have a group of parameters that often appear together
- When you see the same parameters in multiple function signatures
- When parameters represent a cohesive concept
- When you need to add more related parameters
- When parameter ordering becomes confusing

**Benefits**:
- Reduces the number of parameters (improved readability)
- Groups related data together (better organization)
- Makes relationships between data explicit
- Easier to add new related data without changing function signatures
- Enables moving behavior related to the data into the new class
- Reduces errors from parameter ordering mistakes

**Reference**: [Introduce Parameter Object - Refactoring.com](https://refactoring.com/catalog/introduceParameterObject.html)

## Usage

Each class includes a `demonstratePattern()` method that shows the pattern in action. You can run these demonstrations to see the before and after comparisons.

### Example: Running Extract Function demonstration

```php
<?php

require_once 'ExtractFunction.php';

use RefactoringPatterns\ExtractFunction;

$example = new ExtractFunction();
$example->demonstratePattern();
```

### Example: Running Inline Function demonstration

```php
<?php

require_once 'InlineFunction.php';

use RefactoringPatterns\InlineFunction;

$example = new InlineFunction();
$example->demonstratePattern();
```

### Example: Running Introduce Parameter Object demonstration

```php
<?php

require_once 'IntroduceParameterObject.php';

use RefactoringPatterns\IntroduceParameterObject;

$example = new IntroduceParameterObject();
$example->demonstratePattern();
```

## PHP Standards

All code follows PHP best practices:

- **PSR-12**: Extended coding style guide
- **Strict types**: All files use `declare(strict_types=1)` 
- **Namespacing**: All classes use the `RefactoringPatterns` namespace
- **Type hints**: All parameters and return types are properly typed
- **Readonly properties**: Using PHP 8.1+ readonly properties where appropriate
- **DocBlocks**: Comprehensive documentation for all classes and methods

## Requirements

- PHP 8.1 or higher (for readonly properties)
- No external dependencies required

## Learning Resources

- [Refactoring: Improving the Design of Existing Code](https://refactoring.com/) by Martin Fowler
- [Refactoring Catalog](https://refactoring.com/catalog/)

## Notes

These examples are designed for educational purposes to demonstrate the concepts clearly. In real-world scenarios, you would apply these patterns based on your specific codebase needs and context.

## Contributing

When adding new refactoring patterns:
1. Create a new PHP file named after the pattern
2. Follow the existing structure with before/after examples
3. Include comprehensive documentation
4. Add a `demonstratePattern()` method
5. Update this README with the new pattern
6. Follow all PSR standards and PHP best practices

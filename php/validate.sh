#!/bin/sh

# Validation script for refactoring patterns PHP files
# This script validates PHP syntax and runs the demonstration methods

set -e

echo "=== PHP Refactoring Patterns Validation ==="
echo ""

# Check if PHP is available
if ! command -v php > /dev/null 2>&1; then
    echo "Error: PHP is not installed or not in PATH"
    echo "Please install PHP 8.1 or higher to run this validation"
    exit 1
fi

# Check PHP version
PHP_VERSION=$(php -r "echo PHP_VERSION;")
echo "PHP Version: $PHP_VERSION"
echo ""

# Validate PHP syntax for all files
echo "=== Syntax Validation ==="
echo ""

for file in ExtractFunction.php InlineFunction.php IntroduceParameterObject.php; do
    echo "Checking syntax: $file"
    if php -l "$file" > /dev/null 2>&1; then
        echo "✓ $file - Syntax OK"
    else
        echo "✗ $file - Syntax Error"
        php -l "$file"
        exit 1
    fi
done

echo ""
echo "=== Running Demonstrations ==="
echo ""

# Run Extract Function demonstration
echo "----------------------------------------"
echo "1. Extract Function Pattern"
echo "----------------------------------------"
php -r "
require_once 'ExtractFunction.php';
use RefactoringPatterns\ExtractFunction;
\$example = new ExtractFunction();
\$example->demonstratePattern();
"
echo ""

# Run Inline Function demonstration
echo "----------------------------------------"
echo "2. Inline Function Pattern"
echo "----------------------------------------"
php -r "
require_once 'InlineFunction.php';
use RefactoringPatterns\InlineFunction;
\$example = new InlineFunction();
\$example->demonstratePattern();
"
echo ""

# Run Introduce Parameter Object demonstration
echo "----------------------------------------"
echo "3. Introduce Parameter Object Pattern"
echo "----------------------------------------"
php -r "
require_once 'IntroduceParameterObject.php';
use RefactoringPatterns\IntroduceParameterObject;
\$example = new IntroduceParameterObject();
\$example->demonstratePattern();
"
echo ""

echo "=== All validations passed! ==="

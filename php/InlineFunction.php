<?php

declare(strict_types=1);

namespace RefactoringPatterns;

/**
 * Inline Function Refactoring Pattern
 *
 * This refactoring pattern involves replacing a function call with the function's body.
 * It's the opposite of Extract Function and is used when the function body is just as clear
 * as the function name, or when the function is too simple to warrant a separate method.
 *
 * Benefits:
 * - Eliminates unnecessary indirection
 * - Reduces code complexity when functions are too granular
 * - Improves performance by removing function call overhead
 * - Makes code more straightforward when function names don't add clarity
 *
 * When to use:
 * - When a function body is as clear as its name
 * - When you have too many small functions that don't add value
 * - When the indirection is more confusing than helpful
 * - When preparing for a different refactoring (e.g., before re-extracting differently)
 * - When functions are only called from one place and don't provide reusability
 *
 * Warning: Don't inline when:
 * - The function is used in multiple places (would create duplication)
 * - The function provides meaningful abstraction
 * - The function is overridden in subclasses (polymorphism)
 *
 * @see https://refactoring.com/catalog/inlineFunction.html
 */
class InlineFunction
{
    /**
     * BEFORE: Over-abstracted code with unnecessary function layers
     * Problems:
     * - Too many trivial functions that don't add value
     * - Function names don't provide more clarity than the code itself
     * - Excessive indirection makes code harder to follow
     * - More code to maintain without clear benefits
     */
    public function getRatingBefore(Driver $driver): int
    {
        return $this->moreThanFiveLateDeliveries($driver) ? 2 : 1;
    }

    private function moreThanFiveLateDeliveries(Driver $driver): bool
    {
        return $driver->numberOfLateDeliveries > 5;
    }

    /**
     * AFTER: Refactored using Inline Function pattern
     * Benefits:
     * - More direct and easier to understand
     * - Less code to maintain
     * - No loss of clarity (the condition is self-explanatory)
     * - Better performance (one less function call)
     */
    public function getRatingAfter(Driver $driver): int
    {
        return ($driver->numberOfLateDeliveries > 5) ? 2 : 1;
    }

    /**
     * Another example: BEFORE - Unnecessary abstraction in a reporting context
     */
    public function reportLinesBefore(Customer $customer): array
    {
        $lines = [];
        $this->gatherCustomerData($lines, $customer);
        return $lines;
    }

    private function gatherCustomerData(array &$lines, Customer $customer): void
    {
        $lines[] = ['name', $customer->name];
        $lines[] = ['location', $customer->location];
    }

    /**
     * After: AFTER - Inlined for better clarity
     * The function body was simple enough that the extra abstraction didn't help
     */
    public function reportLinesAfter(Customer $customer): array
    {
        $lines = [];
        $lines[] = ['name', $customer->name];
        $lines[] = ['location', $customer->location];
        return $lines;
    }

    /**
     * Real-world example: BEFORE - Over-engineered validation
     */
    public function processOrderBefore(Order $order): bool
    {
        if ($this->isValidOrder($order)) {
            return $this->executeOrder($order);
        }
        return false;
    }

    private function isValidOrder(Order $order): bool
    {
        return $order->total > 0;
    }

    private function executeOrder(Order $order): bool
    {
        return $order->process();
    }

    /**
     * Real-world example: AFTER - Simplified by inlining trivial functions
     * Note: We keep meaningful abstractions but remove unnecessary ones
     */
    public function processOrderAfter(Order $order): bool
    {
        if ($order->total > 0) {
            return $order->process();
        }
        return false;
    }

    /**
     * Example showing WHEN NOT to inline:
     * This function SHOULD NOT be inlined because:
     * - It's used in multiple places
     * - It encapsulates business logic that might change
     * - The name adds semantic meaning to the context
     */
    private function calculateDiscountedPrice(float $basePrice, float $discount): float
    {
        $discountAmount = $basePrice * ($discount / 100);
        $taxRate = 0.08;
        return ($basePrice - $discountAmount) * (1 + $taxRate);
    }

    public function getProductPrice(Product $product): float
    {
        // Don't inline calculateDiscountedPrice - it's meaningful abstraction
        return $this->calculateDiscountedPrice(
            $product->basePrice,
            $product->discount
        );
    }

    /**
     * Example usage demonstrating the refactoring
     */
    public function demonstratePattern(): void
    {
        $driver = new Driver(numberOfLateDeliveries: 7);
        $customer = new Customer(name: 'Acme Corp', location: 'New York');
        $order = new Order(total: 150.00);
        $product = new Product(basePrice: 100.00, discount: 10);

        echo "=== Inline Function Refactoring Pattern ===\n\n";

        echo "Example 1: Driver Rating\n";
        echo "BEFORE (with unnecessary function): Rating = " . $this->getRatingBefore($driver) . "\n";
        echo "AFTER (inlined for clarity): Rating = " . $this->getRatingAfter($driver) . "\n";
        echo "Improvement: Direct comparison is just as clear as the function name\n\n";

        echo "Example 2: Customer Report\n";
        echo "BEFORE (over-abstracted):\n";
        print_r($this->reportLinesBefore($customer));
        echo "AFTER (simplified):\n";
        print_r($this->reportLinesAfter($customer));
        echo "Improvement: Removed unnecessary indirection\n\n";

        echo "Example 3: Order Processing\n";
        echo "BEFORE (trivial functions): " . ($this->processOrderBefore($order) ? "Success" : "Failed") . "\n";
        echo "AFTER (streamlined): " . ($this->processOrderAfter($order) ? "Success" : "Failed") . "\n";
        echo "Improvement: Logic is clearer without trivial wrapper functions\n\n";

        echo "Example 4: When NOT to inline\n";
        echo "Product price with discount: $" . $this->getProductPrice($product) . "\n";
        echo "Note: calculateDiscountedPrice should NOT be inlined because:\n";
        echo "  - It encapsulates complex business logic\n";
        echo "  - It's reusable across different contexts\n";
        echo "  - The name provides important semantic meaning\n\n";

        echo "=== Key Principles ===\n";
        echo "1. Inline when the function body is as clear as the name\n";
        echo "2. Remove indirection that doesn't add value\n";
        echo "3. Keep functions that provide meaningful abstraction\n";
        echo "4. Balance between clarity and conciseness\n";
    }
}

/**
 * Supporting classes for demonstration
 */
class Driver
{
    public function __construct(
        public readonly int $numberOfLateDeliveries
    ) {
    }
}

class Customer
{
    public function __construct(
        public readonly string $name,
        public readonly string $location
    ) {
    }
}

class Order
{
    public function __construct(
        public readonly float $total
    ) {
    }

    public function process(): bool
    {
        // Simulate order processing
        return true;
    }
}

class Product
{
    public function __construct(
        public readonly float $basePrice,
        public readonly float $discount
    ) {
    }
}

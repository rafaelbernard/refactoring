<?php

declare(strict_types=1);

namespace RefactoringPatterns;

/**
 * Extract Function Refactoring Pattern
 *
 * This refactoring pattern involves taking a fragment of code that can be grouped together
 * and turning it into a function whose name explains the purpose of the function.
 *
 * Benefits:
 * - Improves code readability by giving meaningful names to code fragments
 * - Reduces code duplication by extracting reusable logic
 * - Makes code easier to understand and maintain
 * - Facilitates testing by isolating specific behaviors
 *
 * When to use:
 * - When you have a code fragment that can be grouped together
 * - When you need to use the same code fragment in multiple places
 * - When a function is too long and does multiple things
 * - When you need to add comments to explain what code is doing
 *
 * @see https://refactoring.com/catalog/extractFunction.html
 */
class ExtractFunction
{
    /**
     * BEFORE: Long method with mixed responsibilities
     * Problems:
     * - Hard to understand what the method does at a glance
     * - Multiple levels of abstraction mixed together
     * - Difficult to test individual pieces of logic
     * - Code duplication potential
     */
    public function printOwingBefore(array $invoice): void
    {
        $outstanding = 0;

        echo "********************\n";
        echo "*** Customer Owes ***\n";
        echo "********************\n";

        // calculate outstanding
        foreach ($invoice['orders'] as $order) {
            $outstanding += $order['amount'];
        }

        // record due date
        $today = new \DateTimeImmutable();
        $invoice['dueDate'] = $today->modify('+30 days');

        // print details
        echo "name: {$invoice['customer']}\n";
        echo "amount: {$outstanding}\n";
        echo "due: {$invoice['dueDate']->format('Y-m-d')}\n";
    }

    /**
     * AFTER: Refactored method using Extract Function pattern
     * Benefits:
     * - Clear, self-documenting code with meaningful function names
     * - Each function has a single responsibility
     * - Easier to test each piece independently
     * - Better code organization and maintainability
     */
    public function printOwingAfter(array $invoice): void
    {
        $this->printBanner();
        $outstanding = $this->calculateOutstanding($invoice);
        $this->recordDueDate($invoice);
        $this->printDetails($invoice, $outstanding);
    }

    /**
     * Extracted function: Print banner
     * Single responsibility: Display the header banner
     */
    private function printBanner(): void
    {
        echo "********************\n";
        echo "*** Customer Owes ***\n";
        echo "********************\n";
    }

    /**
     * Extracted function: Calculate outstanding amount
     * Single responsibility: Sum up all order amounts
     *
     * @param array $invoice The invoice data
     * @return float The total outstanding amount
     */
    private function calculateOutstanding(array $invoice): float
    {
        $outstanding = 0;
        foreach ($invoice['orders'] as $order) {
            $outstanding += $order['amount'];
        }
        return $outstanding;
    }

    /**
     * Extracted function: Record due date
     * Single responsibility: Set the due date for the invoice
     *
     * @param array &$invoice The invoice data (passed by reference)
     */
    private function recordDueDate(array &$invoice): void
    {
        $today = new \DateTimeImmutable();
        $invoice['dueDate'] = $today->modify('+30 days');
    }

    /**
     * Extracted function: Print invoice details
     * Single responsibility: Display customer and payment information
     *
     * @param array $invoice The invoice data
     * @param float $outstanding The outstanding amount
     */
    private function printDetails(array $invoice, float $outstanding): void
    {
        echo "name: {$invoice['customer']}\n";
        echo "amount: {$outstanding}\n";
        echo "due: {$invoice['dueDate']->format('Y-m-d')}\n";
    }

    /**
     * Example usage demonstrating the refactoring
     */
    public function demonstratePattern(): void
    {
        $invoice = [
            'customer' => 'John Doe',
            'orders' => [
                ['amount' => 100.00],
                ['amount' => 250.50],
                ['amount' => 75.25],
            ],
        ];

        echo "=== BEFORE Refactoring ===\n";
        $this->printOwingBefore($invoice);

        echo "\n=== AFTER Refactoring ===\n";
        // Reset invoice for clean comparison
        $invoice = [
            'customer' => 'John Doe',
            'orders' => [
                ['amount' => 100.00],
                ['amount' => 250.50],
                ['amount' => 75.25],
            ],
        ];
        $this->printOwingAfter($invoice);

        echo "\n=== Key Benefits ===\n";
        echo "1. Each function has a clear, single purpose\n";
        echo "2. Code is self-documenting through function names\n";
        echo "3. Individual functions can be reused and tested independently\n";
        echo "4. Easier to modify specific behaviors without affecting others\n";
    }
}

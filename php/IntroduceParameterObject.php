<?php

declare(strict_types=1);

namespace RefactoringPatterns;

/**
 * Introduce Parameter Object Refactoring Pattern
 *
 * This refactoring pattern involves grouping parameters that naturally go together
 * into a single object. When you see a group of data items that regularly travel
 * together, appearing in function after function, it's a sign they should be
 * combined into a single object.
 *
 * Benefits:
 * - Reduces the number of parameters (improved readability)
 * - Groups related data together (better organization)
 * - Makes relationships between data explicit
 * - Easier to add new related data without changing function signatures
 * - Enables moving behavior related to the data into the new class
 * - Reduces errors from parameter ordering mistakes
 *
 * When to use:
 * - When you have a group of parameters that often appear together
 * - When you see the same parameters in multiple function signatures
 * - When parameters represent a cohesive concept
 * - When you need to add more related parameters
 * - When parameter ordering becomes confusing
 *
 * @see https://refactoring.com/catalog/introduceParameterObject.html
 */
class IntroduceParameterObject
{
    /**
     * BEFORE: Multiple primitive parameters passed around
     * Problems:
     * - Too many parameters (hard to remember and maintain)
     * - Parameters are related but relationship is not explicit
     * - Easy to mix up parameter order
     * - Adding new related data requires changing all function signatures
     * - Difficult to add validation or behavior for the group
     */
    public function amountInvoicedBefore(
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $endDate,
        string $customerName,
        string $customerId,
        string $customerEmail
    ): float {
        $amount = 0;
        foreach ($this->getInvoices() as $invoice) {
            if ($this->isInDateRangeBefore($invoice, $startDate, $endDate) &&
                $this->isForCustomerBefore($invoice, $customerName, $customerId, $customerEmail)) {
                $amount += $invoice['amount'];
            }
        }
        return $amount;
    }

    private function isInDateRangeBefore(
        array $invoice,
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $endDate
    ): bool {
        return $invoice['date'] >= $startDate && $invoice['date'] <= $endDate;
    }

    private function isForCustomerBefore(
        array $invoice,
        string $customerName,
        string $customerId,
        string $customerEmail
    ): bool {
        return $invoice['customerId'] === $customerId;
    }

    /**
     * AFTER: Using parameter objects for related data
     * Benefits:
     * - Clear, self-documenting function signatures
     * - Related data is explicitly grouped
     * - Easy to add new related fields without changing signatures
     * - Can add behavior and validation to the parameter objects
     * - Reduced chance of parameter ordering errors
     */
    public function amountInvoicedAfter(DateRange $dateRange, Customer $customer): float
    {
        $amount = 0;
        foreach ($this->getInvoices() as $invoice) {
            if ($this->isInDateRangeAfter($invoice, $dateRange) &&
                $this->isForCustomerAfter($invoice, $customer)) {
                $amount += $invoice['amount'];
            }
        }
        return $amount;
    }

    private function isInDateRangeAfter(array $invoice, DateRange $dateRange): bool
    {
        return $dateRange->contains($invoice['date']);
    }

    private function isForCustomerAfter(array $invoice, Customer $customer): bool
    {
        return $invoice['customerId'] === $customer->id;
    }

    /**
     * Another example: BEFORE - Coordinates passed as primitives
     */
    public function calculateDistanceBefore(
        float $x1,
        float $y1,
        float $x2,
        float $y2
    ): float {
        $dx = $x2 - $x1;
        $dy = $y2 - $y1;
        return sqrt($dx * $dx + $dy * $dy);
    }

    public function findNearbyLocationsBefore(
        float $centerX,
        float $centerY,
        float $radius,
        array $locations
    ): array {
        $nearby = [];
        foreach ($locations as $location) {
            $distance = $this->calculateDistanceBefore(
                $centerX,
                $centerY,
                $location['x'],
                $location['y']
            );
            if ($distance <= $radius) {
                $nearby[] = $location;
            }
        }
        return $nearby;
    }

    /**
     * Another example: AFTER - Using Point parameter object
     */
    public function calculateDistanceAfter(Point $point1, Point $point2): float
    {
        return $point1->distanceTo($point2);
    }

    public function findNearbyLocationsAfter(
        Point $center,
        float $radius,
        array $locations
    ): array {
        $nearby = [];
        foreach ($locations as $locationData) {
            $location = new Point($locationData['x'], $locationData['y']);
            if ($center->distanceTo($location) <= $radius) {
                $nearby[] = $locationData;
            }
        }
        return $nearby;
    }

    /**
     * Helper method to get sample invoices
     */
    private function getInvoices(): array
    {
        return [
            [
                'date' => new \DateTimeImmutable('2024-01-15'),
                'customerId' => 'C001',
                'amount' => 150.00,
            ],
            [
                'date' => new \DateTimeImmutable('2024-02-20'),
                'customerId' => 'C001',
                'amount' => 275.50,
            ],
            [
                'date' => new \DateTimeImmutable('2024-03-10'),
                'customerId' => 'C002',
                'amount' => 425.75,
            ],
        ];
    }

    /**
     * Example usage demonstrating the refactoring
     */
    public function demonstratePattern(): void
    {
        echo "=== Introduce Parameter Object Refactoring Pattern ===\n\n";

        // Example 1: Invoice calculation
        echo "Example 1: Invoice Calculation\n";
        echo "-------------------------------\n";

        $startDate = new \DateTimeImmutable('2024-01-01');
        $endDate = new \DateTimeImmutable('2024-03-31');
        $customerName = 'John Doe';
        $customerId = 'C001';
        $customerEmail = 'john@example.com';

        echo "BEFORE (5 separate parameters):\n";
        $amountBefore = $this->amountInvoicedBefore(
            $startDate,
            $endDate,
            $customerName,
            $customerId,
            $customerEmail
        );
        echo "Amount invoiced: $" . number_format($amountBefore, 2) . "\n";
        echo "Issues: Too many parameters, unclear relationships\n\n";

        echo "AFTER (2 parameter objects):\n";
        $dateRange = new DateRange($startDate, $endDate);
        $customer = new Customer($customerId, $customerName, $customerEmail);
        $amountAfter = $this->amountInvoicedAfter($dateRange, $customer);
        echo "Amount invoiced: $" . number_format($amountAfter, 2) . "\n";
        echo "Benefits: Clear grouping, can add validation and behavior\n\n";

        // Example 2: Coordinate calculations
        echo "Example 2: Location Distance Calculation\n";
        echo "-----------------------------------------\n";

        $locations = [
            ['name' => 'Store A', 'x' => 10.0, 'y' => 20.0],
            ['name' => 'Store B', 'x' => 15.0, 'y' => 25.0],
            ['name' => 'Store C', 'x' => 50.0, 'y' => 50.0],
        ];

        echo "BEFORE (4 coordinate parameters):\n";
        $nearbyBefore = $this->findNearbyLocationsBefore(10.0, 20.0, 10.0, $locations);
        echo "Found " . count($nearbyBefore) . " nearby locations\n";
        echo "Issues: Easy to mix up x1, y1, x2, y2 parameters\n\n";

        echo "AFTER (Point parameter object):\n";
        $center = new Point(10.0, 20.0);
        $nearbyAfter = $this->findNearbyLocationsAfter($center, 10.0, $locations);
        echo "Found " . count($nearbyAfter) . " nearby locations\n";
        echo "Benefits: Point object can now have behavior (distanceTo method)\n\n";

        echo "=== Key Benefits ===\n";
        echo "1. Function signatures are clearer and more maintainable\n";
        echo "2. Related data is explicitly grouped together\n";
        echo "3. Behavior can be added to parameter objects\n";
        echo "4. Easier to extend without breaking existing code\n";
        echo "5. Reduced chance of parameter ordering mistakes\n";
        echo "6. Parameter objects can enforce validation rules\n";
    }
}

/**
 * Parameter Object: DateRange
 * Encapsulates a range of dates with validation and behavior
 */
class DateRange
{
    public function __construct(
        private readonly \DateTimeImmutable $startDate,
        private readonly \DateTimeImmutable $endDate
    ) {
        if ($endDate < $startDate) {
            throw new \InvalidArgumentException('End date must be after start date');
        }
    }

    public function getStartDate(): \DateTimeImmutable
    {
        return $this->startDate;
    }

    public function getEndDate(): \DateTimeImmutable
    {
        return $this->endDate;
    }

    /**
     * Behavior: Check if a date is within the range
     */
    public function contains(\DateTimeImmutable $date): bool
    {
        return $date >= $this->startDate && $date <= $this->endDate;
    }

    /**
     * Behavior: Get the duration of the range in days
     */
    public function getDurationInDays(): int
    {
        return $this->startDate->diff($this->endDate)->days;
    }
}

/**
 * Parameter Object: Customer
 * Encapsulates customer-related data with validation
 */
class Customer
{
    public readonly string $id;
    public readonly string $name;
    public readonly string $email;

    public function __construct(string $id, string $name, string $email)
    {
        if (empty($id)) {
            throw new \InvalidArgumentException('Customer ID cannot be empty');
        }
        if (empty($name)) {
            throw new \InvalidArgumentException('Customer name cannot be empty');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email address');
        }

        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * Behavior: Get customer display name
     */
    public function getDisplayName(): string
    {
        return "{$this->name} ({$this->email})";
    }
}

/**
 * Parameter Object: Point
 * Encapsulates 2D coordinates with geometric operations
 */
class Point
{
    public function __construct(
        private readonly float $x,
        private readonly float $y
    ) {
    }

    public function getX(): float
    {
        return $this->x;
    }

    public function getY(): float
    {
        return $this->y;
    }

    /**
     * Behavior: Calculate distance to another point
     */
    public function distanceTo(Point $other): float
    {
        $dx = $other->x - $this->x;
        $dy = $other->y - $this->y;
        return sqrt($dx * $dx + $dy * $dy);
    }

    /**
     * Behavior: Create a new point offset from this one
     */
    public function offset(float $dx, float $dy): Point
    {
        return new Point($this->x + $dx, $this->y + $dy);
    }
}

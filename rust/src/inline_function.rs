/// # Inline Function Refactoring Pattern
///
/// Replace a function call with the function's body when the body is as
/// clear as the name.
///
/// ## When to use
/// - When a function body is as clear as its name
/// - When the indirection is more confusing than helpful
/// - When preparing for a different refactoring
///
/// ## When NOT to inline
/// - The function is used in multiple places
/// - The function provides meaningful abstraction
///
/// Reference: <https://refactoring.com/catalog/inlineFunction.html>

struct Driver {
    number_of_late_deliveries: u32,
}

struct Customer {
    name: String,
    location: String,
}

struct Order {
    total: f64,
}

impl Order {
    fn process(&self) -> bool {
        true // simulate processing
    }
}

// === Example 1: Driver Rating ===

// BEFORE: unnecessary wrapper function
fn more_than_five_late_deliveries(driver: &Driver) -> bool {
    driver.number_of_late_deliveries > 5
}

fn get_rating_before(driver: &Driver) -> u32 {
    if more_than_five_late_deliveries(driver) { 2 } else { 1 }
}

// AFTER: inlined — the condition is self-explanatory
fn get_rating_after(driver: &Driver) -> u32 {
    if driver.number_of_late_deliveries > 5 { 2 } else { 1 }
}

// === Example 2: Customer Report ===

// BEFORE: over-abstracted
fn gather_customer_data(customer: &Customer) -> Vec<(&str, &str)> {
    vec![("name", &customer.name), ("location", &customer.location)]
}

fn report_lines_before(customer: &Customer) -> Vec<(&str, &str)> {
    gather_customer_data(customer)
}

// AFTER: inlined for clarity
fn report_lines_after(customer: &Customer) -> Vec<(&str, &str)> {
    vec![("name", &customer.name), ("location", &customer.location)]
}

// === Example 3: Order Processing ===

// BEFORE: trivial wrappers
fn is_valid_order(order: &Order) -> bool {
    order.total > 0.0
}

fn process_order_before(order: &Order) -> bool {
    if is_valid_order(order) { order.process() } else { false }
}

// AFTER: inlined
fn process_order_after(order: &Order) -> bool {
    if order.total > 0.0 { order.process() } else { false }
}

fn main() {
    let driver = Driver { number_of_late_deliveries: 7 };
    let customer = Customer {
        name: "Acme Corp".to_string(),
        location: "New York".to_string(),
    };
    let order = Order { total: 150.00 };

    println!("=== Inline Function Refactoring Pattern ===\n");

    println!("Example 1: Driver Rating");
    println!("BEFORE: Rating = {}", get_rating_before(&driver));
    println!("AFTER:  Rating = {}", get_rating_after(&driver));
    println!("Improvement: Direct comparison is just as clear as the function name\n");

    println!("Example 2: Customer Report");
    println!("BEFORE: {:?}", report_lines_before(&customer));
    println!("AFTER:  {:?}", report_lines_after(&customer));
    println!("Improvement: Removed unnecessary indirection\n");

    println!("Example 3: Order Processing");
    println!("BEFORE: {}", if process_order_before(&order) { "Success" } else { "Failed" });
    println!("AFTER:  {}", if process_order_after(&order) { "Success" } else { "Failed" });
    println!("Improvement: Logic is clearer without trivial wrapper functions\n");

    println!("=== Key Principles ===");
    println!("1. Inline when the function body is as clear as the name");
    println!("2. Remove indirection that doesn't add value");
    println!("3. Keep functions that provide meaningful abstraction");
    println!("4. Balance between clarity and conciseness");
}

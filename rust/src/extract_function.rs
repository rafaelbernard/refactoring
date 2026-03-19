/// # Extract Function Refactoring Pattern
///
/// Take a fragment of code that can be grouped together and turn it into
/// a function whose name explains the purpose.
///
/// ## When to use
/// - When you have a code fragment that can be grouped together
/// - When a function is too long and does multiple things
/// - When you need to add comments to explain what code is doing
///
/// Reference: <https://refactoring.com/catalog/extractFunction.html>

use std::fmt;

struct Order {
    amount: f64,
}

struct Invoice {
    customer: String,
    orders: Vec<Order>,
    due_date: Option<String>,
}

impl fmt::Display for Invoice {
    fn fmt(&self, f: &mut fmt::Formatter<'_>) -> fmt::Result {
        write!(
            f,
            "name: {}\namount: {:.2}\ndue: {}",
            self.customer,
            self.orders.iter().map(|o| o.amount).sum::<f64>(),
            self.due_date.as_deref().unwrap_or("N/A")
        )
    }
}

// === BEFORE: Mixed responsibilities in one function ===

fn print_owing_before(invoice: &mut Invoice) {
    let mut outstanding = 0.0;

    println!("********************");
    println!("*** Customer Owes ***");
    println!("********************");

    // calculate outstanding
    for order in &invoice.orders {
        outstanding += order.amount;
    }

    // record due date
    invoice.due_date = Some("2024-02-14".to_string()); // simplified for demo

    // print details
    println!("name: {}", invoice.customer);
    println!("amount: {outstanding:.2}");
    println!("due: {}", invoice.due_date.as_deref().unwrap());
}

// === AFTER: Each piece extracted into its own function ===

fn print_banner() {
    println!("********************");
    println!("*** Customer Owes ***");
    println!("********************");
}

fn calculate_outstanding(invoice: &Invoice) -> f64 {
    invoice.orders.iter().map(|o| o.amount).sum()
}

fn record_due_date(invoice: &mut Invoice) {
    invoice.due_date = Some("2024-02-14".to_string());
}

fn print_details(invoice: &Invoice, outstanding: f64) {
    println!("name: {}", invoice.customer);
    println!("amount: {outstanding:.2}");
    println!("due: {}", invoice.due_date.as_deref().unwrap());
}

fn print_owing_after(invoice: &mut Invoice) {
    print_banner();
    let outstanding = calculate_outstanding(invoice);
    record_due_date(invoice);
    print_details(invoice, outstanding);
}

fn make_invoice() -> Invoice {
    Invoice {
        customer: "John Doe".to_string(),
        orders: vec![
            Order { amount: 100.00 },
            Order { amount: 250.50 },
            Order { amount: 75.25 },
        ],
        due_date: None,
    }
}

fn main() {
    println!("=== BEFORE Refactoring ===");
    print_owing_before(&mut make_invoice());

    println!("\n=== AFTER Refactoring ===");
    print_owing_after(&mut make_invoice());

    println!("\n=== Key Benefits ===");
    println!("1. Each function has a clear, single purpose");
    println!("2. Code is self-documenting through function names");
    println!("3. Individual functions can be reused and tested independently");
    println!("4. Easier to modify specific behaviors without affecting others");
}

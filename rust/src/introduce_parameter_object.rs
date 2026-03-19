/// # Introduce Parameter Object Refactoring Pattern
///
/// Group parameters that naturally go together into a single object (struct).
///
/// ## When to use
/// - When you have a group of parameters that often appear together
/// - When parameters represent a cohesive concept
/// - When parameter ordering becomes confusing
///
/// Reference: <https://refactoring.com/catalog/introduceParameterObject.html>

// === Parameter Objects ===

struct DateRange {
    start: String,
    end: String,
}

impl DateRange {
    fn contains(&self, date: &str) -> bool {
        date >= self.start.as_str() && date <= self.end.as_str()
    }
}

struct Customer {
    id: String,
    name: String,
    email: String,
}

impl Customer {
    fn display_name(&self) -> String {
        format!("{} ({})", self.name, self.email)
    }
}

#[derive(Clone, Copy)]
struct Point {
    x: f64,
    y: f64,
}

impl Point {
    fn distance_to(&self, other: &Point) -> f64 {
        let dx = other.x - self.x;
        let dy = other.y - self.y;
        (dx * dx + dy * dy).sqrt()
    }
}

struct Invoice {
    date: String,
    customer_id: String,
    amount: f64,
}

fn get_invoices() -> Vec<Invoice> {
    vec![
        Invoice { date: "2024-01-15".into(), customer_id: "C001".into(), amount: 150.00 },
        Invoice { date: "2024-02-20".into(), customer_id: "C001".into(), amount: 275.50 },
        Invoice { date: "2024-03-10".into(), customer_id: "C002".into(), amount: 425.75 },
    ]
}

// === Example 1: Invoice calculation ===

// BEFORE: too many loose parameters
fn amount_invoiced_before(
    start_date: &str,
    end_date: &str,
    _customer_name: &str,
    customer_id: &str,
    _customer_email: &str,
) -> f64 {
    get_invoices()
        .iter()
        .filter(|inv| inv.date.as_str() >= start_date && inv.date.as_str() <= end_date)
        .filter(|inv| inv.customer_id == customer_id)
        .map(|inv| inv.amount)
        .sum()
}

// AFTER: grouped into parameter objects
fn amount_invoiced_after(date_range: &DateRange, customer: &Customer) -> f64 {
    get_invoices()
        .iter()
        .filter(|inv| date_range.contains(&inv.date))
        .filter(|inv| inv.customer_id == customer.id)
        .map(|inv| inv.amount)
        .sum()
}

// === Example 2: Distance / location ===

// BEFORE: four coordinate parameters
fn find_nearby_before<'a>(cx: f64, cy: f64, radius: f64, locations: &'a [(f64, f64, &'a str)]) -> Vec<&'a str> {
    locations
        .iter()
        .filter(|(x, y, _)| {
            let dx = x - cx;
            let dy = y - cy;
            (dx * dx + dy * dy).sqrt() <= radius
        })
        .map(|(_, _, name)| *name)
        .collect()
}

// AFTER: Point parameter object
fn find_nearby_after<'a>(center: &Point, radius: f64, locations: &'a [(Point, &'a str)]) -> Vec<&'a str> {
    locations
        .iter()
        .filter(|(pt, _)| center.distance_to(pt) <= radius)
        .map(|(_, name)| *name)
        .collect()
}

fn main() {
    println!("=== Introduce Parameter Object Refactoring Pattern ===\n");

    // Example 1
    println!("Example 1: Invoice Calculation");
    println!("-------------------------------");

    let before = amount_invoiced_before("2024-01-01", "2024-03-31", "John Doe", "C001", "john@example.com");
    println!("BEFORE (5 separate parameters): ${before:.2}");

    let range = DateRange { start: "2024-01-01".into(), end: "2024-03-31".into() };
    let customer = Customer {
        id: "C001".into(),
        name: "John Doe".into(),
        email: "john@example.com".into(),
    };
    let after = amount_invoiced_after(&range, &customer);
    println!("AFTER  (2 parameter objects):   ${after:.2}");
    println!("Customer: {}\n", customer.display_name());

    // Example 2
    println!("Example 2: Location Distance");
    println!("----------------------------");

    let locations_raw = [(10.0, 20.0, "Store A"), (15.0, 25.0, "Store B"), (50.0, 50.0, "Store C")];
    let nearby_before = find_nearby_before(10.0, 20.0, 10.0, &locations_raw);
    println!("BEFORE (4 coordinate params): {nearby_before:?}");

    let center = Point { x: 10.0, y: 20.0 };
    let locations_pt: Vec<(Point, &str)> = locations_raw
        .iter()
        .map(|(x, y, n)| (Point { x: *x, y: *y }, *n))
        .collect();
    let nearby_after = find_nearby_after(&center, 10.0, &locations_pt);
    println!("AFTER  (Point object):        {nearby_after:?}\n");

    println!("=== Key Benefits ===");
    println!("1. Function signatures are clearer and more maintainable");
    println!("2. Related data is explicitly grouped together");
    println!("3. Behavior can be added to parameter objects (e.g. distance_to, contains)");
    println!("4. Easier to extend without breaking existing code");
    println!("5. Reduced chance of parameter ordering mistakes");
}

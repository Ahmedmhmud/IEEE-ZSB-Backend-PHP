# PHP Backend — Project Documentation

---

## Phase 0: Language Fundamentals

### 1. Printing Variables

Variables start with `$`. Two ways to print them:

```php
$city = "Cairo";

echo $city;        // preferred, no return value
print $city;       // returns 1, can be used inside expressions

// Inside HTML
<p><?= $city ?></p>
```

Joining strings uses a dot:

```php
$welcome = "Hello from " . $city . "!";
```

---

### 2. Conditions

```php
$active = true;

// Standard
if ($active) {
    echo "Account is active.";
} else {
    echo "Account is disabled.";
}

// One-liner
$status = $active ? "active" : "disabled";
```

---

### 3. Arrays

Two types — ordered lists and key-value maps:

```php
// List (indexed)
$scores = [95, 80, 73];
echo $scores[0]; // 95

// Map (associative)
$student = [
    "name"  => "Omar",
    "grade" => "A",
    "year"  => 2,
];
echo $student["name"]; // Omar

// List of maps
$students = [
    ["name" => "Omar",  "grade" => "A", "year" => 2],
    ["name" => "Layla", "grade" => "B", "year" => 3],
    ["name" => "Karim", "grade" => "A", "year" => 1],
];
```

---

### 4. Loops

```php
foreach ($students as $s) {
    echo $s["name"] . " — " . $s["grade"];
}

// Clean HTML version
<?php foreach ($students as $s) : ?>
    <li><?= $s["name"] ?> (Year <?= $s["year"] ?>)</li>
<?php endforeach; ?>
```

---

### 5. Functions

```php
// Defined by name — reusable anywhere
function getTopStudents(array $list, string $grade): array {
    $top = [];
    foreach ($list as $s) {
        if ($s["grade"] === $grade) $top[] = $s;
    }
    return $top;
}

// Assigned to a variable — useful for inline logic
$getByYear = function(array $list, int $year): array {
    $result = [];
    foreach ($list as $s) {
        if ($s["year"] === $year) $result[] = $s;
    }
    return $result;
};

// array_filter — pass a condition as a callback
$seniors = array_filter($students, fn($s) => $s["year"] >= 3);
```

---

## Phase 1: Separating Logic from Templates

### File Structure

```
project/
├── home.php              ← prepares data, loads view
├── about.php
├── contact.php
└── views/
    ├── home.view.php     ← HTML template
    ├── about.view.php
    ├── contact.view.php
    └── partials/
        ├── head.php       ← <head> tag, CSS
        ├── nav.php        ← navigation
        ├── banner.php     ← page heading banner
        └── footer.php
```

Each page has two files: one that sets up data, one that renders HTML.

---

### Page File (data side)

```php
// home.php
$pageTitle = "Home";
require 'views/home.view.php';
```

`require` runs the file in the same scope, so `$pageTitle` is available inside the view and all its partials automatically.

---

### View File (HTML side)

```php
// views/home.view.php
<?php require('partials/head.php'); ?>
<?php require('partials/navbar.php'); ?>
<?php require('partials/title.php'); ?>

<main>
    <p>Welcome to the homepage.</p>
</main>

<?php require('partials/footer.php'); ?>
```

---

### Active Nav Link

`$_SERVER['REQUEST_URI']` holds the current URL path. A small helper compares it to each link:

```php
// helpers.php
function currentPage(string $path): bool {
    return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) === $path;
}
```

```php
// navbar.php
<a href="/about"
   class="<?= currentPage('/about') ? 'nav-active' : 'nav-default' ?>">
    About
</a>
```

`parse_url()` strips any query string (e.g. `?ref=email`) so the comparison always works cleanly.

---

## Phase 2: Routing

Instead of accessing PHP files directly, every request goes through one entry point.

### Updated Structure

```
project/
├── index.php         ← single entry point
├── routes.php        ← URL → controller map
├── helpers.php
├── controllers/
│   ├── home.php
│   ├── about.php
│   └── contact.php
└── views/
    ├── 404.php
    └── partials/
```

---

### Entry Point

```php
// index.php
require 'helpers.php';
require 'routes.php';
```

---

### Router

```php
// routes.php
$path = parse_url($_SERVER['REQUEST_URI'])['path'];

$map = [
    '/'        => 'controllers/home.php',
    '/about'   => 'controllers/about.php',
    '/contact' => 'controllers/contact.php',
];

function dispatch($path, $map) {
    if (array_key_exists($path, $map))
        require $map[$path];
    else
        stop(404);
}

function stop($code = 404) {
    http_response_code($code);
    require "views/{$code}.php";
    die();
}

dispatch($path, $map);
```

Key points:
- `array_key_exists` checks if the URL has a matching route
- `http_response_code` sets the correct HTTP status — without it, error pages return 200
- Double quotes in `"views/{$code}.php"` allow the variable to expand; single quotes won't work here
- `die()` stops execution after the error page

---

## Phase 3: Database

### Structure

```
project/
├── index.php
├── DB.php          ← database class
├── config.php      ← connection settings
├── routes.php
└── ...
```

---

### config.php

```php
return [
    'database' => [
        'host'    => 'localhost',
        'port'    => 3306,
        'dbname'  => 'college',
        'charset' => 'utf8mb4',
    ]
];
```

Loaded with `require`, which returns the array directly:

```php
$cfg = require 'config.php';
// $cfg['database'] has the connection details
```

---

### DB.php

```php
class DB {
    public $conn;

    public function __construct($cfg, $user = 'root', $pass = '') {
        // builds: mysql:host=localhost;port=3306;dbname=college;charset=utf8mb4
        $dsn = 'mysql:' . http_build_query($cfg, '', ';');

        $this->conn = new PDO($dsn, $user, $pass, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    public function run($sql, $params = []) {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
```

`FETCH_ASSOC` returns rows as named key arrays only — no duplicate numeric indexes wasting memory.

The `run()` method uses a prepare → execute pattern. The SQL template is sent first, then the values separately. This prevents SQL injection.

---

### SQL Injection

```php
// Vulnerable — user input goes straight into the query string
$id = $_GET['id'];
$db->conn->query("SELECT * FROM students WHERE id = $id");
// attacker sends: 1 OR 1=1  →  dumps the whole table

// Safe — ? is a placeholder, value is sent separately
$student = $db->run('SELECT * FROM students WHERE id = ?', [$id])->fetch();
```

The database receives the query structure and the value as two separate things — it can never confuse one for the other.

---

### Wiring Everything Together

```php
// index.php
require 'helpers.php';
require 'DB.php';
require 'routes.php';

$cfg = require 'config.php';
$db  = new DB($cfg['database']);
```

`$db` is now available inside every controller loaded by the router, since `require` shares scope.
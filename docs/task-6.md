# PHP Website Security

Main rule: never trust input from users.

## 1. OOP Refactoring

Keep SQL inside model/database classes, not page files.
Use `$this->method()` inside classes.

```php
class Database {
    public function db_read(string $query, array $data = []): array|false {}
}
```

## 2. Login Errors

Use one generic error for all login failures.

```php
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return 'Wrong email or password.';
}
```

## 3. Least Privilege

Give users only the access they need.
Store rank in session and check it with one helper.

```php
function access(string $needed): bool {
    $rank = $_SESSION['user_rank'] ?? '';
    $map = [
        'admin' => ['admin'],
        'editor' => ['admin', 'editor'],
        'user' => ['admin', 'editor', 'user'],
    ];

    return in_array($rank, $map[$needed] ?? [], true);
}
```

## 4. SQL Injection (POST)

Never concatenate login input into SQL.
Validate first, then use prepared statements.

## 5. SQL Injection (GET)

URL params are unsafe too.
Cast numeric IDs.

```php
$id = (int) ($_GET['id'] ?? 0);
```

## 6. Prepared Statements

Prepared statements separate query structure from data.

```php
$st = $pdo->prepare('SELECT * FROM posts WHERE id = :id');
$st->execute([':id' => $id]);
```

## 7. XSS

Escape all user data before printing.

```php
function clean(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
```

## 8. Sessions

Regenerate ID after login to reduce fixation risk.
Use HTTPS and secure cookie flags.

```php
session_regenerate_id(true);
```

## 9. CSRF

Add token to each POST form and verify it on submit.

```php
if (!hash_equals($_SESSION['_token'] ?? '', $_POST['_token'] ?? '')) {
    exit('Invalid request');
}
```

## 10. File Uploads

Check MIME type, rename file, and store outside public web root.

## 11. Passwords

Use password hashing and verify with PHP helpers.
Add rate limiting for failed logins.

```php
$hash = password_hash($password, PASSWORD_BCRYPT);
password_verify($inputPassword, $hash);
```

## 12. Validation vs Sanitization

Validation asks: is input acceptable?
Sanitization asks: how to make output safe?
Use both.

## Quick Reference

| Attack | Basic fix |
|---|---|
| SQL injection | Prepared statements |
| XSS | `htmlspecialchars()` on output |
| CSRF | Form token + check |
| Session hijacking/fixation | HTTPS + `session_regenerate_id(true)` |
| Brute force | Rate limiting + lockout |
| Unsafe uploads | MIME check + rename + private storage |
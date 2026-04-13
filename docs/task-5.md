# PHP Website Security

## Overview
- Principle of least privilege
- Passwords and encryption
- Data hiding
- Security through obscurity
- Account lock attacks
- Brute force attacks
- Modified requests
- Error reporting
- Forced browsing
- Path traversal
- Data parameter delimiters
- SQL/PHP injection attacks
- XSS attacks
- Session hijack/fixation
- Phishing
- General security principles

## Why Web Security?
- Websites are public.
- Users add personal information.
- Knowledge and action: know what is threatening and act on it.
- Becoming an expert takes years.
- Security is an ongoing process.
- There is no absolute security. You can add many security features and still not be fully secure.
- Do not make security too hard for real users.
- "The only truly secure system is the one that is powered off, cast on a block of concrete and put in a sealed room with armed guards, and even that may not be enough."

## Hacker Categories

### White Hat Hacker
- The good side. They find security problems and report them to the owners. They are not a real threat and can be useful.

### Black Hat Hacker
- The type we must prevent. They exploit security problems for themselves, no matter what damage happens to data or the website.

#### Types of Black Hat Hackers
- Curious users: for example, fetching data from the URL.
- Script kiddies: use scripts made by others to get benefit.
- Thrill seekers: hack for fun to test if they can break the website.
- Hacktivists: motivated attackers against large organizations (for example, the FBI).
- Trophy hunters: target big organizations.
- Professionals: hack for money.

## Social Engineering
- Hiding key confidential information.
- Trash.
- Key logger.
- Public information (for example, social media) can be abused -> use 2-step verification.
- Password reset security questions.
- Phishing.

### Example
- Do not share internal details publicly.
- Use 2-step verification for sensitive accounts.

## Keeping Functional Code Private
- Avoid directory listing: -index -indexes.
- Separate private and public folders.
- Always use PHP extensions (JSON vs PHP extension examples).

### Simple Structure Example

```text
project/
    app/        # private code
    views/      # private templates
    public/     # only web root
        index.php
```

## Secure File Includes
- Include and require can execute hidden code (for example, inside an image), so validate files and split extension checks from the requested name.
- Example:

```php
$page = isset($_GET['page']) ? $_GET['page'] : "home";
$folder = "";
$files = glob($folder . "*.php");

if (in_array($page . ".php", $files)) {
    require($page . '.php');
}
```

### Safer Include Example (Whitelist)

```php
$allowed = ['home', 'about', 'contact'];
$page = $_GET['page'] ?? 'home';

if (!in_array($page, $allowed, true)) {
    http_response_code(404);
    exit('Page not found');
}

require __DIR__ . '/pages/' . $page . '.php';
```

## Single Page Loading
- Fewer entry points means better security: one entry file includes the others.

## Using Clean URLs
- By .htaccess.

### .htaccess Example

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]
```
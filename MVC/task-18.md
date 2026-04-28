# MVC Fundamentals (Part 2)

## Controller's job when user clicks "View Profile"
- Controller receives the request from router
- It checks request data (like user id)
- It calls Model to get profile data from database
- It can check authorization (is this user allowed?)
- Then it sends data to View and returns final HTML response

```php
<?php
class UserController extends Controller
{
	public function profile(Request $request): string
	{
		$userId = $request->getBody()['id'] ?? null;
		$user = User::findOne(['id' => $userId]);

		if (!$user) {
			http_response_code(404);
			return $this->render('404');
		}

		return $this->render('profile', ['user' => $user]);
	}
}
?>
```

## Static HTML vs Dynamic PHP View
- Static HTML file: fixed content, same output for all users
- Dynamic PHP View: output changes based on passed data

**Example:**
- Static: always shows "Welcome user"
- Dynamic: shows "Welcome Ahmed" or "Welcome Fouda" based on data

```php
<h1>Welcome, <?= htmlspecialchars($name) ?></h1>
```

## How controller passes data to view
- Controller gets data from Model first
- Then sends it as array in render function
- Keys in array become variables used inside view

```php
<?php
class SiteController extends Controller
{
	public function dashboard(): string
	{
		$user = User::findOne(['id' => 5]);
		return $this->render('dashboard', ['user' => $user]);
	}
}
?>
```

```php
<!-- views/dashboard.php -->
<h2>Hello, <?= htmlspecialchars($user->firstName) ?></h2>
```

## Templating (header and footer)
- MVC uses layout system and partials
- You write navbar/footer once in shared files
- Every page view is injected inside one main layout
- So no copy/paste for every page

```php
<!-- layouts/main.php -->
<?php include_once 'partials/header.php'; ?>
<?php echo $content; ?>
<?php include_once 'partials/footer.php'; ?>
```

- This saves time and keeps all pages consistent

## Why heavy logic inside view is bad
- View should focus on display only
- Complex conditions and loops in view make it hard to read
- It mixes business logic with presentation
- Harder to test and debug
- Better approach: process data in Model/Controller then send clean data to view

**Rule:**
- Model = data logic
- Controller = flow logic
- View = display only

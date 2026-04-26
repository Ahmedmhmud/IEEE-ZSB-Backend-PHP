# PHP MVC Framework Fundamentals

## MVC pattern
- MVC = Model, View, Controller
- It is used to separate responsibilities in the app

**Simple analogy (restaurant):**
- Model = kitchen + pantry (data and rules)
- View = dining room (what user sees)
- Controller = waiter (takes request, asks kitchen, returns result)

### Model
- Responsible for data only
- Talks to database
- Holds validation rules
- Doesn't know anything about HTML or page design

### View
- Responsible for display only
- Receives data from controller and renders HTML
- Should not query database directly
- Can use layouts and reusable partials

### Controller
- Responsible for coordination between Model and View
- Receives HTTP request
- Calls model methods (load/validate/save)
- Returns a view or redirects

```php
<?php
class AuthController extends Controller
{
	public function register(Request $request, Response $response): string
	{
		$model = new RegisterModel();

		if ($request->isPost()) {
			$model->loadData($request->getBody());

			if ($model->validate() && $model->register()) {
				$response->redirect('/');
			}
		}

		return $this->render('auth/register', ['model' => $model]);
	}
}
?>
```

### Why MVC is important
- Without MVC, SQL + logic + HTML become mixed in one file
- Any change becomes risky and hard to maintain
- MVC makes each part independent and easier to update

## Routing
- Router maps request path + HTTP method to the right controller action
- It doesn't do business logic, it just dispatches

**Analogy:**
- Router is like traffic cop at intersection
- It reads destination and sends each car to the correct road

```php
<?php
$app->router->get('/', [SiteController::class, 'home']);
$app->router->get('/contact', [SiteController::class, 'contact']);
$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);
?>
```

```php
<?php
class Router
{
	private array $routes = [];

	public function get(string $path, array $callback): void
	{
		$this->routes['get'][$path] = $callback;
	}

	public function post(string $path, array $callback): void
	{
		$this->routes['post'][$path] = $callback;
	}

	public function resolve(Request $request): mixed
	{
		$path = $request->getPath();
		$method = $request->getMethod();

		$callback = $this->routes[$method][$path] ?? false;

		if ($callback === false) {
			http_response_code(404);
			return $this->renderView('_404');
		}

		return call_user_func($callback, $request, $response);
	}
}
?>
```

### Why router checks HTTP method
- Same URL can have different behavior
- `GET /register` = show form
- `POST /register` = submit form and process data

## Front Controller
- Front Controller means one entry point for all requests
- In most PHP apps, this is `public/index.php`

**Old way:**
- Many direct files like `about.php`, `contact.php`, `register.php`
- Repeated setup in every file

**Modern way:**
- Only one entry file: `public/index.php`
- All requests pass through it, then router dispatches

```php
<?php
require_once __DIR__ . '/../vendor/autoload.php';

$app = new \app\core\Application(__DIR__ . '/..');

$app->router->get('/', [SiteController::class, 'home']);
$app->router->get('/about', [SiteController::class, 'about']);
$app->router->get('/contact', [SiteController::class, 'contact']);
$app->router->get('/register', [AuthController::class, 'register']);
$app->router->post('/register', [AuthController::class, 'register']);

$app->run();
?>
```

### Benefit of front controller
- One place for bootstrap
- Load autoload, DB, session, middleware, error handlers once
- Better consistency and easier maintenance

## Clean URLs
- Clean URLs are human-readable URLs
- They hide internal implementation details like `index.php?page=...`

| Ugly URL | Clean URL |
|---|---|
| `example.com/index.php?page=users&action=profile&id=42` | `example.com/users/42/profile` |
| `example.com/index.php?page=blog&cat=news&post=17` | `example.com/blog/news/17` |
| `example.com/register.php` | `example.com/register` |

### Why clean URLs matter
- Readability: easier for user to understand
- Security: doesn't expose real file structure
- SEO: easier for search engines to classify
- Shareability: easier to share and remember

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.+)$ /index.php [QSA,L]
```

## Separation of concerns
- Don't put SQL directly in HTML files
- Each layer should do one job only

### Wrong approach (mixed responsibilities)
- HTML file opens DB connection
- Same file executes SQL
- Same file renders output
- Hard to maintain, test, and secure

### Correct approach
- Model: fetch and prepare data
- Controller: coordinate and pass data
- View: render only

```php
<?php
class Product extends Model
{
	public static function getAllActive(): array
	{
		return static::findAll(['active' => 1]);
	}
}
?>
```

```php
<?php
class SiteController extends Controller
{
	public function products(): string
	{
		$products = Product::getAllActive();
		return $this->render('products', ['products' => $products]);
	}
}
?>
```

```php
<h1>Our Products</h1>

<?php foreach ($products as $product): ?>
  <div class="product">
	<h2><?= htmlspecialchars($product->name) ?></h2>
	<p><?= htmlspecialchars($product->price) ?></p>
  </div>
<?php endforeach; ?>
```

### Final idea
- MVC is all about clear separation
- Model handles data
- View handles display
- Controller handles flow
- Router + Front Controller make request handling clean and centralized
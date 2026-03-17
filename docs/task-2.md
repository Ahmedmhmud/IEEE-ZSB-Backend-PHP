# PHP Database & Security Learning Doc

## 1. Database Tables and Indexes
Like we did in TablePlus, we created a **UNIQUE index** on the `email` attribute in the `users` table. This is necessary to avoid creating more than one user with the exact same email address.

## 2. Rendering Pages
We learned how to navigate and render different views, specifically moving between `notes.php` and `note.php`:
* **`notes.php`**: Lists all the notes belonging to the current user.
* **`note.php`**: Renders a single, specific note using a simple paragraph presentation.

## 3. Authorization
This is one of the most crucial phases in any project. We learned to authorize browser parameters using a custom function like `authorize($condition, $status = Response::FORBIDDEN)`. 
* This checks if the current user actually has the permissions to view the requested note. 
* We also ensure the requested note actually exists in the database by using a `findOrFail()` method, returning an error if it doesn't.

## 4. Creating Forms
We reviewed how to create HTML forms and the critical differences between the **GET** and **POST** methods. While both can transfer data to the server:
* **GET** exposes the form data directly in the browser's URL query string. 
* **POST** sends data securely in the request body, making it the standard convention for submitting form data.

## 5. Escaping Untrusted Inputs
Taking raw user input and displaying it on an HTML page is a major security threat (known as an XSS, or Cross-Site Scripting attack). It allows malicious users to manipulate the page by injecting executable JavaScript code. To prevent this, we always use PHP's `htmlspecialchars()` function, which safely escapes untrusted inputs before rendering them on the screen.

## 6. Form Validations
The golden rule of backend development: **never trust user input**. 
* **Client-Side:** We use HTML attributes like `required` in the browser.
* **Server-Side:** We create functions to check if the `$_POST` body is empty or contains more characters than expected.
* **Refactoring:** We can take it a step further by extracting our validation logic into a dedicated, separate class to keep our code clean. 
* **Filtering:** We created a method for email validation using PHP's `filter_var()` function alongside the `FILTER_VALIDATE_EMAIL` constant to guarantee the received email format is perfectly valid.
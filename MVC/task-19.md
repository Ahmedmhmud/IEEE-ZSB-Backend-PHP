# MVC and Database

## Model and database
- In MVC, the Model should be the only part that talks directly to the database
- It is responsible for reading, inserting, updating, and deleting data
- It also keeps the database rules in one place
- Controller should not contain SQL queries
- View should never contain SQL queries
- This separation keeps the code cleaner
- It also makes the app easier to test and easier to change later
- If database code is spread everywhere, fixing one thing becomes harder

**Simple idea:**
- Model = data work
- Controller = request flow
- View = display only

## Config file
- Sensitive information like database password should not be hardcoded in main files
- Put it in a separate config file
- That way you do not expose secrets inside many files
- It is safer if someone opens your controller or view files
- It also makes the project easier to maintain
- If you change the password or database host later, you only change it once
- A config file also keeps settings organized

**Example of data you usually keep in config:**
- database host
- database name
- database username
- database password
- app name
- app base URL

## PDO
- PDO means PHP Data Objects
- It is a way to connect to databases in PHP
- It works with many database systems, not only one
- That means your code is more flexible
- PDO is preferred over older methods like `mysqli`
- It gives a cleaner way to run queries
- It also works very well with prepared statements
- Many developers prefer it because it is more modern and secure

**Why PDO is better:**
- supports different databases
- cleaner syntax
- works well with prepared statements
- helps reduce SQL Injection risk

## Prepared statements
- Prepared statements protect your website from SQL Injection attacks
- The SQL query is written first
- The input values are sent separately
- So the database does not treat user input as SQL code
- It treats it as normal data only
- This is important when the user sends a form value
- It stops dangerous input from changing the query itself

**Bad way:**
- build SQL by joining user input directly
- this can be dangerous

**Good way:**
- prepare query first
- bind the values later
- let the database handle the data safely

```php
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
```

```php
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute(['id' => $id]);
```

## Single row and multiple rows
- When you query a database, sometimes you need just one row
- Sometimes you need many rows
- It depends on the real situation in the app

**One row example:**
- Login page: get one user by email
- Profile page: get one user by id
- Order details page: get one order by order id
- Reset password page: get one token record

**Multiple rows example:**
- Products page: get all products
- Admin page: get all users
- Blog page: get all posts
- Comments section: get all comments under one post

## Final idea
- The Model handles database work
- Config file protects sensitive settings
- PDO gives a cleaner and safer database connection
- Prepared statements protect from SQL Injection
- One row is for one record
- Multiple rows are for lists

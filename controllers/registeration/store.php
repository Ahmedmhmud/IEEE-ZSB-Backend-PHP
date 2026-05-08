<?php

use Core\App,
    Core\Database,
    Core\Validator;


$email = $_POST['email'];
$password = $_POST['password'];

$errors = [];
if (! Validator::email($email)) {
    $errors['email'] = 'Please provide a valid email';
}

if (! Validator::string($password, 7, 255)) {
    $errors['password'] = 'Please provide a valid password with at least 7 chars';
}

if (! empty($errors)) {
    return view('registeration/create.view.php', [
        'heading' => 'Register',
        'errors' => $errors
    ]);
}

$db = App::resolve(Database::class);

$user = $db->query('select email from users where email = :email', [
    'email' => $email
])->find();

if ($user) {
    header('location: /');
    exit();
} else {
    $db->query('INSERT INTO users(email, password) VALUES(:email, :password)',[
        'email' => $email,
        'password' => $password
    ]);

    $_SESSION['user'] = [
        'email' => $email
    ];

    header('location: /');
    exit();
}
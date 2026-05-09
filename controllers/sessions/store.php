<?php

use Core\App,
    Core\Database,
    Core\Validator;

$db = App::resolve(Database::class);

$email = $_POST['email'];
$password = $_POST['password'];

$errors = [];
if (! Validator::email($email)) {
    $errors['email'] = 'Please provide a valid email';
}

if (! Validator::string($password)) {
    $errors['password'] = 'Wrong password';
}

if (! empty($errors)) {
    return view('sessions/create.view.php', [
        'heading' => 'Register',
        'errors' => $errors
    ]);
}

$user = $db->query('SELECT * FROM users WHERE email = :email', [
    'email' => $email
])->find();

if ($user) {
    if (password_verify($password, $user['password'])) {
        login([
            'email' => $email
        ]);

        header('location: /');
        exit();
    }
}

return view('sessions/create.view.php', [
    'heading' => 'Log In',
    'errors' => [
        'password' => 'Wrong password'
    ]
]);
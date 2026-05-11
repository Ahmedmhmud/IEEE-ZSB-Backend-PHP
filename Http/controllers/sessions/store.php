<?php

use Core\App,
    Core\Database,
    Http\Forms\LoginForm;

$db = App::resolve(Database::class);

$email = $_POST['email'];
$password = $_POST['password'];

$form = new LoginForm();

if (! $form->validate($email, $password)) {
    return view('sessions/create.view.php', [
        'heading' => 'Register',
        'errors' => $form->getErrors()
    ]);
}

// $errors = [];
// if (! Validator::email($email)) {
//     $errors['email'] = 'Please provide a valid email';
// }

// if (! Validator::string($password)) {
//     $errors['password'] = 'Wrong password';
// }

// if (! empty($errors)) {
//     return view('sessions/create.view.php', [
//         'heading' => 'Register',
//         'errors' => $errors
//     ]);
// }

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
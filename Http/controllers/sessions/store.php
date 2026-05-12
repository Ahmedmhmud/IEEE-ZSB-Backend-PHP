<?php
    
use Http\Forms\LoginForm,
    Core\Authenticator,
    Core\Session;


$email = $_POST['email'];
$password = $_POST['password'];

$form = new LoginForm();

if ($form->validate($email, $password)) {
    if ((new Authenticator)->attempt($email, $password)) {
        redirect('/');
    }

    $form->error('password', 'Wrong password');
}

Session::flash('errors', $form->getErrors());

redirect('/login');

// return view('sessions/create.view.php', [
//         'heading' => 'Log In',
//         'errors' => $form->getErrors()
// ]);

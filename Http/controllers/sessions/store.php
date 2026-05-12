<?php

use Http\Forms\LoginForm,
    Core\Authenticator;



$form = LoginForm::validate($attributes = [
    'email' => $_POST['email'], 
    'password' => $_POST['password']
]);

$signedIn = (new Authenticator)->attempt(
    $attributes['email'], $attributes['password']
);

if (! $signedIn) {
    $form->error(
        'password', 'Wrong password'
    )->throw();    
}

redirect('/');


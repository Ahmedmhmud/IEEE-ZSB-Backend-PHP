<?php

use Core\Session;

view('sessions/create.view.php',[
    'heading' => 'Login',
    'errors' => Session::get('errors')
]);

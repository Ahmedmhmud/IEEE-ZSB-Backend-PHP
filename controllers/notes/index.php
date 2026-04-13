<?php

$heading = 'My Notes';

$config = require base_path('config.php');
$db = new Database($config['database']);

$notes = $db->query('SELECT * FROM notes WHERE user_id = 1')->get();

view('notes/index.view.php', [
    'heading' => $heading,
    'notes' => $notes
]);
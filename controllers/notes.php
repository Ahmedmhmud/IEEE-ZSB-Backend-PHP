<?php

$heading = 'My Notes';

$config = require "./config.php";
$db = new Database($config['database']);

$notes = $db->query('SELECT * FROM notes WHERE user_id = 1');

require './views/notes.view.php';
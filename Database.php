<?php

class Database {
    public $connection;

    public function __construct($config, $username = 'tableuser', $password = 'StrongPassword123!') {
        $dsn = "mysql:" . http_build_query($config, '', ';');
        $this->connection = new PDO($dsn, $username, $password, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    public function query($query, $params = []) {
        $statement = $this->connection->prepare($query);
        $statement->execute($params);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
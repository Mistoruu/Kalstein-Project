<?php

class Database
{
    private PDO $connection;

    public function __construct()
    {
        try {
            $this->connection = new PDO('mysql:host=localhost;dbname=kalstein', 'root', '');            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->exec('SET CHARACTER SET utf8');
        } catch (PDOException $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function connect(): PDO
    {
        return $this->connection;
    }

}

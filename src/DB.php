<?php

namespace Koltsova\Builder;

use Aigletter\Contracts\Builder\DbInterface;
use Aigletter\Contracts\Builder\QueryInterface;

class DB implements DbInterface
{
    public $config;
    public \PDO $dbc;

    public function __construct($config)
    {
        $this->config = $config;
        $this->dbc = new \PDO('mysql:host=localhost', $this->config['dbUsername'], $this->config['dbPassword']);
        $db = $this->dbc->prepare('CREATE DATABASE IF NOT EXISTS Builder');
        $db->execute();
        $this->dbc = new \PDO('mysql:dbname=Builder; host=localhost', $this->config['dbUsername'], $this->config['dbPassword']);
        $this->createTable();
    }

    public function createTable()
    {
        $sql = 'id int PRIMARY KEY AUTO_INCREMENT NOT NULL,
                First_name varchar(255) NOT NULL,
                Age int NOT NULL,
                Status varchar(255) NOT NULL';

        $table = $this->dbc->prepare("SHOW TABLES FROM Builder LIKE '" . $this->config['tableName'] . "';");
        $table->execute();
        $data = $table->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($data)) {
            echo "In database not found tables like " . $this->config['tableName'].'</br>';
            $table = $this->dbc->prepare("CREATE TABLE Builder.".$this->config['tableName']." ($sql)");
            $table->execute();
            echo "Creating table ".$this->config['tableName']." successful!".'</br>';
        }
    }

    public function addInfo($values)
    {
        $sql = "INSERT INTO Builder.".$this->config['tableName']." (First_name, Age, Status)
                VALUES $values";
        $table = $this->dbc->prepare($sql);
        $table->execute();
        echo "Adding data to database table successful!".'</br>';
    }

    public function one(QueryInterface $query): object
    {
        $table = $this->dbc->prepare((string)$query);
        $table->execute();
        $array = $table->fetch(\PDO::FETCH_OBJ);
        return $array;
    }

    public function all(QueryInterface $query): array
    {
        $table = $this->dbc->prepare($query->toSql());
        $table->execute();
        $array = $table->fetchALL(\PDO::FETCH_ASSOC);
        return $array;
    }
}
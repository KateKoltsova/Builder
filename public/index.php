<?php

use Koltsova\Builder\DB;
use Koltsova\Builder\QueryBuilder;

require_once __DIR__ . '/../vendor/autoload.php';
$config = require_once __DIR__.'/../config/config.php';

function generateValues($config) {
    $values = '';
    for ($i=1; $i<=20 ; $i++) {
        $name = $config['namesArray'][rand(0, count($config['namesArray'])-1)];
        $age = rand($config['age'][0], $config['age'][1]);
        $status = $config['status'][rand(0, count($config['status'])-1)];
        $values = $values . "('$name', $age, '$status'),";
    };
    $values = substr($values, 0, -1);
    return $values;
}

$builder = new QueryBuilder();
$query = $builder->table('user')
    ->select(['id', 'first_name', 'age', 'status'])
    ->where(['status' => 'active', 'age' => 21])
    ->order(['id' => 'ASC'])
    ->limit(2)
    ->offset(2)
    ->build();
echo (string) $query;
echo '</br>';
echo $query->toSql();
echo '</br>';

$db = new DB($config);
$db->addInfo(generateValues($config));
echo '<pre>';
print_r($user = $db->one($query));
print_r($users = $db->all($query));
echo '</pre>';

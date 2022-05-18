<?php

use Koltsova\Builder\SqlBuilder;

require_once __DIR__.'/../vendor/autoload.php';

$builder = new SqlBuilder();
echo $builder->table('users')
    ->select(['first_name', 'age', 'status'])
    ->where(['status' => 'active', 'age' => 20])
    ->order(['id' => 'ASC'])
    ->limit(20)
    ->offset(40)
    ->build();

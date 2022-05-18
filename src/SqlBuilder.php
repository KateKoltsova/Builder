<?php

namespace Koltsova\Builder;

use Aigletter\Contracts\Builder\BuilderInterface;
use Aigletter\Contracts\Builder\SqlBuilderInterface;

class SqlBuilder implements SqlBuilderInterface
{
    public string $select;
    public string $table;
    public string $where = '';
    public string $order = '';
    public string $limit = '';
    public string $offset = '';

    public function select($columns): BuilderInterface
    {
        $columns = implode(', ', $columns);
        $this->select = $columns;
        return $this;
    }

    public function where($conditions): BuilderInterface
    {
        $where = '';
        foreach ($conditions as $key => $value) {
            $where = $where . "$key = $value" . ' AND ';
        }
        $where = substr($where, 0, -5);
        $this->where = $where;
        return $this;
    }

    public function table($table): BuilderInterface
    {
        $this->table = $table;
        return $this;
    }

    public function limit($limit): BuilderInterface
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset($offset): BuilderInterface
    {
        $this->offset = $offset;
        return $this;
    }

    public function order($order): BuilderInterface
    {
        $sort = '';
        foreach ($order as $key => $value) {
            $sort = $sort . "$key = $value" . ', ';
        }
        $sort = substr($sort, 0, -2);
        $this->order = $sort;
        return $this;
    }

    public function build(): string
    {
        return "SELECT " . $this->select
            . " FROM " . $this->table
            . (!empty($this->where) ? " WHERE " . $this->where : '')
            . (!empty($this->order) ? " ORDER BY " . $this->order : '')
            . (!empty($this->limit) ? " LIMIT " . $this->limit : '')
            . (!empty($this->offset) ? " OFFSET " . $this->offset : '');
    }
}
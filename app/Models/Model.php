<?php


namespace App\Models;


use Nette\Database\Context;

abstract class Model
{
    protected $table;

    protected $database;

    public function __construct(Context $database){
        $this->database = $database;
    }

    public function getAll() {
        return $this->database->table($this->table);
    }

    public function findOne(int $id) {
        return $this->getAll()->get($id);
    }

    public function create(array $values) {
        return $this->getAll()->insert($values);
    }

}
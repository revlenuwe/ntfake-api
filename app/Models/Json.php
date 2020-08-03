<?php


namespace App\Models;


class Json extends Model
{
    protected $table = 'json_responses';

    public function getBySign(string $sign) {
        return $this->getAll()->where('sign',$sign);
    }
}
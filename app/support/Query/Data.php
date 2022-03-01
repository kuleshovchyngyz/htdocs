<?php

namespace App\support\Query;

class Data
{
    protected $levels = array();
    protected $query;
    public function __construct(iterable $arr, $query){
        foreach ($arr as $ar){
            $this->levels[] = $ar;
        }
        $this->query = $query;
    }

    /**
     * @return array
     */
    public function getLevels(): array
    {
        return $this->levels;
    }

    /**
     * @return mixed
     */
    public function getQuery()
    {
        return $this->query;
    }
}

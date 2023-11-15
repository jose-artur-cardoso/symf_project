<?php

namespace App\Services;

class ArrayUtils
{
    public function getDifferencesBetweenArrays(array $array)
    {
        
        if (count($array) < 2) {
            throw new \InvalidArgumentException("You should pass at least 2 array to compare");
        }
    
        $compareArr = [];
        $keyDifferentArr = [];
        foreach ($array as $item) {
            foreach($item as $key => $val) {
                if (!key_exists($key, $compareArr)) {
                    $compareArr[$key] = $val;
                } elseif ($compareArr[$key] !== $val) {
                    $keyDifferentArr[$key] = true;
                }
            }
        }
        return array_keys($keyDifferentArr);
    }

}
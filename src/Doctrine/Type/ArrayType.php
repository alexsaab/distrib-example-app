<?php

namespace App\Doctrine\Type;

use Doctrine\DBAL\Types\JsonType;

class ArrayType extends JsonType
{
    public function getName()
    {
        return 'array';
    }
} 
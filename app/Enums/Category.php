<?php

namespace App\Enums;

enum Category: string
{
    case P = 'Prescription';
    case O = 'Over the Counter';
    case S = 'Supplement';
}

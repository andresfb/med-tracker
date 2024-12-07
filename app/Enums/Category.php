<?php

namespace App\Enums;

enum Category: string
{
    case Prescription = 'P';
    case Over_the_Counter = 'O';
    case Supplement = 'S';
}

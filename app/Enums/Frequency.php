<?php

namespace App\Enums;

enum Frequency: string
{
    case As_Needed = 'AS';
    case At_Time = 'AT';
    case Daily = 'DA';
    case Weekly = 'WE';
    case Monthly = 'MO';
}

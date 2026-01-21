<?php

namespace App\Enums;

enum FrequencyType: string
{
    case daily = 'daily';
    case Monthly = 'monthly';
    case Yearly  = 'yearly';
}

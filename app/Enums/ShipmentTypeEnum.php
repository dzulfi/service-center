<?php
namespace App\Enums;

enum ShipmentTypeEnum: string
{
    case ToRMA = 'To_RMA';
    case FromRMA = 'From_RMA';
}
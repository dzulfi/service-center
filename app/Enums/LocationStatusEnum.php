<?php
namespace App\Enums;

enum LocationStatusEnum: string
{
    case AtBranch = 'At_BranchOffice';
    case InTransitToRMA = 'In_Transit_To_RMA';
    case AtRMA = 'At_RMA';
    case InTransitFromRMA = 'In_Transit_From_RMA';
    case ReadyForPickup = 'Ready_For_Pickup';
}
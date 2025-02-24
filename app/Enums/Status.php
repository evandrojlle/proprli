<?php
namespace App\Enums;

/**
 * Enumerations are restrictive layers to provide a closed set of possible values ​​for a status.
 */
enum Status: int
{
    case Opened = 1; // Opened status

    case InProgress = 2; // In progress status

    case Completed = 3; // Complete status

    case Rejected = 4; // Rejected Status
}

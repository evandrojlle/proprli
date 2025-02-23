<?php
namespace App\Enums;

enum Status: int
{
    case Opened = 1; 

    case InProgress = 2;

    case Completed = 3;

    case Rejected = 4;
}

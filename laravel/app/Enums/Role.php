<?php

namespace App\Enums;

enum Role: int
{
    case MEMBER = 1;
    case MODERATOR = 2;
    case ADMINISTRATOR = 3;
}
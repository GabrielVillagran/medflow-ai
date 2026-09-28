<?php

namespace App\Enums;

enum UserRole: string
{
    case Patient = 'patient';
    case Provider = 'provider';
    case Administrator = 'administrator';
}

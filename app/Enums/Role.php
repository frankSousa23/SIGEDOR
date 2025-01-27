<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'admin';
    case AREA_MANAGER = 'area_manager';
    case TEACHER = 'teacher';
}

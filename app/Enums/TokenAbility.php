<?php

namespace App\Enums;

enum TokenAbility: string
{
    case POST_CREATE = 'post:create';
    case POST_READ = 'post:read';
    case POST_UPDATE = 'post:update';
    case POST_DELETE = 'post:delete';
}

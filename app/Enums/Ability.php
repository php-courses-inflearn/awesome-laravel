<?php

namespace App\Enums;

enum Ability: string
{
    case POST_CREATE = 'post:create';
    case POST_READ = 'post:read';
    case POST_UPDATE = 'post:update';
    case POST_DELETE = 'post:delete';
}

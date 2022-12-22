<?php

namespace App\Enums;

/**
 * 소셜 로그인 서비스 제공자 목록
 */
enum Provider: string
{
    case Github = 'github';
    //case Facebook = 'facebook';
}

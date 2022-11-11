<?php

namespace Tests\Feature\Rules;

use App\Rules\Password;
use Tests\TestCase;

class PasswordTest extends TestCase
{
    /**
     * Password 규칙 통과 테스트
     *
     * @return void
     */
    public function testPasses()
    {
        $rule = new Password();

        $this->assertEquals(1, $rule->passes('', '!1Qw'));
        $this->assertEquals(0, $rule->passes('', 'password'));
    }

    /**
     * Password 규칙 메시지 테스트
     *
     * @return void
     */
    public function testMessage()
    {
        $rule = new Password();

        $this->assertEquals(__('validation.regex'), $rule->message());
    }
}

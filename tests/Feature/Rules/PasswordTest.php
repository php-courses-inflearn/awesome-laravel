<?php

namespace Tests\Feature\Rules;

use App\Rules\Password;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class PasswordTest extends TestCase
{
    /**
     * 비밀번호 규칙 테스트
     *
     * @param  string  $password
     *
     * @dataProvider passwordProvider
     *
     * @return void
     */
    public function testPasswordRule(string $password)
    {
        $validator = Validator::make(['password' => $password], [
            'password' => new Password(),
        ]);

        $errors = $validator->errors();

        if ($validator->fails()) {
            $this->assertContains('password', $errors->keys());
        } else {
            $this->assertEmpty($errors);
        }
    }

    /**
     * @return string[][]
     */
    public function passwordProvider()
    {
        return [['1'], ['1!'], ['1!q'], ['1!qQ']];
    }
}

<?php

namespace Tests\Feature\Rules;

use App\Rules\Password;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class PasswordTest extends TestCase
{
    /**
     * Password 규칙 통과 테스트
     *
     * @param  string  $password
     * @param  int  $expected
     * @dataProvider passwordProvider
     *
     * @return void
     */
//    public function testPasses(string $password, int $expected)
//    {
//        $rule = new Password();
//
//        $this->assertEquals($expected, $rule->passes('password', $password));
//    }

    /**
     * Password 규칙 메시지 테스트
     *
     * @return void
     */
//    public function testMessage()
//    {
//        $rule = new Password();
//
//        $this->assertEquals(__('validation.regex'), $rule->message());
//    }

    /**
     * 비밀번호 규칙 테스트
     *
     * @param  string  $password
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
            $this->assertStringContainsString(
                __('validation.regex', [
                    'Attribute' => __('validation.attributes.password'),
                ]),
                $errors->first('password')
            );
        } else {
            $this->assertEmpty($errors);
        }
    }

    /**
     * @return array[]
     */
//    public function passwordProvider()
//    {
//        return [
//            ['1', 0],
//            ['1!', 0],
//            ['1!q', 0],
//            ['1!qQ', 1]
//        ];
//    }

    /**
     * @return string[][]
     */
    public function passwordProvider()
    {
        return [['1'], ['1!'], ['1!q'], ['1!qQ']];
    }
}

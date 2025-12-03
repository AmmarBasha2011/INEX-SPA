<?php

use PHPUnit\Framework\TestCase;

require_once 'core/functions/PHP/classes/Validation.php';
require_once 'core/functions/PHP/classes/SecurityV2.php';
require_once 'core/functions/PHP/classes/FormKit.php';

class FormKitTest extends TestCase
{
    public function testRequiredField()
    {
        $config = [
            'name' => [
                'required' => true,
                'messages' => ['required' => 'Name is required.'],
            ],
        ];
        $form = new FormKit($config);
        $this->assertFalse($form->validate(['name' => '']));
        $this->assertEquals('Name is required.', $form->getError('name'));
    }

    public function testEmailValidation()
    {
        $config = [
            'email' => [
                'validate' => ['email' => true],
                'messages' => ['email' => 'Invalid email.'],
            ],
        ];
        $form = new FormKit($config);
        $this->assertFalse($form->validate(['email' => 'invalid']));
        $this->assertEquals('Invalid email.', $form->getError('email'));

        $form = new FormKit($config);
        $this->assertTrue($form->validate(['email' => 'valid@email.com']));
    }

    public function testMinLengthValidation()
    {
        $config = [
            'password' => [
                'validate' => ['minLength' => 8],
                'messages' => ['minLength' => 'Password too short.'],
            ],
        ];
        $form = new FormKit($config);
        $this->assertFalse($form->validate(['password' => 'short']));
        $this->assertEquals('Password too short.', $form->getError('password'));
    }

    public function testMaxLengthValidation()
    {
        $config = [
            'username' => [
                'validate' => ['maxLength' => 10],
                'messages' => ['maxLength' => 'Username too long.'],
            ],
        ];
        $form = new FormKit($config);
        $this->assertFalse($form->validate(['username' => 'thisusernameistoolong']));
        $this->assertEquals('Username too long.', $form->getError('username'));
    }

    public function testNumericValidation()
    {
        $config = [
            'age' => [
                'validate' => ['numeric' => true],
                'messages' => ['numeric' => 'Age must be a number.'],
            ],
        ];
        $form = new FormKit($config);
        $this->assertFalse($form->validate(['age' => 'not-a-number']));
        $this->assertEquals('Age must be a number.', $form->getError('age'));
    }

    public function testStringSanitization()
    {
        $config = ['comment' => ['sanitize' => 'string']];
        $form = new FormKit($config);
        $form->validate(['comment' => ' <p>Hello</p> ']);
        $this->assertEquals('Hello', $form->getSanitizedData()['comment']);
    }

    public function testEmailSanitization()
    {
        $config = ['email' => ['sanitize' => 'email']];
        $form = new FormKit($config);
        $form->validate(['email' => ' (test@example.com) ']);
        $this->assertEquals('test@example.com', $form->getSanitizedData()['email']);
    }

    public function testIntSanitization()
    {
        $config = ['age' => ['sanitize' => 'int']];
        $form = new FormKit($config);
        $form->validate(['age' => '25a']);
        $this->assertEquals(25, $form->getSanitizedData()['age']);
    }

    public function testUrlSanitization()
    {
        $config = ['website' => ['sanitize' => 'url']];
        $form = new FormKit($config);
        $form->validate(['website' => 'https://example.com<script>']);
        $this->assertEquals('https://example.com', $form->getSanitizedData()['website']);
    }

    public function testSuccessfulValidation()
    {
        $config = [
            'name'  => ['required' => true],
            'email' => ['validate' => ['email' => true]],
        ];
        $form = new FormKit($config);
        $this->assertTrue($form->validate(['name' => 'John Doe', 'email' => 'john@example.com']));
        $this->assertEmpty($form->getErrors());
    }

    public function testSanitizationOnValidation()
    {
        $config = [
            'comment' => [
                'sanitize' => 'string',
                'validate' => ['maxLength' => 5],
            ],
        ];
        $form = new FormKit($config);
        $form->validate(['comment' => '  <p>Hello World</p>  ']);
        $this->assertEquals('Hello World', $form->getSanitizedData()['comment']);
    }

    public function testErrorAccumulation()
    {
        $config = [
            'username' => [
                'validate' => [
                    'minLength' => 5,
                    'maxLength' => 10,
                ],
                'messages' => [
                    'minLength' => 'Username too short.',
                    'maxLength' => 'Username too long.',
                ],
            ],
        ];
        $form = new FormKit($config);
        $form->validate(['username' => 'four']);
        $errors = $form->getErrors()['username'];
        $this->assertCount(1, $errors);
        $this->assertEquals('Username too short.', $errors[0]);

        $form = new FormKit($config);
        $form->validate(['username' => 'thisusernameistoolong']);
        $errors = $form->getErrors()['username'];
        $this->assertCount(1, $errors);
        $this->assertEquals('Username too long.', $errors[0]);
    }

    public function testAsyncValidation()
    {
        $config = [
            'username' => [
                'async' => function ($value) {
                    return $value === 'valid';
                },
                'messages' => [
                    'async' => 'Username is already taken.',
                ],
            ],
        ];
        $form = new FormKit($config);
        $form->validate(['username' => 'invalid'], function ($isValid) {
            $this->assertFalse($isValid);
        });
        $this->assertEquals('Username is already taken.', $form->getError('username'));

        $form = new FormKit($config);
        $form->validate(['username' => 'valid'], function ($isValid) {
            $this->assertTrue($isValid);
        });
    }
}

<?php

declare(strict_types=1);

require_once("core/init.php");

use PHPUnit\Framework\TestCase;

class ValidateTest extends TestCase
{
    public function test_returns_dbinst()
    {
        $db = DB::getInstance();
        $val = new Validate();
        $this->assertSame($db, $val->getDB());
    }


    public function test_validate_rules_valid_data()
    {
        $validate = new Validate();
        $validation = $validate->check(
            array('password_current' => 'djordje'),
            array('password_current' => array('required' => true,'min' => 6
            ),)
        );
        $this->assertEmpty($validate->errors());
    }
    public static function provideInvalidData()
    {
        return [
        [array('password_current' => 'djordje','password_new' => ''),
        array(
            'password_current' => array(
                'required' => true,
                'min' => 6
            ),
            'password_new' => array(
                'required' => true,
                'min' => 6
            ))],
        [
            array('password_current' => 'djordje','password_new' => 'ab'),
        array(
            'password_current' => array(
                'required' => true,
                'max' => 5
            ),
            'password_new' => array(
                'required' => true,
                'max' => 3
            ))
        ],
        [ array('email' => 'djordje00karisic@gmail.com','password' => '12345678', 'new_password' => '12345678')
        ,
            array(
                'email' => array(
                    'required' => true,
                    'numeric' => true,
                    'unique' => 'admin'
                ),
                'password' => array(
                    'required' => true,
                    'numeric' => true,
                ))

                ],
                [ array('password_current' => '12345678','password_new' => '123456789', 'password_new_again' => '12345678'),
                    array(
                    'password_current' => array(
                        'required' => true,
                        'min' => 6
                    ),
                    'password_new' => array(
                        'required' => true,
                        'min' => 8
                    ),
                    'password_new_again' => array(
                        'required' => true,
                        'min' => 8,
                        'matches' => 'password_new'
                    )
                    )]

        ];
    }
/**
    * @dataProvider provideInvalidData
    */
    public function test_validate_rules_invalid_data($data, $test)
    {

        $validate = new Validate();
        $validation = $validate->check($data, $test);

        $this->assertNotEmpty($validate->errors());
    }


    public function test_validate_rules_valid_credentials()
    {
        $validate = new Validate();
        $validation = $validate->check(
            array('email' => 'djordje00karisic@gmail.com','password' => '12345678'),
            array(
            'email' => array(
                'required' => true,
                'unique' => 'admin'
            ),
            'password' => array(
                'required' => true,
                'numeric' => true,
            )
            )
        );
            $this->assertEmpty($validate->passed());
    }
}

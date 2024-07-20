<?php

use PHPUnit\Framework\TestCase;
use CodeCorner\Validation\Validation;

class ValidationTest extends TestCase
{
    protected static $pdo;

    public static function setUpBeforeClass(): void
    {
        // Establish MySQL database connection
        $host = 'localhost';
        $dbname = 'mvc';
        $username = 'root';
        $password = 'root';

        // Example mysqli connection
        self::$pdo = new mysqli($host, $username, $password, $dbname);
        // self::$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        // self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function tearDownAfterClass(): void
    {
        // Close MySQL database connection
        self::$pdo = null;
    }

    public function testValidationWithRequiredField()
    {
        $rules = [
            'username' => 'required|numeric|min:2|max:10',
            'email' => 'required|email|unique:users.username',
            'nullable'  => 'nullable',
            'name'  => 'required',
            'gender'  => 'required|in_array:2,3'
        ];

        $validData = [
            'username' => '112',
            'nullable' => null,
            'name'      => 'aas',
            'gender'      => 3,
            'email' => 'customer@example.com',
        ];
        Validation::dbConfigure(self::$pdo);
        Validation::validate($rules, $validData);

        // Assert that there are no errors after validation
        $this->assertEquals(true, Validation::getErrors());
    }

    public function testValidationWithInvalidData()
    {
        $rules = [
            'phone' => 'required|phone',
            'phone_code'   => 'required'
        ];

        $invalidData = [
            'phone' => '8447441246',
            'phone_code' => 'IN',
        ];

        Validation::validate($rules, $invalidData);

        // Assert that there are errors after validation
        $this->assertEquals(true, Validation::getErrors());
    }

    // More test cases can be added as needed
}

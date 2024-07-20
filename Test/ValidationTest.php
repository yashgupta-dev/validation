<?php

use PHPUnit\Framework\TestCase;
use CodeCorner\Validation\Validation;

class ValidationTest extends TestCase
{
    public function testValidationWithRequiredField()
    {
        $rules = [
            'username' => 'required|numeric|min:2|max:10',
            'email' => 'required|email',
            'nullable'  => 'nullable',
            'name'  => 'required',
            'gender'  => 'required|in_array:2,3'
        ];

        $validData = [
            'username' => '112',
            'nullable' => null,
            'name'      => '',
            'gender'      => 3,
            'email' => 'john.doe@example.com',
        ];

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
}

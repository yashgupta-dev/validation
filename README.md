# Validation Library

## Introduction

This is a comprehensive validation library for PHP, providing various methods to validate different types of data against specified rules.

## Installation

Ensure you have Composer installed. You can install the library using Composer by running:

```
composer require codecorners/validation
```

## Usage

1. **Require Composer's Autoload File**

   Make sure to include Composer's autoload file at the beginning of your script:

   ```php
   require_once 'vendor/autoload.php';
   ```

2. **Use the Validation Class**

   Use the `Validation` class from the `CodeCorner\Validation` namespace in your script:

   ```php
   use CodeCorner\Validation\Validation;
   ```

3. **Perform Validation**

   Define your validation rules and validate your data using the `validate` method of the `Validation` class:

   ```php
   Validation::validate([
       'name'  => 'required|min:3',
       'email' => 'required|email',
       'age'   => 'numeric|min:18',
       'url'   => 'URL',
       'phone' => 'phone',
       'amount' => 'decimal',
       'role'  => 'in_array:admin,moderator,user',
       'username' => 'unique:users',
       'password' => 'nullable|string|min:6',
   ], [
       'name' => 'John Doe',
       'email' => 'john@example.com',
       'age' => 25,
       'url' => 'https://example.com',
       'phone' => '+1234567890',
       'amount' => 123.45,
       'role' => 'admin',
       'username' => 'johndoe',
       'password' => null,
   ]);
   ```

   In this example:
   - `name` is required and must be at least 3 characters long.
   - `email` must be a valid email address.
   - `age` must be numeric and at least 18.
   - `url` must be a valid URL.
   - `phone` must be a valid phone number.
   - `amount` must be a decimal number.
   - `role` must be one of: admin, moderator, or user.
   - `username` must be unique (e.g., not already taken in a database).
   - `password` is nullable (optional) and if provided, must be a string of at least 6 characters.

4. **Available Validation Methods**

   Here are the available validation methods and their usage:

   - `required`: The field must be present and not empty.
   - `string`: The field must be a string.
   - `numeric`: The field must be numeric.
   - `in_array:val1,val2,...`: The field must be one of the specified values.
   - `array_required`: The field must be an array and not empty.
   - `file`: The field must be a file upload. // 'size.5000000&type.png,jpeg&name.jpeg,png'
   - `min:value`: The field must be at least `value` characters long (for strings) or numeric value.
   - `max:value`: The field must be at most `value` characters long (for strings) or numeric value.
   - `nullable`: Allows the field to be null or empty.
   - `unique:table,column`: Checks if the field value is unique in a specified database table and column.
   - `email`: The field must be a valid email address.
   - `URL`: The field must be a valid URL.
   - `regex:pattern`: The field must match the specified regular expression pattern.
   - `not_in:val1,val2,...`: The field must not be one of the specified values.
   - `in:val1,val2,...`: Alias for `in_array`.
   - `phone`: The field must be a valid phone number.
   - `decimal`: The field must be a valid decimal number.

5. **Retrieve Validation Errors**

   After performing validation, you can retrieve any validation errors using the `getErrors` method:

   ```php
   $errors = Validation::getErrors();
   print_r($errors);
   ```

   This will output an array of validation errors encountered during the validation process.

6. **Using Database level validation**

   - add your DB object in the config.php file.

   ```php
      Validation::dbConfigure(self::$pdo);
   ```

   then you will use database level validation

   ```php
   <?php

   require_once 'vendor/autoload.php';

      use CodeCorner\Validation\Validation;

      Validation::validate([
         'name'  => 'required|min:3',
         'email' => 'required|unique:`tablename`.`fieldname`',
         'phone' => 'phone|not_in:`tablename`.`fieldname`,`fieldname`-`value`',
         'role'  => 'in:`tablename`.`fieldname',
         'username' => 'assign:`tablename`.`fieldname`',
         
      ], [
         'name' => 'John Doe',
         'email' => 'john@example.com',
         'phone' => '+1234567890',
         'role' => 'admin',
         'username' => 'johndoe',         
      ]);

      $errors = Validation::getErrors();
      print_r($errors);
      ```


## Example

Here's a complete example of how you might use this library:

```php
<?php

require_once 'vendor/autoload.php';

use CodeCorner\Validation\Validation;

Validation::validate([
    'name'  => 'required|min:3',
    'email' => 'required|email',
    'age'   => 'numeric|min:18',
    'url'   => 'URL',
    'phone' => 'phone',
    'amount' => 'decimal',
    'role'  => 'in_array:admin,moderator,user',
    'username' => 'unique:users',
    'password' => 'nullable|string|min:6',
], [
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'age' => 25,
    'url' => 'https://example.com',
    'phone' => '+1234567890',
    'amount' => 123.45,
    'role' => 'admin',
    'username' => 'johndoe',
    'password' => null,
]);

$errors = Validation::getErrors();
print_r($errors);
```

In this example:
- We include Composer's autoload file.
- Define various validation rules for different fields.
- Validate the provided data against these rules.
- Print any validation errors encountered.
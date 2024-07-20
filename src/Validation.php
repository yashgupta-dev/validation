<?php

namespace CodeCorner\Validation;

use CodeCorner\Validation\config\config;
use CodeCorner\Validation\Validators\MaxValidator;
use CodeCorner\Validation\Validators\MinValidator;
use CodeCorner\Validation\Validators\FileValidator;
use CodeCorner\Validation\Validators\EmailValidator;
use CodeCorner\Validation\Validators\PhoneValidator;
use CodeCorner\Validation\Validators\EqualsValidator;
use CodeCorner\Validation\Validators\StringValidator;
use CodeCorner\Validation\Validators\DecimalValidator;
use CodeCorner\Validation\Validators\InArrayValidator;
use CodeCorner\Validation\Validators\RequiredValidator;
use CodeCorner\Validation\Validators\IsNumericValidator;
use CodeCorner\Validation\Validators\NullableValidator;
use CodeCorner\Validation\Validators\RegxValidator;
use CodeCorner\Validation\Validators\RequiredArrayValidator;
use CodeCorner\Validation\Validators\UrlValidator;

/**
 * Validation
 */
class Validation
{

    /**
     * errors
     *
     * @var array
     */
    public static $errors = array();

    /**
     * validationError
     *
     * @var array
     */
    public static $validationError = array();

    public static $imageError = array();

    /**
     * validate
     *
     * @param  array $rules
     * @param  array $request
     * @param  array $msgs
     * @return array|bool
     */
    public static function validate($rules, $request, $msgs = [])
    {
        $data = $request; // passing all request into data array

        $validatedData = []; // multi request array (.*)

        // rules foreach loop
        foreach ($rules as $field => $ruleString) {

            // multiinput validation
            $fieldParts = explode('.', $field) ?? false;

            // check if field key have .*
            if (count($fieldParts) === 2) {

                $getFieldNameOrg = $getFieldName = $fieldParts[0];   // passing value                                

                // single form fields                
                if (in_array($getFieldName, array_keys($data))) {

                    // making input field with name[increment]
                    for ($increment = 0; $increment < count($data[$getFieldNameOrg]); $increment++) {

                        $validatedData[$getFieldNameOrg . $increment] = $data[$getFieldNameOrg][$increment];
                    }

                    /**
                     * applyValidation
                     *
                     * @param  mixed $ruleString
                     * @param  array $data
                     * @param  mixed $field
                     * @param  array $msgs
                     * @return array|bool
                     */
                    for ($increment = 0; $increment < count($data[$getFieldNameOrg]); $increment++) {
                        $getFieldName = $getFieldNameOrg . $increment;
                        self::applyValidation($ruleString, $validatedData, $getFieldName, $msgs);
                    }
                }
            } else {

                // single form fields
                if (in_array($field, array_keys($data))) {

                    /**
                     * applyValidation
                     *
                     * @param  mixed $ruleString
                     * @param  array $data
                     * @param  mixed $field
                     * @param  array $msgs
                     * @return array|bool
                     */
                    self::applyValidation($ruleString, $data, $field, $msgs);
                }
            }
        }
    }

    /**
     * getErrors
     *
     * @return array|bool
     */
    public static function getErrors()
    {

        return empty(self::$errors) ? true : self::$errors;
    }

    /**
     * applyValidation
     *
     * @param  mixed $ruleString
     * @param  array $data
     * @param  mixed $field
     * @param  array $msgs
     * @return void|array
     */
    protected static function applyValidation($ruleString, $data, $field, $msgs = array())
    {
        $rules = explode('|', $ruleString);
        foreach ($rules as $rule) {

            $ruleParts = explode(':', $rule);

            $ruleName = $ruleParts[0];
            $ruleValue = isset($ruleParts[1]) ? $ruleParts[1] : null;
            if ($ruleValue) {
                $getTableContent = explode('.', $ruleValue);
                $table = isset($getTableContent[0]) ? $getTableContent[0] : null;
                $column = isset($getTableContent[1]) ? $getTableContent[1] : null;
            }

            $isArray = !empty(explode('.', $field)[1]) ? '.*' : $field;
            $valid = true;
            $exceptionMsg = '';

            switch ($ruleName) {
                case 'required':

                    $valid = RequiredValidator::validate($data, $field);

                    if (!$valid) {
                        if (empty(self::$validationError[$field])) {
                            self::$validationError[$field] = RequiredValidator::messageCreate($msgs, $field, $ruleName);
                        }
                    }

                    break;

                case 'array_required':

                    if (!empty($data[$field])) {

                        $valid = RequiredArrayValidator::validate($data, $field);
                    }

                    if (!$valid) {

                        if (empty(self::$validationError[$field])) {
                            self::$validationError[$field] = RequiredArrayValidator::messageCreate($msgs, $field, $ruleName);
                        }
                    }
                    break;

                case 'max':
                    $valid = MaxValidator::validate($data, $field, $ruleValue);

                    if (!$valid) {
                        if (empty(self::$validationError[$field])) {
                            self::$validationError[$field] = MaxValidator::messageCreate($msgs, $field, $ruleValue);
                        }
                    }

                    break;

                case 'min':

                    $valid = MinValidator::validate($data, $field, $ruleValue);

                    if (!$valid) {
                        if (empty(self::$validationError[$field])) {
                            self::$validationError[$field] = MinValidator::messageCreate($msgs, $field, $ruleValue);
                        }
                    }

                    break;

                case 'numeric':

                    if (!empty($data[$field])) {
                        $valid = IsNumericValidator::validate($data, $field);
                    }

                    if (!$valid) {

                        if (empty(self::$validationError[$field])) {
                            self::$validationError[$field] = IsNumericValidator::messageCreate($msgs, $field, $ruleName);
                        }
                    }
                    break;

                case 'equals':
                    if (!empty($data[$field]) || !empty($ruleValue)) {
                        $valid = EqualsValidator::validate($data, $field, $ruleValue);

                        if (!$valid) {

                            self::$validationError[$field] = EqualsValidator::messageCreate($msgs, $field, $ruleName);
                        }
                    }

                    break;

                case 'decimal':
                    $valid = DecimalValidator::validate($msgs, $field, $ruleValue);

                    if (!$valid) {

                        if (empty(self::$validationError[$field])) {
                            self::$validationError[$field] = DecimalValidator::messageCreate($msgs, $field, $ruleValue);
                        }
                    }
                    break;

                case 'string':

                    $valid = StringValidator::validate($data, $field, $ruleValue, $ruleName);
                    if (!$valid) {
                        $fieldMsg = isset($msgs[$field]) ? $msgs[$field] : $field;

                        if (empty(self::$validationError[$field])) {
                            self::$validationError[$field] = StringValidator::messageCreate($msgs, $field, $ruleValue, $ruleName);
                        }
                    }
                    break;

                case 'phone':

                    $valid = PhoneValidator::validate($data, $field, $ruleValue, $ruleName);
                    if (!$valid) {

                        if (empty(self::$validationError[$field])) {
                            self::$validationError[$field] = PhoneValidator::messageCreate($msgs, $field, $ruleValue, $ruleName);
                        }
                    }

                    break;

                case 'unique':
                    if (!empty($data[$field])) {
                        $vals = trim($data[$field]);
                        $data[$field] = preg_replace('/\s+/', ' ', $vals);

                        $sql = '';
                        $sql .= 'SELECT count(' . $column . ')  as count';
                        $sql .= ' FROM ' . $table;
                        $sql .= ' WHERE ' . $column . ' = "' . $data[$field] . '"';
                    }

                    try {
                        if (!empty($data[$field])) {

                            $valid = config::SQLQueries($sql,'unique');
                            // !DB::get()->get->query($sql)->fetch_assoc()['count'];
                        }

                        if (!$valid) {
                            $fieldMsg = isset($msgs[$field]) ? $msgs[$field] : $field;
                            if (empty(self::$validationError[$field])) {
                                self::$validationError[$field] = $fieldMsg . " field must be " . $ruleName;
                            }
                        }
                    } catch (\Exception $e) {
                        $valid = false;
                        $exceptionMsg = $e->getMessage();
                    }
                    break;

                case 'not_in':
                    if (!empty($data[$field])) {
                        $vals = trim($data[$field]);
                        $data[$field] = preg_replace('/\s+/', ' ', $vals);

                        $not = explode('-', explode(',', $column)[1])[0] ?? '';
                        $notValue = explode('-', explode(',', $column)[1])[1] ?? '';
                        if (!empty($not) && !empty($notValue)) {
                            $column = explode(',', $column)[0] ?? $column;
                        }

                        $sql = '';
                        $sql .= 'SELECT count(' . $column . ')  as count';
                        $sql .= ' FROM ' . $table;
                        $sql .= ' WHERE ' . $column . ' = "' . $data[$field] . '"';
                        if (!empty($not) && !empty($notValue)) {
                            $sql .= ' AND `' . $not . '` != "' . $notValue . '"';
                        }
                    }

                    try {
                        if (!empty($data[$field])) {
                            $valid = config::SQLQueries($sql,'not_in');
                            //!DB::get()->get->query($sql)->fetch_assoc()['count'];
                        }

                        if (!$valid) {
                            $fieldMsg = isset($msgs[$field]) ? $msgs[$field] : $field;
                            if (empty(self::$validationError[$field])) {
                                self::$validationError[$field] = $fieldMsg . " field must be " . $ruleName;
                            }
                        }
                    } catch (\Exception $e) {
                        $valid = false;
                        $exceptionMsg = $e->getMessage();
                    }
                    break;

                case 'in':
                    if (!empty($data[$field])) {
                        $keys = !empty($data[$field]) ? $data[$field] : 0;
                        if (is_array($keys)) {
                            $keys = implode(',', $keys);
                        }
                        $sql = '';
                        $sql .= 'SELECT count(' . $column . ')  as count';
                        $sql .= ' FROM ' . $table;
                        $sql .= ' WHERE ' . $column . ' IN ("' . $keys . '")';
                    }

                    try {
                        if (!empty($data[$field])) {
                            $valid = config::SQLQueries($sql,'in');
                            // DB::get()->get->query($sql)->fetch_assoc()['count'];
                        }

                        if (!$valid) {
                            $fieldMsg = isset($msgs[$field]) ? $msgs[$field] : $field;
                            if (empty(self::$validationError[$field])) {
                                self::$validationError[$field] = $fieldMsg . " not exists.";
                            }
                        }
                    } catch (\Exception $e) {
                        $valid = false;
                        $exceptionMsg = $e->getMessage();
                    }
                    break;

                case 'assign':
                    if (!empty($data[$field])) {
                        $keys = !empty($data[$field]) ? $data[$field] : 0;
                        if (is_array($keys)) {
                            $keys = implode(',', $keys);
                        }
                        $sql = '';
                        $sql .= 'SELECT count(' . $column . ')  as count';
                        $sql .= ' FROM ' . $table;
                        $sql .= ' WHERE ' . $column . ' IN ("' . $keys . '")';
                    }

                    try {
                        if (!empty($data[$field])) {
                            $valid = config::SQLQueries($sql,'assign');                            
                            // DB::get()->get->query($sql)->fetch_assoc()['count'];
                        }
                        if ($valid) {
                            $valid = false;
                            $fieldMsg = isset($msgs[$field]) ? $msgs[$field] : $field;

                            if (empty(self::$validationError[$field])) {

                                self::$validationError[$field] = $fieldMsg . " already in used";
                            }
                        }
                    } catch (\Exception $e) {

                        $valid = false;
                        $exceptionMsg = $e->getMessage();
                    }
                    break;

                case 'rgex':

                    $valid = RegxValidator::validate($data, $field, $ruleValue, $ruleName);
                    if (!$valid) {
                        if (empty(self::$validationError[$field])) {
                            self::$validationError[$field] = RegxValidator::messageCreate($msgs, $field, $ruleValue, $ruleName);
                        }
                    }
                    break;

                case 'url':

                    $valid = UrlValidator::validate($data, $field, $ruleValue, $ruleName);
                    if (!$valid) {

                        if (empty(self::$validationError[$field])) {
                            self::$validationError[$field] = UrlValidator::messageCreate($msgs, $field, $ruleValue, $ruleName);
                        }
                    }
                    break;

                case 'email':

                    $valid = EmailValidator::validate($data, $field, $ruleValue, $ruleName);

                    if (!$valid) {

                        if (empty(self::$validationError[$field])) {
                            self::$validationError[$field] = EmailValidator::messageCreate($msgs, $field, $ruleValue, $ruleName);
                        }
                    }

                    break;

                case 'file':

                    $valid = FileValidator::validate($data, $field, $ruleValue, $ruleName);

                    if (!$valid) {
                        self::$validationError = array_merge(self::$validationError, self::$imageError);
                    }

                    break;
                case '.*':

                    break;

                case 'in_array':

                    $valid = InArrayValidator::validate($data, $field, $ruleValue, $ruleName);

                    if (!$valid) {
                        self::$validationError[$field] = InArrayValidator::messageCreate($msgs, $field, $ruleValue, $ruleName);
                    }

                    break;

                case 'nullable':

                    $valid = NullableValidator::validate($data, $field, $ruleValue, $ruleName);

                    if (!$valid) {
                        self::$validationError[$field] = NullableValidator::messageCreate($msgs, $field, $ruleValue, $ruleName);
                    }

                    break;
                default:
                    // Custom rule handling here
                    break;
            }

            if (!$valid) {
                if (!$exceptionMsg) {
                    self::$errors = self::$validationError;
                } else {
                    self::$errors[] = $exceptionMsg;
                }
            }
        }
    }
}

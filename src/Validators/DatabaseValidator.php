<?php

namespace CodeCorner\Validation\Validators;

use CodeCorner\Validation\config\config;

class DatabaseValidator
{
    /**
     * Validate if a field is required and not empty
     *
     * @param  array $data
     * @param  string $field
     * @param  mixed $ruleValue
     * @param  mixed $ruleName
     * @return bool
     */
    public static function validate_IN($data, $field, $ruleValue = '', $ruleName = '', $sql, $DB)
    {
        if (!empty($data[$field])) {

            return config::SQLQueries($DB, $sql, 'in');
        }

        return false;
    }

    /**
     * Validate if a field is required and not empty
     *
     * @param  array $data
     * @param  string $field
     * @param  mixed $ruleValue
     * @param  mixed $ruleName
     * @return bool
     */
    public static function validate_NOTIN($data, $field, $ruleValue = '', $ruleName = '', $sql, $DB)
    {
        if (!empty($data[$field])) {
            return config::SQLQueries($DB, $sql, 'not_in');
        }

        return false;
    }

    /**
     * Validate if a field is required and not empty
     *
     * @param  array $data
     * @param  string $field
     * @param  mixed $ruleValue
     * @param  mixed $ruleName
     * @return bool
     */
    public static function validate_UNIQUE($data, $field, $ruleValue = '', $ruleName = '', $sql, $DB)
    {
        if (!empty($data[$field])) {
            return config::SQLQueries($DB, $sql, 'unique');
        }

        return false;
    }

    /**
     * Validate if a field is required and not empty
     *
     * @param  array $data
     * @param  string $field
     * @param  mixed $ruleValue
     * @param  mixed $ruleName
     * @return bool
     */
    public static function validate_ASSIGN($data, $field, $ruleValue = '', $ruleName = '', $sql, $DB)
    {
        if (!empty($data[$field])) {
            return config::SQLQueries($DB, $sql, 'assign');
        }

        return false;
    }

    /**
     * messageCreate if validation fails
     *
     * @param  array $message
     * @param  string $field
     * @param  mixed $ruleValue
     * @param  mixed $ruleName
     * @return string
     */
    public static function messageCreate($message = array(), $field, $ruleValue = null, $ruleName = null)
    {

        $fieldMsg = isset($message[$field]) ? $message[$field] : $field;
        return $fieldMsg . " not exists.";
    }

    /**
     * messageCreate if validation fails
     *
     * @param  array $message
     * @param  string $field
     * @param  mixed $ruleValue
     * @param  mixed $ruleName
     * @return string
     */
    public static function messageCreate_NOTIN($message = array(), $field, $ruleValue = null, $ruleName = null)
    {

        $fieldMsg = isset($message[$field]) ? $message[$field] : $field;
        return $fieldMsg . " field must be " . $ruleName;
    }

    /**
     * messageCreate if validation fails
     *
     * @param  array $message
     * @param  string $field
     * @param  mixed $ruleValue
     * @param  mixed $ruleName
     * @return string
     */
    public static function messageCreate_UNIQUE($message = array(), $field, $ruleValue = null, $ruleName = null)
    {

        $fieldMsg = isset($message[$field]) ? $message[$field] : $field;
        return $fieldMsg . " field must be " . $ruleName;
    }

    /**
     * messageCreate if validation fails
     *
     * @param  array $message
     * @param  string $field
     * @param  mixed $ruleValue
     * @param  mixed $ruleName
     * @return string
     */
    public static function messageCreate_ASSIGN($message = array(), $field, $ruleValue = null, $ruleName = null)
    {

        $fieldMsg = isset($message[$field]) ? $message[$field] : $field;
        return $fieldMsg . " already in used";
    }
}

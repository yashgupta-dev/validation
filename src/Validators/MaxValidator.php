<?php
namespace CodeCorner\Validation\Validators;

class MaxValidator
{
    /**
     * Validate if a field is required and not empty
     *
     * @param  array $data
     * @param  string $field
     * @return void
     */
    public static function validate($data, $field, $ruleValue)
    {

        if(!empty($data[$field]) && $ruleValue >= mb_strlen($data[$field])) {
            return true;
        }

        return false;
    }
    
    /**
     * messageCreate if validation fails
     *
     * @param  array $message
     * @param  string $field
     * @param  string $ruleValue
     * @return string
     */
    public static function messageCreate($message = array(), $field, $ruleValue) {
        
        $fieldMsg = isset($message[$field]) ? $message[$field] : $field;
        return $fieldMsg . " field must be less than " . $ruleValue;
    }
}

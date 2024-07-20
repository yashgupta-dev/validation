<?php
namespace CodeCorner\Validation\Validators;

class InArrayValidator
{
    /**
     * Validate if a field is required and not empty
     *
     * @param  array $data
     * @param  string $field
     * @param  mixed $ruleValue
     * @param  mixed $ruleName
     * @return void
     */
    public static function validate($data, $field, $ruleValue = '', $ruleName = '')
    {
        if (!empty($data[$field])) {

            if (!in_array($data[$field], explode(',', $ruleValue))) {
                return false;
            }

            return true;
        }
        
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
    public static function messageCreate($message = array(), $field, $ruleValue = null, $ruleName = null) {
        
        $fieldMsg = isset($message[$field]) ? $message[$field] : $field;
        return $fieldMsg . " must be in " . $ruleValue;
    }
}

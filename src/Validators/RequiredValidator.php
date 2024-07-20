<?php
namespace CodeCorner\Validation\Validators;

class RequiredValidator
{
    /**
     * Validate if a field is required and not empty
     *
     * @param  array $data
     * @param  string $field
     * @return void
     */
    public static function validate($data, $field)
    {
        if(!empty($data[$field])) {
            return !empty(trim($data[$field])) && $data[$field] !== '';   
        } else {
            return false;
        }
        
    }
    
    /**
     * messageCreate if validation fails
     *
     * @param  array $message
     * @param  string $field
     * @param  string $ruleName
     * @return string
     */
    public static function messageCreate($message = array(), $field, $ruleName) {
        
        $fieldMsg = isset($message[$field]) ? $message[$field] : $field;
        return $fieldMsg . " field must be " . $ruleName;
    }
}

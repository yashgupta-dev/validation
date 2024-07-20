<?php
namespace CodeCorner\Validation\Validators;

class EqualsValidator
{
    /**
     * Validate if a field is required and not empty
     *
     * @param  array $data
     * @param  string $field
     * @return void
     */
    public static function validate($data, $field, $rule = '')
    {
        if ($data[$field] !== $rule) {

            return false;
        }
        
    }
    
    /**
     * messageCreate if validation fails
     *
     * @param  array $message
     * @param  string $field
     * @param  string $rule
     * @return string
     */
    public static function messageCreate($message = array(), $field, $rule = null) {
        
        $fieldMsg = isset($message[$field]) ? $message[$field] : $field;
        return $fieldMsg . " doesn't matched ". $rule;
    }
}

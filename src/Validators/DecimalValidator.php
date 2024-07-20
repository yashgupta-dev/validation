<?php
namespace CodeCorner\Validation\Validators;

class DecimalValidator
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
        if (!empty($data[$field])) {
            return (is_numeric($data[$field]) && preg_match('/^\d+(\.\d{1,' . $rule . '})?$/', $data[$field])) ? true : false;
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
        return  $fieldMsg . " field must be under " . $rule . " decimal value";
    }
}

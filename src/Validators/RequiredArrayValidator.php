<?php
namespace CodeCorner\Validation\Validators;

class RequiredArrayValidator
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
        $nonEmptyElements = !empty($data[$field]) ? array_filter($data[$field], 'strlen') : [];
        return (empty($nonEmptyElements)) ? false : true;       
        
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
        return $fieldMsg . " is empty or contains only empty elements.";
    }
}

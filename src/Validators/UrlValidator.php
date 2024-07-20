<?php
namespace CodeCorner\Validation\Validators;

class UrlValidator
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
        // Define a regular expression pattern to match the desired URLs
        $pattern = '/^(https?:\/\/)?(www\.)?google\.com/i';
        if (!empty($data[$field])) {
            // Use preg_match to check if the URL matches the pattern
            return preg_match($pattern, $data[$field]);
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
        return $fieldMsg . " must be validated";
    }
}

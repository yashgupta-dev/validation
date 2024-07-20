<?php
namespace CodeCorner\Validation\Validators;

class FileValidator
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
            $fileValidation = explode('&', $ruleValue);
            
            foreach ($fileValidation as $irule) {
                $iruleParts = explode('.', $irule);

                $iruleName = $iruleParts[0];

                $iruleValue = isset($iruleParts[1]) ? $iruleParts[1] : null;

                $errorMsg = self::_imageValidate($iruleName, [$iruleName => $data[$field][$iruleName]], $iruleValue, $field);
                if ($errorMsg) {
                    self::$imageError[$field][$iruleName] = $errorMsg;
                }
            }
        }

        $nonEmptyElements = !empty(self::$imageError[$field]) ? array_filter(self::$imageError[$field], 'strlen') : [];

        return empty($nonEmptyElements) ? true : false;
        
    }

     /**
     * _imageValidate
     *
     * @param  mixed $rulname
     * @param  array $data
     * @param  mixed $ruleValue
     * @param  mixed $key
     * @return array|string
     */
    private static function _imageValidate($rulname, $data = array(), $ruleValue = '', $key = '')
    {
        $valid = true;

        switch ($rulname) {
            case 'size':
                if ($data[$rulname] > $ruleValue) {
                    $valid = false;
                }

                if (!$valid) {
                    $sizeRequired = number_format($ruleValue / 1048576, 2) . ' MB';

                    return $key . " " . $rulname . " must be less then " . $sizeRequired;
                }

                break;

            case 'in_array':

                if (!in_array($data[$rulname], explode(',', $ruleValue))) {
                    $valid = false;
                }

                if (!$valid) {
                    return $key . " " . $rulname . " must be " . $ruleValue;
                }

                break;

            case 'type':

                if (!in_array($data[$rulname], explode(',', $ruleValue))) {
                    $valid = false;
                }

                if (!$valid) {
                    return $key . " " . $rulname . " must be " . $ruleValue;
                }

                break;

            case 'name':
                $extension = pathinfo($data[$rulname], PATHINFO_EXTENSION);
                if (!in_array($extension, explode(',', $ruleValue))) {
                    $valid = false;
                }

                if (!$valid) {
                    return $key . " extension" . " must be " . $ruleValue;
                }

                break;

            default:
                // Custom rule handling here
                break;
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
        return  "Invalid " . $fieldMsg . " number.";
    }
}

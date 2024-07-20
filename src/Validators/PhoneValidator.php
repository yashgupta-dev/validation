<?php
namespace CodeCorner\Validation\Validators;

use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use CodeCorner\Validation\config\config;
use libphonenumber\NumberParseException;

class PhoneValidator
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
        if (!empty($data[$field]) && is_numeric($data[$field])) {

            $phoneNumberUtil = PhoneNumberUtil::getInstance();

            // Phone number and country code to validate
            $phoneNumber = $data[$field];
            $countryCode = !empty($data['phone_code']) ? $data['phone_code'] : config::get('DEFAULT_PHONE_CODE'); // Replace with the country code you want to validate against (e.g., IN for India)

            try {
                // Parse the phone number with the provided country code
                $numberProto = $phoneNumberUtil->parse($phoneNumber, $countryCode);

                // Check if the phone number is valid for the specified country
                if ($phoneNumberUtil->isValidNumberForRegion($numberProto, $countryCode)) {
                    // Format the phone number in E.164 format
                    $formattedNumber = $phoneNumberUtil->format($numberProto, PhoneNumberFormat::E164);
                    return true;
                } else {
                    return false;
                   
                }
            } catch (NumberParseException $e) {
                return false;                
            }
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

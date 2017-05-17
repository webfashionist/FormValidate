<?php


/**
 * Validation class.
 *
 * This class contains all the methods needed for the form validation.
 *
 * @author Bob Schockweiler <info@webfashion.in>
 * @see https://github.com/webfashionist/FormValidate
 * @license https://www.gnu.org/licenses/agpl-3.0-standalone.html AGPL-3.0
 *
 */

namespace FormValidate;

class Form {

    private $data, $rules;
    protected $errors = array();

    function __construct($data = array(), $rules = array()) {
        $this->setData($data);
        $this->setRules($rules);
    }


    /**
    * Set data to validate
    * @param array $data Data (POST or GET)
    * @return bool
    **/
    public function setData($data = array()) {
        if($data && is_array($data) && count($data) > 0) {
            $this->data = $data;
            return true;
        }
        $this->data = array();
        return false;
    }


    /**
    * Set rules to validate data against
    * @param array $rules Rules
    * @return bool
    **/
    public function setRules($rules = array()) {
        if($rules && is_array($rules) && count($rules) > 0) {
            $this->rules = $rules;
            return true;
        }
        $this->rules = array();
        return false;
    }


    /**
    * Returns the errors from validate()
    * @return array
    **/
    public function getErrors() {
        return $this->errors;
    }


    /**
    * Cleans up given value
    * @param mixed $value Value
    * @param string $type Type (string/int)
    * @return mixed
    **/
    public function clean($value, $type = 'string') {
        $type = strtolower($type);
        switch($type) {

            case "string":
                $value = filter_var($value, FILTER_SANITIZE_STRING);
                break;

            case "int":
            case "integer":
            case "number":
                $value = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
                break;

            case "email":
                $value = filter_var($value, FILTER_SANITIZE_EMAIL);
                break;

            default:
                $value = filter_var($value, FILTER_SANITIZE_STRING);
                break;

        }
        return $value;
    }


    /**
    * Validate form fields with the given rules. Returns false if any error occured.
    * @return bool
    **/
    public function validate() {
        if(!$this->data || !is_array($this->data) || count($this->data) === 0) {
            return false;
        }
        // reset errors
        $this->errors = array();



        // loop through fields
        foreach($this->rules as $name => $rules) {
            $value = (isset($this->data[$name]) ? $this->data[$name] : '');
            if($rules) {
                // rule exists - loop through rules
                foreach($rules as $rule => $condition) {

                    switch($rule) {
                        case "required":
                            if($condition === true && !$this->required($value)) {
                                // field is required
                                $this->errors[] = 'Please fill out the '.$this->rules[$name]["label"].' field.';
                            }
                            break;

                        case "empty":
                            if($condition === true && !$this->empty($value)) {
                                // field must be empty
                                $this->errors[] = 'The '.$this->rules[$name]["label"].' field must be empty.';
                            }
                            break;

                        case "email":
                            if($condition === true && !$this->email($value)) {
                                // field must be a valid email
                                $this->errors[] = 'Please enter a valid email address.';
                            }
                            break;

                       	case "alphabetical":
                       		if($condition === true && !$this->alphabetical($value)) {
                       			// field must be alphabetical
                       			$this->errors[] = 'Please use only alphabetical characters for the '.$this->rules[$name]["label"].' field.';
                       		}
                            break;

                        case "phone":
                            if($condition === true && !$this->phone($value)) {
                                // field must be a valid phone number
                                $this->errors[] = 'Please enter a valid phone number.';
                            }
                            break;

                        case "number":
                        case "int":
                        case "integer":
                            if($condition === true && !is_numeric($value)) {
                                // field must be a number
                                $this->errors[] = 'Please enter a number for the '.$this->rules[$name]["label"].' field.';
                            }
                            break;

                        case "minlength":
                            if(!$this->minlength($value, $condition)) {
                                // field must have a minimal length
                                $this->errors[] = 'The '.$this->rules[$name]["label"].' field must be at least '.$condition.' characters long.';
                            }
                            break;

                        case "maxlength":
                            if(!$this->maxlength($value, $condition)) {
                                // field must have a minimal length
                                $this->errors[] = 'The '.$this->rules[$name]["label"].' field must be '.$condition.' characters long at maximum.';
                            }
                            break;

                        default:
                            break;
                    }
                }
            }
        }

        // check if any errors occurs
        if($this->errors && is_array($this->errors) && count($this->errors) > 0) {
            // errors did occur
            return false;
        }
        return true;
    }


    /**
    * Check if value is alphabetical
    * @param string $value Value
    * @return bool
    **/
    protected function alphabetical($value) {
        return preg_match("/^[ äöüèéàáíìóòôîêÄÖÜÈÉÀÁÍÌÓÒÔÊa-z]{2,}$/is", $value);
    }

    /**
    * Check if value as a minimal length
    * @param string $value Value
    * @param int $length Length
    * @return bool
    **/
    protected function minlength($value, $length) {
        return (strlen($value) >= $length);
    }


    /**
    * Check if value as a maximal length
    * @param string $value Value
    * @param int $length Length
    * @return bool
    **/
    protected function maxlength($value, $length) {
        return (strlen($value) <= $length);
    }


    /**
    * Check if value is a valid email
    * @param string $value Email
    * @return bool
    **/
    protected function email($value) {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }


    /**
    * Check if value is a valid phone number
    * @param string $value Email
    * @return bool
    **/
    protected function phone($value) {
        return preg_match("/^[+]?[- \(\)\/0-9]{3,18}$/is", $value);
    }


    /**
    * Check if value is empty
    * @param string $value Value
    * @return bool
    **/
    protected function empty($value) {
        return empty($value);
    }


    /**
    * Check if value is set
    * @param string $value Value
    * @return bool
    **/
    protected function required($value) {
        return !empty(trim($value));
    }
}
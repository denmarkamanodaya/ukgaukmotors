<?php namespace Quantum\base\Services;

use Illuminate\Validation\Validator;

/**
 * Class CustomValidator
 * @package App\Services
 */
class CustomValidator extends Validator {

    private $_custom_messages = array(
        "alpha_dash_spaces" => "The :attribute may only contain letters, spaces, and dashes.",
        "alpha_num_spaces" => "The :attribute may only contain letters, numbers, and spaces.",
    );

    public function __construct( $translator, $data, $rules, $messages = array(), $customAttributes = array() ) {
        parent::__construct( $translator, $data, $rules, $messages, $customAttributes );
        $this->_set_custom_stuff();
    }

    /**
     * Setup any customizations etc
     *
     * @return void
     */
    protected function _set_custom_stuff() {
        //setup our custom error messages
        $this->setCustomMessages( $this->_custom_messages );
    }

    /**
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return int
     */
    public function validateAlphaSpaces($attribute, $value, $parameters)
    {
        return preg_match('#^[a-z0-9\x20-]+$#i', $value);
    }

    /**
     * @param $message
     * @param $attribute
     * @param $rule
     * @param $parameters
     * @return mixed
     */
    public function replaceAlphaSpaces($message, $attribute, $rule, $parameters)
    {
        return str_replace('validation.alpha_spaces', 'This may only contain letters, numbers, and spaces.', $message);
    }

    /**
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return int
     */
    public function validateTextArea($attribute, $value, $parameters)
    {
        return preg_match('#^[A-Za-z0-9\-! ,\'\"\/@\.:\(\)]+$#i', $value);
    }

    /**
     * @param $message
     * @param $attribute
     * @param $rule
     * @param $parameters
     * @return mixed
     */
    public function replaceTextArea($message, $attribute, $rule, $parameters)
    {
        return str_replace('validation.text_area', 'This may only contain letters, numbers, and spaces.', $message);
    }

    /**
     * @param $attribute
     * @param $value
     * @param $parameters
     * @return bool
     */
    public function validateRequiredCategoryArray($attribute, $value, $parameters)
    {
        $valid = false;
        if(is_array($value))
        {
            foreach($value as $category)
            {
                if(is_numeric($category)){ $valid = true; }
            }
        }
        return $valid;
    }

    /**
     * @param $message
     * @param $attribute
     * @param $rule
     * @param $parameters
     * @return mixed
     */
    public function replaceRequiredCategoryArray($message, $attribute, $rule, $parameters)
    {
        return str_replace('validation.RequiredCategoryArray', 'An auction category is required.', $message);
    }

    public function validateTelephone($attribute, $value, $parameters)
    {
        return preg_match('#^([0-9\(\)\/\+ \-\#]*)$#i', $value);
    }

    /**
     * @param $message
     * @param $attribute
     * @param $rule
     * @param $parameters
     * @return mixed
     */
    public function replaceTelephone($message, $attribute, $rule, $parameters)
    {
        return str_replace('validation.telephone', 'This may only contain numbers, spaces, -, +, ( ), #.', $message);
    }

    public function validateRequiredArray($attribute, $value, $parameters)
    {
        $valid = false;
        if(is_array($value))
        {
            $count = count($value);
            if($count > 0) $valid = true;
        }
        return $valid;
    }

    /**
     * @param $message
     * @param $attribute
     * @param $rule
     * @param $parameters
     * @return mixed
     */
    public function replaceRequiredArray($message, $attribute, $rule, $parameters)
    {
        return str_replace('validation.RequiredArray', 'No option selected.', $message);
    }

    public function validateThemeExists($attribute, $value, $parameters)
    {
        $viewPath = \Config::get('view.paths');
        $area = isset($parameters[0]) ? $parameters[0] : 'public';
        return file_exists($viewPath[0].'/Theme/'.$value.'/'.$area.'/Template.blade.php');
    }

    public function replaceThemeExists($message, $attribute, $rule, $parameters)
    {
        $area = isset($parameters[0]) ? $parameters[0] : 'public';
        return str_replace('validation.theme_exists', 'Theme does not exist.', $message);
    }

    /**
     * Allow only alphabets, spaces and dashes (hyphens and underscores)
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    protected function validateAlphaDashSpaces( $attribute, $value ) {
        return (bool) preg_match( "/^[A-Za-z\s-_]+$/", $value );
    }

    /**
     * Allow only alphabets, numbers, and spaces
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    protected function validateAlphaNumSpaces( $attribute, $value ) {
        return (bool) preg_match( "/^[A-Za-z0-9\s]+$/", $value );
    }

}
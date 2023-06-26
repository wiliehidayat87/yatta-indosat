<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Form
{
    public $errorLabel;
    public $errorList;

    public function __construct() {
        $this->errorLabel = array();
        $this->errorList  = array();
    }

    public function hasError() {
        return (bool) count($this->errorList);
    }

    public function storeError($label, $message) {
        $this->errorLabel[] = $label;
        $this->errorList[]  = $message;
    }

    public function parseErrorList() {
        if (true !== empty($this->errorList) && true !== empty($this->errorLabel)) {
            $result = '<ul class="error_text">';

            for ($i = 0; $i < count($this->errorList); $i++) {
                $result .= '<li><label for="' . $this->errorLabel[$i] . '">' . $this->errorList[$i] . '</label></li>';
            }

            return $result . '</ul>';
        }

        return;
    }
}


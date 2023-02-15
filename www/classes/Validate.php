<?php
declare(strict_types=1);

class Validate
{
    private $_passed = false,
            $_errors = array(),
            $_db = null;
    public function __construct()
    {
        $this->_db = DB::getInstance();
    }
    public function getDB()
    {
        return $this->_db;
    }
    public function check($source, $items = array())
    {
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $rule_value) {
                $value = trim($source[$item]);
                $item = escape($item);

                if ($rule === 'required' && empty($value) && $rule_value) {
                    $this->_addError("{$item} je potreban");
                } elseif (!empty($value)) {
                    switch ($rule) {
                        case 'min':
                            if (strlen($value) < $rule_value) {
                                $this->_addError("{$item} mora najmanje biti {$rule_value} karaktera.");
                            }
                            break;
                        case 'max':
                            if (strlen($value) > $rule_value) {
                                $this->_addError("{$item} moze najvise biti {$rule_value} karaktera.");
                            }
                            break;
                        case 'matches':
                            if ($value != $source[$rule_value]) {
                                $this->_addError("{$rule_value} mora biti isto {$item}.");
                            }
                            break;
                        case 'unique':
                            $check = $this->_db->get($rule_value, array($item, '=', $value));
                            if ($check->count()) {
                                $this->_addError("{$item} vec postoji.");
                            }
                            break;
                        case 'numeric':
                            if (!is_numeric($value) && $rule_value) {
                                $this->_addError("{$item} mora biti broj.");
                            }
                            break;
                    }
                }
            }
        }

        if (empty($this->_errors)) {
            $this->_passed = true;
        }
        return $this;
    }

    private function _addError($error)
    {
        $this->_errors[] = $error;
    }

    public function errors()
    {
        return $this->_errors;
    }

    public function passed()
    {
        return $this->_passed;
    }
}

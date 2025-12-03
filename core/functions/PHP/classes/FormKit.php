<?php

class FormKit
{
    private $fields = [];
    private $errors = [];
    private $sanitizedData = [];

    public function __construct($config)
    {
        foreach ($config as $fieldName => $rules) {
            $this->fields[$fieldName] = $rules;
        }
    }

    public function validate($data, callable $callback = null)
    {
        $asyncQueue = [];
        foreach ($this->fields as $fieldName => $rules) {
            $value = $data[$fieldName] ?? null;

            if ($value && isset($rules['sanitize'])) {
                $value = $this->applySanitization($value, $rules['sanitize']);
            }
            $this->sanitizedData[$fieldName] = $value;

            if (isset($rules['required']) && $rules['required'] && empty($value)) {
                if (!isset($this->errors[$fieldName])) {
                    $this->errors[$fieldName] = [];
                }
                $this->errors[$fieldName][] = isset($rules['messages']['required']) ? $rules['messages']['required'] : 'This field is required.';
                continue;
            }

            if (isset($rules['validate'])) {
                foreach ($rules['validate'] as $validationRule => $params) {
                    $messages = isset($rules['messages']) ? $rules['messages'] : [];
                    $this->applyValidation($fieldName, $value, $validationRule, $params, $messages);
                }
            }

            if (isset($rules['async'])) {
                $asyncQueue[] = function() use ($fieldName, $value, $rules) {
                    $this->asyncValidate($fieldName, $value, $rules['async'], $rules['messages']);
                };
            }
        }

        if (!empty($asyncQueue)) {
            foreach ($asyncQueue as $task) {
                $task();
            }
        }

        $result = empty($this->errors);

        if ($callback) {
            $callback($result);
        }

        return $result;
    }

    public function asyncValidate($fieldName, $value, $rule, $messages)
    {
        $isValid = $rule($value);

        if (!$isValid) {
            if (!isset($this->errors[$fieldName])) {
                $this->errors[$fieldName] = [];
            }
            $this->errors[$fieldName][] = $messages['async'] ?? 'Invalid ' . $fieldName;
        }
    }

    private function applyValidation($fieldName, $value, $rule, $params, $messages)
    {
        $isValid = true;
        switch ($rule) {
            case 'email':
                $isValid = Validation::isEmail($value);
                break;
            case 'minLength':
                $isValid = Validation::isMinTextLength($value, $params);
                break;
            case 'maxLength':
                $isValid = Validation::isTextLength($value, $params);
                break;
            case 'numeric':
                $isValid = Validation::isNumber($value);
                break;
        }

        if (!$isValid) {
            if (!isset($this->errors[$fieldName])) {
                $this->errors[$fieldName] = [];
            }
            $this->errors[$fieldName][] = $messages[$rule] ?? 'Invalid ' . $fieldName;
        }
    }


    private function applySanitization($value, $sanitizationRule)
    {
        switch ($sanitizationRule) {
            case 'string':
                return SecurityV2::sanitizeString($value);
            case 'email':
                return SecurityV2::sanitizeEmail($value);
            case 'int':
                return SecurityV2::sanitizeInt($value);
            case 'url':
                return SecurityV2::sanitizeUrl($value);
            default:
                return $value;
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getError($fieldName)
    {
        return isset($this->errors[$fieldName]) ? $this->errors[$fieldName][0] : null;
    }

    public function getSanitizedData()
    {
        return $this->sanitizedData;
    }
}

<?php

/**
 * Check if string is JSON
 * @param $str
 * @return bool
 */
function is_json($str): bool
{
    if (!is_string($str)) {
        return false;
    }
    try {
        $result = json_decode($str);
        return (json_last_error() == JSON_ERROR_NONE && (is_object($result) || is_array($result)));
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Generate url string
 * @param string $str
 * @return string
 */
function generateUrl(string $str): string
{
    return mb_strtolower(trim(preg_replace('/\-+/', '-', preg_replace('/[^a-zA-Z0-9_-]+/', '-', Str::ascii($str))), '-'));
}

/**
 * Check the value
 * @param $value
 * @return bool
 */
function checkboxResponseToBool($value): bool
{
    return in_array('' . $value, ['1', 'true', 'on']);
}

/**
 * Uppercase first letter
 *
 * @param string|null $str
 * @param string $encoding
 * @return string
 */
function mb_ucfirst(?string $str = null, string $encoding = 'UTF-8'): string
{
    if (!$str) {
        $str = '';
    }
    $firstChar = mb_substr($str, 0, 1, $encoding);
    return mb_strtoupper($firstChar, $encoding) . mb_substr($str, 1, null, $encoding);
}
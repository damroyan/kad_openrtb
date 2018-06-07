<?php

/**
 * Параметр в int
 *
 * @param int $int
 * @param null $default
 * @param null $min
 * @param null $max
 * @return int|null
 */
function params_int_limit($int = null, $default = null, $min = null, $max = null) {
    if(is_numeric($int)) {
        $int = intval($int);
    }
    else {
        $int = null;
    }

    if(!is_null($min) && $int < $min) {
        $int = null;
    }

    if(!is_null($max) && $int > $max) {
        $int = null;
    }

    if(!is_null($default) && is_null($int)) {
        $int = $default;
    }

    return $int;
}

/**
 * Обработка параметра int
 *
 * @param $int
 * @param bool|false $positive
 * @param bool|false $notzero
 * @return int|null
 */
function params_int($int, $positive = false, $notzero = false, $returnValue = null) {
    $int = (int)$int;

    if($positive && $int < 0) {
        return $returnValue;
    }

    if($notzero && $int === 0) {
        return $returnValue;
    }

    return $int;
}

/**
 * Отображаем только нужные поля
 *
 * @param array $array
 * @param array $fields
 * @return array
 */
function array_fields($array = [], array $fields = []) {
    return array_filter(
        $array,
        function($key) use ($fields) {
            if(in_array($key, $fields)) {
                return true;
            }

            return false;
        },
        ARRAY_FILTER_USE_KEY
    );
}

/**
 * Удаляем ненужные поля из массива
 *
 * @param array $array
 * @param array $fields
 * @return array
 */
function array_unset_fields($array = [], array $fields = []) {
    return array_filter(
        $array,
        function($key) use ($fields) {
            if(in_array($key, $fields)) {
                return false;
            }

            return true;
        },
        ARRAY_FILTER_USE_KEY
    );
}

/**
 * Является ли объект Phalcon\Http\Response
 *
 * @param $obj
 * @return bool
 */
function is_http_response($obj) {
    return ((is_object($obj) && get_class($obj) == 'Phalcon\Http\Response') || is_null($obj)) ? true : false;
}

/**
 * Фильтрует email или NULL
 *
 * @param $email
 * @return null|string
 */
function params_email_or_null($email) {
    $value = trim($email);

    if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return mb_strtolower($email);
    }
    else {
        return null;
    }
}

/**
 * Обрадотчик для получение значений ОТ и ДО
 *
 * @param null $from
 * @param null $to
 * @return array
 */
function params_int_from_to($from = null, $to = null) {
    $tmpFrom    = !is_null($from)   && (int)$from > 0   ? (int)$from    : null;
    $tmpTo      = !is_null($to)     && (int)$to > 0     ? (int)$to      : null;

    if($tmpFrom) {
        if($tmpFrom && $tmpTo) {
            $from = $tmpFrom < $tmpTo ? $tmpFrom : $tmpTo;
        }
        else {
            $from = $tmpFrom;
        }
    }
    else {
        $from = null;
    }

    if($tmpTo) {
        if($tmpFrom && $tmpTo) {
            $to = $tmpTo > $tmpFrom ? $tmpTo : $tmpFrom;
        }
        else {
            $to = $tmpTo;
        }
    }
    else {
        $to = null;
    }

    return array($from, $to);
}

/**
 * Обработчик для получения значения или NULL
 *
 * @param $value
 * @param null $filter
 * @param null $default
 * @return null|string
 */
function params_has_or_null($value, $filter = null, $default = null) {
    $value = trim($value);

    if(is_bool($filter)) {
        if($filter === true) {
            return $value ? (string)$value : $default;
        }
        else {
            return $default;
        }
    }
    elseif ((string)$value === '0') {
        return '0';
    }
    else {
        return $value ? (string)$value : $default;
    }
}

/**
 * Обработчик для получения значения или ''
 *
 * @param $value
 * @param null $filter
 * @return null|string
 */
function params_has_or_blank($value, $filter = null) {
    return params_has_or_null($value, $filter, '');
}

/**
 * Обработчик для получения boolean значения
 *
 * @param $value
 * @param null $filter
 * @param bool $null
 * @return bool|null
 */
function params_bool($value, $filter = null, $null = true) {
    if(is_bool($filter)) {
        if($filter === true) {
            return $value ? true : false;
        }
        else {
            return $null ? null : false;
        }
    }
    else {
        return $value ? true : false;
    }
}

function params_date_or_null($value) {
    $timestamp = strtotime($value);

    if(!$timestamp || $timestamp <= 0) {
        return null;
    }

    return date('Y-m-d', $timestamp);
}

function params_datetime_or_null($value) {
    $timestamp = strtotime($value);

    if(!$timestamp || $timestamp <= 0) {
        return null;
    }

    return date('Y-m-d H:i:s', $timestamp);
}

function params_sql_bool($value, $filter = null, $null = true) {
    $return = params_bool($value, $filter, $null);

    if(is_bool($return)) {
        return $return ? 1 : 0;
    }

    return null;
}

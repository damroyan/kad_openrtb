<?php
namespace Tizer;

class Response {
    const RESPONSE_ERROR = 'error';
    const RESPONSE_SUCCESS = 'ok';

    static private $_fields = array();
    static private $_header = array();

    /**
     * Очистка данных о полях с ошибками
     */
    static public function FieldsClear() {
        self::$_fields = array();
    }

    /**
     * Добавление ошибки для поля
     *
     * @param $field
     * @param $error
     */
    static public function FieldsAdd($field, $error) {
        self::$_fields[$field] = $error;
    }

    /**
     * Добавление ошибки для поля и возрат ошибки
     *
     * @param $field
     * @param $error
     * @param null $code
     * @param string $msg
     * @param null $e
     * @return \Phalcon\Http\Response
     */
    static public function FieldsAddAndStop($field, $error, $code = null, $msg = '', $e = null) {
        self::FieldsAdd($field, $error);
        return self::Error($code, $msg, $e);
    }

    /**
     * Есть ли поля с ошибками
     *
     * @return bool
     */
    static public function FieldsError() {
        return count(self::$_fields) ? true : false;
    }

    /**
     * Очистка дополнительных заголовков
     */
    static public function ClearHeader() {
        self::$_header = array();
    }

    /**
     * Добавление заголовка
     *
     * @param $name
     * @param $value
     */
    static public function AddHeader($name, $value) {
        if(!isset($_header[$name])) {
            $_header[$name] = array();
        }

        self::$_header[$name][] = $value;
    }

    /**
     * Возврат Phalcon\Http\Response с JSON
     *
     * @param array $array
     * @param null $code
     * @return \Phalcon\Http\Response
     */
    public function result($array = array(), $code = null, $obj = false) {
        $response = new \Phalcon\Http\Response();

        if (!preg_match('@MSIE@ui', $_SERVER['HTTP_USER_AGENT'])) {
            $response->setHeader("Content-Type", "application/json");
        }

        if(count(self::$_header)) {
            foreach(self::$_header as $name => $values) {
                foreach($values as $value) {
                    $response->setHeader($name, $value);
                }
            }
        }

        $callback = null;
        if(isset($_GET['callback']) && preg_match('@^[a-z]([a-z0-9_]+)?$@ui', $_GET['callback'])) {
            $callback = $_GET['callback'];
        }

        if($code) {
            $code = (int)$code;
            switch($code) {
                case 400:
//                    $response->setStatusCode($code, "Bad Request");
                    break;

                case 401:
//                    $response->setStatusCode($code, "Unauthorized");
                    break;

                case 403:
//                    $response->setStatusCode($code, "Forbidden");
                    break;

                case 404:
//                    $response->setStatusCode($code, "Not Found");
                    break;

                case 500:
//                    $response->setStatusCode($code, "Internal Server Error");
                    break;

                case 503:
//                    $response->setStatusCode($code, "Service Temporarily Unavailable");
                    break;

                case 550:
//                    $response->setStatusCode($code, "Permission denied");
                    break;

                default:
                    break;
            }
        }

        $response->setContent(
            ($callback ? "{$callback}(" : "") .
                json_encode(
                    $array,
                    ($obj ? JSON_FORCE_OBJECT | JSON_PRETTY_PRINT : JSON_PRETTY_PRINT)
                ) .
                ($callback ? ");" : "")
        );

        return $response;
    }

    /**
     * Вернуть статус OK и данные ответа в 'result'
     *
     * @param array $array
     * @return \Phalcon\Http\Response
     */
    static public function Ok($array = array(), $obj = false, $resultOnly = false) {
        if($resultOnly) {
            return (new self())->result($array, null, $obj);
        }

        return (new self())->result([
            'response' => self::RESPONSE_SUCCESS,
            'result' => $array,
        ], null, $obj);
    }

    /**
     * Вернуть статус Error и описание ошибки
     *
     * @param null $code
     * @param string $msg
     * @param \Exception $e
     * @return \Phalcon\Http\Response
     */
    static public function Error($code = null, $msg = '', $e = null) {
        $array = array(
            'response' => self::RESPONSE_ERROR,
        );

        if(self::FieldsError()) {
            $array['error_fields'] = self::$_fields;
        }

        if($code) {
            $array['error_code'] = $code;
        }

        if($msg) {
            $array['error_msg'] = $msg;
        }

        if($e) {
            $array['error_exception'] = array(
                'code'  => $e->getCode(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
                'msg'   => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            );
        }

        return (new self())->result($array, $code);
    }
}
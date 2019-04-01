<?php
/**
 * Created by PhpStorm.
 * User: sergi
 * Date: 2019-04-01
 * Time: 19:38
 */

namespace App\Domain\Core;

abstract class AbstractOutput
{
    const CODE_OK = 200;
    const CODE_CREATED = 201;
    const CODE_ACCEPTED = 202;
    const CODE_EMPTY = 204;
    const CODE_BAD_REQUEST = 400;
    const CODE_UNAUTHORIZED = 401;
    const CODE_FORBIDDEN = 403;
    const CODE_NOT_FOUND = 404;
    const CODE_UNPROCESSABLE = 422;
    const CODE_FAILED_DEPENDENCY = 424;
    const CODE_SYSTEM = 500;

    protected $errors = array();

    protected $output = array();

    /**
     * @param string $message
     * @param string $context
     * @param int $code
     */
    public function addError($message, $context = "", $code = self::CODE_BAD_REQUEST)
    {
        $this->errors['data'] = [];
        $this->errors['metadata'][] = array('message' => $message, 'context' => $context, 'code' => $code);
    }

    /**
     * @return boolean
     */
    public function hasErrors()
    {
        return count($this->errors) > 0;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    public function init()
    {
        $this->output['data'] = [];
        $this->output['metadata'] = [];
    }


    abstract public function execute(array $data = array());

}
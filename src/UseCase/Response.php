<?php

/**
 * This file is part of the Utils package.
 *
 * (c) Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 */

namespace Utils\UseCase;

/**
 * Class Response
 * error: id, code, title, detail, source (pointer, parameter)
 *
 * @package Utils
 * @author  Lorenzo Marzullo <marzullo.lorenzo@gmail.com>
 * @link    https://github.com/lorenzomar/utils
 */
class Response extends AbstractRequestResponse
{
    const STATUS_SUCCESS = 'success';
    const STATUS_ERROR   = 'error';

    /**
     * @var string
     */
    protected $status;

    /**
     * setError
     *
     * @param string      $key
     * @param string      $code
     * @param null|string $title
     * @param null|string $detail
     * @param array       $meta
     *
     * @return static
     */
    public function setError($key, $code, $title = null, $detail = null, array $meta = [])
    {
        $this->setAsError();

        $v   = $this->get($key, []);
        $v[] = [
            'code'   => $code,
            'title'  => $title,
            'detail' => $detail,
            'meta'   => $meta,
        ];

        return $this->set($key, $v);
    }

    /**
     * setGeneralError
     *
     * @param string      $code
     * @param null|string $title
     * @param null|string $detail
     * @param array       $meta
     *
     * @return static
     */
    public function setGeneralError($code, $title = null, $detail = null, array $meta = [])
    {
        return $this->setError("general", $code, $title, $detail, $meta);
    }

    /**
     * setGeneralErrorByException
     *
     * @param string      $code
     * @param \Exception  $e
     * @param null|string $title
     *
     * @return static
     */
    public function setGeneralErrorByException($code, \Exception $e, $title = null)
    {
        return $this->setGeneralError(
            $code,
            $title,
            $e->getMessage(),
            [
                'message' => $e->getMessage(),
                'code'    => $e->getCode(),
                'trace'   => $e->getTraceAsString(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]
        );
    }

    /**
     * getError.
     *
     * @param string      $key
     * @param null|string $code
     * @param mixed       $default
     *
     * @return mixed
     */
    public function getError($key, $code = null, $default = null)
    {
        $errors = $this->get($key, $default);

        if ($errors === $default) {
            return $default;
        }

        if ($code === null) {
            return $errors;
        }

        foreach ($errors as $error) {
            if ($error['code'] === $code) {
                return $error;
            }
        }

        return $default;
    }

    /**
     * getGeneralError.
     *
     * @param string $code
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getGeneralError($code, $default = null)
    {
        return $this->getError("general", $code, $default);
    }

    /**
     * hasError.
     *
     * @param string      $key
     * @param null|string $code
     *
     * @return bool
     */
    public function hasError($key, $code = null)
    {
        return $this->getError($key, $code, null) !== null;
    }

    /**
     * hasGeneralError
     *
     * @param string $code
     *
     * @return bool
     */
    public function hasGeneralError($code)
    {
        return $this->hasError("general", $code);
    }

    /**
     * setAsError.
     *
     * @return static
     */
    public function setAsError()
    {
        $this->status = static::STATUS_ERROR;

        return $this;
    }

    /**
     * setAsSuccess.
     *
     * @return static
     */
    public function setAsSuccess()
    {
        $this->status = static::STATUS_SUCCESS;

        return $this;
    }

    /**
     * isError.
     *
     * @return bool
     */
    public function isError()
    {
        return $this->status === static::STATUS_ERROR;
    }

    /**
     * isSuccess.
     *
     * @return bool
     */
    public function isSuccess()
    {
        return $this->status === static::STATUS_SUCCESS;
    }
}
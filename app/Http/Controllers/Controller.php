<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    protected $validateMessages = [
        'required' => ':attribute 不能为空',
        'max' => ':attribute 超出允许最大值',
        'min' => ':attribute 超出允许最小值',
        'in' => ':attribute 无效',
        'numeric' => ':attribute 须要为数值',
        'array' => ':attribute 期望值为数组',
        'date_format' => ':attribute 时间格式为 2017-02-07',
    ];

    private $statusCode = 200;
    private $code = 0;
    private $message = '';
    private $content;
    private $contentEncrypt;


    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode = 0)
    {
        $this->statusCode = (int)$statusCode;
        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code = 0)
    {
        $this->code = (int)$code;
        return $this;
    }

    public function getMessage()
    {
        return [
            'code' => $this->code,
            'message' => $this->message,
            'content' => $this->content ? $this->content : '',
            'contentEncrypt' => $this->contentEncrypt ? $this->contentEncrypt : ''
        ];
    }

    public function setMessage($message = '')
    {
        $this->message = trim($message);
        return $this;
    }

    public function setError($message = '', $code = '')
    {
        $this->setMessage($message)->setCode($code);
        return $this;
    }

    public function setContent($key = '', $value = '')
    {
        if (!$key) {
            return $this;
        }
        $this->content[$key] = $value;
        return $this;
    }

    public function setKeyContent( $value = '')
    {
        $this->content = $value;
        return $this;
    }

    public function response()
    {
        return response()
            ->json($this->getMessage());
            /*->setJsonOptions(
                env('APP_ENV') == 'prod'
                    ? JSON_FORCE_OBJECT
                    : JSON_FORCE_OBJECT
                    | JSON_UNESCAPED_UNICODE
                    | JSON_UNESCAPED_SLASHES
                    | JSON_PRETTY_PRINT
            )
			->withHeaders([
				'Access-Control-Allow-Origin' => '*'
			])*/;
    }
    public function responseArray()
    {
        return response()
            ->json($this->getMessage());
            /*->setJsonOptions(
                env('APP_ENV') == 'prod'
                    ? JSON_OBJECT_AS_ARRAY
                    : JSON_OBJECT_AS_ARRAY
                    | JSON_UNESCAPED_UNICODE
                    | JSON_UNESCAPED_SLASHES
                    | JSON_PRETTY_PRINT
            )/*
			->withHeaders([
				'Access-Control-Allow-Origin' => '*'
			])*/;
    }
}

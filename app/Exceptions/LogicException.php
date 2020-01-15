<?php

namespace App\Exceptions;

use Exception;

class LogicException extends Exception
{
    const HTTP_OK = 200;
    const HTTP_UNPROCESSABLE = 422;
    const STATUS_OK = 0;

    const UNIQUE_FIELD_NAME_EMPTY = 21010;
    const UNIQUE_FIELD_NAME_DISALLOW = 21011;
    const UNIQUE_FIELD_VALUE_EMPTY = 21020;
    const UNIQUE_FIELD_VALUE_PRESENT = 21029;

    public static $codeTexts = [
        self::UNIQUE_FIELD_NAME_EMPTY => '唯一字段名不能为空',
        self::UNIQUE_FIELD_NAME_DISALLOW => '该字段不允许唯一检测',
        self::UNIQUE_FIELD_VALUE_EMPTY => '字段值不能为空',
        self::UNIQUE_FIELD_VALUE_PRESENT => '字段值已存在',
    ];

    protected $data;
    protected $http_code;
    protected $error_code;

    /**
     * 构造函数
     * @Author   zhanghong(Laifuzi)
     * @DateTime 2020-01-14
     * @param    int                $error_code 返回数据
     * @param    int                $http_code  错误码
     * @param    array              $meta 除message和code之外，还需要返回值
     */
    public function __construct(int $error_code, int $http_code = self::HTTP_UNPROCESSABLE, array $meta = [])
    {
        $this->meta = $meta;
        $this->http_code = $http_code;
        $this->error_code = $error_code;
    }

    public function render()
    {
        $status_codes = $this->httpStatusCodes();
        $status  = in_array($this->http_code, $status_codes) ? $this->http_code : self::HTTP_OK;

        $texts = self::$codeTexts;
        $message = $texts[$this->error_code]?: '访问页面异常';

        $content = [
            'code' => $this->error_code,
            'message' => $message,
            'meta' => $this->meta,
        ];

        if (request()->expectsJson()) {
            return response()->json($content, $status);
        }

        return view('pages.error', ['message' => $content['message']]);
    }

    public function httpStatusCodes()
    {
        $objClass = new \ReflectionClass(\Symfony\Component\HttpFoundation\Response::class);
        // 此处获取类中定义的全部常量 返回的是 [key=>value,...] 的数组,key是常量名,value是常量值
        return array_values($objClass->getConstants());
    }
}

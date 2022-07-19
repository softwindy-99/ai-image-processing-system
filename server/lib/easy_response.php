<?php
/*
 * @Description: 自用web响应类
 * @Author: Softwindy
 * @Date: 2021-12-01 08:00:00
 */

class easy_response
{
    /* 成员变量 */
    public string $content_type;
    public string $message = "none";
    public string $status = "defeat";
    public $result;
    public int $code = 100;

    /* 构造函数
     *@method __construct
     *@for easy_response
     *@return {object}
     */
    public function __construct()
    {
    }

    /* 设置 Content-type
     *@method set_content_type
     *@for easy_response
     *@param {string} $mime
     *@param {string} $charset
     *@return void
     */
    public function set_content_type(string $mime, string $charset): void
    {
        header("Content-type: " . $mime . ";" . $charset);
    }

    /* 设置 Set-Cookie
     *@method set_cookie
     *@for easy_response
     *@param {string} key
     *@param {string} value
     *@return void
     */
    public function set_cookie(string $key, string $value): void
    {
        header("Set-Cookie: " . $key . "=" . $value);
    }

    /* 设置 code
     *@method set_code
     *@for easy_response
     *@param {int} $code
     *@return void
     */
    public function set_code(int $code): void
    {
        $this->code = $code;
    }

    /* 设置 status
     *@method set_status
     *@for easy_response
     *@param {bool} $status
     *@return void
     */
    public function set_status(bool $status): void
    {
        if ($status)
            $this->status = "success";
        else
            $this->status = "defeat";
    }

    /* 设置 message
     *@method set_message
     *@for easy_response
     *@param {string} $message
     *@return void
     */
    public function set_message(string $message): void
    {
        $this->message = $message;
    }

    /* 设置 result
     *@method set_result
     *@for easy_response
     *@param {string} $message
     *@return void
     */
    public function set_result(array $result): void
    {
        $this->result = $result;
    }

    /* 响应
     *@method response
     *@for easy_response
     *@param socket
     *@return object
     */
    public function response(): void
    {
        $reponse_body["code"] = $this->code;
        $reponse_body["status"] = $this->status;
        $reponse_body["message"] = $this->message;
        $reponse_body["result"] = $this->result;
        echo json_encode($reponse_body);
    }
}

<?php
/*
 * @Description: 公用函数库
 * @Author: Softwindy
 * @Date: 2021-12-01 08:00:00
 */

/* 获取长度为 n 的随机字符串
 *@method get_randstring
 *@for null
 *@param {number} $length 要获得随机字符串的长度
 *@param {string} $range 随机字符范围
 *@return {string}
 */
function get_randstring($length, $range = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"): string
{
    $randstr = "";
    for ($i = 0; $i < $length; $i++) {
        $char = mt_rand(0, strlen($range) - 1);
        $randstr .= $range[$char];
    }
    return $randstr;
}

/* 判断字符串是否为邮箱格式
 *@method check_emailstring
 *@for null
 *@param {string} $emailstring 需要判断的字符串
 *@return {bool}
 */
function check_emailstring($email_string): bool
{
    if ($email_string == "") {
        return false;
    }
    $patrn = '/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.|\-]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/';
    if (preg_match($patrn, $email_string)) {
        return true;
    } else {
        return false;
    }
}

/* 检验 将 input 流的内容转为 json 对象
 *@method input_to_json
 *@for null
 *@param {string} $input input 流 英文
 *@return {object}}
 */
function input_to_json(string $input)
{
    $input = json_decode($input,true);
    return $input;
}

/* 校验 token
 *@method check_token
 *@for null
 *@param {string} $token token
 *@param {easy_mysqli} &$con 数据库对象引用
 *@return {bool}
 */
function check_token(string $token, easy_mysqli &$con)
{
    $user_id = $con->get_value_string("user_token", "token", $token, "user_id");
    if ($user_id === false || $user_id === null) {
        return false;
    } else {
        $now_time = time();
        $dead_time = strtotime($con->get_value_string("user_token", "token", $token, "dead_date"));
        if ($now_time < $dead_time) {
            return (int)$user_id;
        } else {
            return false;
        }
    }
}

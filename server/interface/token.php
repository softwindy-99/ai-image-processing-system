<?php
/*
 *@Description: token 获取(post)
 *
 */

/* 配置 */

declare(strict_types=1); // 打开强类型模式
$config_database = json_decode(file_get_contents("../config/database.json"));

/* 引入 */
include "../lib/easy_mysqli.php";
include "../lib/easy_response.php";
include "../lib/tool.php";

/* 请求类型 */
$method = $_SERVER['REQUEST_METHOD']; // string

/* 接收表单 */
$username = $_POST["username"]; // string
$password = $_POST["password"]; // string 

/* 数据库 */
$con = new easy_mysqli(
    $config_database->server_host,
    $config_database->username,
    $config_database->password,
    $config_database->database_name,
    $config_database->server_port,
    $config_database->server_socket,
);

/* 响应 */
$rep = new easy_response();
$rep->set_content_type("application/json", "charset=UTF-8");

/* 业务逻辑 */
// 1. 检验数据是否合法
// 2. 检验用户是否存在
// 3. 检验密码是否正确
// 4. 更新 token
switch ($method) {
    case "GET":
        $rep->set_code(200);
        $rep->set_status(false);
        $rep->set_message("无此操作");
        break;
    case "POST":
        if ($username != null && $password != null) {
            $password = md5($password); // 32位
            $id = $con->get_value_string("user", "name", $username, "id"); // 用户id
            $right_pwd = $con->get_value_string("user", "name", $username, "password"); // 正确的密码
            if ($id === null) {
                $rep->set_code(200);
                $rep->set_status(false);
                $rep->set_message("登录失败，用户不存在");
            } else if ($id === false) {
                $rep->set_code(200);
                $rep->set_status(false);
                $rep->set_message("登录失败，发生未知错误，请稍后重试");
            } else {
                if ($password === $right_pwd) {
                    $token = get_randstring(128); // token
                    $time = time(); // 当前时间戳
                    $creat_date = date("Y-m-d H:i:s", $time);
                    $dead_date = date("Y-m-d H:i:s", $time + 30 * 24 * 60 * 60);
                    if ($con->update_single_row("user_token", ["token", "creat_date", "dead_date"], [$token, $creat_date, $dead_date], ["s", "s", "s"], "user_id", (int)$id)) {
                        $rep->set_code(100);
                        $rep->set_status(true);
                        $rep->set_message("登录成功");
                        $result["token"] = $token;
                        $rep->set_result($result);
                    } else {
                        $rep->set_code(200);
                        $rep->set_status(false);
                        $rep->set_message("登录失败，发生未知错误，请稍后重试");
                    }
                } else {
                    $rep->set_code(200);
                    $rep->set_status(false);
                    $rep->set_message("登录失败，用户名或密码错误");
                }
            }
        } else {
            $rep->set_code(200);
            $rep->set_status(false);
            $rep->set_message("非法请求");
        }
        break;
    case "PUT":
        $rep->set_code(200);
        $rep->set_status(false);
        $rep->set_message("无此操作");
        break;
    case "DELETE":
        $rep->set_code(200);
        $rep->set_status(false);
        $rep->set_message("无此操作");
        break;
    default:
        $rep->set_code(200);
        $rep->set_status(false);
        $rep->set_message("非法请求");
}
$rep->response();
$con->close_connect();

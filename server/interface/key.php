<?php
/*
 *@Description: 密钥 查询(get)、更新(post)
 */

/* 配置 */

declare(strict_types=1); // 打开强类型模式
$config_database = json_decode(file_get_contents("../config/database.json"));
$config_upload = json_decode(file_get_contents("../config/upload.json"), true);

/* 引入 */
include "../lib/easy_mysqli.php";
include "../lib/easy_response.php";
include "../lib/tool.php";

/* 请求类型和token */
$method = $_SERVER['REQUEST_METHOD']; // string
$headers = getallheaders();
$token = $headers['Authorization']; // token
if ($token != null)
    $token = substr($token, 7); // 使用 PostMan 调试时有 "Bearer " 前缀，需要去除

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
// 查询
//  1. 检验 token
//  2. 检验权限
//  3. 查询 key
// 新增
//  1. 检验 token
//  2. 检验权限
//  3. 设置 key
$result = null;
switch ($method) {
    case "GET":
        if ($token != null)
            $user_id = check_token($token, $con);
        else
            $user_id = false;
        if ($user_id != false) {
            $id = $_GET["id"];
            if ($id != null) {
                $id = (int)$id;
                // 检验请求id和用户id是否一致
                if ($id === $user_id) {
                    $key = $con->get_value_int("user_key", "user_id", $id, "key_string");
                    $rep->set_code(100);
                    $rep->set_status(true);
                    $rep->set_message("请求成功");
                    $result["key"] = $key;
                    $rep->set_result($result);
                } else {
                    $rep->set_code(200);
                    $rep->set_status(false);
                    $rep->set_message("无权访问");
                }
            } else {
                $rep->set_code(200);
                $rep->set_status(false);
                $rep->set_message("参数为空");
            }
        } else {
            $rep->set_code(200);
            $rep->set_status(false);
            $rep->set_message("token无效");
        }
        break;
    case "POST":
        if ($token != null)
            $user_id = check_token($token, $con);
        else
            $user_id = false;
        if ($user_id != false) {
            $new_key = get_randstring(64) . time();
            if ($con->get_value_int("user_key", "user_id", $user_id, "user_id") != null || $con->get_value_int("user_key", "user_id", $user_id, "user_id") != false) {
                if ($con->update_single_row("user_key", ["key_string"], [$new_key], ["s"], "user_id", $user_id)) {
                    $rep->set_code(100);
                    $rep->set_status(true);
                    $rep->set_message("更新key成功");
                    $result["key"] = $new_key;
                    $rep->set_result($result);
                } else {
                    $rep->set_code(200);
                    $rep->set_status(false);
                    $rep->set_message("发生未知错误，请稍后重试");
                }
            } else {
                if ($con->insert_single_row("user_key", ["user_id", "key_string"], [$user_id, $new_key], ["i", "s"])) {
                    $rep->set_code(100);
                    $rep->set_status(true);
                    $rep->set_message("获取key成功");
                    $result["key"] = $new_key;
                    $rep->set_result($result);
                } else {
                    $rep->set_code(200);
                    $rep->set_status(false);
                    $rep->set_message("发生未知错误，请稍后重试");
                }
            }
        } else {
            $rep->set_code(200);
            $rep->set_status(false);
            $rep->set_message("token无效");
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

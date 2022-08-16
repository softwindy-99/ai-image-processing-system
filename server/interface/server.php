<?php
/*
 *@Description: 服务 查询(get)、上传(post)
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
//if ($token != null)
//    $token = substr($token, 7); // 使用 PostMan 调试时有 Bearer 前缀，需要去除

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
//  1. 查询 server表
$result = array();
switch ($method) {
    case "GET":
        $result = $con->get_all_rows("server", ["id", "name"]);
        $rep->set_code(100);
        $rep->set_status(true);
        $rep->set_message("请求成功");
        if ($result != null || $result != false)
            $rep->set_result($result);
        break;
    case "POST":
        $rep->set_code(200);
        $rep->set_status(false);
        $rep->set_message("无此操作");
        break;
    case "PUT":
        $rep->set_code(200);
        $rep->set_status(false);
        $rep->set_message("无此操作");
    case "DELETE":
        $rep->set_code(200);
        $rep->set_status(false);
        $rep->set_message("无此操作");
    default:
        $rep->set_code(200);
        $rep->set_status(false);
        $rep->set_message("非法请求");
}
$rep->response();
$con->close_connect();

<?php
/*
 *@Description: 服务使用记录 查询(get)
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
//  3. 查询
$result = array();
switch ($method) {
    case "GET":
        if ($token != null)
            $user_id = check_token($token, $con);
        else
            $user_id = false;
        if ($user_id != false) {
            $user_permission = (int)$con->get_value_int("user_permission", "user_id", $user_id, "permission");
            if ($user_permission < 2) {
                $rows = $con->get_all_rows("history", ["server_id", "system", "creat_time", "ip", "out_id", "in_id"]);
                if ($rows != null || $rows != false) {
                    for ($i = 0; $i < count($rows); $i++) {
                        $temp["server_id"] = $rows[$i][0];
                        $temp["system"] = $rows[$i][1];
                        $temp["creat_time"] = $rows[$i][2];
                        $temp["ip"] = $rows[$i][3];
                        $temp["out_id"] = $rows[$i][4];
                        $temp["in_id"] = $rows[$i][5];
                        array_push($result, $temp);
                    }
                }
                $rep->set_code(100);
                $rep->set_status(true);
                $rep->set_message("请求成功");
                if ($result != null || $result != false)
                    $rep->set_result($result);
            } else {
                $rep->set_code(200);
                $rep->set_status(false);
                $rep->set_message("权限不足");
            }
        } else {
            $rep->set_code(200);
            $rep->set_status(false);
            $rep->set_message("token无效");
        }
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

<?php
/*
 *@Description: 头像 修改(put)
 */

/* 配置 */

declare(strict_types=1); // 打开强类型模式
$config_database = json_decode(file_get_contents("../config/database.json"));

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
// 修改
//  1. 检验 token
//  2. 检验权限
//  3. 修改 user_profile_photo 表
$result = null;
switch ($method) {
    case "GET":
        $rep->set_code(200);
        $rep->set_status(false);
        $rep->set_message("无此操作");
        break;
    case "POST":
        $rep->set_code(200);
        $rep->set_status(false);
        $rep->set_message("无此操作");
        break;
    case "PUT":
        if ($token != null)
            $user_id = check_token($token, $con);
        else
            $user_id = false;
        $data = input_to_json(file_get_contents('php://input'));
        if ($user_id != false) {
            $image_id = (int)$data["id"];
            // 先检查是否有头像记录
            $id = $con->get_value_int("user_profile_photo", "use_id", $user_id, "id");
            if ($id != null || $id != false) {
                if ($con->update_single_row("user_profile_photo", ["user_id", "image_id"], [$user_id, $image_id], ["i", "i"], "id", (int)$id)) {
                    $rep->set_code(100);
                    $rep->set_status(true);
                    $rep->set_message("头像更新成功");
                } else {
                    $rep->set_code(200);
                    $rep->set_status(false);
                    $rep->set_message("发生未知错误，请稍后重试");
                }
            } else {
                if ($con->insert_single_row("user_profile_photo", ["user_id", "image_id"], [$user_id, $image_id], ["i", "i"])) {
                    $rep->set_code(100);
                    $rep->set_status(true);
                    $rep->set_message("头像更新成功");
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

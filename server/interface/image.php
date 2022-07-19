<?php
/*
 *@Description: 图片 查询(get)、上传(post)
 */

/* 配置 */

declare(strict_types=1); // 打开强类型模式
$config_database = json_decode(file_get_contents("../config/database.json"));
$config_upload = json_decode(file_get_contents("../config/upload.json"),true);

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
//  1. 检验 token
//  2. 查询 图片路径
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
                $url = $con->get_value_int("image", "id", (int)$id, "url");
                $rep->set_code(100);
                $rep->set_status(true);
                $rep->set_message("请求成功");
                $result["url"] = $url;
                $rep->set_result($result);
            } else {
                $rep->set_code(200);
                $rep->set_status(false);
                $rep->set_message("参数无效");
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
            if (!empty($_FILES)) {
                if ($_FILES["image"]["type"] == "image/png" || $_FILES["image"]["type"] == "image/jpg" || $_FILES["image"]["type"] == "image/jpeg") {
                    $temp_url = $_FILES["image"]["tmp_name"];
                    $save_name = get_randstring(16) . time() . ".png";
                    $save_url = $config_upload["image"]["save_url"] . "/" . $save_name;
                    $database_url = $config_upload["protocol"] . "://" . $config_upload["host"] . $config_upload["image"]["host_url"] . "/" . $save_name;
                    if (move_uploaded_file($temp_url, $save_url)) {
                        if ($con->insert_single_row("image", ["url"], [$database_url], ["s"])) {
                            $rep->set_code(100);
                            $rep->set_status(true);
                            $rep->set_message("图片上传成功");
                            $result["id"] = $con->connect->insert_id;
                            $rep->set_result($result);
                        } else {
                            unlink($save_url);
                            $rep->set_code(200);
                            $rep->set_status(false);
                            $rep->set_message("图片上传失败，请稍后再试");
                        }
                    } else {
                        $rep->set_code(200);
                        $rep->set_status(false);
                        $rep->set_message("图片上传失败，请稍后再试");
                    }
                } else {
                    $rep->set_code(200);
                    $rep->set_status(false);
                    $rep->set_message("图片格式错误");
                }
            } else {
                $rep->set_code(200);
                $rep->set_status(false);
                $rep->set_message("没有接收到图片文件");
            }
        } else {
            $rep->set_code(200);
            $rep->set_status(false);
            $rep->set_message("token无效");
        }
        break;
    default:
        $rep->set_code(200);
        $rep->set_status(false);
        $rep->set_message("非法请求");
}
$rep->response();
$con->close_connect();

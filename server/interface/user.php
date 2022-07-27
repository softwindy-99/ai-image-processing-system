<?php
/*
 *@Description: 用户信息 查询(get)、添加(post)、修改(put)
 * 查询：[username] [email] [profile_photo]
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
//   $token = substr($token, 7); // 使用 PostMan 调试时有 Bearer 前缀，需要去除

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
// 1. 判断请求类型
// 查询
//  1. 检验 token
//  2. 检验权限
//  3. 查询 用户名
//  4. 查询 邮箱
//  5. 查询 头像图片路径
// 添加
//  1. 检验 token
//  2. 检验权限
//  3. 插入 user 表
//  4. 插入 token 表
//  5. 插入 user_permission 表
// 修改
//  1. 检验 token
//  2. 检验权限
//  3. 读取要更新的数据
//  3. 更新 user 表 [password] [email]
$result = null;
switch ($method) {
    case "GET":
        if ($token != null)
            $user_id = check_token($token, $con);
        else
            $user_id = false;
        if ($user_id != false) {

            $user_permission = (int)$con->get_value_int("user_permission", "user_id", $user_id, "permission");
            if ($user_permission === 2) {
                // 普通用户
                $username = $con->get_value_int("user", "id", $user_id, "name");
                $email = $con->get_value_int("user", "id", $user_id, "email");
                $image_id = $con->get_value_int("user_profile_photo", "user_id", $user_id, "image_id");
                if ($image_id === null || $image_id === false) {
                    $rep->set_code(101);
                    $rep->set_status(true);
                    $rep->set_message("请求成功");
                    $result["username"] = $username;
                    $result["email"] = $email;
                    $result["profile_url"] = null;
                    $result["user_id"] = $user_id;
                    $rep->set_result($result);
                } else {
                    $image_id = (int)$image_id;
                    $profile_url = $con->get_value_int("image_upload", "id", $image_id, "url");
                    $rep->set_code(102);
                    $rep->set_status(true);
                    $rep->set_message("请求成功");
                    $result["username"] = $username;
                    $result["email"] = $email;
                    $result["profile_url"] = $profile_url;
                    $result["user_id"] = $user_id;
                    $rep->set_result($result);
                }
            } else {
                // 管理用户
                $id = $_GET["id"];
                if ($id != null) { // 查询其他用户
                    $id = (int)$id;
                    $username = $con->get_value_int("user", "id", $id, "name");
                    $email = $con->get_value_int("user", "id", $id, "email");
                    $image_id = $con->get_value_int("user_profile_photo", "user_id", $id, "image_id");
                    if ($image_id === null || $image_id === false) {
                        $rep->set_code(101);
                        $rep->set_status(true);
                        $rep->set_message("请求成功");
                        $result["username"] = $username;
                        $result["email"] = $email;
                        $result["profile_url"] = null;
                        $result["user_id"] = $id;
                        $rep->set_result($result);
                    } else {
                        $image_id = (int)$image_id;
                        $profile_url = $con->get_value_int("image_upload", "id", $image_id, "url");
                        $rep->set_code(102);
                        $rep->set_status(true);
                        $rep->set_message("请求成功");
                        $result["username"] = $username;
                        $result["email"] = $email;
                        $result["profile_url"] = $profile_url;
                        $result["user_id"] = $id;
                        $rep->set_result($result);
                    }
                } else { // 查询自己
                    $username = $con->get_value_int("user", "id", $user_id, "name");
                    $email = $con->get_value_int("user", "id", $user_id, "email");
                    $image_id = $con->get_value_int("user_profile_photo", "user_id", $user_id, "image_id");
                    if ($image_id === null || $image_id === false) {
                        $rep->set_code(101);
                        $rep->set_status(true);
                        $rep->set_message("请求成功");
                        $result["username"] = $username;
                        $result["email"] = $email;
                        $result["profile_url"] = null;
                        $result["user_id"] = $user_id;
                        $rep->set_result($result);
                    } else {
                        $image_id = (int)$image_id;
                        $profile_url = $con->get_value_int("image_upload", "id", $image_id, "url");
                        $rep->set_code(102);
                        $rep->set_status(true);
                        $rep->set_message("请求成功");
                        $result["username"] = $username;
                        $result["email"] = $email;
                        $result["profile_url"] = $profile_url;
                        $result["user_id"] = $user_id;
                        $rep->set_result($result);
                    }
                }
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
        $username = $_POST["username"]; // string
        $password = $_POST["password"]; // string
        $email = $_POST["email"]; // string
        if ($user_id != false) {
            // 注册为管理用户
            $user_permission = (int)$con->get_value_int("user_permission", "user_id", $user_id, "permission");
            if ($user_permission < 2) {
                if ($username != null && $password != null && $email != null) {
                    if (check_emailstring($email)) {
                        $password = md5($password); // 32位
                        if ($con->get_value_string("user", "name", $username, "name") === $username) {
                            $rep->set_code(200);
                            $rep->set_status(false);
                            $rep->set_message("该用户已存在");
                        } else {
                            $con->close_auto_commit();
                            if ($con->insert_single_row("user", ["name", "password", "email"], [$username, $password, $email], ["s", "s", "s"])) {
                                $user_id = mysqli_insert_id($con->connect);
                                if ($con->insert_single_row("user_token", ["user_id"], [$user_id], ["i"])) {
                                    if ($con->insert_single_row("user_permission", ["user_id", "permission", "permission_name"], [$user_id, 1, "管理用户"], ["i", "i", "s"])) {
                                        $con->commit();
                                        $rep->set_code(100);
                                        $rep->set_status(true);
                                        $rep->set_message("注册成功");
                                    } else {
                                        $con->rollback_commit();
                                        $rep->set_code(200);
                                        $rep->set_status(false);
                                        $rep->set_message("发生未知错误，请稍后重试");
                                    }
                                } else {
                                    $con->rollback_commit();
                                    $rep->set_code(200);
                                    $rep->set_status(false);
                                    $rep->set_message("发生未知错误，请稍后重试");
                                }
                            } else {
                                $con->rollback_commit();
                                $rep->set_code(200);
                                $rep->set_status(false);
                                $rep->set_message("发生未知错误，请稍后重试");
                            }
                        }
                    } else {
                        $rep->set_code(200);
                        $rep->set_status(false);
                        $rep->set_message("邮箱格式错误");
                    }
                } else {
                    $rep->set_code(200);
                    $rep->set_status(false);
                    $rep->set_message("非法请求");
                }
            } else {
                $rep->set_code(200);
                $rep->set_status(false);
                $rep->set_message("权限不足");
            }
        } else {
            // 注册为普通用户
            if ($username != null && $password != null && $email != null) {
                if (check_emailstring($email)) {
                    $password = md5($password); // 32位
                    if ($con->get_value_string("user", "name", $username, "name") === $username) {
                        $rep->set_code(200);
                        $rep->set_status(false);
                        $rep->set_message("该用户已存在");
                    } else {
                        $con->close_auto_commit();
                        if ($con->insert_single_row("user", ["name", "password", "email"], [$username, $password, $email], ["s", "s", "s"])) {
                            $user_id = mysqli_insert_id($con->connect);
                            if ($con->insert_single_row("user_token", ["user_id"], [$user_id], ["i"])) {
                                if ($con->insert_single_row("user_permission", ["user_id", "permission", "permission_name"], [$user_id, 2, "普通用户"], ["i", "i", "s"])) {
                                    $con->commit();
                                    $rep->set_code(100);
                                    $rep->set_status(true);
                                    $rep->set_message("注册成功");
                                } else {
                                    $con->rollback_commit();
                                    $rep->set_code(200);
                                    $rep->set_status(false);
                                    $rep->set_message("发生未知错误，请稍后重试");
                                }
                            } else {
                                $con->rollback_commit();
                                $rep->set_code(200);
                                $rep->set_status(false);
                                $rep->set_message("发生未知错误，请稍后重试");
                            }
                        } else {
                            $con->rollback_commit();
                            $rep->set_code(200);
                            $rep->set_status(false);
                            $rep->set_message("发生未知错误，请稍后重试");
                        }
                    }
                } else {
                    $rep->set_code(200);
                    $rep->set_status(false);
                    $rep->set_message("邮箱格式错误");
                }
            } else {
                $rep->set_code(201);
                $rep->set_status(false);
                $rep->set_message("非法请求");
            }
        }
        break;
    case "PUT":
        if ($token != null)
            $user_id = check_token($token, $con);
        else
            $user_id = false;
        $data = input_to_json(file_get_contents('php://input'));
        $con->close_auto_commit();
        $user_id = check_token($token, $con);
        if ($user_id != false) {
            $user_permission = (int)$con->get_value_int("user_permission", "user_id", $user_id, "permission");
            $id = $data["id"];
            if ($user_permission === 2) {
                // 普通用户
                if ($user_id === $id) {
                    // 更新 email
                    $new_email = $data["email"];
                    if ($new_email != null) {
                        if (check_emailstring($new_email)) {
                            if (!$con->update_single_row("user", ["email"], [$new_email], ["s"], "id", $id)) {
                                $result["email"] = "发生未知错误，请稍后重试";
                            } else {
                                $result["email"] = "成功";
                                $con->commit();
                            }
                        } else {
                            $result["email"] = "格式错误";
                        }
                    } else {
                        $result["email"] = "为空";
                    }
                    // 更新 password
                    $new_password = $data["password"];
                    if ($new_password != null) {
                        if (!$con->update_single_row("user", ["password"], [md5($new_password)], ["s"], "id", $id)) {
                            $result["password"] = "发生未知错误，请稍后重试";
                        } else {
                            $result["password"] = "成功";
                            $con->commit();
                        }
                    } else {
                        $result["password"] = "为空";
                    }
                } else {
                    $rep->set_code(200);
                    $rep->set_status(false);
                    $rep->set_message("权限不正确");
                }
            } else {
                // 管理用户
                // 更新 email
                $new_email = $data["email"];
                if ($new_email != null) {
                    if (check_emailstring($new_email)) {
                        if (!$con->update_single_row("user", ["email"], [$new_email], ["s"], "id", $id)) {
                            $result["email"] = "发生未知错误，请稍后重试";
                        } else {
                            $result["email"] = "成功";
                            $con->commit();
                        }
                    } else {
                        $result["email"] = "格式错误";
                    }
                } else {
                    $result["email"] = "为空";
                }
                // 更新 password
                $new_password = $data["password"];
                if ($new_password != null) {
                    if (!$con->update_single_row("user", ["password"], [md5($new_password)], ["s"], "id", $id)) {
                        $result["password"] = "发生未知错误，请稍后重试";
                    } else {
                        $result["password"] = "成功";
                        $con->commit();
                    }
                } else {
                    $result["password"] = "为空";
                }
            }
            $rep->set_code(100);
            $rep->set_status(true);
            $rep->set_message("已更新");
            $rep->set_result($result);
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

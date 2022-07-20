<?php
/*
 * @Description: MySQLi 操作封装
 * @Author: Softwindy
 * @Date: 2022-7-13 08:00:00
 */

class easy_mysqli
{
    /* 成员变量 */
    public mysqli $connect; // 连接对象
    public bool $connect_status = false; // 连接状态
    public bool $connect_autocommit = true; // 自动事务提交状态

    /* 构造函数
     *@method __construct
     *@for easy_mysqli
     *@param {string} hostname 
     *@param {string} user 
     *@param {string} password
     *@param {string} databasename
     *@param {int} port
     *@param {string} socket
     *@return {object}
     */
    public function __construct(string $hostname, string $user, string $password, string $databasename, int $port, string $socket)
    {
        $this->connect = new mysqli(
            $hostname,
            $user,
            $password,
            $databasename,
            $port,
            $socket
        );
        if (!$this->connect->connect_error) {
            $this->connect_status = true;
        } else {
            $this->connect_status = false;
        }
    }

    /* 关闭连接
     *@method close_connect
     *@for easy_mysqli
     *@return void
     */
    public function close_connect(): void
    {
        $this->connect->close();
        $this->connect_status = false;
    }

    /* 开启自动提交事务
     *@method open_auto_commit
     *@for easy_mysqli
     *@return void
     */
    public function open_auto_commit(): void
    {
        $this->connect->autocommit(true);
        $this->connect_autocommit = true;
    }

    /* 关闭自动提交事务
     *@method close_auto_commit
     *@for easy_mysqli
     *@return void
     */
    public function close_auto_commit(): void
    {
        $this->connect->autocommit(false);
        $this->connect_autocommit = false;
    }

    /* 提交事务
     *@method commit
     *@for easy_mysqli
     *@return void
     */
    public function commit(): void
    {
        $this->connect->commit();
    }

    /* 回滚事务
     *@method rollback_commit
     *@for easy_mysqli
     *@return void
     */
    public function rollback_commit(): void
    {
        $this->connect->rollback();
    }

    /* 查询单键值-自定义键 string值
     *@method get_value_string
     *@for easy_mysqli
     *@param {string} $table 表
     *@param {string} $key 键
     *@param {string} $value 值
     *@param {string} $result_key 要查询的字段
     *@return {string} | {bool} | {null}
     */
    public function get_value_string(string $table, string $key, string $value, string $result_key)
    {
        if ($this->connect_status) {
            $result = "";
            $sql_string = "SELECT {$result_key} FROM {$table} WHERE {$key} = ?";
            if ($stmt = mysqli_prepare($this->connect, $sql_string)) {
                $stmt->bind_param("s", $value);
                $stmt->bind_result($result);
                $stmt->execute();
                $stmt->fetch();
                $stmt->close();
                if ($result === "") {
                    return null;
                } else {
                    return $result;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /* 查询单键值-自定义键 int值
     *@method get_key_value_int
     *@for easy_mysqli
     *@param {string} $table 表
     *@param {string} $key 键
     *@param {int} $value 值
     *@param {string} $result_key 要查询的字段
     *@return {string} | {bool} | {null}
     */
    public function get_value_int(string $table, string $key, int $value, string $result_key)
    {
        if ($this->connect_status) {
            $result = "";
            $sql_string = "SELECT {$result_key} FROM {$table} WHERE {$key} = ?";
            if ($stmt = mysqli_prepare($this->connect, $sql_string)) {
                $stmt->bind_param("i", $value);
                $stmt->bind_result($result);
                $stmt->execute();
                $stmt->fetch();
                $stmt->close();
                if ($result === "") {
                    return null;
                } else {
                    return $result;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /* 查询所有行
     *@method get_all_rows
     *@for easy_mysqli
     *@param {string} $table 表
     *@param {array string} $result_key 要查询的字段
     *@return {array} | {bool} | {null}
     */
    public function get_all_rows(string $table, array $result_key)
    {
        if ($this->connect_status) {
            $result = array();
            $result_key_string = "";
            for ($i = 0; $i < count($result_key); $i++) {
                if ($i === count($result_key) - 1) {
                    $result_key_string .= $result_key[$i];
                } else {
                    $result_key_string .= $result_key[$i] . ",";
                }
            }
            $sql_string = "SELECT {$result_key_string} FROM {$table} ";
            if ($stmt = mysqli_prepare($this->connect, $sql_string)) {
                $stmt->store_result();
                $stmt->execute();
                $result_key = array_merge($result_key);
                $result_key = $this->makeValuesReferenced($result_key);
                call_user_func_array(array($stmt, 'bind_result'), $result_key);
                while ($stmt->fetch()) {
                    array_push($result, $result_key);
                }
                $stmt->close();
                if (empty($result)) {
                    return null;
                } else {
                    return $result;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /* 条件查询所有行-int值
     *@method get_rows_int
     *@for easy_mysqli
     *@param {string} $table 表
     *@param {string} $operate 比较符
     *@param {string} $key 键
     *@param {int} $value 值
     *@param {array string} $result_key 要查询的字段
     *@return {array} | {bool} | {null}
     */
    public function get_rows_int(string $table, array $result_key, string $key, int $value, string $operate = "=")
    {
        if ($this->connect_status) {
            $result = array();
            $result_key_string = "";
            for ($i = 0; $i < count($result_key); $i++) {
                if ($i === count($result_key) - 1) {
                    $result_key_string .= $result_key[$i];
                } else {
                    $result_key_string .= $result_key[$i] . ",";
                }
            }
            $sql_string = "SELECT {$result_key_string} FROM {$table} WHERE {$key} {$operate} {$value}";
            if ($stmt = mysqli_prepare($this->connect, $sql_string)) {
                $stmt->store_result();
                $stmt->execute();
                $result_key = array_merge($result_key);
                $result_key = $this->makeValuesReferenced($result_key);
                call_user_func_array(array($stmt, 'bind_result'), $result_key);
                while ($stmt->fetch()) {
                    array_push($result, $result_key);
                }
                $stmt->close();
                if (empty($result)) {
                    return null;
                } else {
                    return $result;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /* 条件查询所有行-string值
     *@method get_rows_string
     *@for easy_mysqli
     *@param {string} $table 表
     *@param {string} $operate 比较符
     *@param {string} $key 键
     *@param {string} $value 值
     *@param {array string} $result_key 要查询的字段
     *@return {array} | {bool} | {null}
     */
    public function get_rows_string(string $table, array $result_key, string $key, string $value, string $operate = "=")
    {
        if ($this->connect_status) {
            $result = array();
            $result_key_string = "";
            for ($i = 0; $i < count($result_key); $i++) {
                if ($i === count($result_key) - 1) {
                    $result_key_string .= $result_key[$i];
                } else {
                    $result_key_string .= $result_key[$i] . ",";
                }
            }
            $sql_string = "SELECT {$result_key_string} FROM {$table} WHERE {$key} {$operate} '{$value}'";
            if ($stmt = mysqli_prepare($this->connect, $sql_string)) {
                $stmt->store_result();
                $stmt->execute();
                $result_key = array_merge($result_key);
                $result_key = $this->makeValuesReferenced($result_key);
                call_user_func_array(array($stmt, 'bind_result'), $result_key);
                while ($stmt->fetch()) {
                    array_push($result, $result_key);
                }
                $stmt->close();
                if (empty($result)) {
                    return null;
                } else {
                    return $result;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /* 插入单行数据
     *@method insert_single_row
     *@for easy_mysqli
     *@param {string} $table 表
     *@param {array string} $key 列名
     *@param {array mixed} $value 值
     *@param {array string} $type 数据类型
     *@return {bool}
     */
    public function insert_single_row(string $table, array $key, array $value, array $type): bool
    {
        if ($this->connect_status) {
            $key_array_string = "";
            for ($i = 0; $i < count($key); $i++) {
                if ($i === count($key) - 1) {
                    $key_array_string .= $key[$i];
                } else {
                    $key_array_string .= $key[$i] . ",";
                }
            }
            $value_array_string = "";
            for ($i = 0; $i < count($value); $i++) {
                if ($i === count($value) - 1) {
                    $value_array_string .= "?";
                } else {
                    $value_array_string .= "?" . ",";
                }
            }
            $sql_string = "INSERT INTO {$table} ({$key_array_string}) VALUES ({$value_array_string})";
            if ($stmt = $this->connect->prepare($sql_string)) {
                $type = implode($type); // $type 数组（多个元素）转字符串
                $params = array_merge((array)$type, $value); // $type 数组，只有一个元素
                $params = $this->makeValuesReferenced($params);
                call_user_func_array(array($stmt, 'bind_param'), $params);
                if ($stmt->execute()) {
                    $stmt->close();
                    return true;
                } else {
                    $stmt->close();
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    /* 更新单行-主键
     *@method update_single_row
     *@for easy_mysqli
     *@param {string} $table 表
     *@param {array string} $key 列名
     *@param {array mixed} $value 值
     *@param {array string} $type 数据类型
     *@param {string} $primary_key 主键
     *@param {int} $primary_value 主键值
     *@return {bool}
     */
    public function update_single_row(string $table, array $key, array $value, array $type, string $primary_key, int $primary_value): bool
    {
        if ($this->connect_status) {
            $key_array_string = "";
            for ($i = 0; $i < count($key); $i++) {
                if ($i === count($key) - 1) {
                    $key_array_string .= $key[$i] . "=?";
                } else {
                    $key_array_string .= $key[$i] . "=?,";
                }
            }
            $sql_string = "UPDATE {$table} SET {$key_array_string} WHERE {$primary_key} = {$primary_value}";
            if ($stmt = $this->connect->prepare($sql_string)) {
                $type = implode($type); // $type 数组（多个元素）转字符串
                $params = array_merge((array)$type, $value); // $type 数组，只有一个元素
                $params = $this->makeValuesReferenced($params);
                call_user_func_array(array($stmt, 'bind_param'), $params);
                if ($stmt->execute()) {
                    $stmt->close();
                    return true;
                } else {
                    $stmt->close();
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /* 删除单行-主键
     *@method delete_single_row
     *@for easy_mysqli
     *@param {string} $table 表
     *@param {string} $primary_key 主键
     *@param {int} $primary_value 主键值
     *@return {bool}
     */
    public function delete_single_row(string $table, string $primary_key, int $primary_value): bool
    {
        if ($this->connect_status) {
            $sql_string = "DELETE FROM {$table} WHERE {$primary_key} = ?";
            if ($stmt = $this->connect->prepare($sql_string)) {
                $stmt->bind_param("i", $primary_value);
                if ($stmt->execute()) {
                    $stmt->close();
                    return true;
                } else {
                    $stmt->close();
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    /* 将数组内元素变为引用类型
     *@method makeValuesReferenced
     *@for easy_mysqli
     *@param {array} &$arr 数组
     *@return {array}
     */
    public function makeValuesReferenced(array &$arr)
    {
        $refs = array();
        foreach ($arr as $key => $value)
            $refs[$key] = &$arr[$key];
        return $refs;
    }
}

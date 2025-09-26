<?php
require_once 'configs/database.php';

abstract class BaseModel {
    // Database connection
    protected static $_connection;

    public function __construct() {
        if (!isset(self::$_connection)) {
            self::$_connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);

            if (self::$_connection->connect_errno) {
                printf("Connect failed: %s\n", self::$_connection->connect_error);
                exit();
            }

            // Set charset để tránh lỗi khi bind utf8
            self::$_connection->set_charset("utf8mb4");
        }
    }

    /**
     * Run prepared statement SELECT
     * @param string $sql
     * @param string $types
     * @param array $params
     * @return array
     */
    protected function select($sql, $types = "", $params = []) {
        $stmt = self::$_connection->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Prepare failed: " . self::$_connection->error);
        }

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        $stmt->close();
        return $rows;
    }

    /**
     * Run prepared statement for INSERT/UPDATE/DELETE
     * @param string $sql
     * @param string $types
     * @param array $params
     * @return bool|int
     */
    protected function execute($sql, $types = "", $params = []) {
        $stmt = self::$_connection->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Prepare failed: " . self::$_connection->error);
        }

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $success = $stmt->execute();
        $insertId = $stmt->insert_id; // lấy id khi insert
        $stmt->close();

        return $insertId > 0 ? $insertId : $success;
    }
}

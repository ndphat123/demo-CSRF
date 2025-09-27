<?php
// UserModel.php
require_once 'BaseModel.php';

class UserModel extends BaseModel
{

    public function __construct()
    {
        parent::__construct();
    }

    // Tìm user theo ID
    public function findUserById($id): ?array
    {
        $sql = "SELECT * FROM users WHERE id = ? LIMIT 1";
        $rows = $this->select($sql, "i", [$id]);
        return $rows[0] ?? null;
    }


    // Tìm user theo keyword (name hoặc email)
    public function findUser(string $keyword): array
    {
        $sql = "SELECT * FROM users WHERE name LIKE ? OR email LIKE ?";
        $kw = "%{$keyword}%";
        return $this->select($sql, "ss", [$kw, $kw]);
    }

    // Xác thực user (dùng password_hash + password_verify)
    public function auth(string $userName, string $password): ?array
    {
        $sql = "SELECT * FROM users WHERE name = ? LIMIT 1";
        $rows = $this->select($sql, "s", [$userName]);
        $user = $rows[0] ?? null;

        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']); // Không trả về hash
            return $user;
        }
        return null;
    }

    // Xóa user theo ID
    public function deleteUserById($id): bool
    {
        $sql = "DELETE FROM users WHERE id = ?";
        return (bool)$this->execute($sql, "i", [$id]);
    }

    // Cập nhật user
    public function updateUser(array $input): bool
    {
        $id = (int)($input['id'] ?? 0);
        if ($id <= 0) return false;

        if (!empty($input['password'])) {
            $hash = password_hash($input['password'], PASSWORD_DEFAULT);
            $sql = "UPDATE users SET name = ?, password = ? WHERE id = ?";
            return (bool)$this->execute($sql, "ssi", [$input['name'], $hash, $id]);
        } else {
            $sql = "UPDATE users SET name = ? WHERE id = ?";
            return (bool)$this->execute($sql, "si", [$input['name'], $id]);
        }
    }

    // Thêm user mới
    public function insertUser(array $input): ?string
    {
        $hash = password_hash($input['password'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (name, password) VALUES (?, ?)";
        $insertId = $this->execute($sql, "ss", [$input['name'], $hash]);
        return $insertId ? (string)$insertId : null;
    }

    // Lấy danh sách user
    public function getUsers(array $params = []): array
    {
        if (!empty($params['keyword'])) {
            $sql = "SELECT * FROM users WHERE name LIKE ? OR email LIKE ?";
            $kw = "%{$params['keyword']}%";
            return $this->select($sql, "ss", [$kw, $kw]);
        } else {
            $sql = "SELECT * FROM users";
            return $this->select($sql);
        }
    }
}

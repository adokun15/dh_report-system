<?php
class User
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }
public function create($user)
{
    try {
        $this->db->beginTransaction();

        $sql = "INSERT INTO users
                    (user_id, role, name)
                VALUES
                    (:user_id, :role, :name)";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':user_id' => $user['user_id'],
            ':role'    => $user['role'],
            ':name'    => $user['name']
        ]);

        $sql = "INSERT INTO auth
                    (user_id, email, password_hash)
                VALUES
                    (:user_id, :email, :password_hash)";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':user_id'      => $user['user_id'],
            ':email'        => $user['email'],
            ':password_hash' => $user['password']
        ]);

        $this->db->commit();

        return true;

    } catch (PDOException $e) {
        if ($this->db->inTransaction()) {
            $this->db->rollBack();
        }

        // Log the exception server-side in production.
        error_log($e->getMessage());

        return false;
    }
}

    public function findUserByEmail($email)
    {
        $sql = "SELECT a.email, u.user_id, u.role, a.password_hash
                FROM auth a
                join users u
                on a.user_id = u.user_id 
                WHERE email = :email
                LIMIT 1";

$stmt = $this->db->prepare($sql);

$stmt->execute([
    ':email' => $email
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
public function findUserByUserId($user_id)
    {
        $sql = "SELECT 
         a.email,
         u.user_id, 
         u.role,
         u.name,
         u.photo
                FROM auth a
                join users u
                on a.user_id = u.user_id 
                WHERE u.user_id = :userid
                LIMIT 1";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':userid' => $user_id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    //Create View For This!
    public function getAllUsers()
    {
    $sql = "
        SELECT 
        users.user_id, users.name, auth.email, users.role,
        COUNT(reports.report_id) as total_reports
        FROM users
        left join auth 
        on users.user_id = auth.user_id
        left join reports
        on reports.author_id = users.user_id 
        where role = 'staff'
        Group by users.user_id
        ORDER BY name ASC
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
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
        $sql = "INSERT INTO users (
                user_id,
                role,
                name,
                email,
                password
            )
            VALUES (
                :user_id,
                :role,
                :name,
                :email,
                :password
            )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':user_id' => $user['user_id'],
            ':role' => $user['role'],
            ':name' => $user['name'],
            ':email' => $user['email'],
            ':password' => $user['password']
        ]);
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
    public function getAllUser()
    {
    $sql = "
        SELECT user_id, name, email, role
        FROM users
        ORDER BY name ASC
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
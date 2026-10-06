<?php
class User
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findUserByEmail($email)
    {
        $sql = "SELECT a.email, u.user_id, u.role, u.name, a.password_hash
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
}
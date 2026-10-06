<?php
class AuthController
{
    private $userModel;

    public function __construct($db)
    {
        $this->userModel = new User($db);
    }

    public function login($email, $password)
    {
        $user = $this->userModel->findUserByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return [
                'success' => false,
                'message' => 'Invalid username or password.'
            ];
        }

        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['user_id'];
        return ['success' => true];
    }
}
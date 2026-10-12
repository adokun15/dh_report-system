<?php 
require_once __DIR__ . '/../config/middleware.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/user.model.php';
requireAdmin();
?>
<?php
class UserController
{
    private $userModel;
    public function __construct($db){
        $this->userModel = new User($db);
    }
    //All users
    public function index()
    {
     return $this->userModel->getAllUsers();
    }

  public function get($user_id) 
     {
        $user = $this->userModel->findUserByUserId($user_id);

        if (!$user){
            return [
                'success' => false,
                'message' => 'User not Found!'
            ];
        }
            return [
                'success' => true,
                'data' => $user
            ];
    }
    
   

    //Create
    public function create($data)
    {
    if (
        empty($data['name']) ||
        empty($data['email']) ||
        empty($data['role'])
    ) {
        return false;
    }

    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    if (!in_array($data['role'], ['admin', 'staff'])) {
        return false;
    }

    $user = [
        'user_id' => $this->generateUuid(),
        'role' => $data['role'],
        'name' => trim($data['name']),
        'email' => trim($data['email']),
        'password' => password_hash(
            'password123',
            PASSWORD_DEFAULT
        )
    ];

    return $this->userModel->create($user);
}

    private function generateUuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}


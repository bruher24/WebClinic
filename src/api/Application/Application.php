<?php



class Application
{
    private DB $db;
    private User $user;

    public function __construct()
    {
        $this->db = new DB;
        $this->user = new User;
    }

    public function signUp(array $data): array
    {
        switch($data['role']) {
            case 'doctor':
                return $this->user->addDoctor($data);
            case 'patient':
                return $this->user->addPatient($data);
            default:
                return array('Msg' => 'Invalid data[role].'); //TODO: add exception & exception handler
        }
    }
}
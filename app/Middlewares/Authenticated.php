<?php
namespace App\Middlewares;
use App\Singletons\Request;
use App\Handlers\Database;
use App\Classes\User;

class Authenticated extends BaseMiddleware
{
    protected $request;
    protected $db;
    
    public function __construct(Request $request){
        $this->request = $request;
        $this->db = new Database;
        parent::__construct();
    }

    protected function handle(){
        try {
            session_start();

            if (empty($_SESSION['unique_id'])) {
                throw new \Exception('You are not allowed to perform this action');
            }

            $stmt = $this->db->connection->prepare('SELECT * FROM sessions WHERE session_id= ? AND expires_at > CURRENT_TIMESTAMP');
            $stmt->execute([$_SESSION['unique_id']]);

            $session = $stmt->fetchAll(\PDO::FETCH_CLASS, User::class);

            if(!$session){
                throw new \Exception('You are not allowed to perform this action');
            }
        }catch(\Exception $e) {
            $this->request->terminateRequestWithException($e);
        }
    }
}
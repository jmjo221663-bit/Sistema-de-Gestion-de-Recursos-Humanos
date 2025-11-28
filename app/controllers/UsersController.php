
<?php
class UsersController extends Controller {
    public function index() {
        Auth::authorize(['admin']);
        $users = User::all();
        $this->view('usuarios/index', compact('users'));
    }
    public function create() {
        Auth::authorize(['admin']);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->verifyCsrf();
            $data = [
                'name'=>trim($_POST['name'] ?? ''),
                'email'=>filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL),
                'password'=>$_POST['password'] ?? '',
                'role'=>$_POST['role'] ?? 'empleado',
                'active'=>isset($_POST['active'])
            ];
            if (!$data['name'] || !$data['email'] || strlen($data['password'])<8) {
                $error="Revisa los campos (contraseña >= 8).";
                return $this->view('usuarios/create', compact('error'));
            }
            User::create($data);
            return $this->redirect('/SGRH_PHP_MVC/public/?route=users');
        }
        $this->view('usuarios/create');
    }
    public function edit() {
        Auth::authorize(['admin']);
        $id = intval($_GET['id'] ?? 0);
        $user = User::find($id);
        if (!$user) { http_response_code(404); echo "No encontrado"; return; }
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            $this->verifyCsrf();
            $data = [
                'name'=>trim($_POST['name'] ?? ''),
                'email'=>filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL),
                'password'=>$_POST['password'] ?? '',
                'role'=>$_POST['role'] ?? 'empleado',
                'active'=>isset($_POST['active'])
            ];
            User::update($id,$data);
            return $this->redirect('/SGRH_PHP_MVC/public/?route=users');
        }
        $this->view('usuarios/edit', compact('user'));
    }
    public function delete() {
        Auth::authorize(['admin']);
        $this->verifyCsrf();
        $id = intval($_POST['id'] ?? 0);
        User::delete($id);
        $this->redirect('/SGRH_PHP_MVC/public/?route=users');
    }
}

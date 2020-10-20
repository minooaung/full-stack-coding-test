<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;


class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = session()->all();
        $userId = $data['login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d'];
        
        $u = new User();
        $result = $u->getAllUsers($userId);
        
        $data = json_decode(json_encode($result[0]), true);
        
        if ($data['is_admin'] == 0) {
            return redirect('/home');
        }
        
        return view('users');
    }
    
    public function getAllUsers()
    {
        $u = new User();
        $users = $u->getAllUsers();
        return json_encode(array('success' => true, 'draw' => 1, "recordsTotal" => count($users), "recordsFiltered" => count($users), 'data' => $users));
    }
    
    public function getUser($id = 0)
    {
        $u = new User();
        $result = $u->getAllUsers($id);
        
        $data = json_decode(json_encode($result[0]), true);
        //print_r($array); exit;
        if (!empty($result)) {
            return json_encode(array('success' => true, 'data' => $data));
        } else {
            return json_encode(array('success' => false));
        }
    }
    
    public function addUser(Request $r)
    {
        $input = $r->input();
        
        $password = Hash::make('platinum01#');
        
        if (!empty($input['name']) && !empty($input['email'])) {
            $data = array('name'=>$input['name'], 'email'=>$input['email'], 'password'=>$password, 'is_admin' => $input['is_admin']);
            
            $u = new User();
            
            $result = $u->getUser($data['name']);
            if ($result->count() > 0) {
                return json_encode(array('success' => false, 'msg' => 'Name already exists'));
            }
            
            $u->addUser($data);
            
            return json_encode(array('success' => true));
        } else {
            return json_encode(array('success' => false, 'msg' => 'Field values are missing'));
        }
    }
    
    public function updateUser(Request $r, $id = 0)
    {
        $input = $r->input();
        
        if (!empty($id) && !empty($input['name']) && !empty($input['email'])) {
            $data = array('name'=>$input['name'], 'email'=>$input['email']);
            
            if (!empty($input['password'])) {
                $data['password'] = Hash::make($input['password']);
            }            
            
            $u = new User();
            $u->updateUser($id, $data);
            
            return json_encode(array('success' => true));
        } else {
            return json_encode(array('success' => false, 'msg' => 'Field values are missing'));
        }
    }
    
    public function deleteUser($id = 0)
    {
        $u = new User();
        
        $result = $u->getAllUsers($id);
        
        if (!empty($result[0])){
            $data = json_decode(json_encode($result[0]), true);
            if ($data['is_admin'] == 1) {
                return json_encode(array('success' => false, 'msg' => 'You are not allowed to delete Admin User!'));
            }
        } else {
            return json_encode(array('success' => false, 'msg' => 'User not found!'));
        }        
        
        $u->deleteUser($id);
        
        return json_encode(array('success' => true));
    }
}

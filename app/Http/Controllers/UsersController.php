<?php

/**
 * Author: Min Oo Aung
 * Requirement: Admin to manage users by performing CRUD
 *
 * Functionalities:
 *  - Only Admin logged in user can access User Register Page (Done)
 *  - ADMIN can search a user from search box
 *  - Admin can create, update and delete user (Done)
 *  - Admin can not delete himself/herself
 *  - Admin can not update/delete other Admin user
 *  - When creating a new user, Admin user has the right to assign Admin role to a new user by ticking "Is Admin" checkbox (Done)
 *  
*/

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
     * Get Logged In User ID from sesstion
     *
     * @return user id
     */
    private function getLoggedInUserId()
    {
        $userId = 0;
        
        $data = session()->all();
        $userId = $data['login_web_59ba36addc2b2f9401580f014c7f58ea4e30989d'];
        return $userId;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $u = new User();
        
        $userId = $this->getLoggedInUserId();     
        $result = $u->getAllUsers($userId);        
        $data = json_decode(json_encode($result[0]), true);
        
        if ($data['is_admin'] == 0) {
            return redirect('/home');
        }
        
        return view('users')->with($data);
    }
    
    /**
     * Get all users
     *
     * @return array()
     */
    public function getAllUsers()
    {
        $u = new User();
        $users = $u->getAllUsers();
        return json_encode(array('success' => true, 'draw' => 1, "recordsTotal" => count($users), "recordsFiltered" => count($users), 'data' => $users));
    }
    
    /**
     * Get a user by ID
     *
     * @return array()
     */
    public function getUser($id = 0)
    {
        $u = new User();
        $result = $u->getAllUsers($id);
        
        $data = json_decode(json_encode($result[0]), true);
        
        if (!empty($result)) {
            return json_encode(array('success' => true, 'data' => $data));
        } else {
            return json_encode(array('success' => false));
        }
    }
    
    /**
     * Create a new user
     *
     * @return Response array()
     */
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
    
    /**
     * Update user
     *
     * @return Response array()
     */
    public function updateUser(Request $r, $id = 0)
    {
        $input = $r->input();
        $u = new User();
        
        if (!empty($id)) {
            $loggedInUserId = $this->getLoggedInUserId();
            
            $result = $u->getAllUsers($id);
            $data = json_decode(json_encode($result[0]), true);
            
            if ($data['id'] !== $loggedInUserId && $data['is_admin'] == 1) {
                return json_encode(array('success' => false, 'msg' => 'You are not allowed to Edit other Admin user'));
            }
        } else {
            return json_encode(array('success' => false, 'msg' => 'User not found'));
        }
        
        if (!empty($id) && !empty($input['name']) && !empty($input['email'])) {            
            $data = array('name'=>$input['name'], 'email'=>$input['email']);
            
            if (!empty($input['password'])) {
                $data['password'] = Hash::make($input['password']);
            }
            
            $u->updateUser($id, $data);
            
            return json_encode(array('success' => true));
        } else {
            return json_encode(array('success' => false, 'msg' => 'Field values are missing'));
        }
    }
    
    /**
     * Delete user
     *
     * @return Response array()
     */
    public function deleteUser($id = 0)
    {
        $loggedInUserId = $this->getLoggedInUserId();
        
        if ($loggedInUserId == $id) {
            return json_encode(array('success' => false, 'msg' => 'You are not allowed to delete yourself'));
        }
        
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

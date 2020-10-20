<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class HomeController extends Controller
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
        
        return view('home')->with($data);
    }
}

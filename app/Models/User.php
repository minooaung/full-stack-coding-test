<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use DB;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'is_admin',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function getAllUsers($id = 0)
    {
        //$users = DB::select('SELECT * FROM users ORDER BY id DESC');
        $result = DB::table('users')->orderBy('id', 'asc')->get();
        
        if ($id > 0) {
            $result = DB::table('users')->where('id', $id)->get();
        }
        
        return $result;
    }
    
    public function getUser($name)
    {
        $value = DB::table('users')->where('name', $name)->get();
        return $value;
    }
    
    public function addUser($data)
    {
        $value = DB::table('users')->where('name', $data['name'])->get();
        if($value->count() == 0){
            $insertid = DB::table('users')->insertGetId($data);
            return $insertid;
        }else{
            return 0;
        }
    }
    
    public function updateUser($id, $data)
    {
        DB::table('users')->where('id', $id)->update($data);
    }
    
    public function deleteUser($id = 0)
    {
      DB::table('users')->where('id', '=', $id)->delete();
    }
}

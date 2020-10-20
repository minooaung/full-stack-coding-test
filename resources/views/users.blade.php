@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header"><h5>{{ __('Users Register') }}</h5></div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif                   
                    
                    <input type="hidden" id="logged_in_user_id" value="{{ $id }}">
                    <input type="hidden" id="logged_in_is_admin" value="{{ $is_admin }}">
                    
                    <!--------- Creating Add Edit Dialog to manage user ----------->
                    <div id="addEditDialog" style="display:none">
                        <table>
                            <tr><td></td></tr>
                            <tr>
                                <td><label>Name: </label></td>
                                <td><input type="text" id="u_name" placeholder="" style="width:350px;"/></td>
                            </tr>
                            <tr><td></td></tr>
                            <tr>
                                <td><label>Email: </label></td>
                                <td><input type="text" id="u_email" placeholder="" style="width:350px;"/></td>
                            </tr>
                            <tr><td></td></tr>
                            <tr id = "is_admin_tr">
                                <td><label>Is Admin: </label></td>
                                <td><input type="checkbox" id="is_admin_checkbox" /></td>
                            </tr>
                            <tr><td></td></tr>
                            <tr id = "chg_pwd_tr">
                                <td><label>Change Password: </label></td>
                                <td><input type="checkbox" id="pwd_change" /></td>
                            </tr>
                            <tr><td></td></tr>
                                <tr>
                                <td><label>Password: </label></td>
                                <td><input type="password" id="u_password" placeholder="" style="width:350px;" disabled/></td>
                            </tr>
                            <tr><td></td></tr>
                        </table>
                    </div>
                    
                    <p>
                        <button id="addUser" class="btn click" onclick="addUser();" style="visibility: visible;">Add</button>
                    </p>
                        
                    <!--------- Creating User Data table ----------->
                    <table id="users_tbl" class="display projects-table table table-striped table-bordered table-hover nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                    <!--------------------------------->
                </div> 
            </div>
        </div>
        <br>
    </div>
    
</div>
@endsection


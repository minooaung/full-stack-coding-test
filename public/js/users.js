$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    //alert('herere');
    var table = $('#users_tbl').DataTable({
        //"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>> " + "t" + "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
        "bDestroy": true,
        "iDisplayLength": 10,
        "serverSide": false,
        "stateSave": true,
        "scrollY"  : "600px",
        "scrollX": true,
        "scrollCollapse": true,
        "emptyTable": "No Records found",
		"sPaginationType": "full_numbers",
        "ajax": {
            "type": "GET",
            "url": '/allusers',
            //"url": '/application/Partners/getPartners',
            "data": function (d){
                //d.sta = $('#selectStatus').val();
            }
        },
        "infoFiltered": "",
        "autoWidth": true, 
        aLengthMenu: [
            [10, 20, 30, -1],
            [10, 20, 30, "All"]
        ],
        
        "columns": [            
            {
                "data" : "id",
                "width" : "50px",
                "mRender": function (data, type, row) {
                    if (data === '') {
                        return '--';
                    } else {
                        return data;
                    }
                }                
            },{
                "data" : "name",
                "width": "180px",
                "mRender": function (data, type, row) {
                    if (data === '') {
                        return '--';
                    } else {
                        return data;
                        //return data.replace(/,/g, ', ');
                    }
                }                
            },{
                "data" : "email",
                "width" : "180px",
                "mRender": function (data, type, row) {
                    if (data === '') {
                        return '--';
                    } else {
                        return data;
                    }
                }                
            },{
                "data" : "is_admin",
                "width" : "80px",
                "mRender": function (data, type, row) {
                    if (data === 1) {
                        return 'Admin';
                    } else {
                        return 'Employee';
                    }
                }                
            },{
                "data": "buttons",
                "width" : "120px",
                "bSearchable": false,
                "mRender": function (data, type, row) {
                    return '<button class="btn click" onclick="editUser('+ row.id +',\'' + row.name + '\',\'' + row.email +'\');">Edit</button><button class="btn click" onclick="deleteUser('+ row.id +');">Delete</button>';
                    //return '<button class="btn click" onclick="editUser('+ row.id +');">Edit</button><button class="btn click" onclick="deleteUser('+ row.id +');">Delete</button>';
                }
            }
        ]
    });
    table.ajax.reload();
    
    addUser = function() {
        $("#u_name").val('');
        $("#u_email").val('');
        $("#u_password").val('');
        
        $("#pwd_change").prop("checked", true);
        $("#is_admin_tr").show();
        $("#chg_pwd_tr").hide();
        $("#u_password").removeAttr('disabled');
        
        $( "#addEditDialog" ).dialog({
            title: 'Add User',
            modal: true,
            height: 300,
            width: 500,
            closeOnEscape: true,
            buttons: [
                {
                    text: "Cancel",
                    "class": 'btn click',
                    click: function() {
                        $("#addEditDialog").dialog("close");
                    }
                },
                {
                    text: "Save",
                    "class": 'btn click',
                    click: function() {
                        submitUser();
                    }
                }
            ]
        });
    };
    
    $("#pwd_change").on('change', function() { 
        if(this.checked) {
            $("#u_password").removeAttr('disabled');
        } else {
            $("#u_password").attr('disabled', 'disabled');
        }        
    });
    
    editUser = function(id, name, email) {
        $('#u_name').val(name);
        $('#u_email').val(email);
        $("#u_password").val('');
        
        if (id) {
            $("#chg_pwd_tr").show();
            $("#pwd_change").prop("checked", false);
            $("#is_admin_tr").hide();
            $("#u_password").attr('disabled', 'disabled');
            
            $( "#addEditDialog" ).dialog({
                title: 'Edit User',
                modal: true,
                height: 300,
                width: 500,
                closeOnEscape: true,
                buttons: [
                    {
                        text: "Cancel",
                        "class": 'btn click',
                        click: function() {
                            $("#addEditDialog").dialog("close");
                        }
                    },
                    {
                        text: "Save",
                        "class": 'btn click',
                        click: function() {
                            submitUser(id);
                        }
                    }
                ]
            });            
        }        
    };
    
    deleteUser = function(id) {
        if (id) {
            $.confirm({
                title: 'Confirm',
                content: 'Are you sure you want to delete this user?',
                buttons: {
                    yes: function() {
                        $.ajax({
                            type: 'DELETE',
                            url: '/deleteuser/'+id,
                            beforeSend: function() {
                                $('body').append('<div class="modal"></div>');
                                $('body').addClass("loading");                
                            },
                            complete: function () {
                                $('body').removeClass("loading");
                            },
                            dataType: 'json',
                            success: function(data) {
                                if (data.success === true) {
                                    table.ajax.reload();
                                } else {
                                    $.confirm({
                                        title: 'Failed',
                                        content: data.msg,
                                        buttons: {                            
                                            close: function(){}
                                        }
                                    });
                                }
                            },
                            error: function(jqXHR,textStatus,errorThrown){
                            }
                        });	
                    },
                    no: function(){
                    }
                }
            });
        }
    };
    
    validateEmail = function (email) {
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        if( !emailReg.test( email ) ) {
            return false;
        } else {
            return true;
        }
    };
    
    function submitUser(user_id = 0) {
        var method = 'POST';
        var ajaxUrl = '/adduser';
        
        var user_name = $('#u_name').val();
        var user_email = $('#u_email').val();
        var user_is_admin = 0;
        
        var is_valid_email = validateEmail(user_email);
        
        if (user_name == '') {
            $.confirm({
                title: 'Alert',
                content: 'Please enter name',
                buttons: {                            
                    close: function(){}
                }
            });
            return true;
        }
        
        if (user_email == '' || !is_valid_email) {
            $.confirm({
                title: 'Alert',
                content: 'Please enter valid email address',
                buttons: {                            
                    close: function(){}
                }
            });
            return true;
        }
        
        if ($("#pwd_change").prop('checked') == true && $("#u_password").val() == '') {
            $.confirm({
                title: 'Alert',
                content: 'Please enter password',
                buttons: {                            
                    close: function(){}
                }
            });
            return true;
        }
        
        var user_password = '';
        if ($("#pwd_change").prop('checked') == true && $("#u_password").val() !== '') {
            user_password = $("#u_password").val();
        }
        
        if (user_id > 0) {
            method = 'PUT';
            ajaxUrl = '/updateuser/'+user_id;
        } else {
            if ($("#is_admin_checkbox").prop('checked') == true) {
                user_is_admin = 1;
            }
        }
        
        $.ajax({
            type: method,
            url: ajaxUrl,
            data: {
                name: user_name,
                email: user_email,
                password: user_password,
                is_admin: user_is_admin
            },
            beforeSend: function() {
                $('body').append('<div class="modal"></div>');
                $('body').addClass("loading");                
            },
            complete: function () {
                $('body').removeClass("loading");
            },
            dataType: 'json',
            success: function(data) {
                //console.log(data.success);
                if (data.success === true) {
                    table.ajax.reload();
                    $("#addEditDialog").dialog("close");                    
                } else {
                    $.confirm({
                        title: 'Failed',
                        content: data.msg,
                        buttons: {                            
                            close: function(){}
                        }
                    });
                    $('body').removeClass("loading");
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
            }
        });
    }
});
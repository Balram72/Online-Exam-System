<?php

//user.php

include('Soes.php');

$object = new Soes;

if(!$object->is_login())
{
    header("location:".$object->base_url."admin");
}

if(!$object->is_master_user())
{
    header("location:".$object->base_url."admin/result.php");
}

include('header.php');
                
?>

<div class="container-fluid px-4">
	<h1 class="mt-4">User Management</h1>
	<ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Student List</li>
    </ol>
    <span id="message"></span>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col">
                    <h6 class="m-0 font-weight-bold">User List</h6>
                </div>
                <div class="col" align="right">
                    <button type="button" name="add_user" id="add_user" class="btn btn-success btn-circle btn-sm"><i class="fas fa-plus"></i></button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="user_table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>User Name</th>
                            <th>User Contact No.</th>
                            <th>User Email</th>
                            <th>User Password</th>
                            <th>Created On</th>
                            <th>Status</th> 
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
include('footer.php');
?>

<div id="userModal" class="modal fade">
  	<div class="modal-dialog">
    	<form method="post" id="user_form" enctype="multipart/form-data">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">Add Data</h4>
          			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        		</div>
        		<div class="modal-body">
        			<span id="form_message"></span>
                    <div class="mb-3">
                        <div class="row">
                            <label class="col-md-4 text-right">User Name <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="user_name" id="user_name" class="form-control" required data-parsley-pattern="/^[a-zA-Z\s]+$/" data-parsley-maxlength="150" data-parsley-trigger="keyup" />
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <label class="col-md-4 text-right">User Contact No. <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="user_contact_no" id="user_contact_no" class="form-control" required data-parsley-type="integer" data-parsley-minlength="10" data-parsley-maxlength="12" data-parsley-trigger="keyup" />
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="row">
                            <label class="col-md-4 text-right">User Email <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="text" name="user_email" id="user_email" class="form-control" required data-parsley-type="email" data-parsley-maxlength="150" data-parsley-trigger="keyup" />
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="row">
                            <label class="col-md-4 text-right">User Password <span class="text-danger">*</span></label>
                            <div class="col-md-8">
                                <input type="password" name="user_password" id="user_password" class="form-control" required data-parsley-minlength="6" data-parsley-maxlength="16" data-parsley-trigger="keyup" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="row">
                            <label class="col-md-4 text-right">User Profile</label>
                            <div class="col-md-8">
                                <input type="file" name="user_image" id="user_image" />
                                <span id="user_uploaded_image"></span>
                            </div>
                        </div>
                    </div>
        		</div>
        		<div class="modal-footer">
          			<input type="hidden" name="hidden_id" id="hidden_id" />
          			<input type="hidden" name="action" id="action" value="Add" />
          			<input type="submit" name="submit" id="submit_button" class="btn btn-success" value="Add" />
          			<button type="button" class="btn btn-default" data-bs-dismiss="modal" aria-label="Close">Close</button>
        		</div>
      		</div>
    	</form>
  	</div>
</div>

<script>
$(document).ready(function(){

	var dataTable = $('#user_table').DataTable({
		"processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url:"user_action.php",
			type:"POST",
			data:{action:'fetch'}
		},
		"columnDefs":[
			{
				"targets":[0, 4, 7],
				"orderable":false,
			},
		],
	});

	$('#add_user').click(function(){
		
		$('#user_form')[0].reset();

		$('#user_form').parsley().reset();

    	$('#modal_title').text('Add Data');

    	$('#action').val('Add');

    	$('#submit_button').val('Add');

    	$('#userModal').modal('show');

    	$('#form_message').html('');

        $('#user_uploaded_image').html('');

	});

    $('#user_image').change(function(){
        var extension = $('#user_image').val().split('.').pop().toLowerCase();
        if(extension != '')
        {
            if(jQuery.inArray(extension, ['gif','png','jpg','jpeg']) == -1)
            {
                alert("Invalid Image File");
                $('#user_image').val('');
                return false;
            }
        }
    });

	$('#user_form').parsley();

	$('#user_form').on('submit', function(event){
		event.preventDefault();
		if($('#user_form').parsley().isValid())
		{		
			$.ajax({
				url:"user_action.php",
				method:"POST",
				data:new FormData(this),
				dataType:'json',
                contentType:false,
                processData:false,
				beforeSend:function()
				{
					$('#submit_button').attr('disabled', 'disabled');
					$('#submit_button').val('wait...');
				},
				success:function(data)
				{
					$('#submit_button').attr('disabled', false);
					if(data.error != '')
					{
						$('#form_message').html(data.error);
						$('#submit_button').val('Add');
					}
					else
					{
						$('#userModal').modal('hide');
						$('#message').html(data.success);
						dataTable.ajax.reload();

						setTimeout(function(){

				            $('#message').html('');

				        }, 5000);
					}
				}
			})
		}
	});

	$(document).on('click', '.edit_button', function(){

		var user_id = $(this).data('id');

		$('#user_form').parsley().reset();

		$('#form_message').html('');

		$.ajax({

	      	url:"user_action.php",

	      	method:"POST",

	      	data:{user_id:user_id, action:'fetch_single'},

	      	dataType:'JSON',

	      	success:function(data)
	      	{

	        	$('#user_name').val(data.user_name);
                $('#user_email').val(data.user_email);
                $('#user_contact_no').val(data.user_contact_no);
                $('#user_password').val(data.user_password);

                $('#user_uploaded_image').html('<img src="'+data.user_profile+'" class="img-fluid img-thumbnail" width="75" height="75" /><input type="hidden" name="hidden_user_image" value="'+data.user_profile+'" />');

	        	$('#modal_title').text('Edit Data');

	        	$('#action').val('Edit');

	        	$('#submit_button').val('Edit');

	        	$('#userModal').modal('show');

	        	$('#hidden_id').val(user_id);

	      	}

	    })

	});

	$(document).on('click', '.delete_button', function(){

    	var id = $(this).data('id');
        var status = $(this).data('status');
        var next_status = 'Enable';
        if(status == 'Enable')
        {
            next_status = 'Disable';
        }
        if(confirm("Are you sure you want to "+next_status+" it?"))
        {
            $.ajax({
                url:"user_action.php",
                method:"POST",
                data:{id:id, action:'delete', status:status, next_status:next_status},
                success:function(data)
                {
                    $('#message').html(data);
                    dataTable.ajax.reload();
                    setTimeout(function(){
                        $('#message').html('');
                    }, 5000);
                }
            });
        }

  	});

});
</script>
<?php

//classes.php

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
	<h1 class="mt-4">Classes Management</h1>
	<ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Class List</li>
    </ol>

    <span id="message"></span>
    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col">
                    <h6 class="m-0 font-weight-bold">Class List</h6>
                </div>
                <div class="col" align="right">
                    <button type="button" name="add_class" id="add_class" class="btn btn-success btn-circle btn-sm"><i class="fas fa-plus"></i></button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="class_table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Class Name</th>
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

<div id="classModal" class="modal fade">
  	<div class="modal-dialog">
    	<form method="post" id="class_form">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">Add Class</h4>
          			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        		</div>
        		<div class="modal-body">
        			<span id="form_message"></span>
		          	<div class="mb-3">
		          		<label>Class Name</label>
		          		<input type="text" name="class_name" id="class_name" class="form-control" required  data-parsley-trigger="keyup" />
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

	var dataTable = $('#class_table').DataTable({
		"processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url:"classes_action.php",
			type:"POST",
			data:{action:'fetch'}
		},
		"columnDefs":[
			{
				"targets":[2],
				"orderable":false,
			},
		],
	});

	$('#add_class').click(function(){
		
		$('#class_form')[0].reset();

		$('#class_form').parsley().reset();

    	$('#modal_title').text('Add Class');

    	$('#action').val('Add');

    	$('#submit_button').val('Add');

    	$('#classModal').modal('show');

    	$('#form_message').html('');

	});

	$('#class_form').parsley();

	$('#class_form').on('submit', function(event){
		event.preventDefault();
		if($('#class_form').parsley().isValid())
		{		
			$.ajax({
				url:"classes_action.php",
				method:"POST",
				data:$(this).serialize(),
				dataType:'json',
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
						$('#classModal').modal('hide');
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

		var class_id = $(this).data('id');

		$('#class_form').parsley().reset();

		$('#form_message').html('');

		$.ajax({

	      	url:"classes_action.php",

	      	method:"POST",

	      	data:{class_id:class_id, action:'fetch_single'},

	      	dataType:'JSON',

	      	success:function(data)
	      	{

	        	$('#class_name').val(data.class_name);

	        	$('#modal_title').text('Edit Class');

	        	$('#action').val('Edit');

	        	$('#submit_button').val('Edit');

	        	$('#classModal').modal('show');

	        	$('#hidden_id').val(class_id);

	      	}

	    })

	});

	$(document).on('click', '.status_button', function(){
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

        		url:"classes_action.php",

        		method:"POST",

        		data:{id:id, action:'change_status', status:status, next_status:next_status},

        		success:function(data)
        		{

          			$('#message').html(data);

          			dataTable.ajax.reload();

          			setTimeout(function(){

            			$('#message').html('');

          			}, 5000);

        		}

      		})

    	}
	});

	$(document).on('click', '.delete_button', function(){

    	var id = $(this).data('id');

    	if(confirm("Are you sure you want to remove it?"))
    	{

      		$.ajax({

        		url:"classes_action.php",

        		method:"POST",

        		data:{id:id, action:'delete'},

        		success:function(data)
        		{

          			$('#message').html(data);

          			dataTable.ajax.reload();

          			setTimeout(function(){

            			$('#message').html('');

          			}, 5000);

        		}

      		})

    	}

  	});

});
</script>
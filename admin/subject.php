<?php

//subject.php

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
	<h1 class="mt-4">Student Management</h1>
	<ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Student List</li>
    </ol>
    <span id="message"></span>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col">
                    <h6 class="m-0 font-weight-bold">Subject List</h6>
                </div>
                <div class="col" align="right">
                    <button type="button" name="add_subject" id="add_subject" class="btn btn-success btn-circle btn-sm"><i class="fas fa-plus"></i></button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="subject_table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Subject Name</th>
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

<div id="subjectModal" class="modal fade">
  	<div class="modal-dialog">
    	<form method="post" id="subject_form">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">Add Subject</h4>
          			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        		</div>
        		<div class="modal-body">
        			<span id="form_message"></span>
                    <!--<div class="mb-3">
                        <label>Class Name</label>
                        <select name="class_id" id="class_id" class="form-control" required>
                            <option value="">Select Class</option>
                            <?php
                            foreach($result as $row)
                            {
                                echo '<option value="'.$row["class_id"].'">'.$row["class_name"].'</option>';
                            }
                            ?>
                        </select>
                    </div>!-->
		          	<div class="mb-3">
		          		<label>Subject Name</label>
		          		<input type="text" name="subject_name" id="subject_name" class="form-control" required data-parsley-trigger="keyup" />
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

	var dataTable = $('#subject_table').DataTable({
		"processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url:"subject_action.php",
			type:"POST",
			data:{action:'fetch'}
		},
		"columnDefs":[
			{
				"targets":[3],
				"orderable":false,
			},
		],
	});

    //
    $('#add_subject').click(function(){
        
        $('#subject_form')[0].reset();

        $('#subject_form').parsley().reset();

        $('#modal_title').text('Add Subject');

        $('#action').val('Add');

        $('#submit_button').val('Add');

        $('#subjectModal').modal('show');

        $('#form_message').html('');

    });
    //

	$('#subject_form').parsley();

	$('#subject_form').on('submit', function(event){
		event.preventDefault();
		if($('#subject_form').parsley().isValid())
		{		
			$.ajax({
				url:"subject_action.php",
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
						$('#subjectModal').modal('hide');
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

		var subject_id = $(this).data('id');

		$('#subject_form').parsley().reset();

		$('#form_message').html('');

		$.ajax({

	      	url:"subject_action.php",

	      	method:"POST",

	      	data:{subject_id:subject_id, action:'fetch_single'},

	      	dataType:'JSON',

	      	success:function(data)
	      	{
                //$('#class_id').val(data.class_id);

	        	$('#subject_name').val(data.subject_name);

	        	$('#modal_title').text('Edit Subject');

	        	$('#action').val('Edit');

	        	$('#submit_button').val('Edit');

	        	$('#subjectModal').modal('show');

	        	$('#hidden_id').val(subject_id);

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

        		url:"subject_action.php",

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

        		url:"subject_action.php",

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
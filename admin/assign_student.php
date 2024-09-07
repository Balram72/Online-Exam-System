<?php

//assign_student.php

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

$object->query = "
SELECT * FROM class_soes 
WHERE class_status = 'Enable' 
ORDER BY class_name ASC";

$class_data = $object->get_result();

$object->query = "
SELECT * FROM student_soes 
WHERE student_status = 'Enable' 
ORDER BY student_name ASC
";

$student_data = $object->get_result();

// $object->query = "
// SELECT * 
// FROM exam_soes
// JOIN class_soes ON class_soes.class_id = exam_soes.exam_class_id
// WHERE exam_soes.exam_status = 'Pending'
// ";
// $exam_data = $object->get_result();



include('header.php');
?>
<div class="container-fluid px-4">
	<h1 class="mt-4">Assign Student Management</h1>
	<ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Assign Student</li>
    </ol>
	
	<span id="message"></span>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col">
                    <h6 class="m-0 font-weight-bold">Student List with Class Name</h6>
                </div>
                <div class="col" align="right">
                    <button type="button" name="assign_student" id="assign_student" class="btn btn-success btn-circle btn-sm"><i class="fas fa-plus"></i></button>
					<button id="sendEmailsButton" type="button" class="btn btn-outline-success btn-circle btn-sm"><i class="fas fa-paper-plane"></i>&nbsp;&nbsp; Send Exam Email </button>
                    <button id="sendExamEmailsButton" type="button" class="btn btn-outline-warning btn-circle btn-sm"><i class="fas fa-stopwatch"></i>&nbsp;&nbsp; Exam Reminder Email </button>

                </div>
            </div>
        </div>
        <div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" id="student_assign_table" width="100%" cellspacing="0">
					<thead>
						<tr>
				   		<th>Send Exam Email</th>
							<th>Roll No.</th>
							<th>Student Name</th>
							<th>Student Email</th>
							<th>Class Name</th>
							<th>Exam Name</th>
							<th>Created On</th>
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

<div id="studentassignModal" class="modal fade">
  	<div class="modal-dialog">
    	<form method="post" id="student_assign_form">
      		<div class="modal-content">
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">Assign Student to Class</h4>
          			<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        		</div>
        		<div class="modal-body">
        			<span id="form_message"></span>
                    <div class="mb-3">
                        <label>Class Name</label>
                        <select name="class_id" id="class_id" class="form-select" required>
                            <option value="">Select Class</option>
                            <?php
                            foreach($class_data as $row)
                            {
																echo '<option value="' . $row['class_id'] . '" data-name="' .$row['class_name'] . '">' .$row['class_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
		          		<label>Exam Name</label>
		          		<select name="exam_id" id="exam_id" class="form-select" required >
                            <option value="">Select Exam</option>
                            
                        </select>
		          	</div>

		          	<div class="mb-3">
		          		<label>Student Name</label>
		          		<select name="student_id" id="student_id" class="form-select" required >
                            <option value="">Select Student</option>
                            <?php
                            foreach($student_data as $row)
                            {
								echo '<option value="' . $row['student_id'] . '" data-name="' .$row['student_name'] . '">' .$row['student_name'] . '</option>';
																
			                }
                            ?>
                        </select>
		          	</div>
                    <div class="mb-3">
                        <label>Roll No.</label>
                        <input type="text" name="student_roll_no" id="student_roll_no" class="form-control" required />
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
    $('#class_id').change(function(){
            var class_id = $('#class_id').val();
            if(class_id != '')
            {
                $.ajax({
                    url:"assign_student_action.php",
                    method:"POST",
                    data:{action:'fetch_subject', class_id:class_id},
                    success:function(data)
                    {
                        $('#exam_id').html(data);
                    }
                });
            }
        });
        
// function updateRollNo() {
//     var studentSelect = document.getElementById('student_id');

//     var selectedStudentOption = studentSelect.options[studentSelect.selectedIndex];
//     var studentName = selectedStudentOption.getAttribute('data-name');
//     var rollNoField = document.getElementById('student_roll_no');

//     var rand = Math.floor(Math.random() * 1000000); // Generate a number up to 999999
//     var randPadded = rand.toString().padStart(6, '0'); // Pad with leading zeros to ensure 6 digits
//     rollNoField.value = studentName+'@'+randPadded;
// }
// document.getElementById('class_id').addEventListener('change', updateRollNo);

</script>
<script>
$(document).ready(function(){

	var dataTable = $('#student_assign_table').DataTable({
		"processing" : true,
		"serverSide" : true,
		"order" : [],
		"ajax" : {
			url:"assign_student_action.php",
			type:"POST",
			data:{action:'fetch'}
		},
		"columnDefs":[
			{
				"targets":[4],
				"orderable":false,
			},
		],
	});

    $('#assign_student').click(function(){
        
        $('#student_assign_form')[0].reset();

        $('#student_assign_form').parsley().reset();

        $('#modal_title').text('Assign Class to Student');

        $('#action').val('Add');

        $('#submit_button').val('Add');

        $('#studentassignModal').modal('show');

        $('#form_message').html('');

    });
    
	$('#student_assign_form').parsley();

	$('#student_assign_form').on('submit', function(event){
		event.preventDefault();
		if($('#student_assign_form').parsley().isValid())
		{		
			$.ajax({
				url:"assign_student_action.php",
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
						$('#studentassignModal').modal('hide');
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

		var student_to_class_id = $(this).data('id');

		$('#student_assign_form').parsley().reset();

		$('#form_message').html('');

		$.ajax({

	      	url:"assign_student_action.php",

	      	method:"POST",

	      	data:{student_to_class_id:student_to_class_id, action:'fetch_single'},

	      	dataType:'JSON',

	      	success:function(data)
	      	{
                $('#class_id').val(data.class_id);

	        	$('#student_id').val(data.student_id);

	        	$('#modal_title').text('Edit Data');

	        	$('#action').val('Edit');

	        	$('#submit_button').val('Edit');

	        	$('#studentassignModal').modal('show');

	        	$('#hidden_id').val(student_to_class_id);

	      	}

	    })

	});

	$(document).on('click', '.delete_button', function(){

    	var id = $(this).data('id');

    	if(confirm("Are you sure you want to remove it?"))
    	{

      		$.ajax({

        		url:"assign_student_action.php",

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

$(document).ready(function() {
            $('#sendEmailsButton').click(function() {
                var emails = [];
                $('.student_checkbox:checked').each(function() {
                    emails.push($(this).data('email'));
                });

                if (emails.length > 0) {
                    $.ajax({
                        url: './student_emails/send_exam_email.php',
                        method: 'POST',
                        data: {emails: emails},
                        success: function(response) {
                            alert(response);
                        },
                        error: function(xhr, status, error) {
                            alert('An error occurred: ' + xhr.status + ' ' + xhr.statusText);
                        }
                    });
                } else {
                    alert('Please select at least one student.');
                }
            });
        });
        
$(document).ready(function() {
            $('#sendExamEmailsButton').click(function() {
                var emails = [];
                $('.student_checkbox:checked').each(function() {
                    emails.push($(this).data('email'));
                });

                if (emails.length > 0) {
                    $.ajax({
                        url: './student_emails/exam_reminder_email.php',
                        method: 'POST',
                        data: {emails: emails},
                        success: function(response) {
                            alert(response);
                        },
                        error: function(xhr, status, error) {
                            alert('An error occurred: ' + xhr.status + ' ' + xhr.statusText);
                        }
                    });
                } else {
                    alert('Please select at least one student.');
                }
            });
        });

</script>
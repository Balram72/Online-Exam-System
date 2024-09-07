<?php
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

include('header.php');
?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Assign Student Result (Pass - Fail)</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Assign Student Result</li>
    </ol>
    
    <span id="message"></span>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col">
                    <h6 class="m-0 font-weight-bold">Student List with Class Name</h6>
                </div>
                <div class="col" align="right">
                    <!--<button type="button" name="assign_student" id="assign_student" class="btn btn-success btn-circle btn-sm"><i class="fas fa-plus"></i></button>-->
                    <button id="sendEmailsButton" type="button" class="btn btn-info btn-circle btn-sm"><i class="fas fa-paper-plane"></i>&nbsp;&nbsp; Final Email </button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Send Exam Email</th>
                            <th>Student Photo</th>
                            <th>Roll No.</th>
                            <th>Student Name</th>
                            <th>Student Email</th>
                            <th>Class Name</th>
                            <th>Exam Name</th>
                             <th>Student Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include('../conn.php');
                        // Main query
                        $q = 'SELECT * FROM student_to_class_soes
                              INNER JOIN class_soes ON class_soes.class_id = student_to_class_soes.class_id
                              INNER JOIN exam_soes ON exam_soes.exam_id = student_to_class_soes.exam_id
                              INNER JOIN student_soes ON student_soes.student_id = student_to_class_soes.student_id';
                        $result = mysqli_query($con, $q);
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                
                                    $class_id = $row['class_id'];
                                    $student_id= $row['student_id'];
                                    $exam_status = $row["exam_status"];
                            
                                $sql = "SELECT  * FROM exam_subject_question_answer WHERE student_id = '$student_id'";
                                     $result2 = mysqli_query($con, $sql);
                                      if ($result2 && mysqli_num_rows($result2) > 0) {
                                        $ros = mysqli_fetch_assoc($result2);
                                         $students_id= $ros['student_id'];
                                 }
                        ?>
                                <tr>
                                    <td><p style="text-align:center;margin-top:10px"><input type="checkbox" class="student_checkbox form-check-input" data-email="<?php echo htmlspecialchars($row["student_email_id"]); ?>"></p></td>
                                    <td><img src="<?php echo htmlspecialchars($row["student_image"]); ?>" class="img-fluid img-thumbnail" width="75" height="75"/></td>
                                    <td><?php echo htmlspecialchars($row["student_roll_no"]); ?></td>
                                    <td><?php echo htmlspecialchars($row["student_name"]); ?></td> 
                                    <td><?php echo htmlspecialchars($row["student_email_id"]); ?></td>
                                    <td><?php echo htmlspecialchars($row["class_name"]); ?></td>
                                    <td><?php echo htmlspecialchars($row["exam_title"]); ?></td>
                                    <td>
                                        
                                        <?php
                                        	if($row["student_status"] == 'Enable')
                                                			{
                                                				echo '<button type="button" name="status_button" class="btn btn-primary btn-sm status_button" data-id="'.$row["student_id"].'" data-status="'.$row["student_status"].'">Enable</button>';
                                                			}
                                                			else
                                                			{
                                                				echo '<button type="button" name="status_button" class="btn btn-danger btn-sm status_button" data-id="'.$row["student_id"].'" data-status="'.$row["student_status"].'">Disable</button>';
                                                			}
                                         ?></td>
                                    <td>
                                        <div class="d-flex justify-content-start">
                                            <?php if ($exam_status == 'Completed') { 
                                                           
                                                                        if ($student_id) {
                                                                            $update_status_query = "UPDATE student_soes SET student_status ='Disable',student_email_verification_code='' WHERE student_id = '$student_id' AND student_email_verification_code=''";
                                                                            $update_result = mysqli_query($con, $update_status_query);
                                                                            if ($update_result) { }else{}
                                                                        }
                                                                     
                                                         if ($student_id == $students_id) { ?>
                                                                    <a href="./student_emails/exam_residuals_email.php?email=<?php echo $row['student_email_id']; ?>" type="button"  class="btn btn-warning btn-circle btn-sm edit_button text-center">Reminder email</a>
                                                                     <?php }else{
                                                                     ?>
                                                                    
                                                                     <a href="./student_emails/pass_email.php?email=<?php echo $row['student_email_id']; ?>" type="button" class="btn btn-success btn-circle btn-sm edit_button">Pass Email</a>
                                                                        &nbsp;
                                                                     <a href="./student_emails/fail_email.php?email=<?php echo $row['student_email_id']; ?>" type="button" class="btn btn-danger btn-circle btn-sm edit_button">Fail Email</a>
                                                                    <?php }  ?>
                                             <?php } else { ?>
                                                <?php echo "Not Start Exam"; ?>
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='7'>No records found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
include('footer.php');
?>

<script>
    $(document).ready(function() {
       $('#example').DataTable({
            "paging": true, 
            "searching": true,
            "ordering": true,
            "info": true,
            "responsive": true
        });

    $('#sendEmailsButton').click(function() {
            var emails = [];
            $('.student_checkbox:checked').each(function() {
                emails.push($(this).data('email'));
            });

            if (emails.length > 0) {
                $.ajax({
                    url: './student_emails/final_email.php',
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

        		url:"student_action.php",

        		method:"POST",

        		data:{id:id, action:'change_status', status:status, next_status:next_status},

        		success:function(data)
        		{
          			$('#message').html(data);
          			window.location.reload();
        		}

      		})

    	}
	});

        
    });
    

        
</script>



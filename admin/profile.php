<?php

include('Soes.php');

$object = new Soes;

if(!$object->is_login())
{
    header("location:".$object->base_url."admin");
}


$object->query = "
    SELECT * FROM user_soes
    WHERE user_id = '".$_SESSION["user_id"]."'
    ";

$result = $object->get_result();

include('header.php');

?>

<div class="container-fluid px-4">
	<h1 class="mt-4">Profile</h1>
	<ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
        <li class="breadcrumb-item active">Profile</li>
    </ol>
    
    <form method="post" id="profile_form" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <span id="message"></span>
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <div class="row">
                            <div class="col">
                                <h6 class="m-0 font-weight-bold text-primary">Profile</h6>
                            </div>
                            <div clas="col" align="right">
                                <input type="hidden" name="action" value="profile" />
                                <button type="submit" name="edit_button" id="edit_button" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Edit</button>
                                &nbsp;&nbsp;
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label>Name</label>
                            <input type="tex" name="user_name" id="user_name" class="form-control" required data-parsley-pattern="/^[a-zA-Z0-9 \s]+$/" data-parsley-maxlength="175" data-parsley-trigger="keyup" />
                        </div>
                        <div class="mb-3">
                            <label>Contact Number</label>
                            <input type="tex" name="user_contact_no" id="user_contact_no" class="form-control" required data-parsley-maxlength="12" data-parsley-type="integer" data-parsley-trigger="keyup" />
                        </div>
                        <div class="mb-3">
                            <label>Email Address</label>
                            <input type="tex" name="user_email" id="user_email" class="form-control" required data-parsley-maxlength="175" data-parsley-type="email" data-parsley-trigger="keyup" />
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="user_password" id="user_password" class="form-control" required data-parsley-maxlength="16" data-parsley-trigger="keyup" />
                        </div>
                        <div class="mb-3">
                            <label>Select Profile Image</label><br />
                            <input type="file" name="user_image" id="user_image" />
                            <br />
                            <span class="text-muted">Only .jpg, .png file allowed for upload</span><br />
                            <span id="uploaded_image"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php
include('footer.php');
?>

<script>
$(document).ready(function(){

    <?php
    foreach($result as $row)
    {
    ?>
    $('#user_name').val("<?php echo $row['user_name']; ?>");
    $('#user_contact_no').val("<?php echo $row['user_contact_no']; ?>");
    $('#user_email').val("<?php echo $row['user_email']; ?>");
    $('#user_password').val("<?php echo $row['user_password']; ?>");
    <?php
        if($row["user_profile"] != '')
        {
    ?>
    $('#uploaded_image').html('<img src="<?php echo $row["user_profile"]; ?>" class="img-thumbnail" width="100" /><input type="hidden" name="hidden_user_profile" value="<?php echo $row["user_profile"]; ?>" />');
    <?php
        }
        else
        {
    ?>
    $('#uploaded_image').html('<input type="hidden" name="hidden_user_profile" value="" />');
    <?php   
        }
    }
    ?>

    $('#user_image').change(function(){
        var extension = $('#user_image').val().split('.').pop().toLowerCase();
        if(extension != '')
        {
            if(jQuery.inArray(extension, ['png','jpg']) == -1)
            {
                alert("Invalid Image File");
                $('#user_image').val('');
                return false;
            }
        }
    });

    $('#profile_form').parsley();

	$('#profile_form').on('submit', function(event){
		event.preventDefault();
		if($('#profile_form').parsley().isValid())
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
					$('#edit_button').attr('disabled', 'disabled');
					$('#edit_button').html('wait...');
				},
				success:function(data)
				{
					$('#edit_button').attr('disabled', false);
                    $('#edit_button').html('<i class="fas fa-edit"></i> Edit');

                    $('#user_name').val(data.user_name);
                    $('#user_contact_no').val(data.user_contact_no);
                    $('#user_email').val(data.user_email);
                    $('#user_password').val(data.user_password);
                    $('#user_profile_name').text(data.user_name);
                    if(data.user_profile != '')
                    {
                        $('#uploaded_image').html('<img src="'+data.user_profile+'" class="img-thumbnail" width="100" /><input type="hidden" name="hidden_user_profile" value="'+data.user_profile+'" />');

                        $('#user_profile_image').attr('src', data.user_profile);
                    }
						
                    $('#message').html(data.success);

					setTimeout(function(){

				        $('#message').html('');

				    }, 5000);
				}
			})
		}
	});

});
</script>
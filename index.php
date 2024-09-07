<?php

//login.php

include('admin/Soes.php');

$object = new Soes;

if($object->is_student_login())
{
	header("location:".$object->base_url."student_dashboard.php");
}
?><!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title> Online Examination Portal </title>
	    <!-- Custom styles for this page -->
	    <link href="vendor/bootstrap/bootstrap.min.css" rel="stylesheet">
	    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	    <link rel="stylesheet" type="text/css" href="vendor/parsley/parsley.css"/>
	    <!-- Custom styles for this page -->
    	<link href="vendor/datatables/dataTables.bootstrap5.min.css" rel="stylesheet">
	    <style>
	    	.border-top { border-top: 1px solid #e5e5e5; }
			.border-bottom { border-bottom: 1px solid #e5e5e5; }
			.box-shadow { box-shadow: 0 .25rem .75rem rgba(0, 0, 0, .05); }
            .wrap-input100{
		border:1px solid #b2b2b2;
	}
    .form  #student_email_id,#student_password{
        height: 45px;
        border: 1px solid #b2b2b2;
        border-radius:0px;
       
    }
    #student_email_id,#student_password:hover{
        border: 1px solid #b2b2b2;
        border-radius:0px;
    }
  
	    </style>
	</head>
	<body>
  <div class="container">
    <div class="row">
      <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
        <div class="card border-0 shadow rounded-3 my-5">
          <div class="card-body p-4 p-sm-5">
            <h2 class="card-title text-center mb-5 ">Please Login </h2>
            <h6 id="error" class="text-center" style="color:red;"></h6>
            <form  method="post" id="login_form">
            <label for="student_email_id" class="mb-2">Email </label>
              <div class="form mb-3">
              <input type="text" name="student_email_id" id="student_email_id" class="form-control" required data-parsley-type="email" data-parsley-trigger="keyup" placeholder="Email"  />
              </div>
        
              <label for="student_password" class="mb-2">Password</label>
              <div class="form" >
              <input type="password" name="student_password" id="student_password" placeholder="Password" class=" form-control" required  />
              </div>
              <div class="d-grid">
              <br />
				    <input type="hidden" name="page" value="login" />
				    <input type="hidden" name="action" value="check_login" />
				    <input type="submit" name="submit" id="login_button" class="btn btn-primary btn-login text-uppercase  p-2 fw-bold" value="Login" />
            <!-- <p><a href="forget_password.php">Forget Password</a></p> -->
              </div>
            </form>
          </div>
        </div>  
<?php
include('footer.php');
?>
<script>
$(document).ready(function(){
	$('#login_form').parsley();
	$('#login_form').on('submit', function(event){
		event.preventDefault();
		if($('#login_form').parsley().isValid())
		{
			$.ajax({
				url:"ajax_action.php",
				method:"POST",
				data:$(this).serialize(),
				dataType:"JSON",
				beforeSend:function()
                {
                    $('#login_button').attr('disabled', 'disabled');
                    $('#login_button').val('wait...');
                },
				success:function(data)
				{
					$('#login_button').attr('disabled', false);
                    if(data.error != '')
                    {
                        $('#error').html(data.error);
                        $('#login_button').val('Login');
                    }
                    else
                    {
                        window.location.href = data.url;
                    }
				}
			});
		}
	});

});

</script>


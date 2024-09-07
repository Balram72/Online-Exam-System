<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>Online Student Exam Management System in PHP</title>

	    <!-- Custom styles for this page -->
	    <link href="vendor/bootstrap/bootstrap.min.css" rel="stylesheet">

	    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	    <link rel="stylesheet" type="text/css" href="vendor/parsley/parsley.css"/>
	    <!-- Custom styles for this page -->
    	<link href="vendor/datatables/dataTables.bootstrap5.min.css" rel="stylesheet">
	    <style>
				  body {
						overflow: hidden;
					}
			.navbar { border-bottom: 1px solid #e5e5e5; }
			.navbar { box-shadow: 0 .10rem .25rem rgba(0, 0, 0, .05); }
	    </style>
	</head>
	<body>
		<?php
		if($object->is_student_login())
		{
		?>
		<nav class="navbar navbar-expand-lg  navbar-light bg-white">
  			<div class="container-fluid">
    			<a class="navbar-brand" href="#">Logo</a>  
				
    			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      				<span class="navbar-toggler-icon"></span>
    			</button>
    			<div class="collapse navbar-collapse" id="navbarNavDropdown">
      				<ul class="navbar-nav ms-auto">
        					<!-- <li class="nav-item">
									<a class="nav-link" href="student_dashboard.php">Home</a>
        				</li> -->
        				<!-- <li class="nav-item">
										<a class="nav-link" href="exam.php">Exam</a>
        				</li> -->
        				<!-- <li class="nav-item">
										<a class="nav-link" href="logout.php" >Logout</a>
        				</li> -->
      				</ul>
    			</div>
  			</div>
		</nav>
		<?php
		}
		else
		{
		?>
		<div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom box-shadow">
			<div class="col">
		    	<h5 class="my-0 mr-md-auto font-weight-normal">Webslesson</h5>
		    </div>
		</div>
	    <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
	      	<h1 class="display-4">Online Student Exam Management System</h1>
	    </div>
	    <br />
	    <br />
		<?php
		}
		?>
	    <!-- <div class="container-fluid"> -->
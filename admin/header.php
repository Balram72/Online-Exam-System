<?php
$object->query = "
    SELECT * FROM user_soes 
    WHERE user_id = '".$_SESSION['user_id']."'
";

$user_result = $object->get_result();

$user_name = '';
$user_profile_image = '';
foreach($user_result as $row)
{
    if($row['user_name'] != '')
    {
        $user_name = $row['user_name'];
    }
    else
    {
        $user_name = 'Master';
    }

    if($row['user_profile'] != '')
    {
        $user_profile_image = $row['user_profile'];
    }
    else
    {
        $user_profile_image = '../img/undraw_profile.svg';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Dashboard - SB Admin</title>
        <link href="../css/styles.css" rel="stylesheet" />
        <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="../vendor/parsley/parsley.css"/>
        <link rel="stylesheet" type="text/css" href="../vendor/datatables/dataTables.bootstrap5.min.css"/>
    </head>

    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="dashboard.php">Online Exam System</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img class="rounded-circle" src="<?php echo $user_profile_image; ?>" id="user_profile_image" style="width:30px"> <?php echo $user_name; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <a class="nav-link" href="classes.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-door-open"></i></div>
                                Classes
                            </a>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#subject_collapse" aria-expanded="false" aria-controls="subject_collapse">
                                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                                Subject
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="subject_collapse" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="subject.php">Subject</a>
                                    <a class="nav-link" href="assign_subject.php">Assign Subject</a>
                                </nav>
                            </div>

                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#student_collapse" aria-expanded="false" aria-controls="student_collapse">
                                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                                Student
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="student_collapse" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="student.php">Student</a>
                                    <a class="nav-link" href="assign_student.php">Assign Student</a>
                                     <a class="nav-link" href="student_result.php">Student Pass & Fail Email</a>
                                </nav>
                            </div>

                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#exam_collapse" aria-expanded="false" aria-controls="exam_collapse">
                                <div class="sb-nav-link-icon"><i class="fas fa-edit"></i></div>
                                Exam
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="exam_collapse" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="exam.php">Exam</a>
                                    <a class="nav-link" href="exam_subject.php">Exam Subject</a>
                                    <a class="nav-link" href="exam_subject_question.php">Question</a>
                                </nav>
                            </div>

                            <a class="nav-link" href="user.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-users-cog"></i></div>
                                User
                            </a>                        

                            <a class="nav-link" href="logout.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-sign-out-alt"></i></div>
                                Logout
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        Admin
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
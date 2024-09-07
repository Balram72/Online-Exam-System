<?php
$exam_id = $_GET['exam_id'];
// echo $exam_id;
require '../../vendor/autoload.php'; 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include("../../conn.php");

$q = "SELECT exam_class_id FROM exam_soes WHERE exam_id = '".$exam_id."' ";
$result = mysqli_query($con,$q);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$exam_class_id =   $row['exam_class_id'];

// echo $exam_class_id;

$q = "SELECT student_id, student_roll_no FROM student_to_class_soes WHERE class_id = '".$exam_class_id."'";
$result = mysqli_query($con,$q);
$students = array();
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $student_roll_no = $row['student_roll_no'];
    $student_id = $row['student_id'];
  
    $students[] = array(
        'student_id' => $student_id,
        'student_roll_no' => $student_roll_no
    );
}
foreach ($students as $student) {
    // echo "Student ID: " . $student['student_id'] . " - Roll No: " . $student['student_roll_no'] . "<br>";
    $query = " SELECT * FROM student_soes WHERE student_id = '".$student['student_id']."'";
    $result = mysqli_query($con,$query);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
      $student_email_id = $row['student_email_id'];
      $student_name = $row['student_name'];

      if ($student_email_id) {
    
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'allumniportal.cloud';
            $mail->SMTPAuth = true;
            $mail->Username = 'examinfo@allumniportal.cloud';
            $mail->Password = 'QaTbLQ!Seq(T';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->setFrom('examinfo@allumniportal.cloud', 'online Exam');
            $mail->addAddress($student_email_id);
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Payment Confirmation and Thank You |'. $student['student_roll_no'].' | OnlineExam.';
            $mail->Body = ' <body style="font-family: Arial, sans-serif; font-size: 14px;">
               <p>Dear <b>'.$student_name.'</b>,</p>
                        <p>
                        <a href="https://allumniportal.cloud/resultshow.php">Cleck the Link</a>
                        If you have any questions or need more information, feel free to reach out. at hr@onlineexamportalserver.com <br>
                     <br/><br/><br/><br/><br/>
                    Regards<br/>
                 
                       
                        </p>
      
                    </font>';
      
            if ($mail->send()) {
                header('location:../exam.php');
                   exit();
            } else {
                throw new Exception('Email could not be sent. Mailer Error: ' . $mail->ErrorInfo);
            }
        } catch (Exception $e) {
            echo '<script>alert("Email could not be sent. ' . $e->getMessage() . '");</script>';
        }
      } else {
        echo '<script>alert("Invalid request.");</script>';
      }
}





?>
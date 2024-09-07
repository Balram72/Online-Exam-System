<?php
require '../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include("../../conn.php");
if (isset($_POST['emails']) && !empty($_POST['emails'])) {
    $emails = $_POST['emails']; 
    $successCount = 0;
    $errorCount = 0;
    foreach ($emails as $email) {
        $query = "SELECT * FROM student_soes WHERE student_email_id = '".$email."'";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $student_email_id = $row['student_email_id'];
        $student_name = $row['student_name'];
        $student_id = $row['student_id'];
        $student_password = $row['student_password'];

        $query = "SELECT * FROM student_to_class_soes WHERE student_id = '".$student_id."'";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $student_roll_no = $row['student_roll_no'];
        $student_class_id = $row['class_id'];


        $query = "SELECT * FROM exam_soes WHERE exam_class_id = '".$student_class_id."'";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $student_exam_id = $row['exam_id'];


        $query = "SELECT * FROM subject_wise_exam_detail WHERE exam_id = '".$student_exam_id."'";
        $result = mysqli_query($con, $query);
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $subject_exam_datetime = $row['subject_exam_datetime'];
  
   
   
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
                $mail->setFrom('examinfo@allumniportal.cloud','Online Examination Portal');
                $mail->addAddress($student_email_id);
                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';
                $mail->Subject = 'Exam Reminder Email |Applicant Roll No : '.$student_roll_no.' |Online Examination Portal.';
                $mail->Body = ' 
                            <html lang="en">
                            <head>
                                <meta charset="UTF-8">
                                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                <title>Online Exam Details</title>
                                <style>
                                    body {
                                        font-family: Arial, sans-serif;
                                        line-height: 1.6;
                                        color: #333;
                                        padding: 20px;
                                        background-color: #f4f4f4;
                                    }
                                    .container {
                                        max-width: 600px;
                                        margin: auto;
                                        background: #fff;
                                        padding: 20px;
                                        border-radius: 8px;
                                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                                    }
                                    h1 {
                                        color: #007bff;
                                    }
                                    .details {
                                        margin: 20px 0;
                                    }
                                    .details p {
                                        margin: 10px 0;
                                    }
                                    .guidelines {
                                        margin-top: 20px;
                                    }
                                    .guidelines ul {
                                        margin: 10px 0;
                                        padding-left: 20px;
                                    }
                                    .guidelines li {
                                        margin-bottom: 10px;
                                    }
                                    .footer {
                                        margin-top: 20px;
                                    }
                                    .footer p {
                                        margin: 5px 0;
                                    }
                                </style>
                            </head>
                            <body>
                                <div class="container">
                                    <h1>Dear '.$student_name.',</h1>
                                    <p>Your Examination Roll Number: '.$student_roll_no.'</p>
                                    <p>We hope this message finds you well.</p>
                                    <p>We are writing to inform you about the upcoming online exam for your course. Please find the details below:</p>
                                    
                                    <div class="details">
                                       <p> <h3>Exam Details:</br>
                                            <ul>
                                                <li>login id = '.$student_email_id.'</li>
                                                <li>login Password = '.$student_password.'</li>
                                            </ul></h3>  
                                        </p>
                                        <p><strong>Date & Time: '.$subject_exam_datetime.' </p>
                                        <p><strong>Access Link:</strong> <a href="https://allumniportal.cloud/" target="_blank">Please Click The Link</a></p>
                                    </div>
                                    
                                    <div class="guidelines">
                                        <h2>Conduct Guidelines:</h2>
                                        <ul>
                                            <li><strong>Technical Requirements:</strong> Ensure your device (computer/laptop) is charged and has a stable internet connection.</li>
                                            <li><strong>Login Time:</strong> Log in to the exam platform at least 15 minutes before the scheduled time to avoid any technical issues.</li>
                                            <li><strong>Identification:</strong> Be prepared to show your student ID via webcam for verification.</li>
                                            <li><strong>Permitted Materials:</strong> Only the following items are allowed: [List of allowed items, if any].</li>
                                            <li><strong>Prohibited Items:</strong> Notes, books, and electronic devices (other than the exam device) are not allowed unless specified.</li>
                                            <li><strong>Environment:</strong> Choose a quiet and well-lit place to take the exam to avoid disturbances.</li>
                                            <li><strong>Behavior:</strong> Maintain academic integrity. Any form of cheating or plagiarism will be dealt with strictly.</li>
                                            <li><strong>Dress Code:</strong> Adhere to the appropriate dress code as per the school regulations.</li>
                                        </ul>
                                    </div>
                                    
                                    <div class="footer">
                                        <p>Failure to comply with these guidelines may result in disciplinary action. Your cooperation is essential to ensure a smooth and fair examination process for everyone.</p>
                                        <p>If you have any questions or need further clarification, please do not hesitate to contact the examination office or your instructor.</p>
                                        <p>Thank you for your attention and cooperation.</p>
                                        <p>Best regards,</p>
                                        <p>[Your Name]<br>
                                        [Your Position]<br>
                                        [School/Organization Name]<br>
                                        [Contact Information]</p>
                                    </div>
                                </div>
                            </body>
                            </html>';
                if ($mail->send()) {  $successCount++; } else { $errorCount++;}
               } catch (Exception $e) { $errorCount++;
                   echo '<script>alert("Email could not be sent to '.$student_email_id.'. Error: ' . $e->getMessage() . '");</script>';
                }
        } else { echo "Invalid email address for $student_name";}
    }if ($successCount > 0) { echo "Emails sent successfully in Email Id:$successCount";}
    if ($errorCount > 0) { echo "Emails failed to send:$errorCoun"; }
} else { echo 'No emails provided'; }
?>

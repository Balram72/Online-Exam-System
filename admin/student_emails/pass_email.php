<?php
require '../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include("../../conn.php");

    $email = $_GET['email']; 
  
                    $query = "SELECT * FROM student_soes WHERE student_email_id = '".$email."'";
                    $result = mysqli_query($con, $query);
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $student_email_id = $row['student_email_id'];
                    $student_name = $row['student_name'];
                    $student_id = $row['student_id'];
                
                    $query = "SELECT * FROM student_to_class_soes WHERE student_id = '".$student_id."'";
                    $result = mysqli_query($con, $query);
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $student_roll_no = $row['student_roll_no'];
                    $student_class_id = $row['class_id'];
                
          if($student_email_id){
           try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'allumniportal.cloud';
            $mail->SMTPAuth = true;
            $mail->Username = 'examinfo@allumniportal.cloud';
            $mail->Password = 'QaTbLQ!Seq(T';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->setFrom('examinfo@allumniportal.cloud', 'Examination Organization');
            $mail->addAddress($student_email_id);
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Pass Email | ' . $student_roll_no . ' | Examination Organization.';
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
                        <h1>Dear ' . htmlspecialchars($student_name) . ',</h1>
                        <p>Your Examination Roll Number: ' . htmlspecialchars($student_roll_no) . '</p>
                        <p>We hope this message finds you well.</p>
                        <p>We are writing to inform you about the upcoming online exam for your course. Please find the details below:</p>
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
            $mail->send();
            echo '<script> alert("email Send Successfully") </script>';
            header('Location: ../student_result.php');
            exit();
        } catch (Exception $e) {
            echo 'Email could not be sent. Error: ' . $mail->ErrorInfo;
        }
    } else {
        echo 'Invalid email address for ' . htmlspecialchars($student_name);
    }

?>

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
                $mail->setFrom('examinfo@allumniportal.cloud', 'Examination Organization');
                $mail->addAddress($student_email_id);
                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';
                $mail->Subject = 'Exam Date Related Information |'. $student_name.' |Examination Organization.';
                $mail->Body = '
                    <body style="font-family: Arial, sans-serif; font-size: 14px;">
                        <p>Dear <b>'.$student_name.'</b>,</p>
                        <p>
                            If you have any questions or need more information, feel free to reach out. at hr@kksepl.com <br>
                            <br/><br/><br/><br/><br/>
                            Regards<br/>
                            Rahul Mishra <br/>
                            (Human Resources Manager) <br/>
                            Kamlesh Kumar Engineers Private Limited,<br/>
                            H.No. 112, Refinery Township Road , <br>
                            Opp. Shiv Deep Public School, VPO- Dadlana, <br>
                            Panipat-132140, Haryana.<br/>
                        </p>
                    </body>';
                if ($mail->send()) {  $successCount++; } else { $errorCount++;}
               } catch (Exception $e) { $errorCount++;
                   echo '<script>alert("Email could not be sent to '.$student_email_id.'. Error: ' . $e->getMessage() . '");</script>';
                }
        } else { echo "Invalid email address for $student_name";}
    }if ($successCount > 0) { echo "Emails sent successfully in Email Id:$successCount";}
    if ($errorCount > 0) { echo "Emails failed to send:$errorCoun"; }
} else { echo 'No emails provided'; }
?>

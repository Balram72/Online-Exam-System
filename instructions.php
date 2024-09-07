<?php
include('admin/Soes.php');

$object = new Soes;

if(!$object->is_student_login())
{
    header("location:".$object->base_url."");
}
$ec = '';

$exam_result_datetime = '';

if(isset($_GET["ec"]))
{
    $ec = $_GET["ec"];
    $object->query = "
    SELECT exam_id, exam_result_datetime FROM exam_soes 
    WHERE exam_code = '".$_GET["ec"]."';
    ";

    $result = $object->get_result();

    foreach($result as $row)
    {
        $exam_result_datetime = $row["exam_result_datetime"];
        $object->query = "
        SELECT * FROM subject_wise_exam_detail 
        INNER JOIN subject_soes 
        ON subject_soes.subject_id = subject_wise_exam_detail.subject_id 
        WHERE subject_wise_exam_detail.exam_id = '".$row["exam_id"]."'
        ";

        $exam_result = $object->get_result();
    }
}
else
{
    header('location:exam.php');
}

if(isset($_SESSION["ec"]))
{
    unset($_SESSION["ec"]);
}
if(isset($_SESSION["esc"]))
{
    unset($_SESSION["esc"]);
}

include('header.php');
    
?>
<style>
    body {
      overflow: hidden;
        background-color: #f5f8fa;
    }

    h6 {
        font-weight: bolder;
    }
  
    li{
      margin-top:10px;
      font-size:16px;
    }
    .scrollable-div {
        height: 630px; 
        padding: 15px; 
        background-color: #fff;
    }
    .container {
        display: flex;
        max-width: 1530px;
    }

    .left {
        width: 90%;
    }

    .right {
        width: 10%;
    }

    .footer-nav {
     
        justify-content: space-between;
        background-color:#fff;
        padding: 10px;
        border-top: 1px solid #ddd;
        position: fixed;
        width: 82.5%;
        bottom: 0;
    }

    .footer-nav a {
        text-decoration: none;
        color: #007bff;
    }
    .user-info{
        margin-top:20px;
        margin-left:-20px;
    }
    .user-info img{
        margin-right:10px;
        border-radius: 50%;
        margin-bottom: 10px;
        width:80%;
    }
    .user-info p{
        margin-top:20px;
        margin-left: 24px;
        font-size:30px;
    }
</style>
   <?php  if(isset($exam_result)){  ?>
<div class="container">
                <?php
                    foreach($exam_result as $row)
                    {
                        $status = '';
                        $action_button = '';
                        $start_time = $row["subject_exam_datetime"];
                        $duration = $object->Get_exam_duration($row["exam_id"]) . ' minute';
                        $end_time = strtotime($start_time . '+' . $duration);
                        $end_time = date('Y-m-d H:i:s', $end_time);
                        if(time() >= strtotime($start_time) && time() <= strtotime($end_time))
                        {
                            $data = array(
                                ':subject_exam_status'  =>  'Started',
                                ':exam_subject_id'      =>  $row["exam_subject_id"]    
                            );
                            $object->query = "
                            UPDATE subject_wise_exam_detail 
                            SET subject_exam_status = :subject_exam_status 
                            WHERE exam_subject_id = :exam_subject_id
                            ";
                            $object->execute($data);
                            $status = '<span class="badge bg-primary">Started</span>';

                            $action_button = '<button type="button" id="nextButton" class="btn btn-primary btn-sm" disabled>Next</button>';

                            $action_button = '<button type="button" class="btn btn-primary view_subject_exam" data-ec="'.$ec.'" data-esc="'.$row["subject_exam_code"].'" disabled style="background-color:#6DD2E4;      margin-left: 600px;  color:#F8FCFE ;border:none;">I am ready to begin</button>';
                        }
                        
                            if(strtotime($start_time) > time())
                            {
                                $status = '<span class="badge bg-warning">Pending</span>';
                                // $action_button = '<button type="button" class="btn btn-primary btn-sm view_subject_exam" data-ec="'.$ec.'" data-esc="'.$row["subject_exam_code"].'"><i class="fas fa-pencil-alt"></i></button>';
                                $action_button = '';
                            }
                        }
                        ?>
    <div class="left">
        <div class="row">
            <div class="col-md-11 scrollable-div ">
            <h2 style="text-align:center;margin-top:10px; font-size:40px"><?php echo $row["subject_name"]; ?></h2>
              <div class="row">
                  <div class="col-12 d-flex justify-content-between">
                      <b>Duration: <?php echo $duration; ?> </b>
                      <b>Maximum Marks: <?php echo $row["subject_total_question"] * $row["marks_per_right_answer"]; ?> </b>
                  </div>
              </div>
            
                <h6 style="font-size:14px; margin-top:10px;">Read the following instructions carefully.</h6>
                        <ol type="1">
                          <li>The test contains 4 sections having <?php echo $row["subject_total_question"]; ?> &nbsp;total questions.</li>
                          <li>Each question has 4 options out of which only one is correct.</li>
                          <li>You have to finish the test in  <?php echo $duration; ?>.</li>
                          <li>Please do not try to guess the answer and attempt all questions.</li>
                          <li>You will be awarded 1 marks for each correct answer.</li>
                          <li>There are no negative marks for any wrong answer or for questions that you have not attempted.</li>
                          <li>You can write this test only once. Make sure that you complete the test before you submit the test and/or  go to The Next Sestion .</li>
                        </ol>
                    
            </div>
        </div>
        
    </div>


    <div class="right">
        <div class="user-info">
           <?php
            include('./conn.php');
             $suid = $_SESSION['student_id'];
                $q = "SELECT * FROM student_soes WHERE student_id = '$suid'";
                $result = mysqli_query($con,$q);
                  if(mysqli_num_rows($result)> 0){
                      $row = mysqli_fetch_assoc($result);
                        $imagePath = $row['student_image'];
                         $correctedPath = str_replace('../', './', $imagePath);
                ?>
            <img src="<?php echo $correctedPath; ?>" alt="User Image">
            
            <?php   } else{ ?>
              <img src="./img/userdefult.png" alt="User Image">
           <?php  }  ?>
            <p><?php echo $_SESSION['STUDENT_NAME']; ?></p>
        </div>   
    </div>
</div>
<div class="footer-nav">
  <form>
  <div>
        <p>
          <b>Choose your default language:</b><select name="" id="languageSelect" style="padding:1px;" required>
          <option value="">-- Select --</option><option value="English">English</option></select><br/>
            <p class="form-text text-danger" style="margin-top:10px">
              Please note all questions will appear in your default language. This language can be changed for a
              particular question later on.
        </p>
        <hr/>
  </div>
  <br/>
  <div class="form-group" style="margin-top:-30px">
    <b>Declaration:</b>
          <div class="form-check" style="margin-left: 1.0em;  font-size:14px; color:#333333;">
            <input class="form-check-input" type="checkbox" id="declaration" required style="margin-top: 1.0em;">
            <label class="form-check-label" for="declaration" style="margin-left: 1.0em;">
             <b>I have read all the instructions carefully and have understood them. I agree not to cheat or use unfairmeans in this examination. I understand that using unfair means of any sort for my own or someone else’sadvantage will lead to my immediate disqualification. The decision of Organination will be final in these matters and cannot be appealed.</b> 
            </label>
          </div>
        </div>
           
<hr/>
  <div>
    <a href="./student_dashboard.php" type="button" class="btn btn-primary" style="background-color:#92c4f2; color:#3a3f43 ;border:none;">Previous</a>

    <?php echo $action_button?>
   
   </div>
  </form>
</div>
<?php } ?>
<?php
include('footer.php');
?>

<script>
$(document).ready(function(){
    function checkButtonState() {
        var isLanguageSelected = $('#languageSelect').val() !== '';
        var isDeclarationChecked = $('#declaration').is(':checked');
        
        if (isLanguageSelected && isDeclarationChecked) {
            $('.view_subject_exam').prop('disabled', false);
        } else {
            $('.view_subject_exam').prop('disabled', true);
        }
    }

    // Initial check
    checkButtonState();

    $('#languageSelect').on('change', function() {
        checkButtonState();
    });

    // Check when the checkbox is clicked
    $('#declaration').on('change', function() {
        checkButtonState();
    });

    $('.view_subject_exam').click(function(){
        var ec = $(this).data('ec');
        var esc = $(this).data('esc');
        $.ajax({
            url:"ajax_action.php",
            method:"POST",
            data:{page:"view_exam", action:"view_subject_exam", ec:ec, esc:esc},
            success:function(data)
            {
                //window.open(data,'_blank');
                window.location.href = data;
            }
        });
    });  

});
</script>


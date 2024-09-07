<?php

//view_subject_exam.php

include('./admin/Soes.php');

$object = new soes();

if(!$object->is_student_login())
{
    header("location:".$object->base_url."");
}

$exam_id = '';
$exam_title = '';
$class_id = '';
$exam_duration = '';

$subject_id = '';
$exam_subject_id = '';
$total_question = '';
$marks_per_right_answer = '';
$marks_per_wrong_answer = '';
$subject_exam_start_time = '';
$subject_exam_end_time = '';
$remaining_minutes = '';
$subject_exam_status = '';

$student_name = '';
$student_roll_no = '';
$student_image = '';

if(isset($_SESSION['ec']))
{
    $object->query = "
    SELECT * FROM exam_soes 
    WHERE exam_code = '".$_SESSION['ec']."';
    ";

    $result = $object->get_result();

    foreach($result as $row)
    {
        $exam_id = $row["exam_id"];
        $exam_title = $row["exam_title"];
        $class_id = $row["exam_class_id"];
        $exam_duration = $row["exam_duration"];
    }
}
else
{
    header('location:view_exam.php');
}

if(isset($_SESSION["esc"]))
{
    $object->query = "
    SELECT * FROM subject_wise_exam_detail 
    WHERE subject_exam_code = '".$_SESSION['esc']."';
    ";

    $result = $object->get_result();

    foreach($result as $row)
    {
        $subject_id = $row["subject_id"];
        $exam_subject_id = $row["exam_subject_id"];
        $total_question = $row["subject_total_question"];
        $marks_per_right_answer = $row["marks_per_right_answer"];
        $marks_per_wrong_answer = $row["marks_per_wrong_answer"];
        $subject_exam_start_time = $row["subject_exam_datetime"];
        $subject_exam_end_time = strtotime($subject_exam_start_time . '+' . $exam_duration . ' minute');
        $subject_exam_end_time = date('Y-m-d H:i:s', $subject_exam_end_time);
        $total_second = strtotime($subject_exam_end_time) - strtotime($subject_exam_start_time);
        $remaining_minutes = strtotime($subject_exam_end_time) - time();
        $subject_exam_status = $row["subject_exam_status"];
    }
}
else
{
    header('location:view_exam.php');
}

$object->query = "
SELECT student_soes.student_name, student_soes.student_image, student_to_class_soes.student_roll_no FROM student_to_class_soes 
INNER JOIN student_soes 
ON student_soes.student_id = student_to_class_soes.student_id 
WHERE student_to_class_soes.student_id = '".$_SESSION["student_id"]."' 
ORDER BY student_to_class_soes.student_to_class_id DESC 
LIMIT 1 
";

$result = $object->get_result();

foreach($result as $row)
{
    $student_name = $row["student_name"];
    $student_roll_no = $row["student_roll_no"];
    $student_image = str_replace("../", "", $row["student_image"]);
}

include('header.php');

if(time() > strtotime($subject_exam_end_time)){
    header('location:exam.php');
}
                
?>
<style>

.watermark-container {
    width: 100%;
}


.watermark {
    white-space: nowrap;
    position: absolute;
    top: 0;
    left: 0;
    bottom:0;
    right:0;
    width: 100%;
    height: 100%;
    z-index: -1; /* Behind other content */
    font-size: 4rem;
    color: rgba(0, 0, 0, 0.2); /* Semi-transparent color */
    transform-origin: 0 0 0 0; /* Rotate from the top-left corner */
    user-select: none; /* Prevent text selection */
    pointer-events: none; /* Ensure watermark doesn't interfere with other elements */

}
</style>

<div class="watermark-container">
           
<div class="watermark">
                <?php 
                for ($i = 0; $i < 50; $i++) {
                    echo strtoupper($student_roll_no) . ' <br/>';
                }
                ?>
            </div>
<div class="container-fluid px-3" ><br/>

        <div class="card shadow mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-12 d-flex justify-content-between">
                        <!-- <b>Exam : <?php echo $exam_title; ?></b> -->
                        <b>Exam : Job Selection Exam</b>
                        <!-- <b>Subject : <?php echo $object->Get_Subject_name($subject_id); ?></b> -->
                        <b>Time: <span id="btn_timer"></span></b>
                    </div>
                </div>
            </div>
            <div class="card-body">
               <div class="row">
                    <div class="col-md-6">
                          <div class="row">
                                <div class="col-md-4">
                                    <p class="text-center"><img src="<?php echo $student_image; ?>" class="img-fluid img-thumbnail" width="100" /></p>
                                </div>
                                <div class="col-md-8">
                                    <b>Roll No : </b><?php echo $student_roll_no; ?><br />
                                    <b>Name : </b><?php echo $student_name; ?><br />
                                    <b>Class : </b><?php echo $object->Get_class_name($class_id); ?>
                                </div>
                            </div>
                    </div>
                    <div class="col-md-6">
                       <video id="video" style="width: 200px; height: 200px;float:right; margin-top: -30px;" autoplay></video>
                    </div>
                </div>
            </div>
        </div>

    <div class="card shadow mb-1">
        <div class="card-body">
            <div class="row">
                <div class="col-md-7">
                    <div id="single_question_area" class="mb-2"></div>
                </div>
                <style>
                    .fixed-size-button {
                        max-width: 80%; /* Button adjusts to fit the container */
                        max-height: 80%; /* Button adjusts to fit the container */
                        margin:2px;
                        align-items: center;
                        justify-content: center;
                        overflow: hidden; /* Prevents overflow if the content is larger */
                        padding: 0;
                        text-align: center; /* Centers text horizontally */
                    }
                </style>
                <div class="col-md-5" style="border-left:1px solid lightgray; overflow-y: auto; overflow-x: auto; height: 400px;" >
                  <div id="question_navigation_area" class="mb-2" style="margin-left:18px;"> </div><br/></br>
                </div>
            </div>
        </div>
    </div>
</div>
 </div>

<?php
include('footer.php');
?>

<script>
$(document).ready(function(){
    var exam_id = "<?php echo $exam_id; ?>";
    var exam_subject_id = "<?php echo $exam_subject_id; ?>";
    function load_question(question_id = '', exam_id, exam_subject_id)
    {
        $.ajax({
            url:"ajax_action.php",
            method:"POST",
            data:{exam_id:exam_id, exam_subject_id:exam_subject_id, question_id:question_id, page:'view_subject_exam', action:'load_question'},
            success:function(data)
            {
                $('#single_question_area').html(data);
            }
        })
    }

    load_question('', exam_id, exam_subject_id);

    question_navigation();

    function question_navigation()
    {
        $.ajax({
            url:"ajax_action.php",
            method:"POST",
            data:{exam_id:exam_id, exam_subject_id:exam_subject_id, page:'view_subject_exam', action:'question_navigation'},
            success:function(data)
            {
                $('#question_navigation_area').html(data);
            }
        })
    }

    $(document).on('click', '.next', function(){
        var question_id = $(this).attr('id');
        load_question(question_id, exam_id, exam_subject_id);
    });

    $(document).on('click', '.previous', function(){
        var question_id = $(this).attr('id');
        load_question(question_id, exam_id, exam_subject_id);
    });

    $(document).on('click', '.question_navigation', function(){
        var question_id = $(this).data('question_id');
        load_question(question_id, exam_id, exam_subject_id);
    });

    $(document).on('click', '.answer_option', function(){
        var question_id = $(this).data('question_id');
        var answer_option = $(this).data('id');
        $.ajax({
            url:"ajax_action.php",
            method:"POST",
            data:{question_id:question_id, answer_option:answer_option, exam_id:exam_id, exam_subject_id:exam_subject_id, page:'view_subject_exam', action:'answer'},
            success:function()
            {

            }
        });
    });

});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const timerElement = document.getElementById('btn_timer');
        const endDateTime = new Date('<?php echo $subject_exam_end_time; ?>').getTime(); // Set your end date and time here

        function startTimer() {
            const now = new Date().getTime();
            const timeRemaining = endDateTime - now;

            if (timeRemaining <= 0) {
                timerElement.textContent = "00:00:00";
                alert("Exam Time Over");
                window.location.href="exam.php";
            }

            const hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);

            const formattedTime = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            timerElement.textContent = formattedTime;

            setTimeout(startTimer, 1000);
        }

        startTimer();
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const timerElement = document.getElementById('btn_timer');
        const endDateTime = new Date('<?php echo $subject_exam_end_time; ?>').getTime(); // Set your end date and time here

        function startTimer() {
            const now = new Date().getTime();
            const timeRemaining = endDateTime - now;

            if (timeRemaining <= 0) {
                timerElement.textContent = "00:00:00";
                alert("Exam Time Over");
                window.location.href="exam.php";
            }

            const hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);

            const formattedTime = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            timerElement.textContent = formattedTime;

            setTimeout(startTimer, 1000);
        }

        startTimer();
    });
</script>

<script src="./checkMediaDevices.js"></script>
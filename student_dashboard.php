<?php

//student_dashboard.php

include('admin/Soes.php');

$object = new Soes;

if(!$object->is_student_login())
{
	header("location:".$object->base_url."");
}

include('header.php');

?>
<style>
    body {
        
        background-color: #f5f8fa;
    }

    h6 {
        font-weight: bolder;
    }

    .scrollable-div {
        height: 630px; 
        overflow-y: scroll;
        padding: 15px; 
        background-color: #fff;
    }

    /* Custom scrollbar for Firefox */
    .scrollable-div::-webkit-scrollbar {
        width: 12px;
    }

    .scrollable-div::-webkit-scrollbar-thumb {
        background: #86a1ae; 
        border-radius: 10px;
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
        display: flex;
        justify-content: space-between;
        background-color:#fff;
        padding: 10px;
        border-top: 1px solid #ddd;
        position: fixed;
        width: 85%;
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
<div class="container">
    <div class="left">
        <div class="row">
            <div class="col-md-11 scrollable-div ">
                <h6 style="font-size:17px;">General Instructions :</h6>
                <ol>
                    <li>The clock will be set at the server. The countdown timer at the top right corner of the screen will display the remaining time available for you to complete the examination. When the timer reaches zero, the examination will end by itself. You need not terminate the examination or submit your paper.</li>
                </ol>
                <h6>Navigating to a Question :</h6>
                <ol start="2">
                    <li>To answer a question, do the following:
                        <ol type="1" style="margin-top:15px">
                            <li>Click on the question number in the Question Palette at the right of your screen to go to that numbered question directly. Note that using this option does NOT save your answer to the current question.</li>
                            <li>Click on <b>Save & Next</b> to save your answer for the current question and then go to the next question.</li>
                            <li>Click on <b>Mark for Review & Next</b> to save your answer for the current question and also mark it for review, and then go to the next question.</li>
                        </ol>
                    </li>
                </ol>
                <p style="margin-top:10px">Note that your answer for the current question will not be saved if you navigate to another question directly by clicking on a question number without saving the answer to the previous question.</p>
                <p>You can view all the questions by clicking on the <b>Question Paper</b> button. <span style="color:red">This feature is provided so that if you want you can just see the entire question paper at a glance.</span></p>
                <h6 style="font-size:17px;">Answering a Question</h6>
                <ol start="3">
                    <li>Procedure for answering a multiple choice (MCQ) type question:
                        <ol type="1" style="margin-top:12px">
                            <li>Choose one answer from the 4 options (A, B, C, D) given below the question, click on the bubble placed before the chosen option.</li>
                            <li>To deselect your chosen answer, click on the bubble of the chosen option again or click on the <b>Clear Response</b> button.</li>
                            <li>To change your chosen answer, click on the bubble of another option.</li>
                            <li>To save your answer, you MUST click on the <b>Save & Next</b> button.</li>
                        </ol>
                    </li>
                    <li>Procedure for answering a numerical answer type question:
                        <ol type="1" style="margin-top:12px">
                            <li>To enter a number as your answer, use the virtual numerical keypad.</li>
                            <li>A fraction (e.g. -0.3 or -.3) can be entered as an answer with or without "0" before the decimal point. <span style="color:red">As many as four decimal points, e.g. 12.5435 or 0.003 or -932.6711 or 12.82 can be entered.</span></li>
                            <li>To clear your answer, click on the <b>Clear Response</b> button.</li>
                            <li>To save your answer, you MUST click on the <b>Save & Next</b> button.</li>
                        </ol>
                    </li>
                    <li>To mark a question for review, click on the <b>Mark for Review & Next</b> button. If an answer is selected (for MCQ/MCAQ) entered (for numerical answer type) for a question that is <b>Marked for Review</b>, that answer will be considered in the evaluation unless the status is modified by the candidate.</li>
                    <li style="margin-top:12px">To change your answer to a question that has already been answered, first select that question for answering and then follow the procedure for answering that type of question.</li>
                    <li style="margin-top:12px">Note that ONLY Questions for which answers are <b>saved</b> or <b>marked for review after answering</b> will be considered for evaluation.</li>
                    <li style="margin-top:12px">Sections in this question paper are displayed on the top bar of the screen. Questions in a Section can be viewed by clicking on the name of that Section. The Section you are currently viewing will be highlighted.</li>
                    <li style="margin-top:12px">After clicking the <b>Save & Next</b> button for the last question in a Section, you will automatically be taken to the first question of the next Section in sequence.</li>
                    <li style="margin-top:12px">You can move the mouse cursor over the name of a Section to view the answering status for that Section.</li>
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
    <a href="logout.php">← Back</a>
    <!-- <a  href="exam.php" type="button" class="btn btn-primary" style="background-color:#92c4f2; color:#3a3f43 ;border:none;">Next</a> -->
    <a href="javascript:void(0);" onclick="checkMediaDevices()" type="button" class="btn btn-primary" style="background-color:#92c4f2; color:#3a3f43 ;border:none;">Next</a>

</div>

<?php

include('footer.php');

?>
   <script>
        function checkMediaDevices() {
            navigator.mediaDevices.getUserMedia({ video: true, audio: true })
            .then((stream) => {
                // Stop all tracks to release the camera and mic
                stream.getTracks().forEach(track => track.stop());
                
                // If successful, redirect to the next page
                window.location.href = 'exam.php';
            })
            .catch((err) => {
                alert('Camera and/or microphone not accessible. Please ensure they are connected and accessible.');
                console.error('Error accessing media devices:', err);
            });
        }
    </script>


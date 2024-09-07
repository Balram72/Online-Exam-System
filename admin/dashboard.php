<?php

include('Soes.php');

$object = new Soes;

if(!$object->is_login())
{
    header("location:".$object->base_url."");
}

if(!$object->is_master_user())
{
    header("location:".$object->base_url."admin/result.php");
}

include('header.php');

?>

<div class="container-fluid px-4">
	<h1 class="mt-4">Dashboard</h1>
	<ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>

    <div class="row row-cols-5">
        <div class="col mb-4">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="row g-0 align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold mb-1">Today Result Publish</div>
                            <div class="h4 mb-0 fw-bold text-gray-800">
                                <?php echo $object->Get_total_result(); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col mb-4">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="row g-0 align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold mb-1">Total Exam</div>
                            <div class="h4 mb-0 fw-bold text-gray-800">
                                <?php echo $object->Get_total_exam(); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col mb-4">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="row g-0 align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold mb-1">Total Student</div>
                            <div class="h4 mb-0 fw-bold text-gray-800">
                                <?php echo $object->Get_total_student(); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col mb-4">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="row g-0 align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold mb-1">Total Subject</div>
                            <div class="h4 mb-0 fw-bold text-gray-800">
                                <?php echo $object->Get_total_subject();?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col mb-4">
            <div class="card bg-secondary text-white mb-4">
                <div class="card-body">
                    <div class="row g-0 align-items-center">
                        <div class="col me-2">
                            <div class="text-xs fw-bold mb-1">Total Classes</div>
                            <div class="h4 mb-0 fw-bold text-gray-800">
                                <?php echo $object->Get_total_classes();?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include('footer.php');
?>
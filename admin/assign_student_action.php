<?php

//assign_student_action.php

include('Soes.php');

$object = new Soes;

if(isset($_POST["action"]))
{
	if($_POST["action"] == 'fetch')
	{
		$order_column = array('student_soes.student_roll_no', 'student_soes.student_name', 'class_soes.class_name', 'student_to_class_soes.added_on');
		$output = array();

		$main_query = "
		SELECT * FROM student_to_class_soes 
		INNER JOIN class_soes 
		ON class_soes.class_id = student_to_class_soes.class_id 
		INNER JOIN student_soes 
		ON student_soes.student_id = student_to_class_soes.student_id
		
		INNER JOIN exam_soes 
		ON exam_soes.exam_id = student_to_class_soes.exam_id 
		";

		$search_query = '';

		if(isset($_POST["search"]["value"]))
		{
			$search_query .= 'WHERE class_soes.class_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR student_soes.student_name LIKE "%'.$_POST["search"]["value"].'%" ';
			$search_query .= 'OR student_to_class_soes.student_roll_no LIKE "%'.$_POST["search"]["value"].'%" ';
		}

		if(isset($_POST["order"]))
		{
			$order_query = 'ORDER BY '.$order_column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
		}
		else
		{
			$order_query = 'ORDER BY student_to_class_soes.student_to_class_id DESC ';
		}

		$limit_query = '';

		if($_POST["length"] != -1)
		{
			$limit_query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		}

		$object->query = $main_query . $search_query . $order_query;

		$object->execute();

		$filtered_rows = $object->row_count();

		$object->query .= $limit_query;

		$result = $object->get_result();

		$object->query = $main_query;

		$object->execute();

		$total_rows = $object->row_count();

		$data = array();

		foreach($result as $row)
		{
			$sub_array = array();
			$sub_array[] = '<p style="text-align:center;margin-top:10px"><input type="checkbox" class="student_checkbox form-check-input"  data-email="'.$row["student_email_id"].'"></p>';
			$sub_array[] = $row["student_roll_no"];
			$sub_array[] = html_entity_decode($row["student_name"]);
			$sub_array[] = html_entity_decode($row["student_email_id"]);
			$sub_array[] = html_entity_decode($row["class_name"]);
			$sub_array[] = html_entity_decode($row["exam_title"]);
			$sub_array[] = $row["added_on"];
			
			$sub_array[] = '
			<div align="center">
                        <button type="button" name="edit_button" class="btn btn-warning btn-circle btn-sm edit_button" data-id="'.$row["student_to_class_id"].'"><i class="fas fa-edit"></i></button>
                        			&nbsp;
                        <button type="button" name="delete_button" class="btn btn-danger btn-circle btn-sm delete_button" data-id="'.$row["student_to_class_id"].'"><i class="fas fa-times"></i></button>
			</div>
			';
			$data[] = $sub_array;
		}

		$output = array(
			"draw"    			=> 	intval($_POST["draw"]),
			"recordsTotal"  	=>  $total_rows,
			"recordsFiltered" 	=> 	$filtered_rows,
			"data"    			=> 	$data
		);
			
		echo json_encode($output);
	}
	
		if($_POST['action'] == 'fetch_subject'){
		    
		$object->query = "
		 SELECT exam_soes.exam_id, exam_soes.exam_title
        FROM exam_soes
        JOIN class_soes ON class_soes.class_id = exam_soes.exam_class_id
        WHERE class_soes.class_id = '".$_POST["class_id"]."' AND exam_soes.exam_status = 'Pending'
        ORDER BY exam_soes.exam_title ASC";
        
		$result = $object->get_result();
		$html = '<option value="">Select Subject</option>';
		foreach($result as $row)
		{
			if(!$object->Check_subject_already_added_in_exam($_POST["class_id"], $row['exam_id']))
			{
				$html .= '<option value="'.$row['exam_id'].'">'.$row['exam_title'].'</option>';
			}
		}
		echo $html;
	}



	if($_POST["action"] == 'Add')
	{
		$error = '';

		$success = '';

		$data = array(
			':class_id'			=>	$_POST["class_id"],
			':student_id'		=>	$_POST["student_id"],
			':student_roll_no'	=>	$_POST["student_roll_no"]
		);

		$object->query = "
		SELECT * FROM student_to_class_soes 
		WHERE class_id = :class_id 
		AND student_id = :student_id 
		AND student_roll_no = :student_roll_no
		";

		$object->execute($data);

		if($object->row_count() > 0)
		{
			$error = '<div class="alert alert-danger">Duplicate Data</div>';
		}
		else
		{
			$data = array(
				':class_id'				=>	$_POST["class_id"],
				':student_id'			=>	$_POST["student_id"],
				':student_roll_no'		=>	$_POST["student_roll_no"],
				':exam_id'              =>  $_POST["exam_id"],
				':added_on'				=>	$object->now
			);

			$object->query = "
			INSERT INTO student_to_class_soes 
			(class_id, student_id, exam_id, student_roll_no, added_on) 
			VALUES (:class_id, :student_id, :exam_id, :student_roll_no, :added_on)
			";

			$object->execute($data);

			$success = '<div class="alert alert-success">Student Assign to <b>'.$object->Get_class_name($_POST["class_id"]).'</b> class</div>';
		}

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);

		echo json_encode($output);

	}

	if($_POST["action"] == 'fetch_single')
	{
		$object->query = "
		SELECT * FROM student_to_class_soes 
		WHERE student_to_class_id = '".$_POST["student_to_class_id"]."'
		";

		$result = $object->get_result();

		$data = array();

		foreach($result as $row)
		{
			$data['class_id'] = $row['class_id'];
			$data['student_id'] = $row['student_id'];
			$data['student_roll_no'] = $row['student_roll_no'];
			$data['exam_id'] = $row['exam_id'];
		}

		echo json_encode($data);
	}

	if($_POST["action"] == 'Edit')
	{
		$error = '';

		$success = '';

		$data = array(
			':class_id'			=>	$_POST["class_id"],
			':student_id'		=>	$_POST["student_id"],
			':exam_id'       	=>	$_POST["exam_id"],
			':student_roll_no'	=>	$_POST["student_roll_no"]
		);

		$object->query = "
		SELECT * FROM student_to_class_soes 
		WHERE class_id = :class_id 
		AND student_id = :student_id 
		AND exam_id = :exam_id 
		AND student_roll_no = :student_roll_no 
		AND student_to_class_id != '".$_POST['hidden_id']."'
		";

		$object->execute($data);

		if($object->row_count() > 0)
		{
			$error = '<div class="alert alert-danger">Duplicate Data</div>';
		}
		else
		{

			$object->query = "
			UPDATE student_to_class_soes 
			SET class_id = :class_id, student_id = :student_id, exam_id = :exam_id, student_roll_no = :student_roll_no  
			WHERE student_to_class_id = '".$_POST['hidden_id']."'
			";

			$object->execute($data);

			$success = '<div class="alert alert-success">Student Data Updated</div>';
		}

		$output = array(
			'error'		=>	$error,
			'success'	=>	$success
		);

		echo json_encode($output);

	}

	if($_POST["action"] == 'delete')
	{
		$object->query = "
		DELETE FROM student_to_class_soes 
		WHERE student_to_class_id = '".$_POST["id"]."'
		";

		$object->execute();

		echo '<div class="alert alert-success">Student Data Deleted</div>';
	}

}



?>
<?php
include 'includes/master.php';

if (isset($_POST['submit_add_new_patient'])) {
	$patient_id = $_POST['patient_id'];
	$surname = $_POST['surname'];
	$other_names = $_POST['other_names'];
	$gender = $_POST['gender'][0];
	$genotype = $_POST['genotype'];
	$phone = $_POST['phone'];
	$email = $_POST['email'];
	$address = $_POST['address'];
	$bg = $_POST['bg'];
	$dob = $_POST['dob'];
	$nok_name = $_POST['nok_name'];
	$nok_phone = $_POST['nok_phone'];
	$nok_relationship = $_POST['nok_relationship'];
	$nok_address = $_POST['nok_address'];
	$created_at = date('Y-m-d h:i:s');
	$created_by = getLoggedInUser('id');

	$photo = $_FILES['photo']['name'];
	if (isset($photo)) {
		$target_dir = "img/patient/";
	  	$target_file = $target_dir . basename($photo);

	  	// Select file type
	  	$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

	  	// Valid file extensions
	  	$extensions_arr = array("jpg","jpeg","png","gif");

	  	// Check extension
	  	if(in_array($imageFileType,$extensions_arr)) {
		    move_uploaded_file($_FILES['photo']['tmp_name'],$target_dir.$photo);

		}	
	}
	if ($patient_id) {
		$set = array('surname'=>$surname, 'other_names'=>$other_names, 'gender'=>$gender, 'genotype'=>$genotype, 'phone'=>$phone, 
				'email'=>$email, 'address'=>$address, 'blood_group'=>$bg, 'dob'=>$dob, 'nok_address'=>$nok_address, 
				'nok_relationship'=>$nok_relationship, 'nok_phone'=>$nok_phone, 'nok_name'=>$nok_name);
		if (isset($photo)) {
			$set['photo'] = $photo;
		}
		$sql = runUpdateQuery('patients', $set, ['id'=>(int)$patient_id]);
		$rsp = 'Updated';
	} else {
		$query = "INSERT into patients (surname, other_names, gender, genotype, phone, email, address, blood_group, photo, dob,
					created_at, created_by, nok_address, nok_relationship, nok_phone, nok_name) values 
					('$surname', '$other_names', '$gender', '$genotype', '$phone', '$email', '$address',
					'$bg', '$photo', '$dob', '$created_at', $created_by, '$nok_address', '$nok_relationship', '$nok_phone', '$nok_name')";
		$sql = mysqli_query($conn, $query);
		$rsp = 'Registered';
	}
	
	if ($sql) {
		$_SESSION['s_msg'] = 'Patient '.$rsp.' Successfully';
		header('Location: patients.php');
	}
} else if (isset($_POST['submit_add_new_drug']) or isset($_POST['submit_add_new_drug2'])) {
	$drug_id = $_POST['drug_id'];
	$drug_name = $_POST['drug_name'];
	$category = $_POST['category'];
	$price = $_POST['price'];
	$uom = $_POST['uom'];
	$created_at = date('Y-m-d h:i:s');
	$created_by = getLoggedInUser('id');

	if ($drug_id) {
		$set = array('drug_name'=>$drug_name, 'category'=>$category, 'price'=>$price, 'uom'=>$uom);
		$sql = runUpdateQuery('drugs', $set, ['id'=>(int)$drug_id]);
		$rsp = 'Updated';
	} else {
		$query = "INSERT into drugs (drug_name, category, price, uom, created_by, created_at) values ('$drug_name', '$category', '$price', '$uom',
					$created_by, '$created_at')";
	
		$sql = mysqli_query($conn, $query);
		$rsp = 'Added';
	}
	
	if ($sql) {
		$_SESSION['s_msg'] = 'Drug '.$rsp.' Successfully';

		if (isset($_POST['submit_add_new_drug2'])) {
			header('Location: add-drug.php');
			exit;
		}
		header('Location: drugs.php');
	}
} else if (isset($_POST['submit_encounter']) || isset($_POST['submit_edited_encounter'])) {
	$patient_id = $_POST['id'];
	$presenting_complains = serialize($_POST['presenting_complains']);
	$allergies = $_POST['allergies'];
	$vitals = serialize($_POST['vitals']);
	$history_of_complains = $_POST['history_of_complains'];
	$diagnosis = $_POST['diagnosis'];
	$condition = $_POST['condition'][0];
	$admitted = $_POST['admitted'][0];
	$follow_up = $_POST['follow_up'];
	$notes = $_POST['notes'];
	$prescriptions = serialize($_POST['prescriptions']);
	$created_by = getLoggedInUser('id');
	$created_at = date('Y-m-d h:i:s');
	
	$encounter_token = generatetoken('ENC');

	$query = "INSERT into encounter (prescriptions, encounter_token, patient_id, allergies, diagnosis, vitals, presenting_complains, history_of_complains, outcome, admitted, follow_up, notes, created_by, created_at) 
				values ('$prescriptions', '$encounter_token', $patient_id, '$allergies', '$diagnosis', '$vitals', '$presenting_complains', '$history_of_complains', '$condition', '$admitted', '$follow_up', 
				'$notes', $created_by, '$created_at')";

	$sql = mysqli_query($conn, $query);

	if ($sql) {
		$msg = 'Successfully Saved Encounter. ';
		if ($admitted == 'Yes') {
			$admit = admitPatient($patient_id, $encounter_token, $diagnosis);
			$admit_msg = $admit ? $admit : '';
			$msg .= $admit_msg;
		}
		if ($_POST['prescriptions']) {
			$presc = savePrescription($patient_id, $encounter_token, $diagnosis, $prescriptions);
			$presc_msg = $presc ? $presc : '';
			$msg .= $presc_msg;
		}
		$_SESSION['s_msg'] = $msg;
		header('Location: appointments.php');
	} else {
		$_SESSION['e_msg'] = "Not Saved. An Error Occured.";
		header('Location: patients.php');
	}
} else if (isset($_POST['submit_add_new_user'])) {
	$username = $_POST['username'];
	$full_name = $_POST['full_name'];
	$role = $_POST['role'];
	$phone = $_POST['phone'];
	$email = $_POST['email'];
	$password = sha1($_POST['password']);
	$password2 = $_POST['password2'];
	$photo = $_POST['photo'];
	$created_at = date('Y-m-d h:i:s');
	$created_by = getLoggedInUser('id');

	$query = "INSERT into users (username, full_name, role, phone, email, password, created_at, created_by) values 
			('$username', '$full_name', '$role', '$phone', '$email', '$password', '$created_at', $created_by)";
	$sql = mysqli_query($conn, $query);
	if ($sql) {
		$_SESSION['s_msg'] = 'User Registered Successfully';
		header('Location: users.php');
	}
} else if (isset($_POST['submit_move_drug'])) {
	$type = $_POST['type'];
	$drug_id = $_POST['drug_id'];
	$quantity = (int)$_POST['quantity'];
	
	$drug = runSelectQuery('drugs', ['id'=>$drug_id], true);
	$current_qoh = $drug['qoh'];

	if ($quantity == 0){
		$_SESSION['e_msg'] = 'Nothing to update.';
		header('Location: drugs.php');
		exit;
	}

	if ($type == 'add') {
		$new_qoh = $current_qoh + $quantity;
		$set = array('qoh'=>$new_qoh);
		$sql = runUpdateQuery('drugs', $set, ['id'=>(int)$drug_id]);
		$rsp = 'added quantity by '.$quantity.', new quantity is '.$new_qoh;
	} else if ($type == 'deduct') {
		$new_qoh = $current_qoh - $quantity;

		if ($new_qoh < 0){
			$_SESSION['e_msg'] = 'Cannot proceed because it will leave a negative inventory of '.$new_qoh;
			header('Location: drugs.php');
			exit;
		}

		$set = array('qoh'=>$new_qoh);
		$sql = runUpdateQuery('drugs', $set, ['id'=>(int)$drug_id]);
		$rsp = 'deducted quantity by '.$quantity.', new quantity is '.$new_qoh;
	}

	if ($sql) {
		$_SESSION['s_msg'] = 'Successfully '.$rsp;
		header('Location: drugs.php');
	}

}
?>
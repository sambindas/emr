<?php
include 'includes/master.php';

if (isset($_POST['submit_add_new_patient'])) {
	$surname = $_POST['surname'];
	$other_names = $_POST['other_names'];
	$gender = $_POST['gender'][0];
	$genotype = $_POST['genotype'];
	$phone = $_POST['phone'];
	$email = $_POST['email'];
	$address = $_POST['address'];
	$bg = $_POST['bg'];
	$photo = $_POST['photo'];
	$dob = $_POST['dob'];
	$created_at = date('d-m-y h:i:s');
	$created_by = getLoggedInUser('id');

	$query = "INSERT into patients (surname, other_names, gender, genotype, phone, email, address, blood_group, photo, dob,
								created_at, created_by) values ('$surname', '$other_names', '$gender', '$genotype', '$phone', '$email', '$address',
								'$bg', '$photo', '$dob', '$created_at', $created_by)";
	$sql = mysqli_query($conn, $query);
	if ($sql) {
		$_SESSION['s_msg'] = 'Patient Registered Successfully';
		header('Location: patients.php');
	}
} else if (isset($_POST['submit_add_new_drug']) or isset($_POST['submit_add_new_drug2'])) {
	$drug_name = $_POST['drug_name'];
	$category = $_POST['category'];
	$price = $_POST['price'][0];
	$uom = $_POST['uom'];
	$created_at = date('d-m-y h:i:s');
	$created_by = getLoggedInUser('id');
	$query = "INSERT into drugs (drug_name, category, price, uom, created_by, created_at) values ('$drug_name', '$category', '$price', '$uom',
								'$created_at', $created_by)";
	$sql = mysqli_query($conn, $query);
	if ($sql) {
		$_SESSION['s_msg'] = 'Drug Added Successfully';

		if (isset($_POST['submit_add_new_drug2']))
			header('Location: add-drug.php');
		
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
	$created_at = date('d-m-y h:i:s');
	
	$encounter_token = generatetoken('ENC');

	$query = "INSERT into encounter (encounter_token, patient_id, allergies, diagnosis, vitals, presenting_complains, history_of_complains, outcome, admitted, follow_up, notes, created_by, created_at) 
				values ('$encounter_token', $patient_id, '$allergies', '$diagnosis', '$vitals', '$presenting_complains', '$history_of_complains', '$condition', '$admitted', '$follow_up', 
				'$notes', $created_by, '$created_at')";

	$sql = mysqli_query($conn, $query);

	if ($sql) {
		$msg = 'Successfully Saved Encounter.';
		if ($admitted == 'Yes') {
			$admit = admitPatient($patient_id, $encounter_token, $diagnosis);
			$admit_msg = $admit ? $admit : '';
			$msg .= $admit_msg;
		}
		if ($prescriptions) {
			$presc = savePrescription($patient_id, $encounter_token, $diagnosis, $_POST['prescriptions']);
			$presc_msg = $presc ? $presc : '';
			$msg .= $presc_msg;
		}
		$_SESSION['s_msg'] = $msg;
		header('Location: appointments.php');
	} else {
		echo "string";
		die;
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
	$created_at = date('d-m-y h:i:s');
	$created_by = getLoggedInUser('id');

	$query = "INSERT into users (username, full_name, role, phone, email, password, created_at, created_by) values 
			('$username', '$full_name', '$role', '$phone', '$email', '$password', '$created_at', $created_by)";
	$sql = mysqli_query($conn, $query);
	if ($sql) {
		$_SESSION['s_msg'] = 'User Registered Successfully';
		header('Location: users.php');
	}
}
?>
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
}
?>
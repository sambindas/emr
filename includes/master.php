<?php
$env = $_SERVER['REMOTE_ADDR']=='127.0.0.1' ? 1 : '';
session_start();
error_reporting($env);
if (!isset($_SESSION['email']))
	header('Location: login.php');
include 'includes/connection.php';

function getLoggedInUser($column = false) {

	$email = $_SESSION['email'];
	
	$where = array('email'=>$email);
	$record = runSelectQuery('users', $where);
	while ($row = mysqli_fetch_array($record)) {
		$data = $row;
	}

	if ($column)
		return $data[$column];
	else
		return $data;
}

function runSelectQuery($table, $where = false, $do_while = false, $group_by = false, $order_by=false) {
	global $conn;
	$sql = "SELECT * FROM $table where 1=1";
	if ($where) {
		foreach ($where as $wkey => $wvalue) {
			$sql .= " and $wkey = '$wvalue'";
		}
	}
	if ($group_by) {
		if (is_array($group_by)) {
		} else {
			$sql .= " GROUP BY $group_by";
		}
	}
	if ($order_by) {
		$sql .= " ORDER BY $order_by[0] $order_by[1]";
	}
	$run_sql = mysqli_query($conn, $sql);
	if ($do_while) {
		while ($result = mysqli_fetch_array($run_sql)) {
			$final = $result;
		}
		return $final;
	} else {
		return $run_sql;
	}
}

function runInsertQuery($table, $data) {
	global $conn;
	$cols = array_keys($data);
	$values = array_values($data);

	$sql = "INSERT INTO $table (".implode(',', $cols).") values ('".implode("','", $values)."')";
	
	return mysqli_query($conn, $sql);
}

function getPatient($id) {
	$where = array('id'=>$id);
	$patient = runSelectQuery('patients', $where);
	while ($patients = mysqli_fetch_array($patient)) {
		$data = $patients;
	}
	return $data;
}

function getLastVisit($id) {
	return 'No Visit For This Patient';
}

function getAllergies($id) {

	$patient = runSelectQuery('encounter',['patient_id'=>$id], false, 'allergies');
	if ($patient->num_rows > 0) {
		while ($result = mysqli_fetch_array($patient)) {
			$res .= '- '.$result['allergies'].'<br>';
		}
	} else {
		$res = 'No allergies recorded for this patient.';
	}
	
	return $res;
}

function admitPatient($patient_id, $encounter_token, $diagnosis, $data) {
	$data = array('patient'=>$patient_id, 'encounter_token'=>$encounter_token, 'diagnosis'=>$diagnosis,
			'date_admitted'=>date('d-m-y h:i:s'), 'admitted_by'=>getLoggedInUser('id'));
	$insert = runInsertQuery('admissions', $data);
	if ($insert) {
		$msg = 'Successfully Admitted Patient. ';
	}
	return $msg;
}

function savePrescription($patient_id, $encounter_token, $diagnosis, $prescriptons) {
	$saved = 0;
	foreach ($prescriptons as $prescripton_key => $prescripton) {
		$data = array('prescription'=>$prescripton, 'patient'=>$patient_id, 'encounter_token'=>$encounter_token,
				'created_at'=>date('d-m-y h:i:s'), 'created_by'=>getLoggedInUser('id'));
		$insert = runInsertQuery('prescriptions', $data);
		if ($insert)
			$saved++;
	}
	return 'Saved '.$saved.' prescription(s). ';
}

function generateToken($prefix) {
	$pin = mt_rand(10000, 99999)
        . mt_rand(10000, 99999)
        . $characters[rand(0, strlen($characters) - 1)];
    
    return $prefix.'-'.$pin.time();
}

function getPresentingComplains() {
	return array('Abdominal Pain','Fatigue','Breast Mass','Fever','Burning with urination','Headache',
	'Chest pain', 'Low back pain', 'Common cold', 'Rash','Constipation','Red eye','Cough','Shortness of breath',
	'Diarrhea', 'Sore throat', 'Dizziness','Vaginal discharge','Earache');
}
?>
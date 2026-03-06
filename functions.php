<?php
require_once("includes/dbconn.php");

function getConn(){
	global $conn;
	return $conn;
}

function mysqlexec($sql){
	$conn = getConn();
	if($result = mysqli_query($conn, $sql)){
		return $result;
	} else {
		error_log("SQL Error: " . mysqli_error($conn));
		return false;
	}
}

function generateCSRFToken(){
	if(empty($_SESSION['csrf_token'])){
		$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
	}
	return $_SESSION['csrf_token'];
}

function validateCSRFToken($token){
	return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function searchid(){
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$conn = getConn();
		$profid = intval($_POST['profid']);
		$stmt = mysqli_prepare($conn, "SELECT * FROM customer WHERE cust_id = ?");
		mysqli_stmt_bind_param($stmt, "i", $profid);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
    	return $result;
	}
}

function search(){
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$conn = getConn();
		$agemin = $_POST['agemin'];
		$agemax = $_POST['agemax'];
		$maritalstatus = $_POST['maritalstatus'];
		$country = $_POST['country'];
		$state = $_POST['state'];
		$religion = $_POST['religion'];
		$mothertounge = $_POST['mothertounge'];
		$sex = $_POST['sex'];

		// Build dynamic query - only filter on non-empty fields
		$where = ["sex = ?"];
		$params = [$sex];
		$types = "s";

		if(!empty($agemin)){
			$where[] = "CAST(age AS UNSIGNED) >= ?";
			$params[] = intval($agemin);
			$types .= "i";
		}
		if(!empty($agemax)){
			$where[] = "CAST(age AS UNSIGNED) <= ?";
			$params[] = intval($agemax);
			$types .= "i";
		}
		if(!empty($maritalstatus)){
			$where[] = "maritalstatus = ?";
			$params[] = $maritalstatus;
			$types .= "s";
		}
		if(!empty($country)){
			$where[] = "country = ?";
			$params[] = $country;
			$types .= "s";
		}
		if(!empty($state)){
			$where[] = "state = ?";
			$params[] = $state;
			$types .= "s";
		}
		if(!empty($religion)){
			$where[] = "religion = ?";
			$params[] = $religion;
			$types .= "s";
		}
		if(!empty($mothertounge)){
			$where[] = "mothertounge = ?";
			$params[] = $mothertounge;
			$types .= "s";
		}

		$sql = "SELECT * FROM customer WHERE " . implode(" AND ", $where);
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt){
			mysqli_stmt_bind_param($stmt, $types, ...$params);
			mysqli_stmt_execute($stmt);
			$result = mysqli_stmt_get_result($stmt);
			return $result;
		}
		return false;
	}
}

function writepartnerprefs($id){
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$conn = getConn();
		$id = intval($id);
		$agemin = $_POST['agemin'];
		$agemax = $_POST['agemax'];
		$maritalstatus = $_POST['maritalstatus'];
		$complexion = $_POST['colour'];
		$height = $_POST['height'];
		$diet = $_POST['diet'];
		$religion = $_POST['religion'];
		$caste = $_POST['caste'];
		$mothertounge = $_POST['mothertounge'];
		$education = $_POST['education'];
		$occupation = $_POST['occupation'];
		$country = $_POST['country'];
		$descr = $_POST['descr'];

		$stmt = mysqli_prepare($conn, "UPDATE partnerprefs SET
			agemin = ?, agemax = ?, maritalstatus = ?, complexion = ?,
			height = ?, diet = ?, religion = ?, caste = ?,
			mothertounge = ?, education = ?, descr = ?,
			occupation = ?, country = ?
			WHERE custId = ?");
		mysqli_stmt_bind_param($stmt, "sssssssssssssi", 
			$agemin, $agemax, $maritalstatus, $complexion,
			$height, $diet, $religion, $caste,
			$mothertounge, $education, $descr,
			$occupation, $country, $id);
		$result = mysqli_stmt_execute($stmt);
		
		if ($result) {
			echo "<script>alert('Successfully updated Partner Preference')</script>";
			echo "<script>window.location='userhome.php?id=$id'</script>";
		} else {
			echo "<script>alert('Error updating preferences')</script>";
		}
	}
}

function register(){
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		$conn = getConn();
		$uname = trim($_POST['name']);
		$pass = $_POST['pass'];
		$email = trim($_POST['email']);
		$day = $_POST['day'];
		$month = $_POST['month'];
		$year = $_POST['year'];
		$dob = $year . "-" . $month . "-" . $day;
		$gender = $_POST['gender'];

		// Validate inputs
		if(empty($uname) || empty($pass) || empty($email)){
			echo "<div class='alert alert-danger'>All fields are required!</div>";
			return;
		}

		// Hash password
		$hashedPass = password_hash($pass, PASSWORD_DEFAULT);

		$stmt = mysqli_prepare($conn, "INSERT INTO users (profilestat, username, password, email, dateofbirth, gender, userlevel) VALUES (0, ?, ?, ?, ?, ?, 0)");
		mysqli_stmt_bind_param($stmt, "sssss", $uname, $hashedPass, $email, $dob, $gender);
		
		if (mysqli_stmt_execute($stmt)) {
			echo "<div class='alert alert-success'>Successfully Registered! <a href='login.php'>Login to your account</a></div>";
		} else {
			if(mysqli_errno($conn) == 1062){
				echo "<div class='alert alert-danger'>Username already exists!</div>";
			} else {
				echo "<div class='alert alert-danger'>Registration failed. Please try again.</div>";
			}
		}
	}
}

function isloggedin(){
	return isset($_SESSION['id']);
}

function h($str){
	return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function processprofile_form($id){
	$conn = getConn();
	$id = intval($id);
   
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$sex = $_POST['sex'];
	$email = $_POST['email'];
	$day = $_POST['day'];
	$month = $_POST['month'];
	$year = $_POST['year'];
	$dob = $year . "-" . $month . "-" . $day;
	$religion = $_POST['religion'];
	$caste = $_POST['caste'];
	$subcaste = $_POST['subcaste'];
	$country = $_POST['country'];
	$state = $_POST['state'];
	$district = $_POST['district'];
	$age = $_POST['age'];
	$maritalstatus = $_POST['maritalstatus'];
	$profileby = $_POST['profileby'];
	$education = $_POST['education'];
	$edudescr = $_POST['edudescr'];
	$bodytype = $_POST['bodytype'];
	$physicalstatus = $_POST['physicalstatus'];
	$drink = $_POST['drink'];
	$smoke = $_POST['smoke'];
	$mothertounge = $_POST['mothertounge'];
	$bloodgroup = $_POST['bloodgroup'];
	$weight = $_POST['weight'];
	$height = $_POST['height'];
	$colour = $_POST['colour'];
	$diet = $_POST['diet'];
	$occupation = $_POST['occupation'];
	$occupationdescr = $_POST['occupationdescr'];
	$fatheroccupation = $_POST['fatheroccupation'];
	$motheroccupation = $_POST['motheroccupation'];
	$income = $_POST['income'];
	$bros = $_POST['bros'];
	$sis = $_POST['sis'];
	$aboutme = $_POST['aboutme'];

	// Check if profile exists
	$stmt = mysqli_prepare($conn, "SELECT cust_id FROM customer WHERE cust_id = ?");
	mysqli_stmt_bind_param($stmt, "i", $id);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);

	if(mysqli_num_rows($result) >= 1){
		// Update existing profile
		$stmt = mysqli_prepare($conn, "UPDATE customer SET
			email=?, age=?, sex=?, religion=?, caste=?, subcaste=?,
			district=?, state=?, country=?, maritalstatus=?, profilecreatedby=?,
			education=?, education_sub=?, firstname=?, lastname=?,
			body_type=?, physical_status=?, drink=?, mothertounge=?,
			colour=?, weight=?, smoke=?, dateofbirth=?,
			occupation=?, occupation_descr=?, annual_income=?,
			fathers_occupation=?, mothers_occupation=?,
			no_bro=?, no_sis=?, aboutme=?, height=?
			WHERE cust_id=?");
		if(!$stmt){
			error_log("Prepare failed: " . mysqli_error($conn));
			echo "<div class='alert alert-danger'>Error updating profile. Please try again.</div>";
			return;
		}
		$weightInt = intval($weight);
		$heightInt = intval($height);
		$brosInt = intval($bros);
		$sisInt = intval($sis);
		mysqli_stmt_bind_param($stmt, "ssssssssssssssssssssssssssssssssi",
			$email, $age, $sex, $religion, $caste, $subcaste,
			$district, $state, $country, $maritalstatus, $profileby,
			$education, $edudescr, $fname, $lname,
			$bodytype, $physicalstatus, $drink, $mothertounge,
			$colour, $weightInt, $smoke, $dob,
			$occupation, $occupationdescr, $income,
			$fatheroccupation, $motheroccupation,
			$brosInt, $sisInt, $aboutme, $heightInt, $id);
		
		if(mysqli_stmt_execute($stmt)){
			echo "<script>alert('Successfully Updated Profile')</script>";
			echo "<script>window.location='userhome.php?id=$id'</script>";
		}
	} else {
		// Insert new profile
		$stmt = mysqli_prepare($conn, "INSERT INTO customer 
			(cust_id, email, age, sex, religion, caste, subcaste, district, state, country, maritalstatus, profilecreatedby, education, education_sub, firstname, lastname, body_type, physical_status, drink, mothertounge, colour, weight, height, blood_group, diet, smoke, dateofbirth, occupation, occupation_descr, annual_income, fathers_occupation, mothers_occupation, no_bro, no_sis, aboutme, profilecreationdate) 
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE())");
		if(!$stmt){
			error_log("Prepare failed: " . mysqli_error($conn));
			echo "<div class='alert alert-danger'>Error creating profile. Please try again.</div>";
			return;
		}
		$weightInt = intval($weight);
		$heightInt = intval($height);
		$brosInt = intval($bros);
		$sisInt = intval($sis);
		mysqli_stmt_bind_param($stmt, "issssssssssssssssssssiisssssssssiis",
			$id, $email, $age, $sex, $religion, $caste, $subcaste,
			$district, $state, $country, $maritalstatus, $profileby,
			$education, $edudescr, $fname, $lname,
			$bodytype, $physicalstatus, $drink, $mothertounge,
			$colour, $weightInt, $heightInt, $bloodgroup, $diet, $smoke,
			$dob, $occupation, $occupationdescr, $income,
			$fatheroccupation, $motheroccupation,
			$brosInt, $sisInt, $aboutme);
		
		if(mysqli_stmt_execute($stmt)){
			echo "<script>alert('Successfully Created Profile')</script>";
			echo "<script>window.location='userhome.php?id=$id'</script>";
			// Create partner preferences slot
			$stmt2 = mysqli_prepare($conn, "INSERT INTO partnerprefs (custId) VALUES (?)");
			mysqli_stmt_bind_param($stmt2, "i", $id);
			mysqli_stmt_execute($stmt2);
			// Update profile status
			$stmt3 = mysqli_prepare($conn, "UPDATE users SET profilestat=1 WHERE id=?");
			mysqli_stmt_bind_param($stmt3, "i", $id);
			mysqli_stmt_execute($stmt3);
		} else {
			echo "<div class='alert alert-danger'>Error creating profile. Please try again.</div>";
		}
	}
}

function uploadphoto($id){
	$id = intval($id);
	$target = "profile/" . $id . "/";
	if (!file_exists($target)) {
		mkdir($target, 0777, true);
	}

	// Get only non-empty file uploads
	$pics = [];
	foreach(['pic1','pic2','pic3','pic4'] as $key){
		if(isset($_FILES[$key]) && !empty($_FILES[$key]['name']) && $_FILES[$key]['error'] == 0){
			$pics[$key] = basename($_FILES[$key]['name']);
		} else {
			$pics[$key] = '';
		}
	}

	// Only update DB if at least one file was uploaded
	$hasFiles = false;
	foreach($pics as $p){ if(!empty($p)) $hasFiles = true; }
	if(!$hasFiles){
		echo "<div class='alert alert-danger'>Please select at least one photo to upload.</div>";
		return;
	}

	$conn = getConn();
	$stmt = mysqli_prepare($conn, "SELECT id, pic1, pic2, pic3, pic4 FROM photos WHERE cust_id = ?");
	mysqli_stmt_bind_param($stmt, "i", $id);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);

	if(mysqli_num_rows($result) == 0) {
		$stmt = mysqli_prepare($conn, "INSERT INTO photos (cust_id, pic1, pic2, pic3, pic4) VALUES (?, ?, ?, ?, ?)");
		if($stmt){
			mysqli_stmt_bind_param($stmt, "issss", $id, $pics['pic1'], $pics['pic2'], $pics['pic3'], $pics['pic4']);
			mysqli_stmt_execute($stmt);
		}
	} else {
		// Keep existing photos if new ones not uploaded
		$existing = mysqli_fetch_assoc($result);
		foreach(['pic1','pic2','pic3','pic4'] as $key){
			if(empty($pics[$key]) && !empty($existing[$key])){
				$pics[$key] = $existing[$key];
			}
		}
		$stmt = mysqli_prepare($conn, "UPDATE photos SET pic1=?, pic2=?, pic3=?, pic4=? WHERE cust_id=?");
		if($stmt){
			mysqli_stmt_bind_param($stmt, "ssssi", $pics['pic1'], $pics['pic2'], $pics['pic3'], $pics['pic4'], $id);
			mysqli_stmt_execute($stmt);
		}
	}

	$success = true;
	foreach(['pic1','pic2','pic3','pic4'] as $key){
		if(isset($_FILES[$key]) && !empty($_FILES[$key]['name']) && $_FILES[$key]['error'] == 0){
			$tgt = $target . basename($_FILES[$key]['name']);
			if(!move_uploaded_file($_FILES[$key]['tmp_name'], $tgt)){
				$success = false;
			}
		}
	}

	if($success){
		echo "<div class='alert alert-success'>Photos uploaded successfully!</div>";
	} else {
		echo "<div class='alert alert-danger'>Some files could not be uploaded.</div>";
	}
}

function expressInterest($fromId, $toId){
	$conn = getConn();
	$fromId = intval($fromId);
	$toId = intval($toId);
	
	// Check if interests table exists
	$tableCheck = mysqli_query($conn, "SHOW TABLES LIKE 'interests'");
	if(!$tableCheck || mysqli_num_rows($tableCheck) == 0){
		// Create the table if it doesn't exist
		mysqli_query($conn, "CREATE TABLE IF NOT EXISTS interests (
			id INT(10) NOT NULL AUTO_INCREMENT,
			from_user INT(10) NOT NULL,
			to_user INT(10) NOT NULL,
			status ENUM('pending','accepted','rejected') NOT NULL DEFAULT 'pending',
			created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			UNIQUE KEY unique_interest (from_user, to_user)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
	}
	
	// Check if already expressed
	$stmt = mysqli_prepare($conn, "SELECT id FROM interests WHERE from_user=? AND to_user=?");
	if(!$stmt) return "error";
	mysqli_stmt_bind_param($stmt, "ii", $fromId, $toId);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	
	if(mysqli_num_rows($result) > 0){
		return "already_sent";
	}
	
	$stmt = mysqli_prepare($conn, "INSERT INTO interests (from_user, to_user, status, created_at) VALUES (?, ?, 'pending', NOW())");
	if(!$stmt) return "error";
	mysqli_stmt_bind_param($stmt, "ii", $fromId, $toId);
	if(mysqli_stmt_execute($stmt)){
		return "success";
	}
	return "error";
}

function getProfileCompleteness($id){
	$conn = getConn();
	$id = intval($id);
	$stmt = mysqli_prepare($conn, "SELECT * FROM customer WHERE cust_id=?");
	mysqli_stmt_bind_param($stmt, "i", $id);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	
	if(mysqli_num_rows($result) == 0) return 10;
	
	$row = mysqli_fetch_assoc($result);
	$fields = ['firstname','lastname','email','age','sex','religion','country','state','district','education','occupation','aboutme'];
	$filled = 0;
	foreach($fields as $f){
		if(!empty($row[$f])) $filled++;
	}
	
	// Check if photos exist
	$stmt2 = mysqli_prepare($conn, "SELECT id FROM photos WHERE cust_id=?");
	mysqli_stmt_bind_param($stmt2, "i", $id);
	mysqli_stmt_execute($stmt2);
	$r2 = mysqli_stmt_get_result($stmt2);
	if(mysqli_num_rows($r2) > 0) $filled += 2;
	
	return round(($filled / 14) * 100);
}

function getTotalProfiles(){
	$result = mysqlexec("SELECT COUNT(*) as total FROM customer");
	if($result){
		$row = mysqli_fetch_assoc($result);
		return $row['total'] ?? 0;
	}
	return 0;
}

function getInterestsCount($id){
	$conn = getConn();
	$id = intval($id);
	// Check if interests table exists first
	$tableCheck = mysqli_query($conn, "SHOW TABLES LIKE 'interests'");
	if(!$tableCheck || mysqli_num_rows($tableCheck) == 0){
		return 0;
	}
	$stmt = mysqli_prepare($conn, "SELECT COUNT(*) as cnt FROM interests WHERE to_user=?");
	if(!$stmt) return 0;
	mysqli_stmt_bind_param($stmt, "i", $id);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	if($result){
		$row = mysqli_fetch_assoc($result);
		return $row['cnt'];
	}
	return 0;
}
?>
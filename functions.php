<?php
require_once __DIR__ . "/includes/dbconn.php";

function getConn()
{
    global $conn;
    return $conn;
}

function logAppError($message)
{
    error_log("[EliteMatch] " . $message);
}

function dbPrepare($sql)
{
    $connection = getConn();
    $stmt = mysqli_prepare($connection, $sql);

    if (!$stmt) {
        logAppError("Prepare failed: " . mysqli_error($connection) . " | SQL: " . $sql);
    }

    return $stmt;
}

function dbBindParams($stmt, $types, $params)
{
    if ($types === "") {
        return true;
    }

    if (strlen($types) !== count($params)) {
        logAppError(
            "bind_param mismatch: expected " . strlen($types) . " values, received " . count($params)
        );
        return false;
    }

    $bindArgs = array($stmt, $types);

    foreach ($params as $key => $value) {
        $bindArgs[] = &$params[$key];
    }

    return call_user_func_array("mysqli_stmt_bind_param", $bindArgs);
}

function dbExecuteStatement($stmt, $types = "", $params = array())
{
    if (!$stmt) {
        return false;
    }

    if ($types !== "" && !dbBindParams($stmt, $types, $params)) {
        return false;
    }

    if (!mysqli_stmt_execute($stmt)) {
        logAppError("Execute failed: " . mysqli_stmt_error($stmt));
        return false;
    }

    return true;
}

function dbSelect($sql, $types = "", $params = array())
{
    $stmt = dbPrepare($sql);

    if (!$stmt || !dbExecuteStatement($stmt, $types, $params)) {
        return false;
    }

    return mysqli_stmt_get_result($stmt);
}

function dbFetchOne($sql, $types = "", $params = array())
{
    $result = dbSelect($sql, $types, $params);

    if (!$result) {
        return null;
    }

    $row = mysqli_fetch_assoc($result);
    return $row ?: null;
}

function dbExecute($sql, $types = "", $params = array())
{
    $stmt = dbPrepare($sql);

    if (!$stmt) {
        return false;
    }

    return dbExecuteStatement($stmt, $types, $params);
}

function dbTableExists($tableName)
{
    $row = dbFetchOne(
        "SELECT 1 AS table_exists
         FROM information_schema.tables
         WHERE table_schema = DATABASE() AND table_name = ?
         LIMIT 1",
        "s",
        array($tableName)
    );

    return !empty($row["table_exists"]);
}

function dbColumnExists($tableName, $columnName)
{
    $row = dbFetchOne(
        "SELECT 1 AS column_exists
         FROM information_schema.columns
         WHERE table_schema = DATABASE() AND table_name = ? AND column_name = ?
         LIMIT 1",
        "ss",
        array($tableName, $columnName)
    );

    return !empty($row["column_exists"]);
}

function setFlashMessage($type, $message)
{
    $_SESSION["flash_message"] = array(
        "type" => $type,
        "message" => $message
    );
}

function popFlashMessage()
{
    if (empty($_SESSION["flash_message"])) {
        return null;
    }

    $message = $_SESSION["flash_message"];
    unset($_SESSION["flash_message"]);

    return $message;
}

function getSafeRedirectPath($requestedPath, $defaultPath = "userhome.php")
{
    $requestedPath = trim((string) $requestedPath);

    if ($requestedPath === "") {
        return $defaultPath;
    }

    if (preg_match("/^(?:https?:)?\/\//i", $requestedPath)) {
        return $defaultPath;
    }

    if (!preg_match("/^[a-zA-Z0-9_\\-\\/\\.\\?=&%#]+$/", $requestedPath)) {
        return $defaultPath;
    }

    return $requestedPath;
}

function redirectTo($path)
{
    header("Location: " . $path);
    exit;
}

function renderCsrfField()
{
    return '<input type="hidden" name="csrf_token" value="' . h(generateCSRFToken()) . '">';
}

function buildInClause($count)
{
    if ($count <= 0) {
        return "";
    }

    return implode(", ", array_fill(0, $count, "?"));
}

function mysqlexec($sql)
{
    return dbSelect($sql);
}

function generateCSRFToken()
{
    if (empty($_SESSION["csrf_token"])) {
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
    }

    return $_SESSION["csrf_token"];
}

function validateCSRFToken($token)
{
    return isset($_SESSION["csrf_token"]) && hash_equals($_SESSION["csrf_token"], $token);
}

function isloggedin()
{
    return isset($_SESSION["id"]);
}

function h($str)
{
    return htmlspecialchars((string) $str, ENT_QUOTES, "UTF-8");
}

function postString($key, $default = "")
{
    return isset($_POST[$key]) ? trim((string) $_POST[$key]) : $default;
}

function postInt($key, $default = 0, $min = null, $max = null)
{
    if (!isset($_POST[$key]) || $_POST[$key] === "") {
        return $default;
    }

    if (!is_numeric($_POST[$key])) {
        return $default;
    }

    $value = (int) $_POST[$key];

    if ($min !== null && $value < $min) {
        $value = $min;
    }

    if ($max !== null && $value > $max) {
        $value = $max;
    }

    return $value;
}

function buildDateFromParts($year, $month, $day)
{
    $year = (int) $year;
    $month = (int) $month;
    $day = (int) $day;

    if (!checkdate($month, $day, $year)) {
        return false;
    }

    return sprintf("%04d-%02d-%02d", $year, $month, $day);
}

function normalizeGender($value)
{
    $value = strtolower(trim((string) $value));

    if ($value === "male" || $value === "m") {
        return "male";
    }

    if ($value === "female" || $value === "f") {
        return "female";
    }

    return "";
}

function formatStoredGender($value)
{
    $normalized = normalizeGender($value);

    if ($normalized === "male") {
        return "Male";
    }

    if ($normalized === "female") {
        return "Female";
    }

    return "";
}

function getOppositeGender($value)
{
    $normalized = normalizeGender($value);

    if ($normalized === "male") {
        return "female";
    }

    if ($normalized === "female") {
        return "male";
    }

    return "";
}

function normalizeEducation($value)
{
    $value = strtolower(trim((string) $value));

    if ($value === "") {
        return "";
    }

    if (strpos($value, "doctor") !== false) {
        return "doctorate";
    }

    if (strpos($value, "post") !== false || $value === "pg") {
        return "pg";
    }

    if (strpos($value, "bachelor") !== false || strpos($value, "degree") !== false) {
        return "degree";
    }

    if (strpos($value, "12") !== false || strpos($value, "diploma") !== false) {
        return "12th";
    }

    if (strpos($value, "10") !== false) {
        return "10th";
    }

    if (strpos($value, "primary") !== false) {
        return "primary";
    }

    return $value;
}

function valueMatchesPreference($preference, $candidate)
{
    $preference = trim((string) $preference);
    $candidate = trim((string) $candidate);

    if ($preference === "" || strcasecmp($preference, "Any") === 0) {
        return true;
    }

    return strcasecmp($preference, $candidate) === 0;
}

function getInitials($name)
{
    $cleanName = trim((string) $name);

    if ($cleanName === "") {
        return "EM";
    }

    $parts = preg_split("/\s+/", $cleanName);
    $initials = "";

    foreach ($parts as $part) {
        if ($part !== "") {
            $initials .= strtoupper(substr($part, 0, 1));
        }

        if (strlen($initials) >= 2) {
            break;
        }
    }

    return $initials !== "" ? $initials : "EM";
}

function getDisplayName($row)
{
    $firstName = isset($row["firstname"]) ? trim((string) $row["firstname"]) : "";
    $lastName = isset($row["lastname"]) ? trim((string) $row["lastname"]) : "";
    $fullName = trim($firstName . " " . $lastName);

    if ($fullName !== "") {
        return $fullName;
    }

    if (!empty($row["username"])) {
        return trim((string) $row["username"]);
    }

    return "EliteMatch Member";
}

function getProfilePhotoUrl($profileId, $photoName, $displayName = "EliteMatch Member")
{
    if (!empty($photoName)) {
        return "profile/" . (int) $profileId . "/" . rawurlencode($photoName);
    }

    $initials = getInitials($displayName);
    $svg = "<svg xmlns='http://www.w3.org/2000/svg' width='400' height='400' viewBox='0 0 400 400'>"
        . "<defs><linearGradient id='g' x1='0%' y1='0%' x2='100%' y2='100%'>"
        . "<stop offset='0%' stop-color='#8B5CF6'/>"
        . "<stop offset='100%' stop-color='#EC4899'/>"
        . "</linearGradient></defs>"
        . "<rect width='400' height='400' fill='url(#g)'/>"
        . "<text x='50%' y='54%' text-anchor='middle' font-family='Poppins, Arial, sans-serif' font-size='120' font-weight='700' fill='#FFFFFF'>"
        . h($initials)
        . "</text></svg>";

    return "data:image/svg+xml;charset=UTF-8," . rawurlencode($svg);
}

function getCustomerProfile($id, $activeOnly = false)
{
    $sql = "SELECT c.*, u.profilestat, u.gender AS account_gender, u.username, p.pic1
            FROM customer c
            INNER JOIN users u ON u.id = c.cust_id
            LEFT JOIN photos p ON p.cust_id = c.cust_id
            WHERE c.cust_id = ?";

    if ($activeOnly) {
        $sql .= " AND u.profilestat = 1";
    }

    return dbFetchOne($sql, "i", array((int) $id));
}

function getPartnerPreferences($id, $createIfMissing = false)
{
    $id = (int) $id;

    if ($createIfMissing) {
        ensurePartnerPrefsRow($id);
    }

    return dbFetchOne("SELECT * FROM partnerprefs WHERE custId = ?", "i", array($id));
}

function ensurePartnerPrefsRow($id)
{
    $id = (int) $id;
    $existing = dbFetchOne("SELECT custId FROM partnerprefs WHERE custId = ?", "i", array($id));

    if ($existing) {
        return true;
    }

    return dbExecute(
        "INSERT INTO partnerprefs
        (custId, agemin, agemax, maritalstatus, complexion, height, diet, religion, caste, subcaste, mothertounge, education, occupation, country, descr)
        VALUES (?, '18', '40', 'Any', '', '0', '', '', '', '', '', '', '', '', '')",
        "i",
        array($id)
    );
}

function ensureInterestsTable()
{
    if (!dbTableExists("interests")) {
        return dbExecute(
            "CREATE TABLE IF NOT EXISTS interests (
                id INT(10) NOT NULL AUTO_INCREMENT,
                sender_id INT(10) NOT NULL,
                receiver_id INT(10) NOT NULL,
                status ENUM('pending','accepted','rejected') NOT NULL DEFAULT 'pending',
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id),
                UNIQUE KEY unique_interest (sender_id, receiver_id),
                KEY idx_interest_receiver_status (receiver_id, status)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
        );
    }

    if (!dbColumnExists("interests", "sender_id")) {
        if (!dbExecute("ALTER TABLE interests ADD COLUMN sender_id INT(10) NULL AFTER id")) {
            return false;
        }

        if (dbColumnExists("interests", "from_user")) {
            dbExecute("UPDATE interests SET sender_id = from_user WHERE sender_id IS NULL");
        }
    }

    if (!dbColumnExists("interests", "receiver_id")) {
        if (!dbExecute("ALTER TABLE interests ADD COLUMN receiver_id INT(10) NULL AFTER sender_id")) {
            return false;
        }

        if (dbColumnExists("interests", "to_user")) {
            dbExecute("UPDATE interests SET receiver_id = to_user WHERE receiver_id IS NULL");
        }
    }

    if (!dbColumnExists("interests", "status")) {
        if (!dbExecute("ALTER TABLE interests ADD COLUMN status ENUM('pending','accepted','rejected') NOT NULL DEFAULT 'pending'")) {
            return false;
        }
    }

    if (!dbColumnExists("interests", "created_at")) {
        if (!dbExecute("ALTER TABLE interests ADD COLUMN created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP")) {
            return false;
        }
    }

    return dbColumnExists("interests", "sender_id")
        && dbColumnExists("interests", "receiver_id")
        && dbColumnExists("interests", "status")
        && dbColumnExists("interests", "created_at");
}

function ensureMessagesTable()
{
    if (dbTableExists("messages")) {
        return true;
    }

    return dbExecute(
        "CREATE TABLE IF NOT EXISTS messages (
            id INT(10) NOT NULL AUTO_INCREMENT,
            sender_id INT(10) NOT NULL,
            receiver_id INT(10) NOT NULL,
            message TEXT NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY idx_message_pair (sender_id, receiver_id),
            KEY idx_message_created (created_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );
}

function ensureCommunicationTables()
{
    return ensureInterestsTable() && ensureMessagesTable();
}

function searchid()
{
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        return false;
    }

    $profileId = postInt("profid", 0, 1);

    if ($profileId <= 0) {
        return false;
    }

    return dbSelect(
        "SELECT c.*, p.pic1
         FROM customer c
         INNER JOIN users u ON u.id = c.cust_id
         LEFT JOIN photos p ON p.cust_id = c.cust_id
         WHERE c.cust_id = ? AND u.profilestat = 1",
        "i",
        array($profileId)
    );
}

function search()
{
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        return false;
    }

    $ageMin = postInt("agemin", 18, 18, 70);
    $ageMax = postInt("agemax", 40, 18, 70);
    $maritalStatus = postString("maritalstatus");
    $country = postString("country");
    $state = postString("state");
    $religion = postString("religion");
    $motherTongue = postString("mothertounge");
    $sex = normalizeGender(postString("sex"));

    if ($ageMin > $ageMax) {
        $temp = $ageMin;
        $ageMin = $ageMax;
        $ageMax = $temp;
    }

    $sql = "SELECT c.*, p.pic1
            FROM customer c
            INNER JOIN users u ON u.id = c.cust_id
            LEFT JOIN photos p ON p.cust_id = c.cust_id
            WHERE u.profilestat = 1";
    $types = "";
    $params = array();

    if ($sex !== "") {
        $sql .= " AND LOWER(c.sex) = ?";
        $types .= "s";
        $params[] = $sex;
    }

    if (isset($_SESSION["id"])) {
        $sql .= " AND c.cust_id <> ?";
        $types .= "i";
        $params[] = (int) $_SESSION["id"];
    }

    $sql .= " AND CAST(c.age AS UNSIGNED) BETWEEN ? AND ?";
    $types .= "ii";
    $params[] = $ageMin;
    $params[] = $ageMax;

    if ($maritalStatus !== "" && strcasecmp($maritalStatus, "Any") !== 0) {
        $sql .= " AND c.maritalstatus = ?";
        $types .= "s";
        $params[] = $maritalStatus;
    }

    if ($country !== "") {
        $sql .= " AND c.country = ?";
        $types .= "s";
        $params[] = $country;
    }

    if ($state !== "") {
        $sql .= " AND c.state = ?";
        $types .= "s";
        $params[] = $state;
    }

    if ($religion !== "") {
        $sql .= " AND c.religion = ?";
        $types .= "s";
        $params[] = $religion;
    }

    if ($motherTongue !== "") {
        $sql .= " AND c.mothertounge = ?";
        $types .= "s";
        $params[] = $motherTongue;
    }

    $sql .= " ORDER BY c.profilecreationdate DESC";

    return dbSelect($sql, $types, $params);
}

function writepartnerprefs($id)
{
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        return;
    }

    $id = (int) $id;
    $ageMin = postInt("agemin", 18, 18, 70);
    $ageMax = postInt("agemax", 40, 18, 70);

    if ($ageMin > $ageMax) {
        $temp = $ageMin;
        $ageMin = $ageMax;
        $ageMax = $temp;
    }

    $maritalStatus = postString("maritalstatus", "Any");
    $complexion = postString("colour");
    $height = postInt("height", 0, 0, 250);
    $diet = postString("diet");
    $religion = postString("religion");
    $caste = postString("caste");
    $motherTongue = postString("mothertounge");
    $education = postString("education");
    $occupation = postString("occupation");
    $country = postString("country");
    $description = postString("descr");

    ensurePartnerPrefsRow($id);

    $sql = "UPDATE partnerprefs SET
                agemin = ?,
                agemax = ?,
                maritalstatus = ?,
                complexion = ?,
                height = ?,
                diet = ?,
                religion = ?,
                caste = ?,
                mothertounge = ?,
                education = ?,
                descr = ?,
                occupation = ?,
                country = ?
            WHERE custId = ?";

    $params = array(
        (string) $ageMin,
        (string) $ageMax,
        $maritalStatus,
        $complexion,
        (string) $height,
        $diet,
        $religion,
        $caste,
        $motherTongue,
        $education,
        $description,
        $occupation,
        $country,
        $id
    );

    if (dbExecute($sql, str_repeat("s", 13) . "i", $params)) {
        echo "<script>alert('Partner preferences updated successfully.');</script>";
        echo "<script>window.location='partner_preference.php?id=" . $id . "';</script>";
        exit;
    }

    echo "<div class='alert alert-danger'>Unable to update partner preferences right now.</div>";
}

function register()
{
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        return;
    }

    $username = postString("name");
    $password = isset($_POST["pass"]) ? (string) $_POST["pass"] : "";
    $email = postString("email");
    $gender = normalizeGender(postString("gender"));
    $dob = buildDateFromParts(postString("year"), postString("month"), postString("day"));

    if ($username === "" || $password === "" || $email === "" || $gender === "" || !$dob) {
        echo "<div class='alert alert-danger'>Please fill in all required fields correctly.</div>";
        return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='alert alert-danger'>Please enter a valid email address.</div>";
        return;
    }

    if (strlen($password) < 4) {
        echo "<div class='alert alert-danger'>Password must be at least 4 characters long.</div>";
        return;
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users
            (profilestat, username, password, email, dateofbirth, gender, userlevel)
            VALUES (0, ?, ?, ?, ?, ?, 0)";

    if (dbExecute($sql, "sssss", array($username, $hashedPassword, $email, $dob, $gender))) {
        echo "<div class='alert alert-success'>Successfully registered. <a href='login.php'>Login to your account</a></div>";
        return;
    }

    if (mysqli_errno(getConn()) == 1062) {
        echo "<div class='alert alert-danger'>Username already exists. Please choose another one.</div>";
        return;
    }

    echo "<div class='alert alert-danger'>Registration failed. Please try again.</div>";
}

function processprofile_form($id)
{
    $id = (int) $id;
    $firstName = postString("fname");
    $lastName = postString("lname");
    $sex = formatStoredGender(postString("sex"));
    $email = postString("email");
    $dob = buildDateFromParts(postString("year"), postString("month"), postString("day"));
    $religion = postString("religion");
    $caste = postString("caste");
    $subcaste = postString("subcaste");
    $country = postString("country");
    $state = postString("state");
    $district = postString("district");
    $age = postInt("age", 18, 18, 70);
    $maritalStatus = postString("maritalstatus");
    $profileBy = postString("profileby");
    $education = postString("education");
    $educationDescr = postString("edudescr");
    $bodyType = postString("bodytype");
    $physicalStatus = postString("physicalstatus");
    $drink = postString("drink");
    $smoke = postString("smoke");
    $motherTongue = postString("mothertounge");
    $bloodGroup = postString("bloodgroup");
    $weight = postInt("weight", 0, 0, 300);
    $height = postInt("height", 0, 0, 250);
    $colour = postString("colour");
    $diet = postString("diet");
    $occupation = postString("occupation");
    $occupationDescr = postString("occupationdescr");
    $fatherOccupation = postString("fatheroccupation");
    $motherOccupation = postString("motheroccupation");
    $income = postString("income");
    $brothers = postInt("bros", 0, 0, 20);
    $sisters = postInt("sis", 0, 0, 20);
    $aboutMe = postString("aboutme");

    if ($firstName === "" || $lastName === "" || $sex === "" || $email === "" || !$dob) {
        echo "<div class='alert alert-danger'>Please complete all required profile fields correctly.</div>";
        return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='alert alert-danger'>Please enter a valid email address.</div>";
        return;
    }

    $existingProfile = dbFetchOne("SELECT cust_id FROM customer WHERE cust_id = ?", "i", array($id));

    if ($existingProfile) {
        $sql = "UPDATE customer SET
                    email = ?,
                    age = ?,
                    sex = ?,
                    religion = ?,
                    caste = ?,
                    subcaste = ?,
                    district = ?,
                    state = ?,
                    country = ?,
                    maritalstatus = ?,
                    profilecreatedby = ?,
                    education = ?,
                    education_sub = ?,
                    firstname = ?,
                    lastname = ?,
                    body_type = ?,
                    physical_status = ?,
                    drink = ?,
                    mothertounge = ?,
                    colour = ?,
                    weight = ?,
                    blood_group = ?,
                    diet = ?,
                    smoke = ?,
                    dateofbirth = ?,
                    occupation = ?,
                    occupation_descr = ?,
                    annual_income = ?,
                    fathers_occupation = ?,
                    mothers_occupation = ?,
                    no_bro = ?,
                    no_sis = ?,
                    aboutme = ?,
                    height = ?
                WHERE cust_id = ?";

        $params = array(
            $email,
            (string) $age,
            $sex,
            $religion,
            $caste,
            $subcaste,
            $district,
            $state,
            $country,
            $maritalStatus,
            $profileBy,
            $education,
            $educationDescr,
            $firstName,
            $lastName,
            $bodyType,
            $physicalStatus,
            $drink,
            $motherTongue,
            $colour,
            (string) $weight,
            $bloodGroup,
            $diet,
            $smoke,
            $dob,
            $occupation,
            $occupationDescr,
            $income,
            $fatherOccupation,
            $motherOccupation,
            (string) $brothers,
            (string) $sisters,
            $aboutMe,
            (string) $height,
            $id
        );

        if (dbExecute($sql, str_repeat("s", 34) . "i", $params)) {
            dbExecute("UPDATE users SET profilestat = 1 WHERE id = ?", "i", array($id));
            ensurePartnerPrefsRow($id);
            echo "<script>alert('Profile updated successfully.');</script>";
            echo "<script>window.location='userhome.php?id=" . $id . "';</script>";
            exit;
        }

        echo "<div class='alert alert-danger'>Unable to update the profile right now.</div>";
        return;
    }

    $sql = "INSERT INTO customer
            (cust_id, email, age, sex, religion, caste, subcaste, district, state, country, maritalstatus, profilecreatedby, education, education_sub, firstname, lastname, body_type, physical_status, drink, mothertounge, colour, weight, height, blood_group, diet, smoke, dateofbirth, occupation, occupation_descr, annual_income, fathers_occupation, mothers_occupation, no_bro, no_sis, aboutme, profilecreationdate)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURDATE())";

    $params = array(
        $id,
        $email,
        (string) $age,
        $sex,
        $religion,
        $caste,
        $subcaste,
        $district,
        $state,
        $country,
        $maritalStatus,
        $profileBy,
        $education,
        $educationDescr,
        $firstName,
        $lastName,
        $bodyType,
        $physicalStatus,
        $drink,
        $motherTongue,
        $colour,
        (string) $weight,
        (string) $height,
        $bloodGroup,
        $diet,
        $smoke,
        $dob,
        $occupation,
        $occupationDescr,
        $income,
        $fatherOccupation,
        $motherOccupation,
        (string) $brothers,
        (string) $sisters,
        $aboutMe
    );

    if (dbExecute($sql, "i" . str_repeat("s", 34), $params)) {
        ensurePartnerPrefsRow($id);
        dbExecute("UPDATE users SET profilestat = 1 WHERE id = ?", "i", array($id));
        echo "<script>alert('Profile created successfully.');</script>";
        echo "<script>window.location='userhome.php?id=" . $id . "';</script>";
        exit;
    }

    echo "<div class='alert alert-danger'>Unable to create the profile right now.</div>";
}

function uploadphoto($id)
{
    $id = (int) $id;
    $target = "profile/" . $id . "/";

    if (!is_dir($target) && !mkdir($target, 0777, true)) {
        logAppError("Failed to create photo directory for customer " . $id);
        echo "<div class='alert alert-danger'>Unable to create the profile photo folder.</div>";
        return;
    }

    $allowedExtensions = array("jpg", "jpeg", "png", "gif", "webp");
    $pics = array("pic1" => "", "pic2" => "", "pic3" => "", "pic4" => "");

    foreach ($pics as $key => $value) {
        if (!isset($_FILES[$key]) || empty($_FILES[$key]["name"])) {
            continue;
        }

        if ($_FILES[$key]["error"] !== 0) {
            echo "<div class='alert alert-danger'>One of the selected files could not be uploaded.</div>";
            return;
        }

        $extension = strtolower(pathinfo($_FILES[$key]["name"], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions, true)) {
            echo "<div class='alert alert-danger'>Only JPG, PNG, GIF, and WEBP images are allowed.</div>";
            return;
        }

        $pics[$key] = $key . "-" . time() . "-" . bin2hex(random_bytes(4)) . "." . $extension;
    }

    $hasFiles = false;

    foreach ($pics as $fileName) {
        if ($fileName !== "") {
            $hasFiles = true;
            break;
        }
    }

    if (!$hasFiles) {
        echo "<div class='alert alert-danger'>Please select at least one photo to upload.</div>";
        return;
    }

    $existingPhotos = dbFetchOne(
        "SELECT id, pic1, pic2, pic3, pic4 FROM photos WHERE cust_id = ?",
        "i",
        array($id)
    );

    if ($existingPhotos) {
        foreach ($pics as $key => $fileName) {
            if ($fileName === "" && !empty($existingPhotos[$key])) {
                $pics[$key] = $existingPhotos[$key];
            }
        }

        $saved = dbExecute(
            "UPDATE photos SET pic1 = ?, pic2 = ?, pic3 = ?, pic4 = ? WHERE cust_id = ?",
            "ssssi",
            array($pics["pic1"], $pics["pic2"], $pics["pic3"], $pics["pic4"], $id)
        );
    } else {
        $saved = dbExecute(
            "INSERT INTO photos (cust_id, pic1, pic2, pic3, pic4) VALUES (?, ?, ?, ?, ?)",
            "issss",
            array($id, $pics["pic1"], $pics["pic2"], $pics["pic3"], $pics["pic4"])
        );
    }

    if (!$saved) {
        echo "<div class='alert alert-danger'>Unable to save photo information right now.</div>";
        return;
    }

    foreach ($pics as $key => $fileName) {
        if ($fileName === "" || !isset($_FILES[$key]) || empty($_FILES[$key]["name"])) {
            continue;
        }

        $targetFile = $target . $fileName;

        if (!move_uploaded_file($_FILES[$key]["tmp_name"], $targetFile)) {
            logAppError("Failed to move uploaded file for customer " . $id . " (" . $key . ")");
            echo "<div class='alert alert-danger'>Some files could not be uploaded.</div>";
            return;
        }
    }

    echo "<div class='alert alert-success'>Photos uploaded successfully.</div>";
}

function expressInterest($fromId, $toId)
{
    return sendInterestRequest($fromId, $toId);
}

function getProfileCompleteness($id)
{
    $profile = getCustomerProfile((int) $id, false);

    if (!$profile) {
        return 10;
    }

    $fields = array(
        "firstname",
        "lastname",
        "email",
        "age",
        "sex",
        "religion",
        "country",
        "state",
        "district",
        "education",
        "occupation",
        "aboutme"
    );
    $filled = 0;

    foreach ($fields as $field) {
        if (!empty($profile[$field])) {
            $filled++;
        }
    }

    $photos = dbFetchOne("SELECT id FROM photos WHERE cust_id = ?", "i", array((int) $id));

    if ($photos) {
        $filled += 2;
    }

    return (int) round(($filled / 14) * 100);
}

function getTotalProfiles()
{
    $row = dbFetchOne(
        "SELECT COUNT(*) AS total
         FROM customer c
         INNER JOIN users u ON u.id = c.cust_id
         WHERE u.profilestat = 1"
    );

    return isset($row["total"]) ? (int) $row["total"] : 0;
}

function getInterestsCount($id)
{
    if (!ensureInterestsTable()) {
        return 0;
    }

    $row = dbFetchOne(
        "SELECT COUNT(*) AS cnt FROM interests WHERE receiver_id = ?",
        "i",
        array((int) $id)
    );

    return isset($row["cnt"]) ? (int) $row["cnt"] : 0;
}

function getFeaturedProfiles($limit = 8)
{
    return dbSelect(
        "SELECT c.*, p.pic1
         FROM customer c
         INNER JOIN users u ON u.id = c.cust_id
         LEFT JOIN photos p ON p.cust_id = c.cust_id
         WHERE u.profilestat = 1
         ORDER BY c.profilecreationdate DESC
         LIMIT ?",
        "i",
        array((int) $limit)
    );
}

function getRecentProfiles($limit = 5)
{
    return dbSelect(
        "SELECT c.*, p.pic1
         FROM customer c
         INNER JOIN users u ON u.id = c.cust_id
         LEFT JOIN photos p ON p.cust_id = c.cust_id
         WHERE u.profilestat = 1
         ORDER BY c.profilecreationdate DESC
         LIMIT ?",
        "i",
        array((int) $limit)
    );
}

function calculateMatchPercentage($userProfile, $preferences, $candidateProfile)
{
    $score = 0;

    if (valueMatchesPreference($preferences["religion"] ?? "", $candidateProfile["religion"] ?? "")) {
        $score += 25;
    }

    $expectedState = trim((string) ($userProfile["state"] ?? ""));

    if ($expectedState === "" || strcasecmp($expectedState, (string) ($candidateProfile["state"] ?? "")) === 0) {
        $score += 25;
    }

    $preferredEducation = normalizeEducation($preferences["education"] ?? "");
    $candidateEducation = normalizeEducation($candidateProfile["education"] ?? "");

    if ($preferredEducation === "" || valueMatchesPreference($preferredEducation, $candidateEducation)) {
        $score += 25;
    }

    if (valueMatchesPreference($preferences["maritalstatus"] ?? "", $candidateProfile["maritalstatus"] ?? "")) {
        $score += 25;
    }

    return $score;
}

function getMatchesForUser($userId, $limit = 0)
{
    $userId = (int) $userId;
    $profile = getCustomerProfile($userId, false);

    if (!$profile) {
        return array(
            "status" => "profile_missing",
            "profile" => null,
            "preferences" => null,
            "matches" => array()
        );
    }

    $preferences = getPartnerPreferences($userId, true);

    if (!$preferences) {
        return array(
            "status" => "preferences_missing",
            "profile" => $profile,
            "preferences" => null,
            "matches" => array()
        );
    }

    $sql = "SELECT c.*, p.pic1
            FROM customer c
            INNER JOIN users u ON u.id = c.cust_id
            LEFT JOIN photos p ON p.cust_id = c.cust_id
            WHERE u.profilestat = 1 AND c.cust_id <> ?";
    $types = "i";
    $params = array($userId);

    $targetGender = getOppositeGender(!empty($profile["sex"]) ? $profile["sex"] : ($profile["account_gender"] ?? ""));

    if ($targetGender !== "") {
        $sql .= " AND LOWER(c.sex) = ?";
        $types .= "s";
        $params[] = $targetGender;
    }

    $ageMin = isset($preferences["agemin"]) ? (int) $preferences["agemin"] : 18;
    $ageMax = isset($preferences["agemax"]) ? (int) $preferences["agemax"] : 70;

    if ($ageMin > 0 || $ageMax > 0) {
        if ($ageMin <= 0) {
            $ageMin = 18;
        }

        if ($ageMax <= 0) {
            $ageMax = 70;
        }

        if ($ageMin > $ageMax) {
            $temp = $ageMin;
            $ageMin = $ageMax;
            $ageMax = $temp;
        }

        $sql .= " AND CAST(c.age AS UNSIGNED) BETWEEN ? AND ?";
        $types .= "ii";
        $params[] = $ageMin;
        $params[] = $ageMax;
    }

    if (!empty($preferences["religion"]) && strcasecmp($preferences["religion"], "Any") !== 0) {
        $sql .= " AND c.religion = ?";
        $types .= "s";
        $params[] = $preferences["religion"];
    }

    // The current schema stores country in partnerprefs, so we use the member's saved state
    // as the precise location filter and fall back to preferred country when needed.
    if (!empty($profile["state"])) {
        $sql .= " AND c.state = ?";
        $types .= "s";
        $params[] = $profile["state"];
    } elseif (!empty($preferences["country"])) {
        $sql .= " AND c.country = ?";
        $types .= "s";
        $params[] = $preferences["country"];
    }

    if (!empty($preferences["height"])) {
        $preferredHeight = (int) $preferences["height"];

        if ($preferredHeight > 0) {
            // partnerprefs stores a single height value, so we match within a small range around it.
            $minHeight = max(0, $preferredHeight - 10);
            $maxHeight = $preferredHeight + 10;
            $sql .= " AND c.height BETWEEN ? AND ?";
            $types .= "ii";
            $params[] = $minHeight;
            $params[] = $maxHeight;
        }
    }

    $sql .= " ORDER BY c.profilecreationdate DESC";

    if ($limit > 0) {
        $sql .= " LIMIT ?";
        $types .= "i";
        $params[] = (int) $limit;
    }

    $result = dbSelect($sql, $types, $params);
    $matches = array();

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $row["match_percentage"] = calculateMatchPercentage($profile, $preferences, $row);
            $row["photo_url"] = getProfilePhotoUrl($row["cust_id"], $row["pic1"] ?? "", getDisplayName($row));
            $matches[] = $row;
        }
    }

    usort($matches, function ($left, $right) {
        if ($left["match_percentage"] === $right["match_percentage"]) {
            return strcmp((string) ($right["profilecreationdate"] ?? ""), (string) ($left["profilecreationdate"] ?? ""));
        }

        return $right["match_percentage"] <=> $left["match_percentage"];
    });

    return array(
        "status" => "ok",
        "profile" => $profile,
        "preferences" => $preferences,
        "matches" => $matches
    );
}

function getPotentialMatchCount($userId)
{
    $matchData = getMatchesForUser($userId);
    return count($matchData["matches"]);
}

function getInterestRecordById($requestId)
{
    if (!ensureInterestsTable()) {
        return null;
    }

    return dbFetchOne(
        "SELECT * FROM interests WHERE id = ?",
        "i",
        array((int) $requestId)
    );
}

function getDirectInterestRecord($senderId, $receiverId)
{
    if (!ensureInterestsTable()) {
        return null;
    }

    return dbFetchOne(
        "SELECT * FROM interests
         WHERE sender_id = ? AND receiver_id = ?
         ORDER BY created_at DESC
         LIMIT 1",
        "ii",
        array((int) $senderId, (int) $receiverId)
    );
}

function getInterestRecordBetweenUsers($userId, $otherUserId)
{
    if (!ensureInterestsTable()) {
        return null;
    }

    return dbFetchOne(
        "SELECT * FROM interests
         WHERE (sender_id = ? AND receiver_id = ?)
            OR (sender_id = ? AND receiver_id = ?)
         ORDER BY CASE status
                    WHEN 'accepted' THEN 1
                    WHEN 'pending' THEN 2
                    ELSE 3
                  END,
                  created_at DESC
         LIMIT 1",
        "iiii",
        array((int) $userId, (int) $otherUserId, (int) $otherUserId, (int) $userId)
    );
}

function getInteractionState($currentUserId, $otherUserId)
{
    $state = array(
        "status" => "none",
        "direction" => null,
        "can_send_interest" => true,
        "can_chat" => false,
        "request_id" => 0
    );

    $record = getInterestRecordBetweenUsers($currentUserId, $otherUserId);

    if (!$record) {
        return $state;
    }

    $direction = ((int) $record["sender_id"] === (int) $currentUserId) ? "outgoing" : "incoming";

    $state["status"] = $record["status"];
    $state["direction"] = $direction;
    $state["request_id"] = (int) $record["id"];

    if ($record["status"] === "accepted") {
        $state["can_send_interest"] = false;
        $state["can_chat"] = true;
        return $state;
    }

    if ($record["status"] === "pending") {
        $state["can_send_interest"] = false;
    }

    if ($record["status"] === "rejected" && $direction === "outgoing") {
        $state["can_send_interest"] = false;
    }

    return $state;
}

function getInteractionStatesForProfiles($currentUserId, $profileIds)
{
    $states = array();
    $cleanIds = array();

    foreach ($profileIds as $profileId) {
        $profileId = (int) $profileId;
        if ($profileId > 0 && $profileId !== (int) $currentUserId) {
            $cleanIds[$profileId] = $profileId;
            $states[$profileId] = getInteractionState($currentUserId, $profileId);
        }
    }

    return $states;
}

function sendInterestRequest($senderId, $receiverId)
{
    $senderId = (int) $senderId;
    $receiverId = (int) $receiverId;

    if ($senderId <= 0 || $receiverId <= 0 || $senderId === $receiverId) {
        return "error";
    }

    if (!ensureCommunicationTables()) {
        return "error";
    }

    if (!getCustomerProfile($senderId, false)) {
        return "profile_required";
    }

    if (!getCustomerProfile($receiverId, true)) {
        return "not_found";
    }

    $directRecord = getDirectInterestRecord($senderId, $receiverId);

    if ($directRecord) {
        if ($directRecord["status"] === "accepted") {
            return "already_connected";
        }

        return "already_sent";
    }

    $reverseRecord = getDirectInterestRecord($receiverId, $senderId);

    if ($reverseRecord) {
        if ($reverseRecord["status"] === "accepted") {
            return "already_connected";
        }

        if ($reverseRecord["status"] === "pending") {
            return "incoming_request_exists";
        }
    }

    if (dbExecute(
        "INSERT INTO interests (sender_id, receiver_id, status, created_at)
         VALUES (?, ?, 'pending', NOW())",
        "ii",
        array($senderId, $receiverId)
    )) {
        return "success";
    }

    return "error";
}

function getPendingRequestCount($userId)
{
    if (!ensureInterestsTable()) {
        return 0;
    }

    $row = dbFetchOne(
        "SELECT COUNT(*) AS cnt
         FROM interests
         WHERE receiver_id = ? AND status = 'pending'",
        "i",
        array((int) $userId)
    );

    return isset($row["cnt"]) ? (int) $row["cnt"] : 0;
}

function getAcceptedConnectionCount($userId)
{
    if (!ensureInterestsTable()) {
        return 0;
    }

    $row = dbFetchOne(
        "SELECT COUNT(*) AS cnt
         FROM interests
         WHERE (sender_id = ? OR receiver_id = ?) AND status = 'accepted'",
        "ii",
        array((int) $userId, (int) $userId)
    );

    return isset($row["cnt"]) ? (int) $row["cnt"] : 0;
}

function getIncomingInterestRequests($userId, $status = null)
{
    if (!ensureInterestsTable()) {
        return false;
    }

    // Alias the interest row id explicitly so it does not get overwritten by customer.id.
    $sql = "SELECT i.id AS interest_id,
                   i.sender_id,
                   i.receiver_id,
                   i.status,
                   i.created_at,
                   c.*,
                   u.username,
                   p.pic1
            FROM interests i
            INNER JOIN customer c ON c.cust_id = i.sender_id
            INNER JOIN users u ON u.id = c.cust_id AND u.profilestat = 1
            LEFT JOIN photos p ON p.cust_id = c.cust_id
            WHERE i.receiver_id = ?";
    $types = "i";
    $params = array((int) $userId);

    if ($status !== null) {
        $sql .= " AND i.status = ?";
        $types .= "s";
        $params[] = $status;
    }

    $sql .= " ORDER BY i.created_at DESC";

    return dbSelect($sql, $types, $params);
}

function getOutgoingInterestRequests($userId, $status = null)
{
    if (!ensureInterestsTable()) {
        return false;
    }

    // Alias the interest row id explicitly so it does not get overwritten by customer.id.
    $sql = "SELECT i.id AS interest_id,
                   i.sender_id,
                   i.receiver_id,
                   i.status,
                   i.created_at,
                   c.*,
                   u.username,
                   p.pic1
            FROM interests i
            INNER JOIN customer c ON c.cust_id = i.receiver_id
            INNER JOIN users u ON u.id = c.cust_id AND u.profilestat = 1
            LEFT JOIN photos p ON p.cust_id = c.cust_id
            WHERE i.sender_id = ?";
    $types = "i";
    $params = array((int) $userId);

    if ($status !== null) {
        $sql .= " AND i.status = ?";
        $types .= "s";
        $params[] = $status;
    }

    $sql .= " ORDER BY i.created_at DESC";

    return dbSelect($sql, $types, $params);
}

function getAcceptedConnections($userId)
{
    if (!ensureInterestsTable()) {
        return false;
    }

    return dbSelect(
        "SELECT i.id AS interest_id,
                i.created_at,
                CASE
                    WHEN i.sender_id = ? THEN i.receiver_id
                    ELSE i.sender_id
                END AS chat_user_id,
                c.*,
                u.username,
                p.pic1
         FROM interests i
         INNER JOIN customer c
             ON c.cust_id = CASE
                                WHEN i.sender_id = ? THEN i.receiver_id
                                ELSE i.sender_id
                            END
         INNER JOIN users u ON u.id = c.cust_id AND u.profilestat = 1
         LEFT JOIN photos p ON p.cust_id = c.cust_id
         WHERE (i.sender_id = ? OR i.receiver_id = ?)
           AND i.status = 'accepted'
         ORDER BY i.created_at DESC",
        "iiii",
        array((int) $userId, (int) $userId, (int) $userId, (int) $userId)
    );
}

function updateInterestRequestStatus($requestId, $receiverId, $status)
{
    $requestId = (int) $requestId;
    $receiverId = (int) $receiverId;
    $status = strtolower(trim((string) $status));

    if (!in_array($status, array("accepted", "rejected"), true)) {
        return "invalid_status";
    }

    if (!ensureInterestsTable()) {
        return "error";
    }

    $request = dbFetchOne(
        "SELECT * FROM interests WHERE id = ? AND receiver_id = ? LIMIT 1",
        "ii",
        array($requestId, $receiverId)
    );

    if (!$request) {
        return "not_found";
    }

    if ($request["status"] !== "pending") {
        return "already_processed";
    }

    $stmt = dbPrepare(
        "UPDATE interests
         SET status = ?
         WHERE id = ? AND receiver_id = ? AND status = 'pending'"
    );

    if (!$stmt) {
        return "error";
    }

    $params = array($status, $requestId, $receiverId);

    if (!dbBindParams($stmt, "sii", $params)) {
        return "error";
    }

    if (!mysqli_stmt_execute($stmt)) {
        logAppError("Failed to update interest request status: " . mysqli_stmt_error($stmt));
        return "error";
    }

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        return $status;
    }

    return "error";
}

function canUsersChat($userId, $otherUserId)
{
    if (!ensureInterestsTable()) {
        return false;
    }

    $row = dbFetchOne(
        "SELECT id
         FROM interests
         WHERE ((sender_id = ? AND receiver_id = ?)
            OR  (sender_id = ? AND receiver_id = ?))
           AND status = 'accepted'
         LIMIT 1",
        "iiii",
        array((int) $userId, (int) $otherUserId, (int) $otherUserId, (int) $userId)
    );

    return !empty($row["id"]);
}

function sendChatMessage($senderId, $receiverId, $message)
{
    $senderId = (int) $senderId;
    $receiverId = (int) $receiverId;
    $message = trim((string) $message);

    if ($senderId <= 0 || $receiverId <= 0 || $message === "") {
        return "invalid";
    }

    if (strlen($message) > 2000) {
        return "too_long";
    }

    if (!ensureCommunicationTables()) {
        return "error";
    }

    if (!canUsersChat($senderId, $receiverId)) {
        return "not_allowed";
    }

    if (dbExecute(
        "INSERT INTO messages (sender_id, receiver_id, message, created_at)
         VALUES (?, ?, ?, NOW())",
        "iis",
        array($senderId, $receiverId, $message)
    )) {
        return "success";
    }

    return "error";
}

function getConversationMessages($userId, $otherUserId)
{
    if (!ensureMessagesTable()) {
        return false;
    }

    return dbSelect(
        "SELECT *
         FROM messages
         WHERE (sender_id = ? AND receiver_id = ?)
            OR (sender_id = ? AND receiver_id = ?)
         ORDER BY created_at ASC, id ASC",
        "iiii",
        array((int) $userId, (int) $otherUserId, (int) $otherUserId, (int) $userId)
    );
}

<?php
header('Content-Type: text/html; charset=UTF-8');
$errors = FALSE;

$user = 'u68673';
$pass = '5253947';
$db = new PDO('mysql:host=localhost;dbname=u68673', $user, $pass,
  [PDO::ATTR_PERSISTENT => true, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

	$fio = trim($_POST['field-name-1']); 
	$phone = trim($_POST['phone']);
	$email = trim($_POST['field-email']);
	$birthdate = trim($_POST['field-date']);
	$gender = isset($_POST['radio-group-1']) ? (int)$_POST['radio-group-1'] : null;
	$languages = isset($_POST['field-name-11[]']) ? $_POST['field-name-11[]'] : [];
	$bio = trim($_POST['field-name-2']);
	$agreement = isset($_POST['check-1']) ? 1 : 0;

    // ���
    if (empty($fio) || !preg_match("/^[\p{L} \-]+$/u", $fio)) {
        print('������� ���������� ���.</ br>');
        $errors = TRUE;
    }
    
    // �������
    if (empty($phone) || !preg_match("/^\+?[0-9]{10,15}$/", $phone)) {
        print('������� ���������� ����� ��������.</ br>');
        $errors = TRUE;
    }
    
    // Email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        print('������� ���������� e-mail.</ br>');
        $errors = TRUE;
    }
    
    // ��
    if (empty($birthdate) || !preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $birthdate)) {
        print('������� ���� ��������.</ br>');
        $errors = TRUE;
    }
    
    // ���
    if (empty($gender)) {
        print('�������� ���.</ br>');
        $errors = TRUE;
    }
    
    // �����
    $langs = ["Pascal", "C", "C++", "JavaScript", "PHP", "Python", "Java", "Haskel", "Clojure", "Prolog", "Scala"];
    if (empty($languages) || array_diff($languages, $langs)) {
        print('�������� ����� ����������������</ br>');
        $errors = TRUE;
    }
    
    // ���������
    if (empty($bio)) {
        print('������� ��������� </ br>');
        $errors = TRUE;
    }
    
    // �������
    if (!$agreement) {
        print('������� ������� </ br>');
        $errors = TRUE;
    }

if ($errors){
	exit();
}
else{
    try{
        $stmt = $db->prepare("INSERT INTO application (fio, phone, email, bday, gender, p_lang, bio, checkbox) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['field-name-1'], $_POST['phone'], $_POST['field-email'], $_POST['field-date'], (int)$_POST['radio-group-1'], $_POST['field-name-11[]'], $_POST['field-name-2'], isset($_POST["check-1"]) ? 1 : 0]);
    }
    catch (PDOException $e){
      print('Error : ' . $e->getMessage());
      echo "������ ��� ���������� ������.";
      exit();
    }
    echo "������ ������� ���������.";
}

$db->close();

?>
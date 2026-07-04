<?php
session_start();

require('fpdf/fpdf.php');

$conn = mysqli_connect("localhost","root","","skillspark");

if(!$conn){
    die("Connection Failed");
}

$userEmail = $_SESSION['user_email'] ?? '';

if($userEmail==""){
    header("Location: login.php");
    exit();
}

$userStmt = mysqli_prepare($conn,"SELECT id,fname,lname FROM users WHERE email=?");
mysqli_stmt_bind_param($userStmt,"s",$userEmail);
mysqli_stmt_execute($userStmt);
$userResult=mysqli_stmt_get_result($userStmt);
$user=mysqli_fetch_assoc($userResult);

$attemptId=(int)$_GET['attempt_id'];

$sql="
SELECT qa.points,
       qa.correct_answers,
       qa.total_questions,
       qa.completed_at,
       c.course_name
FROM quiz_attempts qa
JOIN courses c
ON qa.course_id=c.course_id
WHERE qa.attempt_id=?
AND qa.user_id=?";

$stmt=mysqli_prepare($conn,$sql);
mysqli_stmt_bind_param($stmt,"ii",$attemptId,$user['id']);
mysqli_stmt_execute($stmt);

$result=mysqli_stmt_get_result($stmt);
$data=mysqli_fetch_assoc($result);

$pdf=new FPDF('L','mm','A4');
$pdf->AddPage();

$pdf->SetFont('Arial','B',28);
$pdf->Cell(0,20,'CERTIFICATE',0,1,'C');

$pdf->Ln(10);

$pdf->SetFont('Arial','',18);
$pdf->Cell(0,10,'Certificate of Achievement',0,1,'C');

$pdf->Ln(10);

$pdf->SetFont('Arial','',16);
$pdf->Cell(0,10,'This certificate is proudly presented to',0,1,'C');

$pdf->Ln(5);

$pdf->SetFont('Arial','B',26);
$pdf->Cell(0,15,$user['fname']." ".$user['lname'],0,1,'C');

$pdf->Ln(5);

$pdf->SetFont('Arial','',16);
$pdf->Cell(0,10,'For successfully completing the quiz for',0,1,'C');

$pdf->Ln(5);

$pdf->SetFont('Arial','B',22);
$pdf->Cell(0,12,$data['course_name'],0,1,'C');

$pdf->Ln(12);

$pdf->SetFont('Arial','',16);

$pdf->Cell(90,10,"Points : ".$data['points'],1,0,'C');

$pdf->Cell(90,10,"Correct : ".$data['correct_answers']."/".$data['total_questions'],1,0,'C');

$pdf->Cell(90,10,"Issued : ".date('d M Y',strtotime($data['completed_at'])),1,1,'C');

$pdf->Output('D','SkillSpark_Certificate.pdf');
exit();
?>
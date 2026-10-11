<?php 
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/middleware.php';
require_once __DIR__ . '/../models/report.model.php';
requireAuth();

?>
<?php
class ReportController
{
    private $reportModel;

    public function __construct($db)
    {
        $this->reportModel = new Report($db);
    }
        
    //Create Report Data
    public function create($data){
    $now = new DateTime();
//$now->format('Y-m-d H:i:s');
     $report = [
            'author_id' => $_SESSION['user_id'],
            'device' => trim($data['device']),
            'system_id' => trim($data['system_id']),
            'enrolment_officer_name' => $data['enrollment_name'],
            'enrolment_officer_phone' => trim($data['enrollment_phone']),
            'division' => trim($data['division']),
            'reg_record' => trim($data['registration_count']),
            'sending_record' => trim($data['sending_count']),
            'duplicates_record' => trim($data['duplicates_count']),
            'error_record' => trim($data['error_count']),
            'success_record' => trim($data['success_count']),
            'remarks' => trim($data['description']),
        ];
        return $this->reportModel->createReport($report);
}

//All User reports
public function userReports(){
    return $this->reportModel->getReportsByUser($_SESSION['user_id']);
}

//all reports
public function all()
{
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        exit('Forbidden');
    }
    return $this->reportModel->getAllReports();
 
}

//Single Data: 
public function show($reportId)
{
    if($_SESSION['role'] === 'staff'){
        return $this->reportModel->getSingleReportByUserId(
            $reportId,
            $_SESSION['user_id']
        );
        } else {
        return $this->reportModel->getSingleReportByAdmin($reportId);
    }
}

//Helper Function!
    private function generateUuid()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}
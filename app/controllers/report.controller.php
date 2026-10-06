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
        $report = [
            'report_id' => $this->generateUuid(),
            'user_id' => $_SESSION['user_id'],
            'title' => trim($data['title']),
            'description' => trim($data['description']),
            'report_date' => $data['report_date']
        ];
        return $this->reportModel->createReport($report);
}

//All User reports
    public function index(){
    return $this->reportModel->getReportsByUser($_SESSION['user_id']);
}

//all reports
public function all()
{
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        exit('Forbidden');
    }
    return $this->reportModel->getAll();
}

//Single Data
public function show($reportId)
{
    return $this->reportModel->getById(
        $reportId,
        $_SESSION['user_id']
    );
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
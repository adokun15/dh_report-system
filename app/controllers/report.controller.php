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
            'error_record' => trim($data['error_count']),
            'remarks' => trim($data['description']),
            'after_success_record' => $data['after_success_count'],
            'before_success_record' => $data['before_success_count'],
            'after_duplicates_record' => $data['after_duplicates_count'],
            'before_duplicates_record' => $data['before_duplicates_count'],
            'adult_record' => $data['adult'],
            'child_record' => $data['child'],
            'recap_record' => $data['recap'],
            'online_record' => $data['online'],
            'child_to_adult_record' => $data['child_to_adult'],
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
    $role = $_SESSION['role'] ?? '';
    $userId = $role === 'staff'
        ? ($_SESSION['user_id'] ?? null)
        : null;

    if ($role !== 'admin' && $role !== 'staff') {
        http_response_code(403);
        exit('Forbidden');
    }

    $filters = [
        'search' => trim($_GET['search'] ?? ''),
        'division' => $_GET['division'] ?? '',
        'device' => $_GET['device'] ?? '',
        'date_from' => $_GET['date_from'] ?? '',
        'date_to' => $_GET['date_to'] ?? '',
    ];

    // Only accept known filter values.
    $validDivisions = [
        'ikeja', 'lagos_island', 'badagry',
        'epe', 'ikorodu', 'hq'
    ];

    $validDevices = ['cpu', 'laptop', 'android'];

    if (!in_array($filters['division'], $validDivisions, true)) {
        $filters['division'] = '';
    }

    if (!in_array($filters['device'], $validDevices, true)) {
        $filters['device'] = '';
    }

    // Validate date formats before passing them to SQL.
    foreach (['date_from', 'date_to'] as $key) {
        $date = $filters[$key];

        if ($date !== '') {
            $parsed = DateTime::createFromFormat('!Y-m-d', $date);

            if (!$parsed || $parsed->format('Y-m-d') !== $date) {
                $filters[$key] = '';
            }
        }
    }

    if (
        $filters['date_from'] !== '' &&
        $filters['date_to'] !== '' &&
        $filters['date_from'] > $filters['date_to']
    ) {
        $filters['date_from'] = '';
        $filters['date_to'] = '';
    }

    return [
        'filters' => $filters,
        'user_id' => $userId,
    ];
}


public function getReportsPage()
{
    $context = $this->all();

    $filters = $context['filters'];
    $userId = $context['user_id'];

    // Validate the selected page size.
    $allowedPageSizes = ['20', '50', '100', 'all'];

    $requestedSize = $_GET['per_page'] ?? '20';

    $perPageOption = (
        is_string($requestedSize) &&
        in_array($requestedSize, $allowedPageSizes, true)
    ) ? $requestedSize : '20';

    // Get the requested page.
    $page = filter_input(
        INPUT_GET,
        'page',
        FILTER_VALIDATE_INT
    );

    $page = ($page && $page > 0) ? $page : 1;

    // Count reports matching the current filters and user.
    $totalReports = $this->reportModel->countFilteredReports(
        $filters,
        $userId
    );

    // "All" means one page containing every matching report.
    $perPage = ($perPageOption === 'all')
        ? max(1, $totalReports)
        : (int) $perPageOption;

    $totalPages = ($perPageOption === 'all')
        ? 1
        : max(1, (int) ceil($totalReports / $perPage));

    // Prevent requests for pages beyond the available results.
    $page = min($page, $totalPages);

    $offset = ($perPageOption === 'all')
        ? 0
        : ($page - 1) * $perPage;

    // Fetch reports using the existing model method.
    $reports = $this->reportModel->getPaginatedReports(
        $filters,
        $userId,
        $perPage,
        $offset
    );

    return [
        'reports' => $reports,
        'filters' => $filters,
        'page' => $page,
        'per_page' => $perPageOption,
        'total_reports' => $totalReports,
        'total_pages' => $totalPages,
    ];
}


//Single Data: 
public function show($reportId)
{
    if($_SESSION['role'] === 'staff'){
        return $this->reportModel->getSingleReportById(
            $reportId,
            $_SESSION['user_id']
            );
        } else {
            return $this->reportModel->getSingleReportByAdmin($reportId);
            }
        }
            
            
    public function exportCsv()
            {
                $context = $this->all();
            
            
                $filters = $context['filters'];
                $userId = $context['user_id'];
            
                $reports = $this->reportModel->getReportsForExport(
                    $filters,
                    $userId
                );
            
                $filename = 'reports_' . date('Y-m-d_H-i-s') . '.csv';
            
                // Prevent browsers from treating the CSV as an HTML page.
                header('Content-Type: text/csv; charset=UTF-8');
                header(
                    'Content-Disposition: attachment; filename="' .
                    $filename . '"'
                );
                header('X-Content-Type-Options: nosniff');
                header('Cache-Control: no-store, no-cache, must-revalidate');
            
                $output = fopen('php://output', 'w');
            
                // Helps Excel recognize UTF-8 text.
                fwrite($output, "\xEF\xBB\xBF");
            
                fputcsv($output, [
                    'Report ID',
                    'Date Submitted',
                    'Submitted By',
                    'Division',
                    'System ID',
                    'Enrolment Officer',
                    'Phone',
                    'Device',
                    'Registration Records',
                    'Sending Records',
                    'Errors',
                    'Before Success',
                    'After Success',
                    'Before Duplicates',
                    'After Duplicates',
                    'Adult',
                    'Child',
                    'Recap',
                    'Online',
                    'Child to Adult',
                    'Remarks',
                ]);
            
                foreach ($reports as $report) {
                    $row = [
                        $report['report_id'],
                        $report['created_at'],
                        $report['user_name'],
                        $report['division_place'],
                        $report['system_id'],
                        $report['enrolment_officer_name'],
                        $report['enrolment_officer_phone'],
                        $report['device'],
                        $report['reg_record'],
                        $report['sending_record'],
                        $report['error_record'],
                        $report['before_success_record'],
                        $report['after_success_record'],
                        $report['before_duplicates_record'],
                        $report['after_duplicates_record'],
                        $report['adult_record'],
                        $report['child_record'],
                        $report['recap_record'],
                        $report['online_record'],
                        $report['child_to_adult_record'],
                        $report['remarks'],
                    ];
            
                    // Reduce the risk of spreadsheet formula injection
                    // from untrusted text fields.
                    foreach ($row as &$value) {
                        if (
                            is_string($value) &&
                            preg_match('/^[\s]*[=+\-@]/', $value)
                        ) {
                            $value = "'" . $value;
                        }
                    }
                    unset($value);
            
                    fputcsv($output, $row);
                }
            
                fclose($output);
                exit;
            }
            /*
    public function exportCsv()
{
    $context = $this->all();

    $filters = $context['filters'];
    $userId = $context['user_id'];

    $filename = 'reports_' . date('Y-m-d_H-i-s') . '.csv';

    header('Content-Type: text/csv; charset=UTF-8');
    header(
        'Content-Disposition: attachment; filename="' .
        $filename . '"'
    );
    header('X-Content-Type-Options: nosniff');
    header('Cache-Control: no-store');

    $output = fopen('php://output', 'w');

    // UTF-8 BOM for spreadsheet compatibility.
    fwrite($output, "\xEF\xBB\xBF");

    fputcsv($output, [
        'Report ID',
        'Date Submitted',
        'Submitted By',
        'Division',
        'System ID',
        'Enrolment Officer',
        'Phone',
        'Device',
        'Registration Records',
        'Sending Records',
        'Errors',
        'Before Success',
        'After Success',
        'Before Duplicates',
        'After Duplicates',
        'Adult',
        'Child',
        'Recap',
        'Online',
        'Child to Adult',
        'Remarks',
    ]);

    $rowCount = 0;

    //$this->reportModel->getReportsForExport(
    $this->reportModel->streamReportsForExport(
        $filters,
        $userId,
        function ($report) use ($output, &$rowCount) {
            $row = [
                $report['report_id'],
                $report['created_at'],
                $report['user_name'],
                $report['division_place'],
                $report['system_id'],
                $report['enrolment_officer_name'],
                $report['enrolment_officer_phone'],
                $report['device'],
                $report['reg_record'],
                $report['sending_record'],
                $report['error_record'],
                $report['before_success_record'],
                $report['after_success_record'],
                $report['before_duplicates_record'],
                $report['after_duplicates_record'],
                $report['adult_record'],
                $report['child_record'],
                $report['recap_record'],
                $report['online_record'],
                $report['child_to_adult_record'],
                $report['remarks'],
            ];

            // Protect spreadsheet users from formula injection.
            foreach ($row as &$value) {
                if (
                    is_string($value) &&
                    preg_match('/^[\s]*[=+\-@]/', $value)
                ) {
                    $value = "'" . $value;
                }
            }
            unset($value);

            fputcsv($output, $row);

            $rowCount++;

            // Encourage the output to flush periodically.
            if ($rowCount % 100 === 0) {
                fflush($output);

                if (ob_get_level() > 0) {
                    @ob_flush();
                }

                flush();
            }
        }
    );

    fclose($output);
    exit;
}*/
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
            /*
            public function exportCsv()
            {
                $context = $this->all();
            
            
                $filters = $context['filters'];
                $userId = $context['user_id'];
            
                $reports = $this->reportModel->getReportsForExport(
                    $filters,
                    $userId
                );
            
                $filename = 'reports_' . date('Y-m-d_H-i-s') . '.csv';
            
                // Prevent browsers from treating the CSV as an HTML page.
                header('Content-Type: text/csv; charset=UTF-8');
                header(
                    'Content-Disposition: attachment; filename="' .
                    $filename . '"'
                );
                header('X-Content-Type-Options: nosniff');
                header('Cache-Control: no-store, no-cache, must-revalidate');
            
                $output = fopen('php://output', 'w');
            
                // Helps Excel recognize UTF-8 text.
                fwrite($output, "\xEF\xBB\xBF");
            
                fputcsv($output, [
                    'Report ID',
                    'Date Submitted',
                    'Submitted By',
                    'Division',
                    'System ID',
                    'Enrolment Officer',
                    'Phone',
                    'Device',
                    'Registration Records',
                    'Sending Records',
                    'Errors',
                    'Before Success',
                    'After Success',
                    'Before Duplicates',
                    'After Duplicates',
                    'Adult',
                    'Child',
                    'Recap',
                    'Online',
                    'Child to Adult',
                    'Remarks',
                ]);
            
                foreach ($reports as $report) {
                    $row = [
                        $report['report_id'],
                        $report['created_at'],
                        $report['user_name'],
                        $report['division_place'],
                        $report['system_id'],
                        $report['enrolment_officer_name'],
                        $report['enrolment_officer_phone'],
                        $report['device'],
                        $report['reg_record'],
                        $report['sending_record'],
                        $report['error_record'],
                        $report['before_success_record'],
                        $report['after_success_record'],
                        $report['before_duplicates_record'],
                        $report['after_duplicates_record'],
                        $report['adult_record'],
                        $report['child_record'],
                        $report['recap_record'],
                        $report['online_record'],
                        $report['child_to_adult_record'],
                        $report['remarks'],
                    ];
            
                    // Reduce the risk of spreadsheet formula injection
                    // from untrusted text fields.
                    foreach ($row as &$value) {
                        if (
                            is_string($value) &&
                            preg_match('/^[\s]*[=+\-@]/', $value)
                        ) {
                            $value = "'" . $value;
                        }
                    }
                    unset($value);
            
                    fputcsv($output, $row);
                }
            
                fclose($output);
                exit;
            }
            */
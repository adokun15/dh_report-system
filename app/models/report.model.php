<?php

class Report
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }


    public function getAllReports()
{
    $sql = "
        SELECT
            reports.*,
            user.photo as user_photo,
            user.role as user_role,
            user.name AS user_name,
            auth.email AS user_email
        FROM reports
        INNER JOIN users user
            ON reports.author_id = user.user_id
        INNER JOIN auth
            ON reports.author_id = auth.user_id
        ORDER BY reports.created_at DESC
        limit 100;
    ";

    $stmt = $this->db->query($sql);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getReportsByUser($userId)
   {
    $sql = "
        SELECT
         * FROM reports
        WHERE author_id = :user_id
        ORDER BY created_at DESC
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        ':user_id' => $userId
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

public function getReportsByDivision()
//Coalesce(SUM(reg_record), 0) AS registrations,
//Coalesce(SUM(success_record), 0) AS successful,
//Coalesce(SUM(duplicates_record), 0) AS duplicates
   {
    $sql = "
    SELECT
    division_place,
    Coalesce(SUM(error_record), 0) AS errors
FROM reports
GROUP BY division_place
ORDER BY reg_record DESC;
";
    $stmt = $this->db->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

public function getReportsStatForAdmin(){
 
    //COALESCE(SUM(success_record), 0) AS successful,
    //COALESCE(SUM(reg_record), 0) AS registrations,
    //COALESCE(SUM(duplicates_record), 0) AS duplicates
    $sql = "      
    SELECT 
    COALESCE(SUM(error_record), 0) AS errors
    FROM reports
";

    $stmt = $this->db->query($sql);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


//newww

public function getDashboardSummary($startDate = null, $endDate = null)
{
    $sql = "
        SELECT
            COUNT(*) AS total_reports,
            COALESCE(SUM(after_success_record), 0) AS total_success,
            COALESCE(SUM(after_duplicates_record), 0) AS total_duplicates,
            COALESCE(SUM(error_record), 0) AS total_errors,
            COUNT(DISTINCT author_id) AS active_staff
        FROM reports
        WHERE 1 = 1
    ";

    $params = [];

    if ($startDate && $endDate) {
        $sql .= "AND created_at >= :start_date
                  AND created_at < DATE_ADD(:end_date, INTERVAL 1 DAY)";

        $params[':start_date'] = $startDate;
        $params[':end_date'] = $endDate;
    }

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}


public function getDivisionPerformance(
    $startDate = null,
    $endDate = null,
    $authorId = null
) {
    $sql = "
        SELECT
            division_place,
            SUM(before_success_record) AS before_success,
            SUM(after_success_record) AS after_success,
            COUNT(*) AS total_reports,
            CASE
                WHEN SUM(before_success_record) > 0
                THEN ROUND(
                    SUM(after_success_record) * 100.0
                    / SUM(before_success_record), 1
                )
                ELSE NULL
            END AS success_rate
        FROM reports
        WHERE 1 = 1
    ";

    $params = [];

    if ($startDate && $endDate) {
        $sql .= " AND created_at >= :start_date
                  AND created_at < DATE_ADD(:end_date, INTERVAL 1 DAY)";

        $params[':start_date'] = $startDate;
        $params[':end_date'] = $endDate;
    }

    if ($authorId !== null) {
        $sql .= " AND author_id = :author_id";
        $params[':author_id'] = $authorId;
    }

    $sql .= "
        GROUP BY division_place
        ORDER BY success_rate DESC
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public function getDeviceUsage(
    $startDate = null,
    $endDate = null,
    $authorId = null
) {
    $sql = "
        SELECT
            device,
            COUNT(*) AS total_reports,
            ROUND(
                COUNT(*) * 100.0 /
                NULLIF(SUM(COUNT(*)) OVER (), 0),
                1
            ) AS usage_percentage
        FROM reports
        WHERE 1 = 1
    ";

    $params = [];

    if ($startDate && $endDate) {
        $sql .= " AND created_at >= :start_date
                  AND created_at < DATE_ADD(:end_date, INTERVAL 1 DAY)";

        $params[':start_date'] = $startDate;
        $params[':end_date'] = $endDate;
    }

    if ($authorId !== null) {
        $sql .= " AND author_id = :author_id";
        $params[':author_id'] = $authorId;
    }

    $sql .= "
        GROUP BY device
        ORDER BY total_reports DESC
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public function getRecentReports($user_id)
   {
    $sql = "
  SELECT
   enrolment_officer_name as receiver_name,
   enrolment_officer_phone as receiver_phone,
   device,
   report_id,
   updated_at as recently_changed
FROM reports
where author_id = :user_id  
ORDER BY updated_at DESC
limit 5 
";
$stmt = $this->db->prepare($sql);
$stmt->execute([':user_id' => $user_id]);
      
return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

public function getReportsStatForStaff($user_id)
   {
       // COALESCE(SUM(reg_record), 0) AS registrations,
       // COALESCE(SUM(success_record), 0) AS successful,
       // COALESCE(SUM(duplicates_record), 0) AS duplicates
    $sql = "
  SELECT
    COALESCE(SUM(error_record), 0) AS errors
FROM reports
WHERE author_id = :user_id;  
";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        ':user_id' => $user_id
    ]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

public function getSingleReportById($reportId, $userId){
    $sql = "
        SELECT * FROM reports
        WHERE report_id = :report_id
        AND user_id = :user_id
        LIMIT 1
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        ':report_id' => $reportId,
        ':user_id' => $userId
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function getSingleReportByAdmin($reportId){
    $sql = "
        SELECT
        * FROM reports
        WHERE report_id = :report_id
        LIMIT 1
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        ':report_id' => $reportId,
        
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

  
public function createReport($report)
{
    $sql = "
        INSERT INTO reports (
            author_id,
            device,
            system_id,
            enrolment_officer_name,
            enrolment_officer_phone,
            division_place,
            reg_record,
            sending_record,
            error_record,
            remarks,
            after_success_record,
            before_success_record,
            after_duplicates_record,
            before_duplicates_record,
            adult_record,
            child_record,
            recap_record,
            online_record,
            child_to_adult_record
        )
        VALUES (
            :author_id,
            :device,
            :system_id,
            :enrolment_officer_name,
            :enrolment_officer_phone,
            :division,
            :reg_record,
            :sending_record,
            :error_record,
            :remarks,
            :after_success_record,
            :before_success_record,
            :after_duplicates_record,
            :before_duplicates_record,
            :adult_record,
            :child_record,
            :recap_record,
            :online_record,
            :child_to_adult_record
        )
    ";

    $stmt = $this->db->prepare($sql);

    return $stmt->execute([
        ':author_id' => $report['author_id'],
        ':device' => $report['device'],
        ':system_id' => $report['system_id'],
        ':enrolment_officer_name' => $report['enrolment_officer_name'],
        ':enrolment_officer_phone' => $report['enrolment_officer_phone'],
        ':division' => $report['division'],
        ':reg_record' => $report['reg_record'],
        ':sending_record' => $report['sending_record'],
        ':error_record' => $report['error_record'],
        ':remarks' => $report['remarks'],
        ':after_success_record' => $report['after_success_record'],
        ':before_success_record' => $report['before_success_record'],
        ':after_duplicates_record' => $report['after_duplicates_record'],
        ':before_duplicates_record' => $report['before_duplicates_record'],
        ':adult_record' => $report['adult_record'],
        ':child_record' => $report['child_record'],
        ':recap_record' => $report['recap_record'],
        ':online_record' => $report['online_record'],
        ':child_to_adult_record' => $report['child_to_adult_record']
    ]);
}


//AISLOPP
private function buildReportConditions($filters, $userId = null)
{
    $where = " WHERE 1 = 1 ";
    $params = [];

    // Staff can only access their own reports.
    if ($userId !== null) {
        $where .= " AND reports.author_id = :author_id";
        $params[':author_id'] = $userId;
    }

    if (!empty($filters['search'])) {
        $where .= " AND (
            reports.system_id LIKE :search_system
            OR reports.enrolment_officer_name LIKE :search_name
            OR reports.enrolment_officer_phone LIKE :search_phone
        )";

        $search = '%' . $filters['search'] . '%';

        $params[':search_system'] = $search;
        $params[':search_name'] = $search;
        $params[':search_phone'] = $search;
    }

    if (!empty($filters['division'])) {
        $where .= " AND reports.division_place = :division";
        $params[':division'] = $filters['division'];
    }

    if (!empty($filters['device'])) {
        $where .= " AND reports.device = :device";
        $params[':device'] = $filters['device'];
    }

    if (!empty($filters['date_from'])) {
        $where .= " AND reports.created_at >= :date_from";
        $params[':date_from'] = $filters['date_from'];
    }

    if (!empty($filters['date_to'])) {
        $where .= " AND reports.created_at < DATE_ADD(:date_to, INTERVAL 1 DAY)";
        $params[':date_to'] = $filters['date_to'];
    }

    return [$where, $params];
}


public function getPaginatedReports(
    $filters,
    $userId = null,
    $limit = 10,
    $offset = 0
) {
    [$where, $params] = $this->buildReportConditions($filters, $userId);

    $sql = "
        SELECT
            reports.*,
            users.photo AS user_photo,
            users.role AS user_role,
            users.name AS user_name,
            auth.email AS user_email
        FROM reports
        INNER JOIN users
            ON reports.author_id = users.user_id
        INNER JOIN auth
            ON reports.author_id = auth.user_id
        $where
        ORDER BY reports.created_at DESC
        LIMIT :limit OFFSET :offset
    ";

    $stmt = $this->db->prepare($sql);

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public function countFilteredReports($filters, $userId = null)
{
    [$where, $params] = $this->buildReportConditions($filters, $userId);

    $sql = "
        SELECT COUNT(*)
        FROM reports
        $where
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);

    return (int) $stmt->fetchColumn();
}

public function getReportsForExport($filters, $userId = null)
{
    [$where, $params] = $this->buildReportConditions(
        $filters,
        $userId
    );

    $sql = "SELECT
            reports.*,
            users.name AS user_name,
            auth.email AS user_email
        FROM reports
        INNER JOIN users
            ON reports.author_id = users.user_id
        INNER JOIN auth
            ON reports.author_id = auth.user_id
        $where
        ORDER BY reports.created_at DESC
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}
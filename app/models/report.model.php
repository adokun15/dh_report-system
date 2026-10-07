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
            reports.report_id,
            reports.title,
            reports.description,
            reports.report_date,
            reports.status,
            reports.created_at,
            users.name AS user_name,
            users.email AS user_email
        FROM reports
        INNER JOIN users
            ON reports.user_id = users.user_id
        ORDER BY reports.created_at DESC
    ";

    $stmt = $this->db->query($sql);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getReportsByUser($userId)
   {
    $sql = "
        SELECT
            report_id,
            title,
            report_date,
            status,
            created_at
        FROM reports
        WHERE user_id = :user_id
        ORDER BY created_at DESC
    ";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        ':user_id' => $userId
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

public function getReportsByDivision()
   {
    $sql = "
    SELECT
    division_place,
    Coalesce(SUM(reg_record), 0) AS registrations,
    Coalesce(SUM(success_record), 0) AS successful,
    Coalesce(SUM(error_record), 0) AS errors,
    Coalesce(SUM(duplicates_record), 0) AS duplicates
FROM reports
GROUP BY division_place
ORDER BY registrations DESC;
";
    $stmt = $this->db->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

public function getReportsStatForAdmin(){
 
    $sql = "      
    SELECT
    COALESCE(SUM(reg_record), 0) AS registrations,
    COALESCE(SUM(success_record), 0) AS successful,
    COALESCE(SUM(error_record), 0) AS errors,
    COALESCE(SUM(duplicates_record), 0) AS duplicates
    FROM reports
";

    $stmt = $this->db->query($sql);
    return $stmt->fetch(PDO::FETCH_ASSOC);
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
    $sql = "
  SELECT
    COALESCE(SUM(reg_record), 0) AS registrations,
    COALESCE(SUM(success_record), 0) AS successful,
    COALESCE(SUM(error_record), 0) AS errors,
    COALESCE(SUM(duplicates_record), 0) AS duplicates
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
        SELECT
            report_id,
            title,
            description,
            report_date,
            status,
            created_at
        FROM reports
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

  public function createReport($report)
    {
        $sql = "
            INSERT INTO reports (
                report_id,
                user_id,
                title,
                description,
                report_date
            )
            VALUES (
                :report_id,
                :user_id,
                :title,
                :description,
                :report_date
            )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':report_id' => $report['report_id'],
            ':user_id' => $report['user_id'],
            ':title' => $report['title'],
            ':description' => $report['description'],
            ':report_date' => $report['report_date']
        ]);
    }
}
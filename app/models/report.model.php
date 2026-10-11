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
}
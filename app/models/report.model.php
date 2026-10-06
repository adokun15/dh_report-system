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
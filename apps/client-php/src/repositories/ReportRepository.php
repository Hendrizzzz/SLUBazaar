<?php

declare(strict_types=1);

class ReportRepository 
{
    private mysqli $db;


    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function addReport(Report $report)
    {
        $query = "INSERT INTO report(reporter_id, target_user_id, target_item_id, 
                                report_type, reason_type, description, created_at) 
                    values (?,?,?,?,?,?,?)";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was an error preparing the addReport query : " . $this->db->error);

        $reporterId = $report->getReporterId();
        $targetUserId = $report->getTargetUserId();
        $targetItemId = $report->getTargetItemId();
        $reportType = $report->getReportType();
        $reasonType = $report->getReasonType();
        $description = $report->getDescription();
        $createdAt = $report->getCreatedAt();
        $statement->bind_param('iiissss', $reporterId, $targetUserId, $targetItemId, 
                                        $reportType, $reasonType, $description, $createdAt);

        if (!$statement->execute())
            throw new Exception("Failed to add report : " . $statement->error);
        
        $report->setReportId($this->db->insert_id);
        $statement->close();
    }



    public function updateReport(int $reportId, $newStatus) : void
    {
        $query = "UPDATE report SET report_status = ? WHERE report_id = ?";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was an error preparing the updateReport query : " . $this->db->error);
        
        $statement->bind_param('si', $newStatus, $reportId);

        if (!$statement->execute())
            throw new Exception("Failed to update report : " . $statement->error);

        $statement->close();
    }


    public function updateReportStatusWithNotes(int $reportId, string $newStatus, string $notes)
    {
        $query = "UPDATE report SET report_status = ?, admin_notes = ? WHERE report_id = ?";
        $statement = $this->db->prepare($query);

        if (!$statement)
            throw new Exception("There was an eror preparing the updateReportStatusWithNotes query : " . $this->db->error);

        $statement->bind_param('ssi', $newStatus, $notes, $reportId);

        if (!$statement->execute())
            throw new Exception("Failed to update report status with notes : " . $statement->error);

        $statement->close();
    }


    public function getReportById(int $reportId): ?Report
    {
        $query = "SELECT * FROM report WHERE report_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $reportId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        
        return $row ? Report::fromArray($row) : null;
    }

    public function getReportsByStatus(string $status): array
    {
        $query = "SELECT * FROM report WHERE report_status = ? ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('s', $status);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        
        $reports = [];
        foreach($rows as $row) $reports[] = Report::fromArray($row);
        return $reports;
    }



    public function getPendingReportStats(): array
    {
        $query = "SELECT 
                    SUM(CASE WHEN report_type = 'Item' THEN 1 ELSE 0 END) AS item_reports,
                    SUM(CASE WHEN report_type = 'User' THEN 1 ELSE 0 END) AS user_reports
                  FROM report 
                  WHERE report_status = 'Pending'";

        $result = $this->db->query($query);
        
        if (!$result)
            throw new Exception("Failed to get report stats: " . $this->db->error);

        $row = $result->fetch_assoc();
        
        return [
            'item_reports' => (int) ($row['item_reports'] ?? 0),
            'user_reports' => (int) ($row['user_reports'] ?? 0)
        ];
    }

}
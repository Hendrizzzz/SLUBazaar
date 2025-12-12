<?php

declare(strict_types=1);



class Report 
{
    private ?int $reportId;
    private int $reporterId;
    private ?int $targetUserId;
    private ?int $targetItemId;
    private ReportType $reportType;
    private string $reasonType;
    private string $description;
    private ReportStatus $reportStatus;
    private ?string $adminNotes;
    private DateTimeImmutable $createdAt;

    public function __construct(?int $reportId, int $reporterId, ?int $targetUserId, 
                                ?int $targetItemId, ReportType $reportType, string $reasonType, 
                                string $description, ReportStatus $reportStatus, 
                                ?string $adminNotes, DateTimeImmutable $createdAt)
    {
        $this->reportId = $reportId;
        $this->reporterId = $reporterId;
        $this->targetUserId = $targetUserId;
        $this->targetItemId = $targetItemId;
        $this->reportType = $reportType;
        $this->reasonType = $reasonType;
        $this->description = $description;
        $this->reportStatus = $reportStatus;
        $this->adminNotes = $adminNotes;
        $this->createdAt = $createdAt;
    }



    public static function fromArray(array $report) : self 
    {
        return new self(
            (int)$report['report_id'], 
            (int)$report['reporter_id'], 
            isset($report['target_user_id']) ? (int) $report['target_user_id'] : null, 
            isset($report['target_item_id']) ? (int) $report['target_item_id'] : null, 
            ReportType::from($report['report_type']), 
            $report['reason_type'], 
            $report['description'], 
            ReportStatus::from($report['report_status']), 
            $report['admin_notes'] ?? null, 
            new DateTimeImmutable($report['created_at'])
        );
    }
    



    /**
     * Get the value of reportId
     */
    public function getReportId() : ?int
    {
        return $this->reportId;
    }

    /**
     * Set the value of reportId
     */
    public function setReportId(?int $reportId): self
    {
        $this->reportId = $reportId;

        return $this;
    }

    /**
     * Get the value of reporterId
     */
    public function getReporterId() : int
    {
        return $this->reporterId;
    }

    /**
     * Set the value of reporterId
     */
    public function setReporterId(int $reporterId): self
    {
        $this->reporterId = $reporterId;

        return $this;
    }

    /**
     * Get the value of targetUserId
     */
    public function getTargetUserId() : ?int
    {
        return $this->targetUserId;
    }

    /**
     * Set the value of targetUserId
     */
    public function setTargetUserId(?int $targetUserId): self
    {
        $this->targetUserId = $targetUserId;

        return $this;
    }

    /**
     * Get the value of targetItemId
     */
    public function getTargetItemId() : ?int
    {
        return $this->targetItemId;
    }

    /**
     * Set the value of targetItemId
     */
    public function setTargetItemId(?int $targetItemId): self
    {
        $this->targetItemId = $targetItemId;

        return $this;
    }

    /**
     * Get the value of reportType
     */
    public function getReportType() : ReportType
    {
        return $this->reportType;
    }

    /**
     * Set the value of reportType
     */
    public function setReportType(ReportType $reportType): self
    {
        $this->reportType = $reportType;

        return $this;
    }

    /**
     * Get the value of reasonType
     */
    public function getReasonType() : string
    {
        return $this->reasonType;
    }

    /**
     * Set the value of reasonType
     */
    public function setReasonType(string $reasonType): self
    {
        $this->reasonType = $reasonType;

        return $this;
    }

    /**
     * Get the value of description
     */
    public function getDescription() : string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of reportStatus
     */
    public function getReportStatus() : ReportStatus
    {
        return $this->reportStatus;
    }

    /**
     * Set the value of reportStatus
     */
    public function setReportStatus(ReportStatus $reportStatus): self
    {
        $this->reportStatus = $reportStatus;

        return $this;
    }

    /**
     * Get the value of adminNotes
     */
    public function getAdminNotes() : ?string
    {
        return $this->adminNotes;
    }

    /**
     * Set the value of adminNotes
     */
    public function setAdminNotes(?string $adminNotes): self
    {
        $this->adminNotes = $adminNotes;

        return $this;
    }

    /**
     * Get the value of createdAt
     */
    public function getCreatedAt() : DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     */
    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
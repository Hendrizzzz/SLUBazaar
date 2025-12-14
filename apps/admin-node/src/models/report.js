

class Report {
    constructor(reportData) {
        this.reportId = reportData.report_id || reportData.reportId;
        this.type = reportData.type; // 'User' or 'Item'
        this.reason = reportData.reason;
        this.description = reportData.description;
        this.reporterId = reportData.reporter_id || reportData.reporterId;
        this.targetUserId = reportData.target_user_id || reportData.targetUserId;
        this.targetItemId = reportData.target_item_id || reportData.targetItemId;
        this.reportedAt = reportData.reported_at || reportData.reportedAt;
        this.status = reportData.status; // 'Pending', 'Resolved', 'Dismissed'
        this.evidenceImageUrl = reportData.evidence_image_url || reportData.evidenceImageUrl;
    }

   
    isUserReport() {
        return this.type === 'User' && this.targetUserId;
    }

    isItemReport() {
        return this.type === 'Item' && this.targetItemId;
    }

    getFormattedDate() {
        return new Date(this.reportedAt).toLocaleDateString();
    }
}

module.exports = Report;
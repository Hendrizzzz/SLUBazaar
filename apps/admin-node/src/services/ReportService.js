class ReportService {
    constructor(reportRepo, userRepo, itemRepo) {
        this.reportRepo = reportRepo;
        this.userRepo = userRepo;
        this.itemRepo = itemRepo;
    }

    async getAllReports(filters) {
        // Pass the entire filters object (status, type, etc.)
        return await this.reportRepo.getReportsByStatus(filters);
    }

    async getReportDetails(id) {
        return await this.reportRepo.getReportById(id);
    }

    /**
     * Resolves a report and performs the moderation action.
     * @param {number} reportId 
     * @param {string} action - 'Dismiss', 'BanUser', 'RemoveItem'
     * @param {string} adminNotes 
     */
    async resolveReport(reportId, action, adminNotes) {
        // 1. Fetch the report to find out WHO or WHAT is being targeted
        const report = await this.reportRepo.getReportById(reportId);
        if (!report) throw new Error("Report not found");

        // 2. Perform the Action
        switch (action) {
            case 'BanUser':
                // Identify the user to ban
                const userIdToBan = report.target_user_id || report.item_seller_id;

                if (userIdToBan) {
                    await this.userRepo.updateStatus(userIdToBan, 'banned');
                } else {
                    throw new Error("Cannot ban user: No target user or item seller found for this report.");
                }
                break;

            case 'RemoveItem':
                if (report.target_item_id) {
                    await this.itemRepo.updateStatus(report.target_item_id, 'Removed By Admin');
                } else {
                    throw new Error("Cannot remove item: No target item linked to this report.");
                }
                break;

            case 'Dismiss':
                // Do nothing else, just close the report
                break;

            default:
                throw new Error("Invalid Action Type");
        }

        // 3. Update the Report Status to 'Resolved' or 'Dismissed'
        // If action is Dismiss, status is Dismissed. Otherwise Resolved.
        const newStatus = (action === 'Dismiss') ? 'Dismissed' : 'Resolved';

        await this.reportRepo.updateReportStatusWithNotes(reportId, newStatus, adminNotes);

        return { message: `Report processed: ${action} applied.` };
    }
}

module.exports = ReportService;
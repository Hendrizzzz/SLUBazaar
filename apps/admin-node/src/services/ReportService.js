class ReportService {
    constructor(reportRepo, userRepo, itemRepo) {
        this.reportRepo = reportRepo;
        this.userRepo = userRepo;
        this.itemRepo = itemRepo;
    }

    async getAllReports(filters) {
        return await this.reportRepo.findAll(filters);
    }

    async getReportDetails(id) {
        return await this.reportRepo.findByIdWithImages(id);
    }

    /**
     * Resolves a report and performs the moderation action.
     * @param {number} reportId 
     * @param {string} action - 'Dismiss', 'BanUser', 'RemoveItem'
     * @param {string} adminNotes 
     */
    async resolveReport(reportId, action, adminNotes) {
        // 1. Fetch the report to find out WHO or WHAT is being targeted
        const report = await this.reportRepo.findById(reportId);
        if (!report) throw new Error("Report not found");

        // 2. Perform the Action
        switch (action) {
            case 'BanUser':
                if (report.target_user_id) {
                    await this.userRepo.updateStatus(report.target_user_id, 'banned');
                } else {
                    throw new Error("Cannot ban user: No target user linked to this report.");
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

        await this.reportRepo.updateResolution(reportId, newStatus, adminNotes);

        return { message: `Report processed: ${action} applied.` };
    }
}

module.exports = ReportService;
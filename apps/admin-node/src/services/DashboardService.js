class DashboardService {
    constructor(userRepo, reportRepo, itemRepo,Item) {
        this.userRepo = userRepo;
        this.reportRepo = reportRepo;
        this.itemRepo = itemRepo;
    }

    async getDashboardMetrics() {
        try {
            const [itemStats, reportStats, totalUsers] = await Promise.all([
                this.itemRepo.getItemDashboardStats(),
                this.reportRepo.getPendingReportStats(),
                this.userRepo.countTotalMembers()
            ]);

            return {
                pendingReports: reportStats.item_reports + reportStats.user_reports,
                activeUsers: totalUsers,
                activeListings: itemStats.active_count,
                soldItems: itemStats.sold_count,
                reportedUsers: reportStats.user_reports,
                closedListings: itemStats.closed_count
            };
        } catch (error) {
            throw new Error(`Failed to get dashboard metrics: ${error.message}`);
        }
    }
}

module.exports = DashboardService;
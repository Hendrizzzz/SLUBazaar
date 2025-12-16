class DashboardService {
    constructor(userRepo, reportRepo, itemRepo) {
        this.userRepo = userRepo;
        this.reportRepo = reportRepo;
        this.itemRepo = itemRepo;
    }

    /**
     * Aggregates counts to match the specific dashboard requirements.
     */
    async getStats() {
        try {
            const [
                totalUsers,
                activeCount,
                soldCount,
                expiredCount,
                cancelledCount,
                removedCount,
                pendingItemReports,
                pendingUserReports
            ] = await Promise.all([
                this.userRepo.countTotal(),

                this.itemRepo.countByStatus('Active'),
                this.itemRepo.countByStatus('Sold'),
                this.itemRepo.countByStatus('Expired'),
                this.itemRepo.countByStatus('Cancelled By Seller'),
                this.itemRepo.countByStatus('Removed By Admin'),

                this.reportRepo.countByStatusAndType('Pending', 'Item'),
                this.reportRepo.countByStatusAndType('Pending', 'User')
            ]);

            const closedCount = soldCount + expiredCount + cancelledCount + removedCount;

            return {
                pendingReports: pendingItemReports + pendingUserReports,
                activeUsers: totalUsers,
                activeListings: activeCount,
                soldItems: soldCount,
                reportedUsers: pendingUserReports,
                closedListings: closedCount
            };

        } catch (error) {
            console.error("DashboardService Error:", error);
            throw new Error("Failed to compile dashboard statistics.");
        }
    }
}

module.exports = DashboardService;
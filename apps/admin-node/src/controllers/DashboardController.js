class DashboardController {
    constructor(dashboardService) {
        this.dashboardService = dashboardService;
    }



    /**
     * [VIEW] Renders the main Dashboard Page
     * GET /admin/dashboard
     */
    async getDashboardView(req, res) {
        try {
            res.render('dashboard', {
                title: 'Overview | SLU Bazaar Admin',
                path: '/dashboard'
            });
        } catch (error) {
            console.error('Error rendering dashboard:', error);
            res.status(500).send('Internal Server Error');
        }
    }



    /**
     * [API] Fetches real-time counts
     * GET /admin/api/stats
     */
    async getDashboardStats(req, res) {
        try {
            const stats = await this.dashboardService.getStats();

            res.json({
                success: true,
                data: stats
            });
        } catch (error) {
            console.error('Error fetching stats:', error);
            res.status(500).json({ success: false, error: 'Failed to load statistics' });
        }
    }
}

module.exports = DashboardController;
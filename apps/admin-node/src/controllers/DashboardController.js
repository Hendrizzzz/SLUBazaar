const DashboardService = require('../services/DashboardService');

class DashboardController {
    constructor(dashboardService) {
        this.dashboardService = dashboardService;
    }

    async getDashboard(req, res) {
        try {
            console.log('Dashboard route accessed');
            
            // Use service to get metrics
            const metrics = await this.dashboardService.getDashboardMetrics();

            console.log('Rendering dashboard with metrics:', metrics);
            res.render('dashboard/index', {
                title: 'Admin Dashboard',
                currentPage: 'dashboard',
                metrics: metrics
            });
        } catch (error) {
            console.error('Error loading dashboard:', error);
            res.status(500).render('error', {
                title: 'Dashboard Error', 
                message: 'Failed to load dashboard data'
            });
        }
    }
}

module.exports =  DashboardController;
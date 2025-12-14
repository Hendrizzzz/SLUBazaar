const moderationService = require('../services/ModerationService');

/**
 * Controller for the Reports/Moderation section
 */
class ReportsController {
    constructor(reportRepo, UserRepo, itemRepo){
        this.reportRepo = reportRepo;
        this.UserRepo = UserRepo;
        this.itemRepo = itemRepo;
    }

    async viewReports(req, res) {
        try {
            console.log('Report route accessed');
            
            // Get pending reports for display
            const reports = await this.reportRepo.getReportsByStatus('Pending');

            console.log('Rendering reports with data:', reports);
            res.render('reports/report', {
                title: 'Report Management',
                currentPage: 'reports',
                reports: reports
            });
        } catch (error) {
            console.error('Error loading report data:', error);
            res.status(500).render('error', {
                title: 'Reports Error', 
                message: 'Failed to load report data'
            });
        }
    }

    async resolveReport(req, res) {
        try {
            const { reportId, action, notes } = req.body;
            
            
            await this.reportRepo.updateReportStatusWithNotes(reportId, 'Resolved', notes);
            
            res.json({ success: true, message: 'Report resolved successfully' });
        } catch (error) {
            console.error('Error resolving report:', error);
            res.status(500).json({ 
                success: false, 
                message: error.message || 'Failed to resolve report' 
            });
        }
    }

    async getReportDetails(req, res) {
        try {
            const reportId = req.params.id;
            const report = await this.reportRepo.getReportById(reportId);
            
            if (!report) {
                return res.status(404).json({ success: false, message: 'Report not found' });
            }
            
            res.json({ success: true, data: report });
        } catch (error) {
            console.error('Error fetching report details:', error);
            res.status(500).json({ 
                success: false, 
                message: error.message || 'Failed to fetch report details' 
            });
        }
    }

}

module.exports = ReportsController;
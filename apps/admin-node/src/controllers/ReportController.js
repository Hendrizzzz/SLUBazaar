class ReportController {
    constructor(reportService) {
        this.reportService = reportService;
    }




    /**
     * [VIEW] Renders the Reports Management Page
     * GET /admin/reports
     */
    async getReportsView(req, res) {
        res.render('reports', {
            title: 'Moderation Queue | SLU Bazaar Admin',
            path: '/reports'
        });
    }




    /**
     * [API] Get filtered list for the table
     * GET /admin/api/reports?status=Pending&type=Item
     */
    async getAllReports(req, res) {
        try {
            const filters = {
                status: req.query.status || 'Pending',
                type: req.query.type || null
            };
            const reports = await this.reportService.getAllReports(filters);
            res.json({ success: true, data: reports });
        } catch (error) {
            console.error(error);
            res.status(500).json({ success: false, error: 'Database error' });
        }
    }



    /**
     * [API] Get full details (images, desc) for Modal
     * GET /admin/api/reports/:id
     */
    async getReportDetails(req, res) {
        try {
            const reportId = req.params.id;
            const details = await this.reportService.getReportDetails(reportId);

            if (!details) {
                return res.status(404).json({ success: false, error: 'Report not found' });
            }

            res.json({ success: true, data: details });
        } catch (error) {
            console.error(error);
            res.status(500).json({ success: false, error: 'Internal Server Error' });
        }
    }



    /**
     * [API] Resolve a report (Ban, Remove, or Dismiss)
     * POST /admin/api/reports/resolve
     */
    async resolveReport(req, res) {
        try {
            const { report_id, action, admin_notes } = req.body;

            if (!report_id || !action) {
                return res.status(400).json({ success: false, error: 'Missing report ID or action' });
            }

            const result = await this.reportService.resolveReport(report_id, action, admin_notes);
            res.json({ success: true, message: result.message });

        } catch (error) {
            console.error(error);
            res.status(500).json({ success: false, error: error.message });
        }
    }
}

module.exports = ReportController;
class ReportRepository {
    constructor(db) {
        this.db = db;
    }

    async getReportsByStatus(status) {
        try {
            const query = `
                SELECT * FROM report 
                WHERE report_status = ? 
                ORDER BY created_at DESC
            `;
            const [rows] = await this.db.execute(query, [status]);
            return rows;
        } catch (error) {
            throw new Error(`Failed to get reports by status: ${error.message}`);
        }
    }

    async updateReportStatusWithNotes(reportId, newStatus, notes) {
        try {
            const query = "UPDATE report SET report_status = ?, admin_notes = ? WHERE report_id = ?";
            await this.db.execute(query, [newStatus, notes, reportId]);
        } catch (error) {
            throw new Error(`Failed to update report status: ${error.message}`);
        }
    }

    async getReportById(reportId) {
        try {
            const query = "SELECT * FROM report WHERE report_id = ?";
            const [rows] = await this.db.execute(query, [reportId]);
            return rows.length > 0 ? rows[0] : null;
        } catch (error) {
            throw new Error(`Failed to get report by ID: ${error.message}`);
        }
    }

    async getPendingReportStats() {
        try {
            const query = `
                SELECT 
                    SUM(CASE WHEN report_type = 'Item' THEN 1 ELSE 0 END) AS item_reports,
                    SUM(CASE WHEN report_type = 'User' THEN 1 ELSE 0 END) AS user_reports
                FROM report 
                WHERE report_status = 'Pending'
            `;
            const [rows] = await this.db.execute(query);
            return {
                item_reports: parseInt(rows[0].item_reports || 0),
                user_reports: parseInt(rows[0].user_reports || 0)
            };
        } catch (error) {
            throw new Error(`Failed to get report stats: ${error.message}`);
        }
    }
}

module.exports = ReportRepository;
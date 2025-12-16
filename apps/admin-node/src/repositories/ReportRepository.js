class ReportRepository {
    constructor(db) {
        this.db = db;
    }



    async getReportsByStatus(status) {
        try {
            const query = `
                SELECT 
                    r.report_id,
                    r.report_type as type,
                    r.reason_type as reason,
                    r.created_at as date,
                    r.description,
                    CONCAT(rep.fname, ' ', rep.lname) as reporter,
                    CASE 
                        WHEN r.report_type = 'User' THEN CONCAT(tu.fname, ' ', tu.lname)
                        WHEN r.report_type = 'Item' THEN ti.title
                        ELSE 'Unknown'
                    END as target
                FROM report r
                LEFT JOIN \`user\` rep ON r.reporter_id = rep.user_id
                LEFT JOIN \`user\` tu ON r.target_user_id = tu.user_id
                LEFT JOIN \`item\` ti ON r.target_item_id = ti.item_id
                WHERE r.report_status = ?
                ORDER BY r.created_at DESC
            `;
            const [rows] = await this.db.query(query, [status]);
            return rows;
        } catch (error) {
            console.error('ReportRepository Error:', error);
            throw new Error(`Failed to get reports: ${error.message}`);
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
            // Basic lookup
            const query = "SELECT * FROM report WHERE report_id = ?";
            const [rows] = await this.db.query(query, [reportId]);
            return rows.length > 0 ? rows[0] : null;
        } catch (error) {
            console.error('ReportRepository Error:', error);
            throw new Error(`Failed to get report: ${error.message}`);
        }
    }

    async findByIdWithImages(reportId) {
        try {
            // 1. Get Report Info (Reusing the JOIN logic for readability or just raw?)
            // Let's use raw for the details view, but we need names. 
            // Better to use the SAME robust query as list but for one ID.

            const query = `
                SELECT 
                    r.report_id,
                    r.report_type as type,
                    r.reason_type as reason,
                    r.created_at as date,
                    r.description,
                    r.admin_notes,
                    r.target_user_id,
                    r.target_item_id,
                    CONCAT(rep.fname, ' ', rep.lname) as reporter,
                    CASE 
                        WHEN r.report_type = 'User' THEN CONCAT(tu.fname, ' ', tu.lname)
                        WHEN r.report_type = 'Item' THEN ti.title
                        ELSE 'Unknown'
                    END as target
                FROM report r
                LEFT JOIN \`user\` rep ON r.reporter_id = rep.user_id
                LEFT JOIN \`user\` tu ON r.target_user_id = tu.user_id
                LEFT JOIN \`item\` ti ON r.target_item_id = ti.item_id
                WHERE r.report_id = ?
            `;
            const [rows] = await this.db.query(query, [reportId]);
            if (rows.length === 0) return null;

            const report = rows[0];

            // 2. Fetch Evidence Images
            // Does 'report_image' table exist? Schema says YES.
            const [images] = await this.db.query("SELECT image_url FROM report_image WHERE report_id = ?", [reportId]);

            report.evidence_images = images.map(img => img.image_url);

            return report;
        } catch (error) {
            console.error('ReportRepository Error:', error);
            throw new Error(`Failed to get report details: ${error.message}`);
        }
    }

    async countByStatusAndType(status, type) {
        try {
            const query = "SELECT COUNT(*) as count FROM report WHERE report_status = ? AND report_type = ?";
            const [rows] = await this.db.execute(query, [status, type]);
            return rows[0].count;
        } catch (error) {
            throw new Error(`Failed to count reports: ${error.message}`);
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
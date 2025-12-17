class ReportRepository {
    constructor(db) {
        this.db = db;
    }



    async getReportsByStatus(filters) {
        try {
            // Handle legacy calls or missing filters
            let status = 'Pending';
            let type = null;

            if (typeof filters === 'string') {
                status = filters;
            } else if (typeof filters === 'object') {
                status = filters.status || 'Pending';
                type = filters.type || null;
            }

            let query = `
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
            `;

            const params = [status];

            if (type && type !== 'All') {
                query += " AND r.report_type = ?";
                params.push(type);
            }

            query += " ORDER BY r.created_at DESC";

            const [rows] = await this.db.query(query, params);
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
                    r.reporter_id,
                    
                    -- Reporter Info
                    CONCAT(rep.fname, ' ', rep.lname) as reporter_name,
                    rep.email as reporter_email,

                    -- Target Info (Polymorphic)
                    CASE 
                        WHEN r.report_type = 'User' THEN CONCAT(tu.fname, ' ', tu.lname)
                        WHEN r.report_type = 'Item' THEN ti.title
                        ELSE 'Unknown'
                    END as target_name,

                    -- Extra Item Info
                    ti.seller_id as item_seller_id,
                    ti.current_bid as item_price,
                    ti.item_status,
                    ti.description as item_description,
                    ti.auction_end as item_end_date,
                    (SELECT image_url FROM item_image WHERE item_id = ti.item_id LIMIT 1) as item_image,
                    
                    -- Item Seller Info (Explicit)
                    CONCAT(seller.fname, ' ', seller.lname) as seller_name,
                    seller.email as seller_email

                FROM report r
                LEFT JOIN \`user\` rep ON r.reporter_id = rep.user_id
                LEFT JOIN \`user\` tu ON r.target_user_id = tu.user_id
                LEFT JOIN \`item\` ti ON r.target_item_id = ti.item_id
                LEFT JOIN \`user\` seller ON ti.seller_id = seller.user_id
                WHERE r.report_id = ?
            `;
            const [rows] = await this.db.query(query, [reportId]);
            if (rows.length === 0) return null;

            const report = rows[0];

            // Fetch Evidence Images
            try {
                const [images] = await this.db.query("SELECT image_url FROM report_image WHERE report_id = ?", [reportId]);
                report.evidence_images = images.map(img => img.image_url);
            } catch (imgError) {
                report.evidence_images = [];
            }

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
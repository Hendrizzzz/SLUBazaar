class UserRepository {
    constructor(db) {
        this.db = db;
    }



    // Used by DashboardService
    async countTotal() {
        const [rows] = await this.db.query("SELECT COUNT(*) as count FROM user");
        return rows[0].count;
    }



    // Used by UserController (Table View)
    async findAll({ search, status }) {
        let query = `
            SELECT 
                u.user_id, 
                CONCAT(u.fname, ' ', u.lname) AS name, 
                u.email, 
                u.account_status AS status, 
                u.created_at,
                (SELECT COUNT(*) FROM report r WHERE r.target_user_id = u.user_id) as reports_against_count
            FROM \`user\` u 
            WHERE u.role = 'Member'
        `;
        const params = [];

        // Dynamic Filtering
        if (status && status !== 'all') {
            query += " AND u.account_status = ?";
            params.push(status);
        }

        if (search) {
            query += " AND (u.email LIKE ? OR u.fname LIKE ? OR u.lname LIKE ?)";
            const term = `%${search}%`;
            params.push(term, term, term);
        }

        query += " ORDER BY u.created_at DESC";

        const [rows] = await this.db.query(query, params);
        return rows;
    }



    async findById(id) {
        const [rows] = await this.db.query("SELECT * FROM user WHERE user_id = ?", [id]);
        return rows[0] || null;
    }



    async updateStatus(id, status) {
        const [result] = await this.db.query(
            "UPDATE user SET account_status = ? WHERE user_id = ?",
            [status, id]
        );
        return result.affectedRows > 0;
    }
}

module.exports = UserRepository;
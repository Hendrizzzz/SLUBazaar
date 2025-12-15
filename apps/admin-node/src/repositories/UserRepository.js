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
        let query = "SELECT user_id, fname, lname, email, account_status, created_at FROM user WHERE 1=1";
        const params = [];

        // Dynamic Filtering
        if (status && status !== 'all') {
            query += " AND account_status = ?";
            params.push(status);
        }

        if (search) {
            query += " AND (email LIKE ? OR fname LIKE ? OR lname LIKE ?)";
            const term = `%${search}%`;
            params.push(term, term, term);
        }

        query += " ORDER BY created_at DESC";

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
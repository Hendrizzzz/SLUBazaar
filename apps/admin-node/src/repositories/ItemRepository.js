class ItemRepository {
    constructor(db) {
        this.db = db;
    }

    // Used by DashboardService
    async countByStatus(status) {
        const [rows] = await this.db.query(
            "SELECT COUNT(*) as count FROM item WHERE item_status = ?",
            [status]
        );
        return rows[0].count;
    }




    // Used by ListingController (Table View)
    async findAll({ search, status }) {
        let query = `
            SELECT 
                i.item_id, 
                i.title, 
                i.current_bid as price, 
                i.description, 
                (SELECT img.image_url FROM item_image img WHERE img.item_id = i.item_id LIMIT 1) as image_url,
                i.item_status as status, 
                i.created_at, 
                CONCAT(u.fname, ' ', u.lname) as seller
            FROM \`item\` i
            LEFT JOIN \`user\` u ON i.seller_id = u.user_id
            WHERE 1=1
        `;
        const params = [];

        if (status && status !== 'all') {
            query += " AND i.item_status = ?";
            params.push(status);
        }

        if (search) {
            query += " AND i.title LIKE ?";
            params.push(`%${search}%`);
        }

        query += " ORDER BY i.created_at DESC";

        const [rows] = await this.db.query(query, params);
        return rows;
    }

    async findById(id) {
        const [rows] = await this.db.query("SELECT * FROM item WHERE item_id = ?", [id]);
        return rows[0] || null;
    }




    // Used for Soft Delete / Restore
    async updateStatus(id, status) {
        const [result] = await this.db.query(
            "UPDATE item SET item_status = ? WHERE item_id = ?",
            [status, id]
        );
        return result.affectedRows > 0;
    }



    // Used for Sanitization (Req #10)
    async updateContent(id, title, description) {
        const [result] = await this.db.query(
            "UPDATE item SET title = ?, description = ? WHERE item_id = ?",
            [title, description, id]
        );
        return result.affectedRows > 0;
    }
}

module.exports = ItemRepository;
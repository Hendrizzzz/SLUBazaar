
class ItemRepository {
    constructor(db) {
        this.db = db;
    }

    async getAllItemsForAdmin() {
        try {
            const query = `
                SELECT 
                    item_id as itemId,
                    seller_id as sellerId,
                    title,
                    description,
                    current_bid as currentBid,
                    created_at as createdAt,
                    item_status as itemStatus,
                    category
                FROM item
                ORDER BY created_at DESC
            `;
            const [rows] = await this.db.execute(query);
            return rows;
        } catch (error) {
            throw new Error(`Failed to get all items: ${error.message}`);
        }
    }

    async getItemDashboardStats() {
        try {
            const query = `
                SELECT 
                    SUM(CASE WHEN item_status = 'Active' THEN 1 ELSE 0 END) AS active_count,
                    SUM(CASE WHEN item_status IN ('Sold', 'Awaiting Meetup') THEN 1 ELSE 0 END) AS closed_count,
                    SUM(CASE WHEN item_status = 'Sold' THEN 1 ELSE 0 END) AS sold_count
                FROM item
            `;
            const [rows] = await this.db.execute(query);
            return {
                active_count: parseInt(rows[0].active_count || 0),
                closed_count: parseInt(rows[0].closed_count || 0),
                sold_count: parseInt(rows[0].sold_count || 0)
            };
        } catch (error) {
            throw new Error(`Failed to get item stats: ${error.message}`);
        }
    }

    async updateItemStatus(itemId, newStatus) {
        try {
            const query = "UPDATE item SET item_status = ? WHERE item_id = ?";
            await this.db.execute(query, [newStatus, itemId]);
        } catch (error) {
            throw new Error(`Failed to update item status: ${error.message}`);
        }
    }

    async removeAllActiveItemsBySeller(sellerId) {
        try {
            const query = "UPDATE item SET item_status = 'Removed By Admin' WHERE seller_id = ? AND item_status = 'Active'";
            await this.db.execute(query, [sellerId]);
        } catch (error) {
            throw new Error(`Failed to remove items by seller: ${error.message}`);
        }
    }
}

module.exports = ItemRepository;
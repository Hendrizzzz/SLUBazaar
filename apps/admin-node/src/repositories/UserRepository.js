
class UserRepository {
    constructor(db) {
        this.db = db;
    }

    async getAllActiveUsers() {
        try {
            const query = `
                SELECT 
                    user_id as userId,
                    fname as firstName,
                    lname as lastName,
                    email,
                    average_rating as averageRating,
                    created_at as createdAt,
                    account_status as accountStatus
                FROM user 
                WHERE account_status = ?
            `;
            const [rows] = await this.db.execute(query, ['active']);
            return rows;
        } catch (error) {
            throw new Error(`Failed to get active users: ${error.message}`);
        }
    }


    async getAllBannedUsers() {
        try {
            const query = `
                SELECT 
                    user_id as userId,
                    fname as firstName,
                    lname as lastName,
                    email,
                    average_rating as averageRating,
                    created_at as createdAt,
                    account_status as accountStatus
                FROM user 
                WHERE account_status = ?
            `;
            const [rows] = await this.db.execute(query, ['banned']);
            return rows;
        } catch (error) {
            throw new Error(`Failed to get banned users: ${error.message}`);
        }
    }

    async updateAccountStatus(userId, newStatus) {
        try {
            const query = "UPDATE user SET account_status = ? WHERE user_id = ?";
            await this.db.execute(query, [newStatus, userId]);
        } catch (error) {
            throw new Error(`Failed to update account status: ${error.message}`);
        }
    }

    async countTotalMembers() {
        try {
            const query = "SELECT COUNT(*) as total FROM user";
            const [rows] = await this.db.execute(query);
            return parseInt(rows[0].total || 0);
        } catch (error) {
            throw new Error(`Failed to count total members: ${error.message}`);
        }
    }

    async updateUser(userId, userData) {
        try {
            const { firstName, lastName, email } = userData;
            const query = "UPDATE user SET fname = ?, lname = ?, email = ? WHERE user_id = ?";
            await this.db.execute(query, [firstName, lastName, email, userId]);
            return true;
        } catch (error) {
            throw new Error(`Failed to update user: ${error.message}`);
        }
    }

}

module.exports = UserRepository;
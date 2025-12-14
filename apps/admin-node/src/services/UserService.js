class UserService {
    constructor(userRepo) {
        this.userRepo = userRepo;
    }
    
    async getAllActiveUsers(search, status) {
        try {
            // Get all active users from the repository
            const users = await this.userRepo.getAllActiveUsers();
            
            // Filter by search term if provided
            let filteredUsers = users;
            if (search) {
                const searchTerm = search.toLowerCase();
                filteredUsers = users.filter(user => 
                    (user.firstName && user.firstName.toLowerCase().includes(searchTerm)) || 
                    (user.lastName && user.lastName.toLowerCase().includes(searchTerm)) ||
                    (user.email && user.email.toLowerCase().includes(searchTerm))
                );
            }
            
            return filteredUsers;
        } catch (error) {
            throw new Error(`Failed to get active users: ${error.message}`);
        }
    }
    
    async getAllBannedUsers() {
        try {
            return await this.userRepo.getAllBannedUsers();
        } catch (error) {
            throw new Error(`Failed to get banned users: ${error.message}`);
        }
    }
    
    async updateAccountStatus(userId) {
        try {
            // First get the current status of the user
            // Note: This is a simplified approach - in a real app, you'd want to pass the desired status
            const users = await this.userRepo.getAllActiveUsers();
            const user = users.find(u => u.userId == userId);
            
            if (!user) {
                // Check if user is banned
                const bannedUsers = await this.userRepo.getAllBannedUsers();
                const bannedUser = bannedUsers.find(u => u.userId == userId);
                if (bannedUser) {
                    // User is banned, so unban them
                    await this.userRepo.updateAccountStatus(userId, 'active');
                    return true;
                }
                throw new Error('User not found');
            }
            
            // User is active, so ban them
            await this.userRepo.updateAccountStatus(userId, 'banned');
            return true;
        } catch (error) {
            throw new Error(`Failed to update account status: ${error.message}`);
        }
    }
    
    async updateUser(userId, userData) {
        try {
            return await this.userRepo.updateUser(userId, userData);
        } catch (error) {
            throw new Error(`Failed to update user: ${error.message}`);
        }
    }
}

module.exports = UserService;
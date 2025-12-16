class UserService {
    constructor(userRepo) {
        this.userRepo = userRepo;
    }

    async getAllUsers(filters) {
        return await this.userRepo.findAll(filters);
    }

    async getUserById(id) {
        const user = await this.userRepo.findById(id);
        if (!user) return null;

        // Optional: Remove sensitive data before sending to controller
        delete user.password_hash;
        return user;
    }

    async updateUserStatus(userId, status) {
        // Validation: Ensure we don't set a weird status string
        const validStatuses = ['active', 'banned', 'unverified'];
        if (!validStatuses.includes(status)) {
            throw new Error("Invalid user status provided.");
        }

        return await this.userRepo.updateStatus(userId, status);
    }
}

module.exports = UserService;
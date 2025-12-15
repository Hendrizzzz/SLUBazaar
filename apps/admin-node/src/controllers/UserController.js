class UserController {
    constructor(userService) {
        this.userService = userService;
    }





    /**
     * [VIEW] Renders the User Directory
     * GET /admin/users
     */
    async getUsersView(req, res) {
        res.render('users', {
            title: 'User Management | SLU Bazaar Admin',
            path: '/users'
        });
    }



    /**
     * [API] Search and Filter Users
     * GET /admin/api/users?q=juan&status=active
     */
    async getAllUsers(req, res) {
        try {
            const filters = {
                search: req.query.q || '',
                status: req.query.status || 'all'
            };
            const users = await this.userService.getAllUsers(filters);
            res.json({ success: true, data: users });
        } catch (error) {
            res.status(500).json({ success: false, error: 'Failed to fetch users' });
        }
    }




    /**
     * [API] Get single user profile for Admin view
     * GET /admin/api/users/:id
     */
    async getUserProfile(req, res) {
        try {
            const userId = req.params.id;
            const user = await this.userService.getUserById(userId);

            if (!user) {
                return res.status(404).json({ success: false, error: 'User not found' });
            }
            res.json({ success: true, data: user });
        } catch (error) {
            res.status(500).json({ success: false, error: 'Internal error' });
        }
    }




    /**
     * [API] Update Status (Ban/Unban)
     * POST /admin/api/users/:id/status
     */
    async updateUserStatus(req, res) {
        try {
            const userId = req.params.id;
            const { status } = req.body; // 'active' or 'banned'

            if (!['active', 'banned'].includes(status)) {
                return res.status(400).json({ success: false, error: 'Invalid status' });
            }

            await this.userService.updateUserStatus(userId, status);
            res.json({ success: true, message: `User marked as ${status}` });
        } catch (error) {
            res.status(500).json({ success: false, error: error.message });
        }
    }




    // TODO: Implement this 
    async editUser(req, res) {
        // Implementation for editing name/email directly
        res.json({ success: true, message: 'Feature not implemented yet' });
    }
}

module.exports = UserController;
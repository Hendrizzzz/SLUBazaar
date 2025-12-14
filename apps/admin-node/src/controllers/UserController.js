const UserService = require('../services/UserService');

/**
 * Controller for the User Management section
 */
class UsersController {
    constructor(userService) {
        this.userService = userService;
    }
  
    async viewUsers(req, res) {
        try {
            const { search, status } = req.query;
            const users = await this.userService.getAllActiveUsers(search, status);
            
            res.render('users/users', {
                title: 'User Management',
                currentPage: 'users',
                users: users,
                searchQuery: search || '',
                statusFilter: status || 'all'
            });
        } catch (error) {
            console.error('Error loading users:', error);
            res.status(500).render('error', {
                title: 'Users Error',
                message: 'Failed to load users'
            });
        }
    }

    async viewBannedUsers(req, res) {
        try {
            const users = await this.userService.getAllBannedUsers();
            
            res.render('users/banned', {
                title: 'Banned Users',
                currentPage: 'users',
                users: users
            });
        } catch (error) {
            console.error('Error loading banned users:', error);
            res.status(500).render('error', {
                title: 'Banned Users Error',
                message: 'Failed to load banned users'
            });
        }
    }

   
    async banUser(req, res) {
        try {
            const userId = req.params.id;
            await this.userService.updateAccountStatus(userId);
            
            res.json({ success: true, message: 'User banned successfully' });
        } catch (error) {
            console.error('Error banning user:', error);
            res.status(500).json({ 
                success: false, 
                message: error.message || 'Failed to ban user' 
            });
        }
    }

   
    async unbanUser(req, res) {
        try {
            const userId = req.params.id;
            await this.userService.updateAccountStatus(userId);
            
            res.json({ success: true, message: 'User unbanned successfully' });
        } catch (error) {
            console.error('Error unbanning user:', error);
            res.status(500).json({ 
                success: false, 
                message: error.message || 'Failed to unban user' 
            });
        }
    }

    async editUser(req, res) {
        try {
            const userId = req.params.id;
            await this.userService.updateUser(userId, req.body);
            res.json({ success: true, message: 'User updated successfully' });
        } catch (error){
            console.error('Error updating user:', error);
            res.status(500).json({ 
                success: false, 
                message: error.message || 'Failed to update user' 
            });
        }
    }
    
}

module.exports = UsersController;
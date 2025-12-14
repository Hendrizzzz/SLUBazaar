
class User {
    constructor(userData) {
        this.userId = userData.user_id || userData.userId;
        this.firstName = userData.fname || userData.firstName;
        this.lastName = userData.lname || userData.lastName;
        this.email = userData.email;
        this.accountStatus = userData.account_status || userData.accountStatus;
        this.role = userData.role;
        this.createdAt = userData.created_at || userData.createdAt;
    }

   
    getFullName() {
        return `${this.firstName} ${this.lastName}`;
    }

   
    isActive() {
        return this.accountStatus === 'active';
    }

  
    isBanned() {
        return this.accountStatus === 'banned';
    }
}

module.exports = User;
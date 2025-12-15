/**
 * Maps database ENUM columns to JavaScript constants.
 * This ensures consistency across the application.
 */

const UserStatus = Object.freeze({
    ACTIVE: 'active',
    UNVERIFIED: 'unverified',
    BANNED: 'banned'
});

const UserRole = Object.freeze({
    ADMIN: 'Admin',
    MEMBER: 'Member'
});

const ItemStatus = Object.freeze({
    PENDING: 'Pending',
    ACTIVE: 'Active',
    EXPIRED: 'Expired',
    AWAITING_MEETUP: 'Awaiting Meetup',
    SOLD: 'Sold',
    DISPUTED: 'Disputed',
    CANCELLED_BY_SELLER: 'Cancelled By Seller',
    REMOVED_BY_ADMIN: 'Removed By Admin'
});

const ReportStatus = Object.freeze({
    PENDING: 'Pending',
    IN_REVIEW: 'In Review',
    RESOLVED: 'Resolved',
    DISMISSED: 'Dismissed'
});

const ReportType = Object.freeze({
    USER: 'User',
    ITEM: 'Item'
});

module.exports = {
    UserStatus,
    UserRole,
    ItemStatus,
    ReportStatus,
    ReportType
};
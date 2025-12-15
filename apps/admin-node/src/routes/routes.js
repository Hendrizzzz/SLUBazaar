const express = require('express');
const router = express.Router();

const db = require('../../config/db');

const UserRepository = require('../repositories/UserRepository');
const ReportRepository = require('../repositories/ReportRepository');
const ItemRepository = require('../repositories/ItemRepository');

const DashboardService = require('../services/DashboardService');
const ListingService = require('../services/ListingService');
const UserService = require('../services/UserService');
const ReportService = require('../services/ReportService');

const DashboardController = require('../controllers/DashboardController');
const ReportsController = require('../controllers/ReportController');
const UsersController = require('../controllers/UserController');
const ListingsController = require('../controllers/ListingController');




const userRepo = new UserRepository(db);
const reportRepo = new ReportRepository(db);
const itemRepo = new ItemRepository(db);

const dashboardService = new DashboardService(userRepo, reportRepo, itemRepo);
const listingService = new ListingService(itemRepo);
const userService = new UserService(userRepo);
const reportService = new ReportService(reportRepo, userRepo, itemRepo);

const dashboardController = new DashboardController(dashboardService);
const reportsController = new ReportsController(reportService);
const usersController = new UsersController(userService);
const listingsController = new ListingsController(listingService);




// ---------------------------------------------------------
// SECTION A: VIEW ROUTES
// These return HTML pages (EJS Views)
// ---------------------------------------------------------

router.get('/dashboard', (req, res) => dashboardController.getDashboardView(req, res));
router.get('/reports', (req, res) => reportsController.getReportsView(req, res));
router.get('/users', (req, res) => usersController.getUsersView(req, res));
router.get('/listings', (req, res) => listingsController.getListingsView(req, res));




// ---------------------------------------------------------
// SECTION B: API ROUTES (AJAX)
// These return JSON data for tables, modals, and actions
// ---------------------------------------------------------


// --- DASHBOARD API ---
// Trigger: Auto-refresh / Page Load stats
router.get('/api/stats', (req, res) => dashboardController.getDashboardStats(req, res));



// --- REPORTS API ---
// Trigger: Load table data (with filters)
router.get('/api/reports', (req, res) => reportsController.getAllReports(req, res));
// Trigger: Click "Review" button (Modal details)
router.get('/api/reports/:id', (req, res) => reportsController.getReportDetails(req, res));
// Trigger: Click "Dismiss", "Ban", or "Remove Item" inside Modal
router.post('/api/reports/resolve', (req, res) => reportsController.resolveReport(req, res));



// --- USERS API ---
// Trigger: Load table data (Search & Filter)
router.get('/api/users', (req, res) => usersController.getAllUsers(req, res));
// Trigger: View User Profile (Modal/Page)
router.get('/api/users/:id', (req, res) => usersController.getUserProfile(req, res));
// Trigger: Click "Ban" or "Unban" button
router.post('/api/users/:id/status', (req, res) => usersController.updateUserStatus(req, res));



// --- LISTINGS API ---
// Trigger: Load table data (Search & Filter)
router.get('/api/listings', (req, res) => listingsController.getAllListings(req, res));
// Trigger: Click "Delete" button (Soft Delete)
router.post('/api/listings/:id/remove', (req, res) => listingsController.removeListing(req, res));
// Trigger: Click "Save Changes" in Edit Modal (Sanitize text)
router.post('/api/listings/:id/update', (req, res) => listingsController.updateListingContent(req, res));



module.exports = router;
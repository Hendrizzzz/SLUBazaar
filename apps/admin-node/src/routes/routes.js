const express = require('express');
const router = express.Router();

// Import database connection
const db = require('../config/db');

// Import repositories
const UserRepository = require('../repositories/UserRepository');
const ReportRepository = require('../repositories/ReportRepository');
const ItemRepository = require('../repositories/ItemRepository');

// Import services
const DashboardService = require('../services/DashboardService');
const ListingService = require('../services/ListingService');
const UserService = require('../services/UserService');

// Import controllers
const DashboardController = require('../controllers/DashboardController');
const ReportsController = require('../controllers/ReportController');
const UsersController = require('../controllers/UserController');
const ListingsController = require('../controllers/ListingController');

// Create repository instances
const userRepo = new UserRepository(db);
const reportRepo = new ReportRepository(db);
const itemRepo = new ItemRepository(db);

// Create service instances
const dashboardService = new DashboardService(userRepo, reportRepo, itemRepo);
const listingService = new ListingService(itemRepo);
const userService = new UserService(userRepo);

// Create controller instances with dependencies
const dashboardController = new  DashboardController(dashboardService);
const reportsController = new ReportsController(reportRepo, userRepo, itemRepo);
const usersController = new UsersController(userService);
const listingsController = new ListingsController(listingService);

// Define routes
router.get('/dashboard', (req, res) => dashboardController.getDashboard(req, res));
router.get('/reports', (req, res) => reportsController.viewReports(req, res));
router.post('/reports/resolve', (req, res) => reportsController.resolveReport(req, res));
router.get('/reports/:id/details', (req, res) => reportsController.getReportDetails(req, res));
router.get('/users', (req, res) => usersController.viewUsers(req, res));
router.post('/users/:id/ban', (req, res) => usersController.banUser(req, res));
router.post('/users/:id/unban', (req, res) => usersController.unbanUser(req, res));
router.post('/users/:id/edit', (req, res) => usersController.editUser(req, res));
router.get('/listings', (req, res) => listingsController.viewListings(req, res));
router.post('/listings/:id/remove', (req, res) => listingsController.removeListing(req, res));
router.post('/listings/:id/restore', (req, res) => listingsController.restoreListing(req, res));

module.exports = router;
const express = require('express');
const path = require('path');
const dotenv = require('dotenv');
const expressLayouts = require('express-ejs-layouts');

// Import Middleware
const authMiddleware = require('./middleware/authMiddleware');

dotenv.config();

const app = express();

// View Engine
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));
app.use(expressLayouts);

// CHANGE: Make sure this matches your filename (main.ejs)
app.set('layout', 'layouts/layout');

// Static Files
app.use(express.static(path.join(__dirname, '../public')));

// Body Parser
app.use(express.urlencoded({ extended: true }));
app.use(express.json());

// Routes
const indexRouter = require('./routes/index');
const adminRouter = require('./routes/routes');

// 1. Root Route (Public or Redirect)
app.use('/', indexRouter);

app.use('/admin', authMiddleware, adminRouter);

// 404 Handler (Page Not Found)
app.use((req, res, next) => {
    res.status(404).send("Page not found");
});

// 500 Handler (Server Error)
app.use((err, req, res, next) => {
    console.error(err.stack);
    res.status(500).send("Something broke!");
});

module.exports = app;
// Main routes

const express = require('express');
const router = express.Router();

// Home page
router.get('/', (req, res) => {
    res.render('index', {
        title: 'SLUBazaar Admin Panel'
    });
});

module.exports = router;
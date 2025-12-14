
const express = require('express');
const path = require('path');
const dotenv = require('dotenv');
const expressLayouts = require('express-ejs-layouts');

dotenv.config();

const app = express();


app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));
app.use(expressLayouts);
app.set('layout', 'layouts/main');


app.use(express.static(path.join(__dirname, '../public')));


app.use(express.urlencoded({ extended: true }));
app.use(express.json());


const indexRouter = require('./routes/index');
const adminRouter = require('./routes/routes');

app.use('/', indexRouter);
app.use('/admin', adminRouter);


app.use((req, res, next) => {
    res.status(404).render('error', {
        title: 'Page Not Found',
        message: 'Sorry, the page you are looking for does not exist.'
    });
});


app.use((err, req, res, next) => {
    console.error(err.stack);
    res.status(500).render('error', {
        title: 'Server Error',
        message: 'Something went wrong on our end. Please try again later.'
    });
});

module.exports = app;
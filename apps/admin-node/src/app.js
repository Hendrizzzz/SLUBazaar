
const express = require('express');
const path = require('path');
const dotenv = require('dotenv');
const expressLayouts = require('express-ejs-layouts');

dotenv.config();

const app = express();


app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));
app.use(expressLayouts);
app.set('layout', 'layouts/layout');


app.use(express.static(path.join(__dirname, '../public')));


app.use(express.urlencoded({ extended: true }));
app.use(express.json());


const indexRouter = require('./routes/index');
const adminRouter = require('./routes/routes');

app.use('/', indexRouter);
app.use('/admin', adminRouter);


app.use((req, res, next) => {
    // Redirect to dashboard instead of rendering non-existent error view
    console.log(err.stack);
});

app.use((err, req, res, next) => {
    console.error(err.stack);
    // Redirect to dashboard instead of rendering non-existent error view
    res.redirect('/admin/dashboard');
});

module.exports = app;
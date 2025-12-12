# SLUBazaar

## 📂 New Project Structure

Everything has moved. Please familiarize yourself with the new layout:

```text
SLUBazaar/  (Git Root)
│
├── apps/
│   ├── client-php/         <-- OLD ROOT MOVED HERE (Marketplace)
│   │   ├── public/         <-- Apache DocumentRoot MUST point here
│   │   ├── src/
│   │   ├── config/
│   │   └── composer.json
│   │
│   └── admin-node/         <-- NEW APP (Admin Panel)
│       ├── src/
│       ├── public/         <-- Admin assets only
│       ├── package.json
│       └── .env
│
├── storage/                <-- Shared uploads folder
│   └── uploads/
│
├── docker-compose.yml
└── README.md
```

---

## ⚠️ ACTION REQUIRED: Update Your Local Environment

Because the files have moved, your local XAMPP/Apache setup **will break** until you update your Virtual Host configuration.

### Step 1: Update Apache VHost
Open your `httpd-vhosts.conf` file (usually in `C:\xampp\apache\conf\extra\httpd-vhosts.conf`).

Update the `DocumentRoot` path to point to the new location:

```apache
<VirtualHost *:80>
    ServerName slubazaar.local
    
    # 🛑 OLD PATH: .../htdocs/SLUBazaar/public
    
    # ✅ NEW PATH:
    DocumentRoot "C:/xampp/htdocs/SLUBazaar/apps/client-php/public"
    
    <Directory "C:/xampp/htdocs/SLUBazaar/apps/client-php/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### Step 2: Restart Apache
Stop and Start the Apache module in your XAMPP Control Panel.

### Step 3: Run the Setup Script (Critical!)
We use a shared folder for uploads so both the PHP and Node.js apps can see the images. You must link these folders.

1.  Open the project folder in File Explorer.
2.  Right-click `setup_dev.bat` and select **Run as Administrator**.
3.  This creates the "symlinks" connecting `public/uploads` to `shared/uploads`.

---

## 🛠️ Setting Up the Node.js Admin Panel

The Admin Panel is a separate application running on **Port 3000**.

### 1. Installation
Navigate to the node folder and install dependencies:
```bash
cd apps/admin-node
npm install
```

### 2. Configuration
Create a `.env` file inside `apps/admin-node/` and add your database credentials:
```ini
DB_HOST=localhost
DB_USER=root
DB_PASS=
DB_NAME=slubazaar
PORT=3000
```

### 3. Running the Server
To start the Admin Panel:
```bash
npm start
# OR
node server.js
```
Access the admin panel at: `http://localhost:3000/admin`

---

## 📐 Node.js Developer Guide (Layered Architecture)

We are using a **Layered Architecture** for the Admin Panel. Do not put logic in the entry files. Follow this strict structure:

### Directory Tree
```text
admin-node/
│
├── server.js             # Entry Point (Starts server)
├── .env                  # Environment Variables
│
└── src/
    ├── config/           # DB Connection
    │   └── db.js
    │
    ├── controllers/      # Handle Request/Response (Input/Output only)
    │   ├── authController.js
    │   └── dashboardController.js
    │
    ├── services/         # BUSINESS LOGIC (The "Brain")
    │   ├── authService.js
    │   └── statsService.js
    │
    ├── repositories/     # RAW SQL Queries (The Data Layer)
    │   └── userRepository.js
    │
    ├── routes/           # URL Definitions
    │   ├── authRoutes.js
    │   └── index.js
    │
    ├── middleware/       # Security & Validation
    │   ├── isAuth.js
    │   └── validate.js
    │
    └── views/            # EJS Templates
        ├── layouts/
        └── pages/
```

### Coding Standards

#### 1. The Entry Point (`server.js`)
Keep this clean. It only starts the server.

```javascript
require('dotenv').config();
const app = require('./src/app');
const PORT = process.env.PORT || 3000;

app.listen(PORT, () => {
    console.log(`🚀 Admin Server running on http://localhost:${PORT}`);
});
```

#### 2. The App Setup (`src/app.js`)
Configuration of Express, Views, and Global Middleware happens here.

```javascript
const express = require('express');
const path = require('path');
const mainRoutes = require('./routes');

const app = express();

app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));
app.use(express.static(path.join(__dirname, 'public')));
app.use(express.urlencoded({ extended: true }));

// Mount Main Router
app.use('/', mainRoutes);

module.exports = app;
```

#### 3. Controller vs. Service vs. Repository
Follow this flow for all new features:

*   **Controller:** Accepts `req`, asks Service for data, sends `res`.
*   **Service:** Contains logic, calculations, and rules.
*   **Repository:** Contains the `db.query`.

**Example Controller (`src/controllers/dashboardController.js`)**
```javascript
const statsService = require('../services/statsService');

exports.getDashboard = async (req, res, next) => {
    try {
        // Controller calls Service. It does NOT write SQL.
        const userCount = await statsService.countTotalUsers();
        
        res.render('pages/dashboard', { users: userCount });
    } catch (error) {
        next(error);
    }
};
```

**Example Service (`src/services/statsService.js`)**
```javascript
const db = require('../config/db'); 

exports.countTotalUsers = async () => {
    const [rows] = await db.query("SELECT COUNT(*) as count FROM user");
    return rows[0].count;
};
```
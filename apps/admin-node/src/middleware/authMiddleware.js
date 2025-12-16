const { UserRole } = require('../models/Enums'); // If you created the Enums file
// const ApiResponse = require('../models/ApiResponse'); // If you created this

const authMiddleware = (req, res, next) => {
    console.log(`[AuthCheck] Request to: ${req.originalUrl}`);

    // -----------------------------------------------------------------
    // DEVELOPMENT MODE: (Allow everything)
    // -----------------------------------------------------------------
    // Since we haven't bridged the PHP Session yet, we assume 
    // the user is an Admin so you can build the UI.

    // Uncomment this line to fake a user session in Node
    req.user = { id: 1, role: 'Admin', name: 'Dev Admin' };

    next();
    return; // Stop here for now
    // -----------------------------------------------------------------


    // -----------------------------------------------------------------
    // PRODUCTION LOGIC (To implement later):
    // -----------------------------------------------------------------
    /*
    // 1. Check if the PHP session cookie exists (usually PHPSESSID)
    const phpSessionId = req.headers.cookie; 

    if (!phpSessionId) {
        // If it's an AJAX request (API), return JSON error
        if (req.xhr || req.path.includes('/api/')) {
            return res.status(401).json({ success: false, error: 'Unauthorized' });
        }
        // If it's a HTML Page request, redirect to PHP Login
        return res.redirect('/public/index.php?action=login'); 
    }

    // 2. Ideally, query the DB session table here to verify the ID
    // ... logic to query DB ...
    
    // 3. If valid, next()
    next();
    */
};

module.exports = authMiddleware;
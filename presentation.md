# SLU Bazaar: 12-Minute Professional Technical Presentation

**Theme:** "The Best Tool for the Job" - Demonstrating a Hybrid Microservice Architecture.

---

## 0. The Hook & Architecture (2 Minutes)
**Goal:** Establish technical competence immediately. Don't start with "login page". Start with the *System*.

*   **Display:** Open `docker-compose.yml` in VS Code (or a diagram if you have one).
*   **The Narrative:** "Most student projects use one language. SLU Bazaar uses a **Hybrid Polyglot Architecture** to solve specific problems:"
    1.  **PHP 8.2 (Marketplace):** "Chosen for the student-facing site because of its robust session handling and server-side rendering speed."
    2.  **Node.js (Admin Panel):** "Chosen for the Admin Dashboard to leverage non-blocking I/O for real-time monitoring."
    3.  **MySQL 8 (Shared Brain):** "Both apps connect to a single containerized database."
    4.  **Docker Orchestration:** "The entire stack spins up with one command, simulating a production cloud environment."

---

## 1. Feature A: The Marketplace & Bidding Logic (4 Minutes)
**Goal:** Show the core product value, then prove the code works.

### The Flow (Demo)
1.  **Browser:** Open **Student Login**.
2.  **Action:** Create a listing (e.g., "Scientific Calculator"). Upload an image.
3.  **Twist:** Open an **Incognito Window** (User 2). Place a Bid.
    *   *Show the UI update.*

### The Code (Deep Dive)
*   **Switch to VS Code:** `apps/client-php/src/controllers/AuctionController.php`
*   **Explain `placeBid()` Method:**
    *   "This isn't just updating a number. The Controller enforces business logic:"
    *   Validates the bid increment.
    *   Checks the auction deadline (Timezone logic).
    *   **Anti-Sniping (Optional):** "If a bid comes in last minute, how we handle it."

---

## 2. Feature B: The Background Worker (2 Minutes)
**Goal:** Show you understand "Async" operations.

### The Concept
*   **Say:** "A major challenge in auction sites is 'Who wins?'. We didn't want the user's browser to calculate the winner every time they refreshed the page. That's slow."

### The Code (Deep Dive)
*   **Switch to VS Code:** `apps/client-php/console.php` (or `Worker` class).
*   **Explain:** "We built a **headless worker** container (`slubazaar-worker`)."
*   **Highlight:**
    *   "It runs independently of the web server."
    *   "It scans the DB every minute for auctions where `end_time < NOW()`."
    *   "It atomically processes the transaction and marks the item 'SOLD'."

---

## 3. Feature C: The Hybrid Asset Bridge (3 Minutes)
**Goal:** The "Wow" Factor. Show how two different languages share files.

### The Flow (Demo)
1.  **Browser:** Open **Admin Panel** (Port 3000).
2.  **Action:** Go to "All Item Listings".
3.  **Reveal:** Find the "Calculator" we just uploaded in PHP. Click it. **The PHP image loads inside the Node App.**

### The Code (Deep Dive)
*   **Say:** "This looks simple, but it's technically difficult. PHP saves files to its folder. Node cannot see inside the PHP container."
*   **Switch to VS Code:** `apps/admin-node/src/app.js` (or `index.js`).
*   **Highlight The "Bridge":**
    ```javascript
    // The Static Asset Bridge
    app.use('/admin/uploads', express.static('/app/public/assets/uploads'));
    ```
*   **Explain:**
    *   "We used a **Docker Named Volume** (`uploads_data`) to create a shared physical storage layer."
    *   "This Middleware acts as a bridge, serving the exact same physical byte-data that PHP wrote, but through the Node.js server."

---

## 4. Closing (1 Minute)
*   **Summary:** "SLU Bazaar is a complete ecosystem. We handled Authentication, Microservice Communication (via DB/Disk), and Containerization."
*   **Final Line:** "We are ready for deployment."

---

## Q&A Prep (Be ready to open these)
*   **"How do you handle security?"** (Show SQL Injection protection in `Database.php` PDO Binding).
*   **"What if Docker fails?"** (Refer to the `restart: always` policy in YAML).

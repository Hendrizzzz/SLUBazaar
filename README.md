# SLU Bazaar - Hybrid Micro-service Marketplace

**Team:** 312Team-DIV-CENTERED 

**Submission Date:** December 2025

---

## 1. Executive Summary

SLU Bazaar is a distributed web application designed to facilitate secure trading within the SLU community. It employs a **Hybrid Polyglot Architecture**, leveraging the stability of **PHP 8.2** for the customer-facing Marketplace and the asynchronous performance of **Node.js (v18)** for the real-time Administration Panel.

All components are containerized and orchestrated via **Docker Compose** to ensure a consistent, reproducible deployment environment on Ubuntu Server.

### Core Services
1.  **`slubazaar_client` (PHP)**: The student marketplace (Port **8080**).
2.  **`slubazaar_admin` (Node.js)**: The moderation dashboard (Port **3000**).
3.  **`slubazaar_worker` (PHP CLI)**: A background daemon that processes expired auctions and cleanup tasks.
4.  **`slubazaar_db` (MySQL 8)**: The centralized persistent data store (Port **3307**).

---

## 2. Deployment Instructions (Ubuntu Server)

**Prerequisites:**
*   Docker Engine (v20.10+)
*   Docker Compose (v2.0+)

### Step-by-Step Deployment

1.  **Integrity Check**: Ensure the `312-Team-DIV-CENTERED-fin.zip` is extracted and you are within the root directory containing `docker-compose.yml`.

2.  **Database Seed Verification**:
    Ensure the file `312-Team-DIV-CENTERED.sql` exists in the root directory. This file is automatically mapped to the database container's entrypoint and will initialize the schema on the *first run only*.

3.  **Launch the Stack**:
    Execute the orchestration command in detached mode:
    ```bash
    sudo docker-compose up --build -d
    ```

4.  **Verification**:
    Check the status of the containers:
    ```bash
    sudo docker-compose ps
    ```
    *All 4 services should display a status of `Up`.*

---

## 3. Access Points

| Service | access URL | Credentials (If Applicable) |
| :--- | :--- | :--- |
| **Student Marketplace** | `http://localhost:8080` | Register a new account or Login |
| **Admin Dashboard** | `http://localhost:3000` | Login with Admin Account |
| **Database (Direct)** | `localhost:3307` | **User:** `root` / **Pass:** `rootpassword` |

---

## 4. Operational Runbook & Troubleshooting

As per our experience, we have identified potential environmental edge cases and provided their resolutions below.

### ISSUE 1: "Connection Refused" on First Boot
**Symptom**: The PHP or Node.js app throws a database connection error immediately after starting.
**Root Cause**: Race Condition. The MySQL container takes 10-30 seconds to initialize its file structure and import the `.sql` dump. The application containers might attempt to connect before the database socket is ready.
**Solution**:
The system is designed with **Resilience** in mind (`restart: always`).
1.  Wait 30-60 seconds.
2.  The containers will automatically restart and connect successfully once MySQL is ready.
3.  You can manually force a retry: `sudo docker-compose restart client admin`

### ISSUE 2: Image Uploads Fail (Permission Denied)
**Symptom**: Users cannot upload profile pictures or item images.
**Root Cause**: Linux Permission Variance. The Docker volumes map to the host filesystem. If the host folder permissions are owned by `root`, the `www-data` user inside the PHP container may face write restrictions.
**Solution**:
Run the following command on the host machine to grant write access to the uploaded content folder:
```bash
sudo chmod -R 777 apps/client-php/public/uploads
sudo chmod -R 777 apps/admin-node/public/uploads
```
*(Note: In a production environment, we would use strict chown ownership, but 777 is permissive for this academic demonstration).*

### ISSUE 3: Port Conflicts (Address already in use)
**Symptom**: Docker fails to start with error `Bind for 0.0.0.0:3000 failed: port is already allocated`.
**Root Cause**: Another service (like a local Node process or another web server) is using port 3000, 8080, or 3307.
**Solution**:
1.  Identify the blocking process: `sudo lsof -i :3000`
2.  Kill the process OR modify our `docker-compose.yml` to bind to a different host port (e.g., `"3001:3000"`).

### ISSUE 4: Missing "Sold" Items in Profile
**Symptom**: Items marked as sold do not appear in the "Sold" tab.
**Context**: This was a known issue related to matching strict bid prices.
**Status**: **RESOLVED**. We patched the `ItemRepository` logic to track the `buyer_id` directly on the item record upon transaction verification, ensuring 100% data accuracy regardless of bid history quirks.

---

## 5. Architecture Notes

### Shared Persistence Strategy
To support the Hybrid architecture, we implemented a **Named Docker Volume** (`uploads_data`).
*   **Mount Point PHP**: `/var/www/html/public/uploads`
*   **Mount Point Node**: `/app/public/uploads`
*   **Benefit**: This allows the Admin Panel to instantly render images uploaded by the PHP Client without complex S3 integration or file synchronization scripts.

### Background Worker Pattern
We introduced a dedicated `worker` container that executes `php console.php auction:worker`. This decouples heavy background tasks (like checking for thousands of expired auctions) from the request-response cycle of the web server, ensuring the UI remains snappy for end-users.
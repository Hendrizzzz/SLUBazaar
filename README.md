# SLU Bazaar - Hybrid Micro-service Marketplace

**Team:** 312Team-DIV-CENTERED 

**Submission Date:** December 2025

---

## 1. Executive Summary

SLU Bazaar is a distributed web application designed to facilitate secure trading within the SLU community. It employs a **Hybrid Polyglot Architecture**, leveraging the stability of **PHP 8.2** for the customer-facing Marketplace and the asynchronous performance of **Node.js (v18)** for the real-time Administration Panel.

All components are containerized and orchestrated via **Docker Compose** to ensure a consistent, reproducible deployment environment on Ubuntu Server.

### Core Services
1.  **`slubazaar_client` (PHP)**: The student marketplace (Port **80**).
2.  **`slubazaar_admin` (Node.js)**: The moderation dashboard (Port **3000**).
3.  **`slubazaar_worker` (PHP CLI)**: A background daemon that processes expired auctions and cleanup tasks.
4.  **`slubazaar_db` (MySQL 8)**: The centralized persistent data store (Port **3307**).

---

## 2. Deployment Instructions (Windows - Quick Start)

If you simply want to test the application on a local Windows machine, follow these simplified steps.

**Prerequisites:**
*   **Docker Desktop** for Windows installed and running.

**Steps:**
1.  **Unzip**: Extract the `312-Team-DIV-CENTERED-fin.zip` file to any folder (e.g., Desktop or Documents).
2.  **Open Terminal**: Open PowerShell or Command Prompt inside the `SLUBazaar` folder.
3.  **Launch**:
    ```powershell
    docker-compose up --build -d
    ```
4.  **Done**: You can now access the app (See Section 4. Access Points).

---

## 3. Deployment Instructions (Ubuntu Server - Production)

**Prerequisites:**
*   **Virtual Machine**: Ubuntu Server (20.04/22.04 LTS) running on VirtualBox.
*   **Network**: Configured with a **Bridged Adapter** (Settings -> Network -> Attached to: Bridged Adapter).
    *   *To find your IP Address*: Run `ip a` or `hostname -I` in your Ubuntu terminal.

### Step-by-Step Deployment Guide

#### Phase 1: Transfer Code to Server
From your Windows (Host) machine, use `scp` (Secure Copy) to send the project zip file to your Ubuntu Server.
Open PowerShell or Command Prompt on Windows:

```powershell
# Syntax: scp <path_to_zip> <username>@<ip_address>:~
scp "C:\Path\To\312-Team-DIV-CENTERED-fin.zip" username@192.168.x.x:~
```
*   Replace `192.168.x.x` with the IP you found earlier.
*   **Note**: If prompted "Are you sure you want to continue connecting?", type **yes** and press Enter.
*   Enter your Ubuntu password when prompted.

#### Phase 2: Server Environment Setup
Log into your Ubuntu Server and run these commands to prepare the environment:

```bash
# 1. Update package lists
sudo apt update

# 2. Install Docker, Compose, and Unzip
sudo apt install -y unzip docker.io docker-compose

# 3. Enable Docker service
sudo systemctl enable --now docker
```

#### Phase 3: Launch Application
Unzip the project and start the container fleet:

```bash
# 1. Unzip the project
unzip 312-Team-DIV-CENTERED-fin.zip

# 2. Enter the project directory
cd SLUBazaar

# 3. Start the containers (Detached Mode)
sudo docker-compose up --build -d
```

#### Phase 4: Verification
Check that all 4 containers are up and running:
```bash
sudo docker-compose ps
```
*Status should be `Up` for all services.*

---

## 4. Access Points & Host Configuration

### Professional Access (Applies to both Windows & Ubuntu)
To access the site via a clean URL like `http://slubazaar.com` instead of `localhost` or an IP address, edit your **Windows Host File**:

1.  Open **Notepad** as Administrator.
2.  Open file: `C:\Windows\System32\drivers\etc\hosts`
3.  Add this line at the bottom:
    *   **For Windows Docker**: `127.0.0.1  slubazaar.com`
    *   **For Ubuntu VM**: `192.168.x.x  slubazaar.com` (Replace with actual VM IP)

### Service URLs

| Service | Access URL | Credentials |
| :--- | :--- | :--- |
| **Student Marketplace** | `http://slubazaar.com` (or IP Port 80) | Register or Login |
| **Admin Dashboard** | `http://slubazaar.com:3000` (or IP Port 3000) | Login with Admin Account |
| **Database (Direct)** | `slubazaar.com:3307` | **User:** `root` / **Pass:** `rootpassword` |

---

## 5. Operational Runbook & Troubleshooting

As per our experience during development, we have identified potential edge cases and provided their resolutions below.

### ISSUE 1: "Connection Refused" on First Boot
**Symptom**: The PHP or Node.js app throws a database connection error immediately after starting.
**Root Cause**: Race Condition. The MySQL container takes 10-30 seconds to initialize schemas.
**Solution**:
The system is resilient. **Wait 30-60 seconds**, and the containers will automatically reconnect.
You can force a retry manually:
```bash
docker-compose restart client admin
```

### ISSUE 2: Admin Images Not Loading
**Symptom**: Images appear broken in the Admin Panel but work in the Client.
**Root Cause**: Path mismatch between frontend code and backend storage.
**Solution**:
We have implemented a **Static Asset Bridge** in the Node.js server to map `/admin/uploads` requests directly to the `assets` folder. Ensure you rebuilt the container:
```bash
docker-compose up --build -d admin
```

### ISSUE 3: Port Conflicts
**Symptom**: Error `Bind for 0.0.0.0:80 failed: port is already allocated`.
**Root Cause**: Another web server (like Apache, Nginx, or IIS) is already running on the host.
**Solution**:
Stop the conflicting service or stop XAMPP/WAMP if running locally.

### ISSUE 4: Missing "Sold" Items in Profile
**Symptom**: Items marked as sold do not appear in the "Sold" tab.
**Status**: **RESOLVED**. We successfully patched the `ItemRepository` logic and DTO mappers to track `buyer_id` accurately, ensuring data integrity regardless of transaction complexity.

---

## 6. Architecture Notes

### Shared Persistence Strategy
To support the Hybrid architecture, we implemented a **Named Docker Volume** strategy.
*   **Source**: `/public/assets/uploads` (Unified Storage)
*   **Mapping**: Both PHP and Node.js containers mount this exact path.
*   **Database**: Stores paths as `assets/uploads/items/...`.
*   **Result**: Zero latency sharing of assets between the separate microservices.
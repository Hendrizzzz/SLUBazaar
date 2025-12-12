@echo off
cd /d "%~dp0"
echo ==========================================
echo   SLU Bazaar - Developer Setup Script
echo ==========================================
echo.

:: 1. Create the Shared Directory if it doesn't exist
if not exist "shared\uploads\items" (
    echo [Check] Creating shared storage directory...
    mkdir "shared\uploads\items"
) else (
    echo [Check] Shared storage directory exists.
)

:: 2. Link Client-PHP
if exist "apps\client-php\public\assets\uploads" (
    echo [Action] Removing existing uploads folder in client-php to replace with link...
    rmdir "apps\client-php\public\assets\uploads" /s /q
)

echo [Action] Linking Client-PHP to Shared Storage...
:: Use absolute path for target to avoid relative path hell
mklink /D "apps\client-php\public\assets\uploads" "%~dp0shared\uploads"
if %errorlevel% neq 0 (
    echo [ERROR] Failed to create Client Symlink. Run as Administrator!
    pause
    exit /b
)

:: 3. Link Admin-Node
if not exist "apps\admin-node\public" mkdir "apps\admin-node\public"

if exist "apps\admin-node\public\uploads" (
    echo [Action] Removing existing uploads folder in admin-node...
    rmdir "apps\admin-node\public\uploads" /s /q
)

echo [Action] Linking Admin-Node to Shared Storage...
mklink /D "apps\admin-node\public\uploads" "%~dp0shared\uploads"
if %errorlevel% neq 0 (
    echo [ERROR] Failed to create Admin Symlink. Run as Administrator!
    pause
    exit /b
)

echo.
echo ==========================================
echo   [SUCCESS] Environment Linked!
echo   Files saved in 'shared/uploads' will now appear in both apps.
echo ==========================================
pause

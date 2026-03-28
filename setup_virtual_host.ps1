# Hajee Virtual Host Setup Script
# Run this as Administrator

Write-Host "=== Hajee Virtual Host Setup ===" -ForegroundColor Cyan
Write-Host ""

# Check if running as Administrator
$isAdmin = ([Security.Principal.WindowsPrincipal] [Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)

if (-not $isAdmin) {
    Write-Host "ERROR: This script must be run as Administrator!" -ForegroundColor Red
    Write-Host "Right-click PowerShell and select 'Run as Administrator'" -ForegroundColor Yellow
    pause
    exit 1
}

# Step 1: Copy virtual host configuration
Write-Host "Step 1: Copying virtual host configuration..." -ForegroundColor Green
$source = "c:\laragon\www\Hajee\hajee.test.conf"
$destination = "C:\laragon\etc\apache2\sites-enabled\auto.hajee.test.conf"

if (Test-Path $source) {
    Copy-Item $source $destination -Force
    Write-Host "  ✓ Virtual host config copied successfully" -ForegroundColor Green
} else {
    Write-Host "  ✗ Source file not found: $source" -ForegroundColor Red
    exit 1
}

# Step 2: Update hosts file
Write-Host ""
Write-Host "Step 2: Updating Windows hosts file..." -ForegroundColor Green
$hostsPath = "C:\Windows\System32\drivers\etc\hosts"
$hostsContent = Get-Content $hostsPath -Raw

if ($hostsContent -notmatch "hajee\.test") {
    Add-Content -Path $hostsPath -Value "`n127.0.0.1 hajee.test"
    Write-Host "  ✓ Added hajee.test to hosts file" -ForegroundColor Green
} else {
    Write-Host "  ℹ hajee.test already exists in hosts file" -ForegroundColor Yellow
}

# Step 3: Restart Apache
Write-Host ""
Write-Host "Step 3: Please restart Apache in Laragon:" -ForegroundColor Green
Write-Host "  1. Open Laragon" -ForegroundColor White
Write-Host "  2. Click 'Stop All'" -ForegroundColor White
Write-Host "  3. Click 'Start All'" -ForegroundColor White

Write-Host ""
Write-Host "=== Setup Complete! ===" -ForegroundColor Cyan
Write-Host "After restarting Apache, visit: http://hajee.test" -ForegroundColor Green
Write-Host ""
pause

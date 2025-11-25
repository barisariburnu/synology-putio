# Synology Put.io Module Build Script (Windows)
# This script creates the putio.host package file

Write-Host "Building Synology Put.io Module..." -ForegroundColor Cyan

# Check if required files exist
Write-Host "Checking required files..." -ForegroundColor Yellow

$requiredFiles = @("INFO", "putio.php", "LICENSE")
$allFilesExist = $true

foreach ($file in $requiredFiles) {
    if (-not (Test-Path $file)) {
        Write-Host "ERROR: $file file not found!" -ForegroundColor Red
        $allFilesExist = $false
    }
}

if (-not $allFilesExist) {
    exit 1
}

Write-Host "✓ All required files found" -ForegroundColor Green

# Check if tar is available (Windows 10 1803+ has built-in tar)
$tarExists = Get-Command tar -ErrorAction SilentlyContinue

if ($tarExists) {
    Write-Host "Creating putio.host package using tar..." -ForegroundColor Yellow
    tar -czf putio.host INFO putio.php LICENSE
}
else {
    Write-Host "tar not found. Please install tar or use WSL/Git Bash" -ForegroundColor Red
    Write-Host "Alternative: Use 7-Zip or WinRAR to create a .tar.gz archive" -ForegroundColor Yellow
    Write-Host "Files to include: INFO, putio.php, LICENSE" -ForegroundColor Yellow
    exit 1
}

# Verify the package was created
if (Test-Path "putio.host") {
    $size = (Get-Item "putio.host").Length
    $sizeKB = [math]::Round($size / 1KB, 2)
    
    Write-Host "✓ Package created successfully!" -ForegroundColor Green
    Write-Host "Package size: $sizeKB KB" -ForegroundColor Cyan
    Write-Host ""
    Write-Host "To install:" -ForegroundColor Yellow
    Write-Host "1. Upload putio.host to your Synology NAS"
    Write-Host "2. Go to Download Station > Settings > File Hosting"
    Write-Host "3. Click Add and select putio.host"
    Write-Host "4. Configure with your Put.io credentials"
}
else {
    Write-Host "ERROR: Failed to create package!" -ForegroundColor Red
    exit 1
}

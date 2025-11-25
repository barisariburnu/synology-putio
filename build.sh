#!/bin/bash

# Synology Put.io Module Build Script
# This script creates the putio.host package file

set -e

echo "Building Synology Put.io Module..."

# Define colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Check if required files exist
echo -e "${YELLOW}Checking required files...${NC}"
if [ ! -f "INFO" ]; then
    echo "ERROR: INFO file not found!"
    exit 1
fi

if [ ! -f "putio.php" ]; then
    echo "ERROR: putio.php file not found!"
    exit 1
fi

if [ ! -f "LICENSE" ]; then
    echo "ERROR: LICENSE file not found!"
    exit 1
fi

echo -e "${GREEN}✓ All required files found${NC}"

# Create the package
echo -e "${YELLOW}Creating putio.host package...${NC}"
tar -czf putio.host INFO putio.php LICENSE

# Verify the package was created
if [ -f "putio.host" ]; then
    SIZE=$(du -h putio.host | cut -f1)
    echo -e "${GREEN}✓ Package created successfully!${NC}"
    echo -e "Package size: ${SIZE}"
    echo ""
    echo "To install:"
    echo "1. Upload putio.host to your Synology NAS"
    echo "2. Go to Download Station > Settings > File Hosting"
    echo "3. Click Add and select putio.host"
    echo "4. Configure with your Put.io credentials"
else
    echo "ERROR: Failed to create package!"
    exit 1
fi

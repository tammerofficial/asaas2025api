#!/bin/bash

# Get Local Network IP Address
# This script finds your local IP address for accessing the API from other devices

# Colors
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${BLUE}=== Finding Your Local IP Address ===${NC}\n"

# Method 1: Try ipconfig (macOS)
if command -v ipconfig &> /dev/null; then
    for interface in en0 en1 eth0 wlan0; do
        IP=$(ipconfig getifaddr $interface 2>/dev/null)
        if [ -n "$IP" ]; then
            echo -e "${GREEN}✅ Found IP on interface ${interface}:${NC}"
            echo -e "${YELLOW}Local IP: ${IP}${NC}"
            echo ""
            echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
            echo -e "${GREEN}📱 Access from other devices on the same network:${NC}"
            echo -e "${YELLOW}API Base URL: http://${IP}/api/central/v1${NC}"
            echo -e "${YELLOW}Example: http://${IP}/api/central/v1/auth/login${NC}"
            echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
            exit 0
        fi
    done
fi

# Method 2: Try ifconfig
if command -v ifconfig &> /dev/null; then
    IP=$(ifconfig | grep "inet " | grep -v "127.0.0.1" | awk '{print $2}' | head -1)
    if [ -n "$IP" ]; then
        echo -e "${GREEN}✅ Found IP:${NC}"
        echo -e "${YELLOW}Local IP: ${IP}${NC}"
        echo ""
        echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
        echo -e "${GREEN}📱 Access from other devices on the same network:${NC}"
        echo -e "${YELLOW}API Base URL: http://${IP}/api/central/v1${NC}"
        echo -e "${YELLOW}Example: http://${IP}/api/central/v1/auth/login${NC}"
        echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
        exit 0
    fi
fi

# Method 3: Try hostname -I (Linux)
if command -v hostname &> /dev/null; then
    IP=$(hostname -I 2>/dev/null | awk '{print $1}')
    if [ -n "$IP" ] && [ "$IP" != "127.0.0.1" ]; then
        echo -e "${GREEN}✅ Found IP:${NC}"
        echo -e "${YELLOW}Local IP: ${IP}${NC}"
        echo ""
        echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
        echo -e "${GREEN}📱 Access from other devices on the same network:${NC}"
        echo -e "${YELLOW}API Base URL: http://${IP}/api/central/v1${NC}"
        echo -e "${YELLOW}Example: http://${IP}/api/central/v1/auth/login${NC}"
        echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
        exit 0
    fi
fi

echo -e "${YELLOW}⚠️  Could not automatically detect IP address${NC}"
echo ""
echo -e "${BLUE}Manual methods:${NC}"
echo "1. macOS: System Preferences → Network → Advanced → TCP/IP"
echo "2. Linux: Run 'ip addr' or 'hostname -I'"
echo "3. Windows: Run 'ipconfig' in command prompt"


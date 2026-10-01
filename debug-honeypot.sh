#!/bin/bash
TARGET="http://91.134.134.100"
UA="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36"

echo "Sending debug request..."
curl -v -A "$UA" "$TARGET/login"

echo ""
echo "Checking logs..."
grep "Honeypot check" storage/logs/laravel.log | tail -n 1

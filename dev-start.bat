@echo off
setlocal enabledelayedexpansion

:: Find the local IPv4 address
for /f "tokens=2 delims=:" %%a in ('ipconfig ^| findstr "IPv4"') do (
    set temp_ip=%%a
    :: Remove the leading space
    set DEVICE_IP=!temp_ip:~1!
    :: Take the first IP found and stop
    goto :found
)

:found
echo Detected IP Address: %DEVICE_IP%
echo Starting Laravel Development Servers...

:: 1. Start PHP Server
start "Artisan Serve" cmd /k "php artisan serve --host %DEVICE_IP% --port 8080"

:: 2. Start Reverb
start "Reverb" cmd /k "php artisan reverb:start"

:: 3. Start Queue Worker
start "Queue Worker" cmd /k "php artisan queue:work"

:: 4. Run NPM Build
start "NPM Build" cmd /k "npm run build"

echo All systems launched.
pause

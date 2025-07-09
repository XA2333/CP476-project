@echo off
rem -- Switch to UTF-8 codepage first --
chcp 65001 >nul

rem -- Optional: Ensure Python output is also UTF-8 --
set PYTHONIOENCODING=utf-8



pause


@echo off
echo =====================================
echo Inventory Management System QA Test Launcher
echo =====================================
echo.

echo Checking if Python is installed...
python --version >nul 2>&1
if %errorlevel% neq 0 (
    echo Error: Python not found, please install Python 3.x first
    echo Download link: https://www.python.org/downloads/
    pause
    exit /b 1
)

echo Checking required modules...
python -c "import requests" >nul 2>&1
if %errorlevel% neq 0 (
    echo Installing requests module...
    pip install requests
)

echo.
echo Starting automated testing...
echo.

python automated_qa_test.py

echo.
echo Testing completed! 
echo Detailed test reports generated in current directory:
echo - QA_Test_Script.md (manual test script)
echo - test_results.json (automated test results)
echo.
pause

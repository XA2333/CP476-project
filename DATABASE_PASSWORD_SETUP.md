# Database Password Configuration Guide

## Overview
The system now uses environment variables to securely store database passwords. This prevents sensitive information from being exposed in your code.

## Setup Instructions

### Step 1: Configure Your Password
Edit the `.env` file and replace `your_mysql_password_here` with your actual MySQL password:

```bash
# Open .env file and change this line:
DB_PASSWORD=your_actual_mysql_password

# Examples:
DB_PASSWORD=mawentao0806
DB_PASSWORD=123456
DB_PASSWORD=mySecretPassword123

# If you don't have a MySQL password (default XAMPP/WAMP setup):
DB_PASSWORD=
```

### Step 2: Test the Connection
1. Save the `.env` file
2. Access your website: `http://localhost/connect.php`
3. If connection fails, check your password in the `.env` file

## File Structure
```
your-project/
├── .env                 # Your password configuration (DO NOT commit to Git)
├── load_env.php         # Environment variable loader
├── connect.php          # Database connection with env support
└── other files...
```

## Security Benefits

✅ **Password Protection**: Password is not visible in source code
✅ **Version Control Safe**: .env file should not be committed to Git
✅ **Environment Flexibility**: Different passwords for different environments
✅ **Easy Configuration**: Change password without editing PHP code

## Troubleshooting

### Problem: "Access denied" error
**Solution**: Check your password in the `.env` file

### Problem: Environment variable not found
**Solution**: Make sure the `.env` file exists and contains `DB_PASSWORD=your_password`

### Problem: Still getting connection errors
**Solutions**:
1. Verify MySQL service is running
2. Check username is correct (default: `root`)
3. Try empty password if you're using default XAMPP/WAMP setup

## Quick Test Commands

Test if your environment variable is working:

```php
<?php
require_once 'load_env.php';
echo "DB_PASSWORD = '" . getenv('DB_PASSWORD') . "'";
?>
```

## Alternative Setup (Manual)

If you prefer not to use a .env file, you can set the environment variable manually:

**Windows PowerShell:**
```powershell
$env:DB_PASSWORD="your_password_here"
```

**Windows Command Prompt:**
```cmd
set DB_PASSWORD=your_password_here
```

**Direct PHP (not recommended for production):**
```php
$pass = 'your_password_here'; // Replace the getenv() line
```

## Next Steps

1. Edit your `.env` file with the correct password
2. Test the database connection
3. Add `.env` to your `.gitignore` file (if using Git)
4. Keep your `.env` file secure and never share it publicly

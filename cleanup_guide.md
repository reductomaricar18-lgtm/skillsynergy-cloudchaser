# Project Cleanup Guide

## Current Project Structure Issues

You have multiple copies of your SIA project in different locations:

### Main Project (KEEP THIS ONE)
- Location: `d:\laragon\www\siaproject\`
- This appears to be your active working directory
- Contains the most recent files including the dashboard.php we just fixed

### Duplicate Project (CAN BE REMOVED)
- Location: `d:\laragon\www\siaproject\htdocs\siaproject\`
- This appears to be an older copy
- May contain outdated files

### Default Web Server Files (CAN BE REMOVED)
- Location: `d:\laragon\www\siaproject\htdocs\dashboard\`
- These are default XAMPP/Laragon dashboard files
- Not part of your project

## Recommended Actions

### 1. IMMEDIATE: Test the SQL Fix
- Try accessing your dashboard now: `http://localhost/siaproject/dashboard.php`
- The SQL error should be resolved

### 2. Backup Current State
```bash
# In PowerShell, navigate to your project root
cd "d:\laragon\www\"
# Create a backup of your main project
Copy-Item -Path "siaproject" -Destination "siaproject_backup_$(Get-Date -Format 'yyyy-MM-dd')" -Recurse
```

### 3. Clean Up Project Structure
```bash
# Remove the htdocs folder completely (after backing up)
Remove-Item -Path "d:\laragon\www\siaproject\htdocs" -Recurse -Force
```

### 4. Verify Your Web Server Configuration
- Make sure your web server (Laragon) is pointing to the correct directory
- Your project should be accessible at: `http://localhost/siaproject/`

## Files Fixed
- ✅ `dashboard.php` - Fixed SQL GROUP BY error (leaderboard query)
- ✅ `matched_tab.php` - Fixed SQL GROUP BY error (matched users query)  
- ✅ `message.php` - Fixed SQL GROUP BY error (message previews query)
- ✅ Minor code cleanup (removed empty CSS attributes)

## Next Steps
1. Test the dashboard to confirm the SQL error is fixed
2. Back up your project
3. Remove duplicate folders
4. Continue development in the main project folder only

## Important Notes
- Always work in `d:\laragon\www\siaproject\` (your main project)
- Never edit files in the `htdocs\siaproject\` folder (it's a duplicate)
- Keep your database connection settings consistent across all files

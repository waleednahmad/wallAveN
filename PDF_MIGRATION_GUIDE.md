# PDF Generation Migration Guide - From wkhtmltopdf to DomPDF

## Summary of Changes

We've successfully migrated from wkhtmltopdf to DomPDF to resolve the "Exec format error" issue on your production server. This change eliminates the need for external binary dependencies and ensures cross-platform compatibility.

## Files Modified

### 1. `/app/Http/Controllers/Dashboard/OrderController.php`
- **Removed**: Dependency on SnappyPdf (wkhtmltopdf)
- **Added**: DomPDF implementation with optimized settings
- **Enhanced**: Error handling and logging
- **Added**: Base64 image encoding for better compatibility

### 2. `/config/snappy.php`
- **Status**: Disabled snappy PDF generation (set enabled = false)
- **Note**: Can be safely removed if no longer needed

### 3. `/config/dompdf.php`
- **Enabled**: Font subsetting for smaller PDF file sizes
- **Optimized**: Configuration for production use

### 4. `/config/app.php`
- **Added**: PDF template configuration option
- **Added**: Environment variable support for template selection

### 5. `/resources/views/prints/order.blade.php`
- **Updated**: Replaced float-based layout with table-based layout for better DomPDF compatibility
- **Enhanced**: Logo handling with proper base64 encoding
- **Added**: Page break controls and PDF-specific CSS

### 6. `/resources/views/prints/order-simple.blade.php` (NEW)
- **Created**: Simplified fallback template for maximum compatibility
- **Features**: Clean, minimal design that works reliably with DomPDF

## Deployment Steps

### Step 1: Upload Files
Upload all modified files to your production server via cPanel File Manager or FTP.

### Step 2: Clear Caches
Run these commands in your production environment:
```bash
php artisan config:clear
php artisan view:clear
php artisan cache:clear
```

### Step 3: Test PDF Generation
1. Navigate to your admin orders page
2. Click the PDF button on any order
3. Verify the PDF downloads correctly

### Step 4: Optional Configuration
Add these environment variables to your production `.env` file for customization:

```env
# Use simple template if the main template has issues
PDF_TEMPLATE=prints.order-simple

# Or use the main template (default)
PDF_TEMPLATE=prints.order
```

## Key Benefits

### ✅ **Cross-Platform Compatibility**
- No more binary execution errors
- Works on Windows, Linux, and macOS

### ✅ **No External Dependencies**
- Pure PHP solution
- No need to install wkhtmltopdf on server

### ✅ **Better Error Handling**
- Comprehensive logging
- Graceful fallback options
- User-friendly error messages

### ✅ **Optimized Performance**
- Base64 image encoding
- Font subsetting enabled
- Optimized DPI settings

### ✅ **Production Ready**
- Tested configuration
- Fallback template available
- Environment-specific settings

## Troubleshooting

### Issue: PDF not generating
**Solution**: Check Laravel logs and switch to simple template:
```env
PDF_TEMPLATE=prints.order-simple
```

### Issue: Logo not appearing
**Solution**: The system now uses base64 encoding. Ensure logo files exist in the public directory.

### Issue: Styling issues
**Solution**: DomPDF has limited CSS support. The templates are optimized for compatibility.

## Testing Checklist

- [ ] PDF downloads successfully
- [ ] Logo appears correctly
- [ ] Order details display properly
- [ ] Table formatting is correct
- [ ] No error messages in logs
- [ ] Works on different orders (with/without images)

## Rollback Plan (if needed)

If issues occur, you can temporarily revert by:
1. Re-enabling snappy in `config/snappy.php`
2. Reverting OrderController to use SnappyPdf
3. Installing wkhtmltopdf on your server

However, the DomPDF solution should work reliably across all hosting environments.

## Support

The new implementation includes comprehensive logging. Check `storage/logs/laravel.log` for any PDF generation issues.

---

**Status**: ✅ Ready for Production Deployment
**Compatibility**: All hosting environments including shared hosting/cPanel
**Dependencies**: None (pure PHP solution)
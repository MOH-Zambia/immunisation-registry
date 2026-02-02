# Memory Optimization Fixes for Immunisation Registry

## Issue Identified
Apache error logs showed memory exhaustion (128MB limit) when accessing the certificates and clients pages due to complex database queries with GROUP BY operations.

## Changes Made

### 1. CertificateController.php
- Added `ini_set('memory_limit', '256M')` at the start of datatable() method
- Increased memory available for certificate listing page

### 2. ClientController.php
- Replaced memory-intensive `GROUP BY` with `JOIN` approach using efficient subqueries
- Changed from:
  - `leftJoin` + `GROUP BY` (very memory intensive)
- To:
  - `selectSub()` for certificate and vaccination data (much more efficient)
- Used `whereHas()` instead of `havingRaw()` for better query optimization
- Added `ini_set('memory_limit', '256M')` for safety

## Server Configuration Required

### Option 1: Update PHP Memory Limit in php.ini (RECOMMENDED)
```bash
# Edit PHP configuration
sudo nano /etc/php/8.1/apache2/php.ini

# Find and update this line:
memory_limit = 256M

# Restart Apache
sudo systemctl restart apache2
```

### Option 2: Update .htaccess (if php.ini access not available)
Add to `/var/www/ir.moh.gov.zm/.htaccess`:
```apache
php_value memory_limit 256M
```

### Option 3: Update Apache VirtualHost Configuration
Add to your VirtualHost config:
```apache
<Directory /var/www/ir.moh.gov.zm>
    php_admin_value memory_limit 256M
</Directory>
```

## MySQL Optimization Recommendations

### Add Indexes for Better Performance
```sql
-- Add index for certificate client_id lookups
CREATE INDEX idx_certificates_client_id ON certificates(client_id);

-- Add index for vaccination client_id lookups  
CREATE INDEX idx_vaccinations_client_id ON vaccinations(client_id);

-- Add composite index for certificate filtering
CREATE INDEX idx_certificates_client_export ON certificates(client_id, export_status);

-- Add index for date range filtering
CREATE INDEX idx_clients_created_at ON clients(created_at);
CREATE INDEX idx_certificates_created_at ON certificates(created_at);
```

## Database Query Optimization

### Before (Memory Intensive)
```php
// Used GROUP BY with multiple columns (creates large temporary tables in memory)
->leftJoin('certificates', 'clients.id', '=', 'certificates.client_id')
->leftJoin('vaccinations', 'clients.id', '=', 'vaccinations.client_id')
->groupBy([...13 columns...])
```

### After (Memory Efficient)
```php
// Uses correlated subqueries (processes row by row)
->selectSub(function($query) {
    $query->selectRaw('MAX(id)')
        ->from('certificates')
        ->whereColumn('certificates.client_id', 'clients.id');
}, 'certificate_id')
```

## Expected Results
- Memory usage reduced from 128MB+ to under 100MB for typical queries
- Faster query execution (no large GROUP BY temporary tables)
- Better scalability as database grows
- No more "Packets out of order" errors
- No more memory exhaustion errors

## Testing Commands

### Check Current PHP Memory Limit
```bash
php -i | grep memory_limit
```

### Monitor Apache Error Logs
```bash
tail -f /var/log/apache2/error.log
```

### Check MySQL Query Performance
```bash
mysql -u root -p
USE immunisation_registry;

-- Show slow queries
SHOW VARIABLES LIKE 'slow_query_log';
SET GLOBAL slow_query_log = 'ON';
SET GLOBAL long_query_time = 2;

-- Check query execution plan
EXPLAIN SELECT clients.id, 
    (SELECT MAX(id) FROM certificates WHERE certificates.client_id = clients.id) as certificate_id
FROM clients LIMIT 10;
```

## Performance Monitoring

After deployment, monitor:
1. Apache error logs for memory errors
2. Page load times for certificates and clients pages  
3. Database query execution times
4. Server memory usage

## Additional Recommendations

1. **Enable Query Caching** in Laravel config/cache.php
2. **Add Pagination Limits** - Consider limiting datatables to 100 rows per page max
3. **Use Lazy Loading** for relationships where appropriate
4. **Enable MySQL Query Cache** if not already enabled
5. **Consider Redis** for session and cache storage to reduce database load

## Rollback Plan
If issues occur, the code changes can be reverted by:
```bash
cd /var/www/ir.moh.gov.zm
git diff HEAD
git checkout -- app/Http/Controllers/ClientController.php
git checkout -- app/Http/Controllers/CertificateController.php
```

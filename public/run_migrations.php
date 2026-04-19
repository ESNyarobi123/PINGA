<?php

// Enhanced Migration Runner for Webuzo/Shared Hosting
// This script helps run Laravel migrations when SSH access is limited

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Database Migration Runner - Winga</title>
    <style>
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; 
            padding: 20px; 
            background: #f5f5f5; 
            line-height: 1.6;
        }
        .container { 
            max-width: 900px; 
            margin: 0 auto; 
            background: white; 
            padding: 30px; 
            border-radius: 8px; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.1); 
        }
        pre { 
            background: #2d2d2d; 
            color: #f8f8f2; 
            padding: 15px; 
            border-radius: 4px; 
            overflow-x: auto;
            font-size: 13px;
            line-height: 1.5;
        }
        .success { 
            background: #d4edda; 
            color: #155724; 
            padding: 12px; 
            border-radius: 4px; 
            margin: 10px 0;
            border-left: 4px solid #28a745;
        }
        .error { 
            background: #f8d7da; 
            color: #721c24; 
            padding: 12px; 
            border-radius: 4px; 
            margin: 10px 0;
            border-left: 4px solid #dc3545;
        }
        .warning { 
            background: #fff3cd; 
            color: #856404; 
            padding: 15px; 
            border-radius: 4px; 
            margin-bottom: 20px; 
            border-left: 4px solid #ffc107;
        }
        .info { 
            background: #d1ecf1; 
            color: #0c5460; 
            padding: 12px; 
            border-radius: 4px; 
            margin: 10px 0;
            border-left: 4px solid #17a2b8;
        }
        h1 { color: #333; margin-top: 0; }
        h2 { color: #555; font-size: 1.3em; margin-top: 25px; border-bottom: 2px solid #eee; padding-bottom: 8px; }
        code { 
            background: #f4f4f4; 
            padding: 2px 6px; 
            border-radius: 3px; 
            font-family: 'Courier New', monospace;
            color: #e83e8c;
        }
        .step { margin: 15px 0; }
        .step-title { font-weight: bold; color: #333; margin-bottom: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Winga Database Migration Tool</h1>
        
        <div class="warning">
            <strong>⚠️ SECURITY WARNING:</strong> Delete this file (<code>public/run_migrations.php</code>) immediately after use. 
            Leaving it accessible allows anyone to run migrations on your database.
        </div>

        <?php
        
        // Step 1: Check if Laravel can be loaded
        echo "<h2>Step 1: Loading Laravel Application</h2>";
        
        try {
            $autoloadPath = __DIR__ . '/../vendor/autoload.php';
            
            if (!file_exists($autoloadPath)) {
                throw new Exception("Autoload file not found at: $autoloadPath. Please run 'composer install' first.");
            }
            
            require $autoloadPath;
            echo "<div class='success'>✓ Autoloader loaded successfully</div>";
            
        } catch (Exception $e) {
            echo "<div class='error'><strong>Error loading autoloader:</strong><br>" . htmlspecialchars($e->getMessage()) . "</div>";
            echo "</div></body></html>";
            exit;
        }

        // Step 2: Bootstrap Laravel
        echo "<h2>Step 2: Bootstrapping Laravel</h2>";
        
        try {
            $appPath = __DIR__ . '/../bootstrap/app.php';
            
            if (!file_exists($appPath)) {
                throw new Exception("Bootstrap file not found at: $appPath");
            }
            
            $app = require $appPath;
            echo "<div class='success'>✓ Laravel application bootstrapped</div>";
            
            $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
            $kernel->bootstrap();
            echo "<div class='success'>✓ Console kernel initialized</div>";
            
        } catch (Exception $e) {
            echo "<div class='error'><strong>Error bootstrapping Laravel:</strong><br>" . htmlspecialchars($e->getMessage()) . "</div>";
            echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
            echo "</div></body></html>";
            exit;
        }

        // Step 3: Check Environment
        echo "<h2>Step 3: Environment Check</h2>";
        
        try {
            $envFile = __DIR__ . '/../.env';
            
            echo "<div class='step'>";
            echo "<div class='step-title'>Environment File:</div>";
            if (file_exists($envFile)) {
                echo "<div class='success'>✓ .env file exists</div>";
            } else {
                echo "<div class='error'>✗ .env file NOT found. Please create it from .env.example</div>";
            }
            echo "</div>";
            
            echo "<div class='step'>";
            echo "<div class='step-title'>App Environment:</div>";
            echo "<div class='info'>" . config('app.env') . "</div>";
            echo "</div>";
            
            echo "<div class='step'>";
            echo "<div class='step-title'>App Debug Mode:</div>";
            echo "<div class='info'>" . (config('app.debug') ? 'Enabled' : 'Disabled') . "</div>";
            echo "</div>";
            
        } catch (Exception $e) {
            echo "<div class='error'><strong>Error checking environment:</strong><br>" . htmlspecialchars($e->getMessage()) . "</div>";
        }

        // Step 4: Test Database Connection
        echo "<h2>Step 4: Database Connection Test</h2>";
        
        try {
            echo "<div class='step'>";
            echo "<div class='step-title'>Database Driver:</div>";
            $dbConnection = config('database.default');
            echo "<div class='info'>" . htmlspecialchars($dbConnection) . "</div>";
            echo "</div>";
            
            $dbConfig = config("database.connections.{$dbConnection}");
            
            if ($dbConnection !== 'sqlite') {
                echo "<div class='step'>";
                echo "<div class='step-title'>Database Host:</div>";
                echo "<div class='info'>" . htmlspecialchars($dbConfig['host'] ?? 'Not set') . "</div>";
                echo "</div>";
                
                echo "<div class='step'>";
                echo "<div class='step-title'>Database Name:</div>";
                echo "<div class='info'>" . htmlspecialchars($dbConfig['database'] ?? 'Not set') . "</div>";
                echo "</div>";
                
                echo "<div class='step'>";
                echo "<div class='step-title'>Database Username:</div>";
                echo "<div class='info'>" . htmlspecialchars($dbConfig['username'] ?? 'Not set') . "</div>";
                echo "</div>";
            } else {
                echo "<div class='step'>";
                echo "<div class='step-title'>SQLite Database Path:</div>";
                echo "<div class='info'>" . htmlspecialchars($dbConfig['database'] ?? 'Not set') . "</div>";
                echo "</div>";
            }
            
            // Test actual connection
            echo "<div class='step'>";
            echo "<div class='step-title'>Testing Connection:</div>";
            DB::connection()->getPdo();
            echo "<div class='success'>✓ Database connection successful!</div>";
            echo "</div>";
            
            // Get database version
            $dbVersion = DB::select('SELECT VERSION() as version')[0]->version ?? 
                        DB::select('SELECT sqlite_version() as version')[0]->version ?? 
                        'Unknown';
            echo "<div class='step'>";
            echo "<div class='step-title'>Database Version:</div>";
            echo "<div class='info'>" . htmlspecialchars($dbVersion) . "</div>";
            echo "</div>";
            
        } catch (Exception $e) {
            echo "<div class='error'><strong>✗ Database connection failed:</strong><br>" . htmlspecialchars($e->getMessage()) . "</div>";
            echo "<div class='warning'><strong>Common fixes:</strong><br>";
            echo "1. Check your .env file has correct DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD<br>";
            echo "2. Ensure the database exists on your server<br>";
            echo "3. Verify database user has correct permissions<br>";
            echo "4. For Webuzo, use 'localhost' or '127.0.0.1' as DB_HOST</div>";
            echo "</div></body></html>";
            exit;
        }

        // Step 5: Check Migrations Table
        echo "<h2>Step 5: Migrations Status</h2>";
        
        try {
            $migrationsTable = config('database.migrations.table', 'migrations');
            
            if (Schema::hasTable($migrationsTable)) {
                $ranMigrations = DB::table($migrationsTable)->count();
                echo "<div class='info'>Migrations table exists. {$ranMigrations} migration(s) have been run previously.</div>";
            } else {
                echo "<div class='warning'>Migrations table does not exist yet. It will be created during first migration.</div>";
            }
            
        } catch (Exception $e) {
            echo "<div class='warning'>Could not check migrations table: " . htmlspecialchars($e->getMessage()) . "</div>";
        }

        // Step 6: Run Migrations
        echo "<h2>Step 6: Running Migrations</h2>";
        
        try {
            echo "<p>Executing <code>php artisan migrate --force</code>...</p>";
            
            // Capture output using output buffering
            ob_start();
            
            $exitCode = Artisan::call('migrate', [
                '--force' => true,
                '--verbose' => true
            ]);
            
            $output = Artisan::output();
            ob_end_clean();
            
            echo "<pre>" . htmlspecialchars($output) . "</pre>";
            
            if ($exitCode === 0) {
                echo "<div class='success'><strong>✓ Migration completed successfully!</strong></div>";
            } else {
                echo "<div class='warning'><strong>Migration command returned exit code: {$exitCode}</strong></div>";
            }
            
            // Show final migration count
            if (Schema::hasTable($migrationsTable)) {
                $totalMigrations = DB::table($migrationsTable)->count();
                echo "<div class='info'>Total migrations in database: {$totalMigrations}</div>";
            }
            
        } catch (Exception $e) {
            echo "<div class='error'><strong>✗ Error executing migration:</strong><br>" . htmlspecialchars($e->getMessage()) . "</div>";
            echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
        }

        // Step 7: Final Instructions
        echo "<h2>Step 7: Next Steps</h2>";
        echo "<div class='warning'>";
        echo "<strong>IMPORTANT - Delete this file now!</strong><br><br>";
        echo "Run this command via FTP/File Manager or SSH:<br>";
        echo "<code>rm public/run_migrations.php</code><br><br>";
        echo "Or simply delete the file <code>public/run_migrations.php</code> manually.";
        echo "</div>";
        
        ?>
        
        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; color: #666; font-size: 0.9em;">
            <strong>Troubleshooting Tips for Webuzo:</strong>
            <ul>
                <li>Make sure your .env file exists and has correct database credentials</li>
                <li>Database host is usually 'localhost' on Webuzo</li>
                <li>Ensure your database user has CREATE, ALTER, DROP permissions</li>
                <li>Check PHP version is compatible (PHP 8.2+)</li>
                <li>Verify all Composer dependencies are installed</li>
            </ul>
        </div>
    </div>
</body>
</html>

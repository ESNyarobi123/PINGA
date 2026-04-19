<?php

// Fix Migration State - Registers existing tables without re-running migrations
// Use this when tables exist but migrations table is out of sync

error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Fix Migration State - Winga</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #f8f9fa;
            font-weight: 600;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .badge-warning { background: #fff3cd; color: #856404; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Fix Migration State</h1>
        
        <div class="warning">
            <strong>⚠️ SECURITY WARNING:</strong> Delete this file (<code>public/fix_migrations.php</code>) immediately after use.
        </div>

        <?php
        
        try {
            require __DIR__ . '/../vendor/autoload.php';
            $app = require __DIR__ . '/../bootstrap/app.php';
            $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
            $kernel->bootstrap();
            
            echo "<div class='success'>✓ Laravel loaded successfully</div>";
            
        } catch (Exception $e) {
            echo "<div class='error'>Error loading Laravel: " . htmlspecialchars($e->getMessage()) . "</div>";
            echo "</div></body></html>";
            exit;
        }

        // Check database connection
        try {
            DB::connection()->getPdo();
            echo "<div class='success'>✓ Database connected</div>";
        } catch (Exception $e) {
            echo "<div class='error'>Database connection failed: " . htmlspecialchars($e->getMessage()) . "</div>";
            echo "</div></body></html>";
            exit;
        }

        echo "<h2>Step 1: Analyzing Current State</h2>";

        // Get all migration files
        $migrationPath = database_path('migrations');
        $migrationFiles = glob($migrationPath . '/*.php');
        sort($migrationFiles);

        echo "<div class='info'>Found " . count($migrationFiles) . " migration files</div>";

        // Get migrations that have been run
        $ranMigrations = [];
        try {
            if (Schema::hasTable('migrations')) {
                $ranMigrations = DB::table('migrations')->pluck('migration')->toArray();
                echo "<div class='info'>Found " . count($ranMigrations) . " migrations in database</div>";
            } else {
                echo "<div class='warning'>Migrations table doesn't exist. Creating it...</div>";
                Schema::create('migrations', function($table) {
                    $table->increments('id');
                    $table->string('migration');
                    $table->integer('batch');
                });
                echo "<div class='success'>✓ Migrations table created</div>";
            }
        } catch (Exception $e) {
            echo "<div class='error'>Error checking migrations: " . htmlspecialchars($e->getMessage()) . "</div>";
        }

        echo "<h2>Step 2: Migration Status</h2>";

        // Check which migrations need to be registered
        $needsRegistration = [];
        $nextBatch = DB::table('migrations')->max('batch') + 1;

        echo "<table>";
        echo "<thead><tr><th>Migration File</th><th>Status</th><th>Action</th></tr></thead>";
        echo "<tbody>";

        foreach ($migrationFiles as $file) {
            $migrationName = str_replace('.php', '', basename($file));
            $isRan = in_array($migrationName, $ranMigrations);
            
            echo "<tr>";
            echo "<td><small>" . htmlspecialchars($migrationName) . "</small></td>";
            
            if ($isRan) {
                echo "<td><span class='badge badge-success'>Registered</span></td>";
                echo "<td>-</td>";
            } else {
                // Check if tables from this migration exist
                $tablesExist = false;
                
                // Special check for the problematic migration
                if ($migrationName === '2026_03_11_020001_add_admin_control_center_tables') {
                    $tablesExist = Schema::hasTable('phone_block_attempts') || 
                                   Schema::hasTable('dispute_evidence') || 
                                   Schema::hasTable('broadcast_messages') || 
                                   Schema::hasTable('admin_audit_log');
                }
                
                if ($tablesExist) {
                    echo "<td><span class='badge badge-warning'>Tables Exist</span></td>";
                    echo "<td>Will register without running</td>";
                    $needsRegistration[] = $migrationName;
                } else {
                    echo "<td><span class='badge badge-danger'>Not Run</span></td>";
                    echo "<td>Needs to run normally</td>";
                }
            }
            
            echo "</tr>";
        }

        echo "</tbody></table>";

        echo "<h2>Step 3: Fix Migration State</h2>";

        if (empty($needsRegistration)) {
            echo "<div class='info'>No migrations need manual registration. You can run normal migrations now.</div>";
        } else {
            echo "<div class='warning'>Found " . count($needsRegistration) . " migration(s) with existing tables that need registration.</div>";
            
            try {
                foreach ($needsRegistration as $migration) {
                    DB::table('migrations')->insert([
                        'migration' => $migration,
                        'batch' => $nextBatch
                    ]);
                    echo "<div class='success'>✓ Registered: <code>{$migration}</code></div>";
                }
                
                echo "<h2>Step 4: Run Remaining Migrations</h2>";
                echo "<p>Now running migrations that haven't been executed yet...</p>";
                
                ob_start();
                $exitCode = Artisan::call('migrate', ['--force' => true]);
                $output = Artisan::output();
                ob_end_clean();
                
                echo "<pre>" . htmlspecialchars($output) . "</pre>";
                
                if ($exitCode === 0) {
                    echo "<div class='success'><strong>✓ All migrations completed successfully!</strong></div>";
                } else {
                    echo "<div class='warning'>Migration command returned exit code: {$exitCode}</div>";
                }
                
            } catch (Exception $e) {
                echo "<div class='error'>Error during fix: " . htmlspecialchars($e->getMessage()) . "</div>";
                echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
            }
        }

        echo "<h2>Final Status</h2>";
        
        $finalCount = DB::table('migrations')->count();
        $totalFiles = count($migrationFiles);
        
        echo "<div class='info'>";
        echo "<strong>Migrations in database:</strong> {$finalCount}<br>";
        echo "<strong>Migration files:</strong> {$totalFiles}<br>";
        if ($finalCount === $totalFiles) {
            echo "<br><span style='color: #28a745; font-weight: bold;'>✓ All migrations are synchronized!</span>";
        } else {
            echo "<br><span style='color: #856404; font-weight: bold;'>⚠ Some migrations may still need attention</span>";
        }
        echo "</div>";

        echo "<h2>Next Steps</h2>";
        echo "<div class='warning'>";
        echo "<strong>IMPORTANT - Delete these files now!</strong><br><br>";
        echo "1. Delete <code>public/fix_migrations.php</code> (this file)<br>";
        echo "2. Delete <code>public/run_migrations.php</code> (if it exists)<br><br>";
        echo "You can delete them via FTP/File Manager or run:<br>";
        echo "<code>rm public/fix_migrations.php public/run_migrations.php</code>";
        echo "</div>";
        
        ?>
    </div>
</body>
</html>

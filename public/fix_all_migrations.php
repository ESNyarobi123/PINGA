<?php

// Complete Migration State Fix - Handles all duplicate tables and missing registrations
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Complete Migration Fix - Winga</title>
    <style>
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; 
            padding: 20px; 
            background: #f5f5f5; 
            line-height: 1.6;
        }
        .container { 
            max-width: 1000px; 
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
            font-size: 12px;
            line-height: 1.4;
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
            margin: 10px 0; 
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
            font-size: 13px;
        }
        th, td {
            padding: 8px;
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
            font-size: 11px;
            font-weight: 600;
        }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-info { background: #d1ecf1; color: #0c5460; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Complete Migration State Fix</h1>
        
        <div class="warning">
            <strong>⚠️ SECURITY WARNING:</strong> Delete all migration helper files after use!
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

        try {
            DB::connection()->getPdo();
            echo "<div class='success'>✓ Database connected: " . htmlspecialchars(config('database.connections.' . config('database.default') . '.database')) . "</div>";
        } catch (Exception $e) {
            echo "<div class='error'>Database connection failed: " . htmlspecialchars($e->getMessage()) . "</div>";
            echo "</div></body></html>";
            exit;
        }

        echo "<h2>Step 1: Get All Existing Tables</h2>";

        // Get all tables in database
        $tables = DB::select('SHOW TABLES');
        $dbName = config('database.connections.' . config('database.default') . '.database');
        $tableKey = "Tables_in_{$dbName}";
        $existingTables = array_map(function($table) use ($tableKey) {
            return $table->$tableKey;
        }, $tables);

        echo "<div class='info'>Found " . count($existingTables) . " tables in database</div>";
        echo "<details><summary>View all tables</summary><pre>" . implode("\n", $existingTables) . "</pre></details>";

        echo "<h2>Step 2: Analyze Migration Files</h2>";

        $migrationPath = database_path('migrations');
        $migrationFiles = glob($migrationPath . '/*.php');
        sort($migrationFiles);

        // Get migrations that have been run
        $ranMigrations = [];
        if (Schema::hasTable('migrations')) {
            $ranMigrations = DB::table('migrations')->pluck('migration')->toArray();
        } else {
            echo "<div class='warning'>Creating migrations table...</div>";
            Schema::create('migrations', function($table) {
                $table->increments('id');
                $table->string('migration');
                $table->integer('batch');
            });
            echo "<div class='success'>✓ Migrations table created</div>";
        }

        echo "<div class='info'>Migration files: " . count($migrationFiles) . " | Registered: " . count($ranMigrations) . "</div>";

        // Define which tables each migration creates
        $migrationTableMap = [
            '2026_03_11_020001_add_admin_control_center_tables' => [
                'phone_block_attempts',
                'dispute_evidence', 
                'broadcast_messages',
                'admin_audit_log'
            ],
            '2026_03_11_025410_create_dispute_evidence_table' => ['dispute_evidence'],
            '2026_03_11_025427_create_broadcast_messages_table' => ['broadcast_messages'],
            '2026_03_14_194833_add_retry_columns_to_payments_and_withdrawals' => [] // Just adds columns
        ];

        echo "<h2>Step 3: Identify Migrations to Register</h2>";

        $toRegister = [];
        $nextBatch = DB::table('migrations')->max('batch') + 1;

        echo "<table>";
        echo "<thead><tr><th>Migration</th><th>Creates Tables</th><th>Tables Exist?</th><th>Status</th><th>Action</th></tr></thead>";
        echo "<tbody>";

        foreach ($migrationFiles as $file) {
            $migrationName = str_replace('.php', '', basename($file));
            $isRan = in_array($migrationName, $ranMigrations);
            
            $createdTables = $migrationTableMap[$migrationName] ?? [];
            $tablesExist = [];
            
            foreach ($createdTables as $table) {
                if (in_array($table, $existingTables)) {
                    $tablesExist[] = $table;
                }
            }
            
            echo "<tr>";
            echo "<td><small>" . htmlspecialchars($migrationName) . "</small></td>";
            echo "<td><small>" . (empty($createdTables) ? '-' : implode(', ', $createdTables)) . "</small></td>";
            echo "<td><small>" . (empty($tablesExist) ? '-' : implode(', ', $tablesExist)) . "</small></td>";
            
            if ($isRan) {
                echo "<td><span class='badge badge-success'>Registered</span></td>";
                echo "<td>-</td>";
            } else {
                // Check if this migration's tables already exist
                $allTablesExist = !empty($createdTables) && count($tablesExist) === count($createdTables);
                $someTablesExist = !empty($tablesExist);
                
                if ($allTablesExist || $someTablesExist || empty($createdTables)) {
                    echo "<td><span class='badge badge-warning'>Not Registered</span></td>";
                    echo "<td><span class='badge badge-info'>Will Register</span></td>";
                    $toRegister[] = $migrationName;
                } else {
                    echo "<td><span class='badge badge-danger'>Not Run</span></td>";
                    echo "<td>Needs migration</td>";
                }
            }
            
            echo "</tr>";
        }

        echo "</tbody></table>";

        echo "<h2>Step 4: Register Missing Migrations</h2>";

        if (empty($toRegister)) {
            echo "<div class='info'>No migrations need registration. All migrations are in sync!</div>";
        } else {
            echo "<div class='warning'>Registering " . count($toRegister) . " migration(s) without running them...</div>";
            
            try {
                foreach ($toRegister as $migration) {
                    DB::table('migrations')->insert([
                        'migration' => $migration,
                        'batch' => $nextBatch
                    ]);
                    echo "<div class='success'>✓ Registered: <code>{$migration}</code></div>";
                }
                
                echo "<div class='success'><strong>✓ All migrations registered successfully!</strong></div>";
                
            } catch (Exception $e) {
                echo "<div class='error'>Error registering migrations: " . htmlspecialchars($e->getMessage()) . "</div>";
                echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
            }
        }

        echo "<h2>Final Status</h2>";
        
        $finalCount = DB::table('migrations')->count();
        $totalFiles = count($migrationFiles);
        
        echo "<table>";
        echo "<tr><th>Metric</th><th>Value</th></tr>";
        echo "<tr><td>Migration Files</td><td>{$totalFiles}</td></tr>";
        echo "<tr><td>Registered Migrations</td><td>{$finalCount}</td></tr>";
        echo "<tr><td>Database Tables</td><td>" . count($existingTables) . "</td></tr>";
        echo "</table>";
        
        if ($finalCount === $totalFiles) {
            echo "<div class='success'><strong>✓ Perfect! All {$totalFiles} migrations are now synchronized with the database!</strong></div>";
        } else {
            $diff = $totalFiles - $finalCount;
            echo "<div class='warning'><strong>⚠ {$diff} migration(s) still need attention</strong></div>";
        }

        // Show all registered migrations
        echo "<h2>All Registered Migrations</h2>";
        $allMigrations = DB::table('migrations')->orderBy('id')->get();
        echo "<table>";
        echo "<thead><tr><th>#</th><th>Migration</th><th>Batch</th></tr></thead>";
        echo "<tbody>";
        foreach ($allMigrations as $mig) {
            echo "<tr>";
            echo "<td>{$mig->id}</td>";
            echo "<td><small>{$mig->migration}</small></td>";
            echo "<td>{$mig->batch}</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";

        echo "<h2>✅ Next Steps</h2>";
        echo "<div class='success'>";
        echo "<strong>Migration state has been fixed!</strong><br><br>";
        echo "Your database and migrations are now synchronized. You can now:<br>";
        echo "1. Run future migrations normally using <code>php artisan migrate</code><br>";
        echo "2. Your application should work correctly now<br>";
        echo "</div>";
        
        echo "<div class='error' style='margin-top: 20px;'>";
        echo "<strong>🔒 CRITICAL - Delete these files NOW:</strong><br><br>";
        echo "Delete ALL migration helper files from your server:<br>";
        echo "• <code>public/run_migrations.php</code><br>";
        echo "• <code>public/fix_migrations.php</code><br>";
        echo "• <code>public/fix_all_migrations.php</code> (this file)<br><br>";
        echo "Via SSH: <code>rm public/run_migrations.php public/fix_migrations.php public/fix_all_migrations.php</code><br>";
        echo "Or delete manually via FTP/File Manager";
        echo "</div>";
        
        ?>
    </div>
</body>
</html>

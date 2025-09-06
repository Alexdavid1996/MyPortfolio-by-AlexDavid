<?php
/**
 * Minimal Installer — Controller-only
 * Views:
 *   installer/templates/permission.php
 *   installer/templates/requirements.php
 *   installer/templates/form.php
 *   installer/templates/success.php
 * Assets:
 *   installer/assets/style.css
 * Runs:
 *   php artisan migrate --force
 *   php artisan db:seed --class=Database\\Seeders\\DemoContentSeeder --force
 */

declare(strict_types=1);

// Small helpers for templates and safety
if (!function_exists('h')) {
    function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
}
if (!function_exists('ensure_dir')) {
    function ensure_dir(string $p, int $mode = 0775): void { if (!is_dir($p)) @mkdir($p, $mode, true); }
}
// Recursively chmod (dirs: $dMode, files: $fMode)
if (!function_exists('rr_chmod')) {
    function rr_chmod(string $path, int $dMode = 0775, int $fMode = 0664): void {
        if (!file_exists($path)) return;

        @chmod($path, is_dir($path) ? $dMode : $fMode);
        if (is_dir($path)) {
            $it = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST
            );
            foreach ($it as $item) {
                @chmod($item->getPathname(), $item->isDir() ? $dMode : $fMode);
            }
        }
    }
}
if (!function_exists('generate_app_key')) {
    function generate_app_key(): string { return 'base64:' . base64_encode(random_bytes(32)); }
}

if (!function_exists('envv')) {
    function envv(string $v): string {
        if ($v === '' || preg_match('/[\s#"\']|=|&/', $v)) {
            $v = str_replace(["\\", '"'], ["\\\\", '\"'], $v);
            return "\"$v\"";
        }
        return $v;
    }
}


/**
 * Recursively copy directory (fallback when symlink not allowed)
 */
function rr_copy($src, $dst) {
    if (!is_dir($src)) return false;
    if (!is_dir($dst)) @mkdir($dst, 0775, true);
    $dir = opendir($src);
    if ($dir === false) return false;
    while (false !== ($file = readdir($dir))) {
        if ($file === '.' || $file === '..') continue;
        $from = $src . DIRECTORY_SEPARATOR . $file;
        $to   = $dst . DIRECTORY_SEPARATOR . $file;
        if (is_dir($from)) {
            rr_copy($from, $to);
        } else {
            @copy($from, $to);
            @chmod($to, 0664);
        }
    }
    closedir($dir);
    return true;
}

/**
 * Setup permissions and folders
 */
function setupPermissions(): array {
    $ROOT = is_file(__DIR__ . '/artisan') ? __DIR__ : dirname(__DIR__);
    // ADD: detect the real web root (works for /public and shared-host public_html)
    $PUBLIC = (is_dir("$ROOT/public") && file_exists("$ROOT/public/index.php"))
        ? "$ROOT/public"
        : (is_dir("$ROOT/public_html") ? "$ROOT/public_html" : "$ROOT/public");

    $output = [];
    $success = true;

    try {
        // Directories to create
        $dirs = [
            "$ROOT/storage",
            "$ROOT/storage/app",
            "$ROOT/storage/app/public",
            "$ROOT/storage/framework",
            "$ROOT/storage/framework/cache",
            "$ROOT/storage/framework/cache/data",
            "$ROOT/storage/framework/sessions",
            "$ROOT/storage/framework/views",
            "$ROOT/storage/framework/testing",
            "$ROOT/storage/framework/cache/purifier",
            "$ROOT/storage/logs",
            "$ROOT/bootstrap/cache",
        ];

        foreach ($dirs as $d) {
            if (!is_dir($d)) {
                if (@mkdir($d, 0775, true)) {
                    $output[] = "✅ Created: $d";
                } else {
                    $output[] = "❌ Failed to create: $d";
                    $success = false;
                }
            } else {
                $output[] = "✅ Exists: $d";
            }
        }

        // Set permissions
        rr_chmod("$ROOT/storage", 0775, 0664);
        rr_chmod("$ROOT/bootstrap/cache", 0775, 0664);

        // CHANGED: use $PUBLIC instead of $ROOT/public for upload folders
        $publicDirs = [
            "$PUBLIC/image/avatars",
            "$PUBLIC/image/blog_posts",
            "$PUBLIC/image/portfolio",   // use this spelling everywhere
            "$PUBLIC/favicon",
            "$PUBLIC/shared",
        ];

        foreach ($publicDirs as $dir) {
            if (!is_dir($dir)) {
                if (@mkdir($dir, 0775, true)) {
                    $output[] = "✅ Created: $dir";
                } else {
                    $output[] = "❌ Failed to create: $dir";
                    $success = false;
                }
            }
            rr_chmod($dir, 0775, 0664);
        }

        $output[] = "✅ Permissions set on storage/, bootstrap/cache/, and public upload folders";

        // Clear compiled views & cache files
        $views = glob("$ROOT/storage/framework/views/*.php") ?: [];
        foreach ($views as $f) { @unlink($f); }
        $cache = glob("$ROOT/storage/framework/cache/*") ?: [];
        foreach ($cache as $f) { if (is_file($f)) @unlink($f); }
        $output[] = "✅ Cleared compiled views and cache";

        // CHANGED: link/copy storage to the real public path
        $publicStorageLink = "$PUBLIC/storage";
        $storagePublicDir  = "$ROOT/storage/app/public";

        if (is_link($publicStorageLink) || is_dir($publicStorageLink)) {
            $output[] = "✅ public/storage already present";
        } else {
            $linked = false;
            if (function_exists('symlink')) {
                $linked = @symlink($storagePublicDir, $publicStorageLink);
            }
            if ($linked) {
                $output[] = "✅ Created symlink public/storage → storage/app/public";
            } else {
                $output[] = "⚠️ Symlink not allowed, copying files instead…";
                if (rr_copy($storagePublicDir, $publicStorageLink)) {
                    $output[] = "✅ Copied storage/app/public → public/storage";
                } else {
                    $output[] = "⚠️ Nothing to copy yet (storage/app/public is empty).";
                }
            }
        }

        $output[] = "✅ PHP version: " . PHP_VERSION;

    } catch (Exception $e) {
        $output[] = "❌ Error: " . $e->getMessage();
        $success = false;
    }

    return [
        'success' => $success,
        'output' => '<p>' . implode('</p><p>', $output) . '</p>'
    ];
}


// Basic cache busting to avoid stale templates
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

// Handle AJAX requests for requirements checking, database testing, and permission setup
if (isset($_GET['check']) || isset($_GET['action'])) {
    header('Content-Type: application/json');

    if (($_GET['check'] ?? '') === 'requirements') {
        echo json_encode(['requirements' => checkSystemRequirements()]);
        exit;
    }

    if (($_GET['check'] ?? '') === 'database' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $result = testDatabaseConnection($_POST);
        echo json_encode($result);
        exit;
    }

    if (($_GET['action'] ?? '') === 'setup_permissions' && $_SERVER['REQUEST_METHOD'] === 'POST') {
        $result = setupPermissions();
        echo json_encode($result);
        exit;
    }
}

// Resolve project paths
$ROOT       = is_file(__DIR__ . '/artisan') ? __DIR__ : dirname(__DIR__);
$ENV_FILE   = $ROOT . '/.env';
$LOCK_FILE  = $ROOT . '/storage/installer.lock';
$LOG_FILE   = $ROOT . '/storage/logs/installer.log';

// Download installer log if requested
if (($_GET['action'] ?? '') === 'download_log') {
    if (is_file($LOG_FILE)) {
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="installer.log"');
        readfile($LOG_FILE);
    } else {
        header('HTTP/1.0 404 Not Found');
        echo 'Log file not found.';
    }
    exit;
}

/**
 * Detect the base URL for the current request.
 */
function detectBaseUrl(): string {
    $scheme = 'http';
    if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
        $scheme = $_SERVER['HTTP_X_FORWARDED_PROTO'];
    } elseif (
        (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
        (($_SERVER['SERVER_PORT'] ?? '') == '443')
    ) {
        $scheme = 'https';
    }

    $host = $_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] ?? 'localhost');
    $scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');

    return rtrim($scheme . '://' . $host . ($scriptDir ? '/' . ltrim($scriptDir, '/') : ''), '/');
}

// Defaults from environment or detection
$envAppUrl = detectBaseUrl();
$adminPrefix = 'admin';
if (file_exists($ENV_FILE) && ($envContent = file_get_contents($ENV_FILE)) !== false) {
    if (preg_match('/^APP_URL=([^\r\n]+)/m', $envContent, $m)) {
        $envAppUrl = trim($m[1]);
    }
    if (preg_match('/^ADMIN_PREFIX=([^\r\n]+)/m', $envContent, $m)) {
        $adminPrefix = trim($m[1]);
    }
}

/**
 * Check system requirements for Laravel installation
 */
function checkSystemRequirements(): array {
    $requirements = [];
    
    // PHP Version Check
    $phpVersion = PHP_VERSION;
    $phpVersionOk = version_compare($phpVersion, '8.2.0', '>=');
    $requirements[] = [
        'name' => 'PHP Version',
        'description' => "Current: {$phpVersion} (Required: 8.2+)",
        'status' => $phpVersionOk ? 'passed' : 'failed'
    ];
    
    // Required PHP Extensions
    $extensions = [
        'openssl' => 'OpenSSL Extension',
        'pdo' => 'PDO Extension',
        'pdo_mysql' => 'PDO MySQL Extension',
        'mysqli' => 'MySQLi Extension',
        'mbstring' => 'Mbstring Extension',
        'tokenizer' => 'Tokenizer Extension',
        'xml' => 'XML Extension',
        'ctype' => 'Ctype Extension',
        'json' => 'JSON Extension',
        'bcmath' => 'BCMath Extension',
        'curl' => 'cURL Extension',
        'fileinfo' => 'Fileinfo Extension',
        'gd' => 'GD Extension'
    ];
    
    foreach ($extensions as $ext => $name) {
        $loaded = extension_loaded($ext);
        $requirements[] = [
            'name' => $name,
            'description' => $loaded ? 'Installed' : 'Not installed',
            'status' => $loaded ? 'passed' : 'failed'
        ];
    }
    
    // Directory Permissions
$ROOT = is_file(__DIR__ . '/artisan') ? __DIR__ : dirname(__DIR__);
// detect the real web root (public or public_html)
$PUBLIC = (is_dir("$ROOT/public") && file_exists("$ROOT/public/index.php"))
    ? "$ROOT/public"
    : (is_dir("$ROOT/public_html") ? "$ROOT/public_html" : "$ROOT/public");

$directories = [
    $ROOT . '/storage'                 => 'storage/ directory',
    $ROOT . '/bootstrap/cache'         => 'bootstrap/cache/ directory',
    $PUBLIC . '/image/avatars'         => 'public/image/avatars (upload avatars)',
    $PUBLIC . '/image/blog_posts'      => 'public/image/blog_posts (upload blog images)',
    $PUBLIC . '/image/portfolio'       => 'public/image/portfolio (upload portfolio images)',
    $PUBLIC . '/favicon'               => 'public/favicon (site icon)',
    $PUBLIC . '/shared'                => 'public/shared (social links / sharing)'
];
    
    foreach ($directories as $dir => $name) {
        if (!is_dir($dir)) {
            @mkdir($dir, 0775, true);
        }
        $writable = is_writable($dir);
        $requirements[] = [
            'name' => $name,
            'description' => $writable ? 'Writable' : 'Not writable',
            'status' => $writable ? 'passed' : 'failed'
        ];
    }
    
    return $requirements;
}

/**
 * Test database connection
 */
function testDatabaseConnection(array $data): array {
    $host = $data['db_host'] ?? '127.0.0.1';
    $port = (int)($data['db_port'] ?? 3306);
    $dbname = $data['db_name'] ?? '';
    $user = $data['db_user'] ?? '';
    $pass = $data['db_pass'] ?? '';

    if ($dbname === '') {
        return ['success' => false, 'message' => 'Database name is required'];
    }
    if ($user === '') {
        return ['success' => false, 'message' => 'Database username is required'];
    }
    
    try {
        if (!class_exists('mysqli')) {
            return ['success' => false, 'message' => 'MySQLi extension is not loaded'];
        }

        mysqli_report(MYSQLI_REPORT_OFF);

        // Test server connection
        $mysqli = @new mysqli($host, $user, $pass, '', $port);
        if ($mysqli->connect_errno) {
            return ['success' => false, 'message' => 'Connection failed: ' . $mysqli->connect_error];
        }
        
        // Test database creation/access
        $dbnameEsc = $mysqli->real_escape_string($dbname);
        $result = $mysqli->query("CREATE DATABASE IF NOT EXISTS `{$dbnameEsc}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        if (!$result) {
            return ['success' => false, 'message' => 'Cannot create database: ' . $mysqli->error];
        }
        
        // Test database selection
        if (!$mysqli->select_db($dbname)) {
            return ['success' => false, 'message' => 'Cannot select database: ' . $mysqli->error];
        }
        
        $mysqli->close();
        return ['success' => true, 'message' => 'Database connection successful'];
        
    } catch (Exception $e) {
        return ['success' => false, 'message' => 'Connection error: ' . $e->getMessage()];
    }
}

// Shared state to pass into templates
$errors = [];
$success = [];
$isCompleted = false;
$alreadyInstalled = file_exists($ENV_FILE) && file_exists($LOCK_FILE);
$lastLogEntry = '';

// Handle Re-install POST first
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['reinstall'] ?? '') === '1') {
    @unlink($ENV_FILE);
    @unlink($LOCK_FILE);
    header('Location: ' . ($_SERVER['PHP_SELF'] ?? 'installer.php'));
    exit;
}

// Handle Install POST
$isInstallRequest = (
    ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST'
    && isset($_POST['install']) && $_POST['install'] === '1'
);

if ($isInstallRequest) {
    try {
        ensure_dir($ROOT . '/storage/logs');
        ensure_dir($ROOT . '/bootstrap/cache');
        rr_chmod($ROOT . '/storage', 0775, 0664);
        rr_chmod($ROOT . '/bootstrap/cache', 0775, 0664);
        // Gather application inputs
        $appName = trim($_POST['app_name'] ?? '');
        $adminPrefix = trim($_POST['admin_prefix'] ?? '');
        $appUrl = rtrim(trim($_POST['app_url'] ?? ''), '/');

        if ($appName === '') {
            $errors[] = 'Application name is required.';
        }
        if ($adminPrefix === '') {
            $errors[] = 'Admin URL prefix is required.';
        } elseif (!preg_match('/^[a-zA-Z0-9\-_]+$/', $adminPrefix)) {
            $errors[] = 'Admin URL prefix may only contain letters, numbers, hyphens, and underscores.';
        }
        if ($appUrl === '') {
            $errors[] = 'Application URL is required.';
        } elseif (!filter_var($appUrl, FILTER_VALIDATE_URL)) {
            $errors[] = 'Application URL must be a valid URL.';
        } else {
            $envAppUrl = $appUrl;
        }

        // Gather database inputs
        $dbHost = trim($_POST['db_host'] ?? '127.0.0.1');
        $dbPort = trim($_POST['db_port'] ?? '3306');
        $dbName = trim($_POST['db_name'] ?? '');
        $dbUser = trim($_POST['db_user'] ?? '');
        $dbPass = (string)($_POST['db_pass'] ?? '');

        if ($dbName === '') {
            $errors[] = 'Database name is required.';
        }
        if ($dbUser === '') {
            $errors[] = 'Database username is required.';
        }

        // Connect to MySQL server
        if (!$errors) {
            if (!class_exists('mysqli')) {
                $errors[] = 'MySQLi extension is not loaded.';
            } else {
                mysqli_report(MYSQLI_REPORT_OFF);
                $mysqli = @new mysqli($dbHost, $dbUser, $dbPass, '', (int)$dbPort);
                if ($mysqli->connect_errno) {
                    $errors[] = "Server connection failed: " . $mysqli->connect_error;
                } else {
                    $success[] = "Connected to MySQL server.";
                }
            }
        }

        // Create database if missing
        if (!$errors) {
            $dbnameEsc = $mysqli->real_escape_string($dbName);
            if (!$mysqli->query("CREATE DATABASE IF NOT EXISTS `{$dbnameEsc}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci")) {
                $errors[] = "Could not create database: " . $mysqli->error;
            } else {
                $success[] = "Database ensured: " . h($dbName);
            }
        }

        // Select database
        if (!$errors) {
            if (!$mysqli->select_db($dbName)) {
                $errors[] = "Could not select database: " . $mysqli->error;
            }
        }

       
       // Write minimal .env (do NOT use .env.example)
if (!$errors) {
    $envContent = implode("\n", [
        'APP_NAME='        . envv($appName),
        'APP_ENV=production',
        'APP_KEY='         . generate_app_key(),
        'APP_DEBUG=false',
        'APP_URL='         . envv($appUrl),
        '',
        'LOG_CHANNEL=stack',
        'LOG_LEVEL=debug',
        '',
        '# Files / uploads',
        'FILESYSTEM_DISK=public',
        'PURIFIER_CACHE_DIR=framework/cache/purifier',
        '',
        '# Database',
        'DB_CONNECTION=mysql',
        'DB_HOST='         . envv($dbHost),
        'DB_PORT='         . envv($dbPort),
        'DB_DATABASE='     . envv($dbName),
        'DB_USERNAME='     . envv($dbUser),
        'DB_PASSWORD='     . envv($dbPass),
        '',
        '# Session / cache / queue',
        'SESSION_DRIVER=file',
        'CACHE_STORE=file',
        'QUEUE_CONNECTION=sync',
        '',
        '# Mail (logs emails to storage/logs/*.log)',
        'MAIL_MAILER=log',
        'MAIL_FROM_ADDRESS="hello@example.com"',
        'MAIL_FROM_NAME="${APP_NAME}"',
        '',
        '# Frontend',
        'VITE_APP_NAME="${APP_NAME}"',
        '',
        '# Admin URL prefix (env-driven)',
        'ADMIN_PREFIX='    . envv($adminPrefix),
        '',
    ]);

    if (file_put_contents($ENV_FILE, $envContent) === false) {
        $errors[] = "Failed to write .env file.";
    } else {
        $success[] = ".env file created.";
    }
}


        // Run migrations and seeders using Laravel's internal API
        if (!$errors) {
            $autoload = $ROOT . '/vendor/autoload.php';
            if (!file_exists($autoload)) {
                $errors[] = 'Composer dependencies are missing. Please run "composer install".';
            } else {
                try {
                    require $autoload;
                    $app = require $ROOT . '/bootstrap/app.php';
                    $artisan = $app->make(Illuminate\Contracts\Console\Kernel::class);

                    // Ensure fresh configuration and set runtime database settings
                    $artisan->call('config:clear');
                    $artisan->call('cache:clear');
                    $artisan->call('route:clear');
                    $artisan->call('view:clear');

                    if (is_file($ROOT . '/bootstrap/cache/config.php')) {
                        @unlink($ROOT . '/bootstrap/cache/config.php');
                    }

                    config([
                        'database.default' => 'mysql',
                        'database.connections.mysql.host' => $dbHost,
                        'database.connections.mysql.port' => $dbPort,
                        'database.connections.mysql.database' => $dbName,
                        'database.connections.mysql.username' => $dbUser,
                        'database.connections.mysql.password' => $dbPass,
                    ]);
                } catch (Throwable $e) {
                    $errors[] = 'Application bootstrap failed: ' . $e->getMessage();
                }
            }
        }

        if (!$errors) {
            try {
                $artisan->call('migrate', ['--force' => true]);
                $success[] = 'Migrations completed.';
            } catch (Throwable $e) {
                $errors[] = 'Migration failed: ' . $e->getMessage();
            }
        }

        if (!$errors) {
            try {
                $artisan->call('db:seed', ['--class' => 'Database\\Seeders\\DemoContentSeeder', '--force' => true]);
                $success[] = 'Demo content seeded.';
            } catch (Throwable $e) {
                $errors[] = 'Seeding failed: ' . $e->getMessage();
            }
        }

        if (!$errors) {
            @file_put_contents($LOCK_FILE, "installed_at=" . date('c') . "\n");

            $isCompleted = true;
            $alreadyInstalled = true;
        }
    } catch (Throwable $e) {
        ensure_dir(dirname($LOG_FILE));
        $logEntry = '[' . date('c') . '] ' . $e->__toString() . "\n";
        @file_put_contents($LOG_FILE, $logEntry, FILE_APPEND);
        $errors[] = 'Installation error: ' . $e->getMessage() . ' See storage/logs/installer.log for details.';
        $lastLogEntry = trim($logEntry);
    }
}

// Determine which step to show
$step = $_GET['step'] ?? '';
$templateBase = __DIR__ . '/installer/templates';

if ($isCompleted && empty($errors)) {
    include $templateBase . '/success.php';
} else {
    switch ($step) {
        case 'permissions':
            include $templateBase . '/permission.php';
            break;
        case 'requirements':
            $requirements = checkSystemRequirements();
            $requirementsPassed = array_reduce($requirements, function($carry, $req) {
                return $carry && $req['status'] === 'passed';
            }, true);
            include $templateBase . '/requirements.php';
            break;
        case 'install':
            include $templateBase . '/form.php';
            break;
        default:
            // Start with permissions if not installed
            if (!$alreadyInstalled) {
                include $templateBase . '/permission.php';
            } else {
                include $templateBase . '/form.php';
            }
            break;
    }
}

/* Footer credit (for all templates to optionally echo):
   <footer class="footer center"><small>Installer by Alex David</small></footer>
 */

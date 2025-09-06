<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portfolio Installer - Requirements Check</title>
    <link rel="stylesheet" href="/installer/assets/style.css?v=5">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <main class="container">
        <div class="installer-header">
            <h1>🔍 System Requirements</h1>
            <p class="installer-subtitle">Checking if your server meets all requirements</p>
        </div>

        <!-- Progress Steps -->
        <div class="steps">
            <div class="step completed" data-step="permissions">
                <span>1. Permissions</span>
            </div>
            <div class="step active" data-step="requirements">
                <span>2. Requirements</span>
            </div>
            <div class="step" data-step="configuration">
                <span>3. Configuration</span>
            </div>
            <div class="step" data-step="complete">
                <span>4. Complete</span>
            </div>
        </div>

        <div class="requirements-section">
            <h2>📋 System Requirements Check</h2>
            <div class="requirements-list">
                <!-- Requirements will be populated by JavaScript -->
            </div>
        </div>

        <?php if (isset($requirementsPassed) && $requirementsPassed): ?>
        <div class="notice success">
            <strong>✅ All requirements passed!</strong> Your server is ready for installation.
        </div>
        
        <div class="mt-3">
            <a href="/installer.php?step=install" class="btn btn-primary">
                🚀 Continue to Installation
            </a>
        </div>
        <?php else: ?>
        <div class="notice error">
            <strong>❌ Some requirements failed.</strong> Please fix the issues above before proceeding.
        </div>
        
        <div class="mt-3">
            <button onclick="window.location.reload()" class="btn btn-secondary">
                🔄 Re-check Requirements
            </button>
        </div>
        <?php endif; ?>

        <div class="requirements-info">
            <h2>📖 About These Requirements</h2>
            
            <div class="info-section">
                <h3>PHP Version</h3>
                <p>Laravel 12 requires PHP 8.2 or higher for optimal performance and security features.</p>
            </div>

            <div class="info-section">
                <h3>PHP Extensions</h3>
                <ul>
                    <li><strong>OpenSSL:</strong> Required for secure data encryption and API communication</li>
                    <li><strong>PDO & PDO MySQL:</strong> Database connectivity and operations</li>
                    <li><strong>Mbstring:</strong> Multi-byte string handling for international characters</li>
                    <li><strong>Tokenizer:</strong> PHP code parsing for Blade templates</li>
                    <li><strong>XML:</strong> XML document processing for various features</li>
                    <li><strong>Ctype:</strong> Character type checking functions</li>
                    <li><strong>JSON:</strong> JSON data encoding and decoding</li>
                    <li><strong>BCMath:</strong> Arbitrary precision mathematics</li>
                    <li><strong>Curl:</strong> HTTP requests for external API integration</li>
                    <li><strong>Fileinfo:</strong> File type detection for uploads</li>
                    <li><strong>GD:</strong> Image processing for thumbnails and avatars</li>
                </ul>
            </div>

            <div class="info-section">
                <h3>Directory Permissions</h3>
                <p>These directories need write permissions for the application to function properly:</p>
                <ul>
                    <li><strong>storage/:</strong> Logs, cache, sessions, and uploaded files</li>
                    <li><strong>bootstrap/cache/:</strong> Compiled views and configuration cache</li>
                </ul>
            </div>

            <div class="info-section">
                <h3>Database Connection</h3>
                <p>A working MySQL/MariaDB connection is required for data storage. The installer will test connectivity before proceeding.</p>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="flex items-center justify-center gap-4 text-sm">
            <span>Made with ❤️ by Alex David</span>
            <a href="https://byalexdavid.com/" target="_blank" rel="noopener" class="hover:underline">🌐</a>
            <a href="https://www.youtube.com/@ByAlexdavid" target="_blank" rel="noopener" class="hover:underline">📺</a>
            <a href="https://www.linkedin.com/in/alex-david-du-ba01601b1/" target="_blank" rel="noopener" class="hover:underline">💼</a>
        </div>
    </footer>

    <script src="/installer/assets/script.js?v=5"></script>

    <style>
        .requirements-info {
            margin-top: 40px;
            padding-top: 32px;
            border-top: 1px solid var(--border);
        }
        
        .info-section {
            margin-bottom: 24px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid var(--border);
        }
        
        .info-section h3 {
            color: var(--primary);
            margin: 0 0 12px;
            font-size: 1.1rem;
        }
        
        .info-section p {
            color: var(--text-secondary);
            margin-bottom: 12px;
        }
        
        .info-section ul {
            margin: 12px 0;
            padding-left: 20px;
            color: var(--text-secondary);
        }
        
        .info-section li {
            margin-bottom: 8px;
        }
        
        .info-section strong {
            color: var(--text-primary);
        }
    </style>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portfolio Installer - Permissions Check</title>

    <!-- Lowercase absolute path -->
    <link rel="stylesheet" href="/installer/assets/style.css?v=5">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <main class="container">
        <div class="installer-header">
            <h1>🔧 Permission Setup</h1>
            <p class="installer-subtitle">Setting up folders, permissions, and storage links</p>
        </div>

        <!-- Progress Steps -->
        <div class="steps">
            <div class="step active" data-step="permissions">
                <span>1. Permissions</span>
            </div>
            <div class="step" data-step="requirements">
                <span>2. Requirements</span>
            </div>
            <div class="step" data-step="configuration">
                <span>3. Configuration</span>
            </div>
            <div class="step" data-step="complete">
                <span>4. Complete</span>
            </div>
        </div>

        <div class="preparation-section">
            <h2>📁 Setting Up Your Environment</h2>
            <div id="preparation-output" class="preparation-output">
                <!-- Permission setup output will be populated by JavaScript -->
            </div>
        </div>

        <div id="permission-status" class="notice" style="display: none;">
            <!-- Status will be updated by JavaScript -->
        </div>

        <div id="continue-section" style="display: none; margin-top: 24px;">
            <!-- Lowercase absolute path -->
            <a href="/installer.php?step=requirements" class="btn btn-primary">
                🔍 Continue to Requirements Check
            </a>
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

    <!-- Lowercase absolute path -->
    <script src="/installer/assets/script.js?v=5"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            runPermissionSetup();
        });

        async function runPermissionSetup() {
            const output = document.getElementById('preparation-output');
            const status = document.getElementById('permission-status');
            const continueSection = document.getElementById('continue-section');
            
            try {
                // Lowercase absolute path
                const response = await fetch('/installer.php?action=setup_permissions', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: '{}' // tiny body to satisfy some hosts
                });

                // Read raw text first so we can show HTML if it isn't JSON
                const text = await response.text();

                let result;
                try { result = JSON.parse(text); }
                catch (e) {
                    output.innerHTML = text; // show server HTML/error to help debug if needed
                    status.className = 'notice error';
                    status.innerHTML = '<strong>❌ Server returned HTML, not JSON.</strong> See details above.';
                    status.style.display = 'block';
                    return;
                }
                
                if (result.success) {
                    output.innerHTML = result.output || '';
                    status.className = 'notice success';
                    status.innerHTML = '<strong>✅ Permission Setup Complete!</strong> All folders, permissions, and storage links have been configured.';
                    status.style.display = 'block';
                    continueSection.style.display = 'block';
                } else {
                    output.innerHTML = result.output || 'Permission setup failed.';
                    status.className = 'notice error';
                    status.innerHTML = '<strong>❌ Permission Setup Failed!</strong> Please check the errors above and try again.';
                    status.style.display = 'block';
                    continueSection.innerHTML = '<button onclick="location.reload()" class="btn btn-secondary">🔄 Retry Permission Setup</button>';
                    continueSection.style.display = 'block';
                }
            } catch (error) {
                output.innerHTML = '<p style="color: #ef4444;">Error: ' + error.message + '</p>';
                status.className = 'notice error';
                status.innerHTML = '<strong>❌ Setup Failed!</strong> An error occurred during permission setup.';
                status.style.display = 'block';
            }
        }
    </script>
</body>
</html>

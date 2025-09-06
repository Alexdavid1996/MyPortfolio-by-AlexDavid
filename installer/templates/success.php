<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portfolio Installer - Success</title>
    <link rel="stylesheet" href="/installer/assets/style.css?v=5">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <main class="container">
        <div class="installer-header">
            <h1>🎉 Installation Complete!</h1>
            <p class="installer-subtitle">Your portfolio is ready to use</p>
        </div>

        <!-- Progress Steps -->
        <div class="steps">
            <div class="step completed" data-step="permissions">
                <span>1. Permissions</span>
            </div>
            <div class="step completed" data-step="requirements">
                <span>2. Requirements</span>
            </div>
            <div class="step completed" data-step="configuration">
                <span>3. Configuration</span>
            </div>
            <div class="step completed" data-step="complete">
                <span>4. Complete</span>
            </div>
        </div>

        <div class="notice success">
            <strong>Congratulations!</strong> Your personal portfolio and blog has been successfully installed and configured.
        </div>

        <?php if (!empty($success)) : ?>
        <div class="notice success">
            <ul>
                <?php foreach ($success as $msg): ?>
                    <li><?= h($msg) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <div class="notice success">
    <ul>
        <li><strong>Default Admin Credentials</strong></li>
        <li>Email: <strong>user@example.com</strong></li>
        <li>Password: <strong>password</strong></li>
    </ul>
    <small>You can update these anytime in the Admin Dashboard "Settings".</small>
</div>


        <div class="success-content">
            <h2>🚀 Next Steps</h2>
            
            <div class="next-steps">
                <div class="step-item">
                    <h3>1. Access Your Portfolio</h3>
                    <p>Visit your new portfolio website:</p>
                    <a href="<?= h($envAppUrl) ?>"
                       class="btn btn-primary" target="_blank">
                        View Portfolio
                    </a>
                </div>

                <div class="step-item">
                    <h3>2. Access Admin Panel</h3>
                    <p>Manage your content and settings:</p>
                    <a href="<?= h(rtrim($envAppUrl, '/')) ?>/<?= h($adminPrefix) ?>"
                       class="btn btn-secondary" target="_blank">
                        Admin Panel
                    </a>
                </div>

                <div class="step-item">
                    <h3>3. Customize Your Content</h3>
                    <ul>
                        <li>Update your profile information and avatar</li>
                        <li>Add your skills, experience, and languages</li>
                        <li>Create portfolio projects with images and tech stacks</li>
                        <li>Write blog posts and organize them by categories</li>
                        <li>Configure site settings and social media links</li>
                    </ul>
                </div>
            </div>

            <div class="security-recommendations">
                <h2>🔐 Security Recommendations</h2>
                <div class="notice">
                    <strong>Important:</strong> For security, we recommend:
                    <ul>
                        <li>Delete or rename this installer directory after setup</li>
                        <li>Change default admin credentials in the admin panel</li>
                        <li>Set up regular database backups</li>
                        <li>Enable HTTPS for production use</li>
                    </ul>
                </div>
            </div>

            <div class="quick-actions mt-3">
                <h2>Quick Actions</h2>
                <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                    <button onclick="window.location.reload()" class="btn btn-secondary">
                        Refresh Page
                    </button>
                    <form method="post" style="display: inline;">
                        <input type="hidden" name="reinstall" value="1">
                        <button type="submit" class="btn btn-warning">Re-install</button>
                    </form>
                </div>
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

    <style>
        .success-content {
            margin-top: 24px;
        }
        
        .next-steps {
            display: grid;
            gap: 24px;
            margin-bottom: 32px;
        }
        
        .step-item {
            padding: 20px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid var(--border);
        }
        
        .step-item h3 {
            margin: 0 0 12px;
            color: var(--primary);
            font-size: 1.2rem;
        }
        
        .step-item p {
            margin-bottom: 16px;
            color: var(--text-secondary);
        }
        
        .step-item ul {
            margin: 16px 0;
            padding-left: 20px;
            color: var(--text-secondary);
        }
        
        .step-item li {
            margin-bottom: 8px;
        }
        
        .security-recommendations {
            margin-top: 32px;
        }
    </style>
</body>
</html>
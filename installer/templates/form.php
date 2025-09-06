<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Portfolio Installer - Setup</title>
  <link rel="stylesheet" href="/installer/assets/style.css?v=5">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
  <main class="container">
    <div class="installer-header">
      <h1>My Portfolio Installer</h1>
      <p class="installer-subtitle">Set up your personal portfolio and blog in just a few steps</p>
    </div>

    <!-- Progress Steps -->
    <div class="steps">
      <div class="step completed" data-step="permissions">
        <span>1. Permissions</span>
      </div>
      <div class="step completed" data-step="requirements">
        <span>2. Requirements</span>
      </div>
      <div class="step active" data-step="configuration">
        <span>3. Configuration</span>
      </div>
      <div class="step" data-step="complete">
        <span>4. Complete</span>
      </div>
    </div>

<?php if (!empty($errors)) : ?>
  <div class="notice error">
    <strong>Please fix the following errors:</strong>
    <ul>
      <?php foreach ($errors as $e): ?>
        <li><?= h($e) ?></li>
      <?php endforeach; ?>
    </ul>

    <p style="margin-top:8px;color:#6b7280;">
      Make sure your database credentials are correct.
    </p>
  </div>
<?php endif; ?>


    <form method="post">
      <input type="hidden" name="install" value="1">

      <h2>⚙️ Application Configuration</h2>
      <div class="form-group">
        <label class="label">Application Name (required)</label>
        <input class="input" type="text" name="app_name"
               placeholder="My Portfolio"
               value="<?= h($_POST['app_name'] ?? 'My Portfolio') ?>" required>
      </div>

      <div class="form-group">
        <label class="label">Admin URL Prefix (required)</label>
        <input class="input" type="text" name="admin_prefix"
               placeholder="admin"
               value="<?= h($_POST['admin_prefix'] ?? 'admin') ?>" required>
        <div class="input-description">Custom admin URL path (e.g., 'dashboard')</div>
      </div>

      <div class="form-group">
        <label class="label">Application URL (required)</label>
        <input class="input" type="url" name="app_url"
               placeholder="https://example.com"
               value="<?= h($_POST['app_url'] ?? detectBaseUrl()) ?>" required>
        <div class="input-description">URL of this application</div>
      </div>

      <h2>🗄️ Database Configuration</h2>
      <div class="form-group">
        <label class="label">Database Host</label>
        <input class="input" type="text" name="db_host"
               value="<?= h($_POST['db_host'] ?? 'localhost') ?>">
        <div class="input-description">Usually localhost or 127.0.0.1</div>
      </div>

      <div class="form-group">
        <label class="label">Database Port</label>
        <input class="input" type="number" name="db_port"
               value="<?= h($_POST['db_port'] ?? '3306') ?>">
        <div class="input-description">Default MySQL port is 3306</div>
      </div>

      <div class="form-group">
        <label class="label">Database Name (required)</label>
        <input class="input" type="text" name="db_name"
               placeholder="Database Name"
               value="<?= h($_POST['db_name'] ?? '') ?>" required>
        <div class="input-description">Name of the database for your portfolio</div>
      </div>

      <div class="form-group">
        <label class="label">Database Username</label>
        <input class="input" type="text" name="db_user"
               placeholder="Database username"
               value="<?= h($_POST['db_user'] ?? '') ?>">
        <div class="input-description">Database user with create/modify permissions</div>
      </div>

      <div class="form-group">
        <label class="label">Database Password</label>
        <input class="input" type="password" name="db_pass"
               placeholder="Enter database password"
               value="<?= h($_POST['db_pass'] ?? '') ?>">
        <div class="input-description">Leave empty if no password is set</div>
      </div>

      <div class="mt-3">
        <button type="submit" class="btn btn-primary">
          🚀 Install My Portfolio
        </button>
      </div>
    </form>

    <?php if (!empty($lastLogEntry)) : ?>
      <div class="notice" style="margin-top:16px;">
        <p><strong>Last log entry</strong></p>
        <pre style="white-space:pre-wrap;overflow-x:auto;"><?= h($lastLogEntry) ?></pre>
        <p style="margin-top:8px;color:#6b7280;">Full details in storage/logs/installer.log.</p>
      </div>
    <?php endif; ?>
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
</body>
</html>

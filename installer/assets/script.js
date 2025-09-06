/**
 * Installer Client-Side Validation & Requirements Check
 * Installer by Alex David
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize the installer
    initializeRequirementsCheck();
    initializeFormValidation();
    initializeProgressSteps();
});

/**
 * Check system requirements before installation
 */
function initializeRequirementsCheck() {
    const requirementsSection = document.querySelector('.requirements-section');
    if (!requirementsSection) return;

    // Check requirements via AJAX
    fetch('/installer.php?check=requirements', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        updateRequirementsDisplay(data.requirements);
        
        // Enable/disable install button based on requirements
        const installButton = document.querySelector('.btn-primary[type="submit"]');
        const allPassed = data.requirements.every(req => req.status === 'passed');
        
        if (installButton) {
            installButton.disabled = !allPassed;
            if (!allPassed) {
                installButton.textContent = 'Fix Requirements First';
                installButton.classList.add('btn-disabled');
            }
        }
    })
    .catch(error => {
        console.error('Requirements check failed:', error);
    });
}

/**
 * Update requirements display
 */
function updateRequirementsDisplay(requirements) {
    const container = document.querySelector('.requirements-list');
    if (!container) return;

    container.innerHTML = '';
    
    requirements.forEach(requirement => {
        const item = document.createElement('div');
        item.className = `requirement-item ${requirement.status}`;
        
        item.innerHTML = `
            <div class="requirement-info">
                <strong>${requirement.name}</strong>
                <div class="requirement-description">${requirement.description}</div>
            </div>
            <div class="requirement-status ${requirement.status}">
                ${requirement.status === 'passed' ? 'Passed' : 'Failed'}
            </div>
        `;
        
        container.appendChild(item);
    });
}

/**
 * Initialize form validation
 */
function initializeFormValidation() {
    const form = document.querySelector('form');
    if (!form) return;

    // Real-time validation for required fields
    const requiredInputs = form.querySelectorAll('input[required]');
    requiredInputs.forEach(input => {
        input.addEventListener('blur', validateField);
        input.addEventListener('input', clearFieldError);
    });

    // Database connection test
    const dbInputs = form.querySelectorAll('input[name^="db_"]');
    dbInputs.forEach(input => {
        input.addEventListener('change', debounce(testDatabaseConnection, 1000));
    });

    // Form submission validation
    form.addEventListener('submit', handleFormSubmit);
}

/**
 * Validate individual field
 */
function validateField(event) {
    const field = event.target;
    const value = field.value.trim();
    
    // Remove existing error
    clearFieldError(event);
    
    // Check if required field is empty
    if (field.hasAttribute('required') && !value) {
        showFieldError(field, 'This field is required');
        return false;
    }

    // Special validation for admin prefix
    if (field.name === 'admin_prefix') {
        if (value && !/^[a-zA-Z0-9\-_]+$/.test(value)) {
            showFieldError(field, 'Only letters, numbers, hyphens, and underscores allowed');
            return false;
        }
    }

    // URL validation
    if (field.name === 'app_url') {
        try {
            new URL(value);
        } catch {
            showFieldError(field, 'Please enter a valid URL');
            return false;
        }
    }

    return true;
}

/**
 * Show field error
 */
function showFieldError(field, message) {
    field.classList.add('error');
    
    // Remove existing error message
    const existingError = field.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
    
    // Add new error message
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.textContent = message;
    errorDiv.style.color = 'var(--error)';
    errorDiv.style.fontSize = '0.85rem';
    errorDiv.style.marginTop = '4px';
    
    field.parentNode.appendChild(errorDiv);
}

/**
 * Clear field error
 */
function clearFieldError(event) {
    const field = event.target;
    field.classList.remove('error');
    
    const errorDiv = field.parentNode.querySelector('.field-error');
    if (errorDiv) {
        errorDiv.remove();
    }
}

/**
 * Test database connection
 */
function testDatabaseConnection() {
    const form = document.querySelector('form');
    const dbData = new FormData();
    
    // Collect database fields
    ['db_host', 'db_port', 'db_name', 'db_user', 'db_pass'].forEach(field => {
        const input = form.querySelector(`input[name="${field}"]`);
        if (input) {
            dbData.append(field, input.value);
        }
    });

    // Show testing indicator
    const dbSection = document.querySelector('h2:has(+ .form-group input[name^="db_"])');
    if (dbSection) {
        dbSection.innerHTML = 'Database <span style="color: var(--warning);">Testing Connection...</span>';
    }

    // Test connection
    fetch('/installer.php?check=database', {
        method: 'POST',
        body: dbData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (dbSection) {
            if (data.success) {
                dbSection.innerHTML = 'Database <span style="color: var(--success);">✓ Connected</span>';
            } else {
                dbSection.innerHTML = 'Database <span style="color: var(--error);">✗ Connection Failed</span>';
            }
        }
    })
    .catch(error => {
        if (dbSection) {
            dbSection.innerHTML = 'Database <span style="color: var(--error);">✗ Test Failed</span>';
        }
    });
}

/**
 * Handle form submission  
 */
function handleFormSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const requiredFields = form.querySelectorAll('input[required]');
    let isValid = true;

    // Validate all required fields
    requiredFields.forEach(field => {
        if (!validateField({ target: field })) {
            isValid = false;
        }
    });

    if (!isValid) {
        event.preventDefault();
        
        // Show error notice
        showFormError('Please fix the highlighted errors before proceeding.');
        
        // Scroll to first error
        const firstError = form.querySelector('.error');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    } else {
        // Show loading overlay
        showInstallationLoading();
        
        // Submit form after a brief delay to show loading
        setTimeout(() => {
            // Remove the event listener and submit normally
            form.removeEventListener('submit', handleFormSubmit);
            form.submit();
        }, 1000);
    }
}

/**
 * Show installation loading overlay
 */
function showInstallationLoading() {
    // Create loading overlay if it doesn't exist
    let overlay = document.querySelector('.loading-overlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.className = 'loading-overlay';
        overlay.innerHTML = `
            <div class="loading-content">
                <div class="loading-title">Installing Your Portfolio</div>
                <div class="loading-message">Please wait while we set up your application...</div>
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>
                <div class="loading-steps">
                    <div class="loading-step" data-step="config">
                        <span class="step-icon">⚙️</span>
                        <span>Creating configuration files</span>
                    </div>
                    <div class="loading-step" data-step="database">
                        <span class="step-icon">🗄️</span>
                        <span>Setting up database</span>
                    </div>
                    <div class="loading-step" data-step="permissions">
                        <span class="step-icon">🔐</span>
                        <span>Configuring permissions</span>
                    </div>
                    <div class="loading-step" data-step="finalize">
                        <span class="step-icon">✨</span>
                        <span>Finalizing installation</span>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(overlay);
    }
    
    // Show overlay
    overlay.classList.add('active');
    
    // Animate progress bar and steps
    const progressFill = overlay.querySelector('.progress-fill');
    const steps = overlay.querySelectorAll('.loading-step');
    let currentStep = 0;
    
    const animateProgress = () => {
        // Update progress bar
        const progress = ((currentStep + 1) / steps.length) * 100;
        progressFill.style.width = progress + '%';
        
        // Update step states
        steps.forEach((step, index) => {
            step.classList.remove('active', 'completed');
            if (index < currentStep) {
                step.classList.add('completed');
                step.querySelector('.step-icon').textContent = '✅';
            } else if (index === currentStep) {
                step.classList.add('active');
            }
        });
        
        currentStep++;
        if (currentStep < steps.length) {
            setTimeout(animateProgress, 1000);
        }
    };
    
    // Start animation after a brief delay
    setTimeout(animateProgress, 500);
}

/**
 * Show form-level error
 */
function showFormError(message) {
    // Remove existing form error
    const existingError = document.querySelector('.form-error');
    if (existingError) {
        existingError.remove();
    }
    
    // Create new error notice
    const errorDiv = document.createElement('div');
    errorDiv.className = 'notice error form-error';
    errorDiv.textContent = message;
    
    // Insert before form
    const form = document.querySelector('form');
    form.parentNode.insertBefore(errorDiv, form);
}

/**
 * Initialize progress steps indicator
 */
function initializeProgressSteps() {
    const stepsContainer = document.querySelector('.steps');
    if (!stepsContainer) return;

    // Determine current step based on page content
    let currentStep = 'requirements';
    
    if (document.querySelector('form')) {
        currentStep = 'configuration';
    }
    
    if (document.querySelector('.success-message')) {
        currentStep = 'complete';
    }

    // Update step indicators
    const steps = stepsContainer.querySelectorAll('.step');
    steps.forEach(step => {
        const stepName = step.dataset.step;
        
        if (stepName === currentStep) {
            step.classList.add('active');
        } else if (isStepCompleted(stepName, currentStep)) {
            step.classList.add('completed');
        }
    });
}

/**
 * Check if a step is completed
 */
function isStepCompleted(stepName, currentStep) {
    const stepOrder = ['requirements', 'configuration', 'complete'];
    const stepIndex = stepOrder.indexOf(stepName);
    const currentIndex = stepOrder.indexOf(currentStep);
    
    return stepIndex < currentIndex;
}

/**
 * Debounce utility function
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Add smooth animations to elements
 */
function addSmoothAnimations() {
    // Fade in animation for requirement items
    const requirementItems = document.querySelectorAll('.requirement-item');
    requirementItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            item.style.transition = 'all 0.3s ease';
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
        }, index * 100);
    });
}

// Initialize animations when DOM is loaded
document.addEventListener('DOMContentLoaded', addSmoothAnimations);
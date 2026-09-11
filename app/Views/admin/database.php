<?php
/**
 * Surya Vistaara Pvt. Ltd. (SVPL)
 * Database Management, Backup, Restore & Maintenance View
 */
$userRole = $user['role'] ?? 'ADMIN';
$prefix = ($userRole === 'MANAGER') ? '/manager' : '/admin';
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h4 class="m-0 font-outfit fw-bold text-navy"><i class="bi bi-database-fill-gear text-warning me-2"></i> Database Backup, Restore & System Maintenance</h4>
        <p class="text-muted small mb-0">Manage system database records, export SQL backups, restore backups, or seed demo data.</p>
    </div>
    <div>
        <a href="<?= url($prefix . '/database/backup') ?>" class="btn btn-success fw-bold me-2">
            <i class="bi bi-download me-1"></i> Download SQL Backup
        </a>
    </div>
</div>

<?php if (!empty($successMsg)): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($successMsg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (!empty($errorMsg)): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($errorMsg) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row g-4 mb-4">
    <!-- Quick Actions Card 1: Backup & Restore -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-navy text-white py-3">
                <h6 class="m-0 font-outfit fw-bold"><i class="bi bi-cloud-arrow-down-fill text-warning me-2"></i> Database Backup & Restore (.sql)</h6>
            </div>
            <div class="card-body">
                <!-- Backup Download Section -->
                <div class="p-3 bg-light rounded-3 border mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="fw-bold text-navy mb-1"><i class="bi bi-file-earmark-arrow-down-fill text-success me-1"></i> Download Full SQL Backup</h6>
                            <p class="small text-muted mb-0">Generates a complete dump file containing all table structures and records.</p>
                        </div>
                        <a href="<?= url($prefix . '/database/backup') ?>" class="btn btn-success btn-sm fw-bold text-nowrap ms-2">
                            <i class="bi bi-download me-1"></i> Download .SQL
                        </a>
                    </div>
                </div>

                <!-- Restore Backup Form Section -->
                <form action="<?= url($prefix . '/database/restore') ?>" method="POST" enctype="multipart/form-data" class="p-3 bg-light rounded-3 border">
                    <?= csrf_field() ?>
                    <h6 class="fw-bold text-navy mb-1"><i class="bi bi-file-earmark-arrow-up-fill text-primary me-1"></i> Restore Database from Backup File</h6>
                    <p class="small text-muted mb-3">Upload a previously exported <code>.sql</code> backup file to restore database tables.</p>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Select .SQL Backup File</label>
                        <input type="file" name="backup_file" accept=".sql" class="form-control form-control-sm" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm fw-bold w-100" onclick="return confirm('Are you sure you want to restore the database from this SQL file? Existing records may be overwritten.');">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Restore Database Now
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Quick Actions Card 2: Purge All Data & Seed Demo Data -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-3 h-100">
            <div class="card-header bg-navy text-white py-3">
                <h6 class="m-0 font-outfit fw-bold"><i class="bi bi-tools text-warning me-2"></i> Database Maintenance & Data Purging</h6>
            </div>
            <div class="card-body">
                <!-- Insert Demo Data Section -->
                <div class="p-3 bg-light rounded-3 border mb-4">
                    <form action="<?= url($prefix . '/database/seed-demo') ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold text-navy mb-1"><i class="bi bi-magic text-info me-1"></i> Insert Sample Demo Data</h6>
                                <p class="small text-muted mb-0">Populates realistic solar applications across all 10 stages, demo advisors, and BOE records.</p>
                            </div>
                            <button type="submit" class="btn btn-info text-dark btn-sm fw-bold text-nowrap ms-2" onclick="return confirm('Insert sample demo solar applications and BOE demo records?');">
                                <i class="bi bi-plus-circle-fill me-1"></i> Seed Demo Data
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Purge All Data Section -->
                <div class="p-3 bg-danger bg-opacity-10 border border-danger border-opacity-25 rounded-3">
                    <form action="<?= url($prefix . '/database/purge') ?>" method="POST">
                        <?= csrf_field() ?>
                        <h6 class="fw-bold text-danger mb-1"><i class="bi bi-trash3-fill me-1"></i> Remove / Purge All Table Data</h6>
                        <p class="small text-muted mb-3">Completely clears all customers, leads, advisors, documents, commissions, financial ledger vouchers, and history log records. Preserves Admin user accounts.</p>

                        <button type="submit" class="btn btn-danger btn-sm fw-bold w-100" onclick="return confirm('CAUTION: Are you sure you want to PURGE ALL DATA from the database? This action cannot be undone unless you have a backup!');">
                            <i class="bi bi-exclamation-octagon-fill me-1"></i> Purge / Empty All Database Tables
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Database Table Overview Card -->
<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white py-3">
        <h6 class="m-0 font-outfit fw-bold text-navy">
            <i class="bi bi-table me-2 text-primary"></i> Current Database Tables & Record Statistics (Engine: <?= strtoupper($driver) ?>)
        </h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                <thead class="table-light">
                    <tr>
                        <th>Table Name</th>
                        <th>Record Count</th>
                        <th>Table Size (Approx)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tables as $t): ?>
                    <tr>
                        <td><code><?= htmlspecialchars($t['name']) ?></code></td>
                        <td><strong class="text-navy"><?= number_format($t['rows']) ?></strong> records</td>
                        <td><span class="text-muted"><?= $t['bytes'] > 0 ? number_format($t['bytes'] / 1024, 2) . ' KB' : 'N/A' ?></span></td>
                        <td><span class="badge bg-success-subtle text-success border border-success-subtle">Active</span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

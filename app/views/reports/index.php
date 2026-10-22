<?php
session_start();
require_once __DIR__ . '/../../controllers/report.controller.php';
requireAuth();

$reportController = new ReportController($pdo);

// Handle downloads before any HTML is rendered.
if (($_GET['export'] ?? '') === 'csv') {
    $reportController->exportCsv();
}

$result = $reportController->getReportsPage();

$reports = $result['reports'];
$filters = $result['filters'];
$page = $result['page'];
$per_page = $result['per_page'];
$totalPages = $result['total_pages'];
$totalReports = $result['total_reports'];


$exportQuery = $filters;
$exportQuery['export'] = 'csv';

?>

<?php require __DIR__ . '/../../components/head.php'; ?>


<section class="dashboard">
<?php require __DIR__ . '/../../components/private_nav.php'; ?>
    <main class="dashboard_body centered">
    <header class="reports_header">
            <div>
                <h2>
                <?php if($role && $role === 'staff'): ?>
                    <span> My </span>
                <?php else: ?>
                    <span> All </span>
                <?php endif; ?>
                Reports
            </h2>
            </div>
    <div>


<?php if($role && $role === 'admin'): ?>            
<a
href="?<?= htmlspecialchars(http_build_query($exportQuery)) ?>"
    class="export_csv"
>
Export CSV
</a>
    <?php endif; ?>
<a href="./reports/create">
    Submit Report
</a>        
</div>
    </header>


<button type="button" class="filter-trigger" id="openFilters">
    Filters
</button>

<div class="filter-modal" id="filterModal" aria-hidden="true">
    <div class="filter-modal__overlay" id="closeFilters"></div>

    <section
        class="filter-modal__content"
        role="dialog"
        aria-modal="true"
        aria-labelledby="filterModalTitle"
    >
        <header class="filter-modal__header">
            <div>
                <h2 id="filterModalTitle">Filter reports</h2>
                <p>Find reports using the filters below.</p>
            </div>

            <button
                type="button"
                id="dismissFilters"
                aria-label="Close filters"
            >
                &times;
            </button>
        </header>


<form method="GET" class="report-filters">
    <input
        type="search"
        class="report-filters__search"
        name="search"
        placeholder="Search system ID, officer or phone..."
        value="<?= htmlspecialchars($filters['search']) ?>"
    >

    <select name="division">
        <option value="">All divisions</option>
        <?php
        $divisions = [
            'ikeja' => 'Ikeja',
            'lagos_island' => 'Lagos Island',
            'badagry' => 'Badagry',
            'epe' => 'Epe',
            'ikorodu' => 'Ikorodu',
            'hq' => 'Headquarters',
        ];

        foreach ($divisions as $value => $label):
        ?>
            <option
                value="<?= $value ?>"
                <?= $filters['division'] === $value ? 'selected' : '' ?>
            >
                <?= htmlspecialchars($label) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="device">
        <option value="">All devices</option>
        <?php foreach (['cpu', 'laptop', 'android'] as $device): ?>
            <option
                value="<?= $device ?>"
                <?= $filters['device'] === $device ? 'selected' : '' ?>
            >
                <?= ucfirst($device) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>
        From
        <input
            type="date"
            name="date_from"
            value="<?= htmlspecialchars($filters['date_from']) ?>"
        >
    </label>

    <label>
        To
        <input
            type="date"
            name="date_to"
            value="<?= htmlspecialchars($filters['date_to']) ?>"
        >
    </label>

    <button type="submit">Apply filters</button>

    <a href="<?= htmlspecialchars(strtok($_SERVER['REQUEST_URI'], '?')) ?>">
        Clear
    </a>
 </form>

    </section>
</div>


<p class="results-count">
    <?= number_format($totalReports) ?>
    <?= $totalReports === 1 ? 'report' : 'reports' ?> found
</p>


        
    <section class="reports_table">
        <?php if (empty($reports)): ?>
            <p>No reports.</p>
            <?php else: ?>


<table class="reports_table">
    <thead>
        <tr>
            <th>Title</th>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <th>Submitted By</th>
            <?php endif; ?>
            <th>Date</th>
            <th>Device</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($reports as $report): ?>
            <tr>
                <td class="truncate-single-line">
                    <?= htmlspecialchars($report['remarks']) ?>
                </td>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <td>
                        <?= htmlspecialchars($report['user_name']) ?>
                    </td>
                <?php endif; ?>
                <td>
                    <?= htmlspecialchars($report['created_at']) ?>
                </td>
                <td>
                   <p> <?= htmlspecialchars($report['device']) ?> </p>
                    <p><?= htmlspecialchars($report['system_id']) ?></p>
                </td>
                <td>
                    <a href="./reports/view?id=<?= urlencode($report['report_id']) ?>">
                        View
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<?php
$query = $filters;

$query['per_page'] = $per_page;
?>

<nav class="pagination" aria-label="Reports pagination">

<form method="GET" class="pagination-size">
    <?php foreach ($query as $key => $value): ?>
        <?php if ($key !== 'page' && $key !== 'per_page'): ?>
            <input
                type="hidden"
                name="<?= htmlspecialchars($key) ?>"
                value="<?= htmlspecialchars((string) $value) ?>"
            >
        <?php endif; ?>
    <?php endforeach; ?>

    <label for="per_page">Rows per page:</label>

    <select
        name="per_page"
        id="per_page"
        onchange="this.form.submit()"
    >

    
<?php foreach (['20', '50', '100', 'all'] as $size): ?>
    <option
        value="<?= $size ?>"
        <?= (string) $per_page === $size ? 'selected' : '' ?>
    >
        <?= $size === 'all' ? 'All' : $size ?>
    </option>
<?php endforeach; ?>


        <?php /*foreach (['20', '50', '100', 'all'] as $size): ?>
            <option
                value="<?= $size ?>"
                <?= (string) $perPage === $size ? 'selected' : '' ?>
            >
                <?= $size === 'all' ? 'All' : $size ?>
            </option>
        <?php endforeach; */?>
    </select>
</form>

<?php if ($page > 1): ?>
        <?php $query['page'] = $page - 1; ?>
        <a href="?<?= htmlspecialchars(http_build_query($query)) ?>">
            Previous
        </a>
    <?php else: ?>
        <span class="disabled">Previous</span>
    <?php endif; ?>

    <span>
        Page <?= $page ?> of <?= $totalPages ?>
    </span>

    <?php if ($page < $totalPages): ?>
        <?php $query['page'] = $page + 1; ?>
        <a href="?<?= htmlspecialchars(http_build_query($query)) ?>">
            Next
        </a>
    <?php else: ?>
        <span class="disabled">Next</span>
    <?php endif; ?>
</nav>

 <?php endif; ?>
</section>  

</main>
</section>

<?php require __DIR__ . '/../../components/bottom.php'; ?>
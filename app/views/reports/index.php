<?php
session_start();
require_once __DIR__ . '/../../controllers/report.controller.php';
requireAuth();

$reportController = new ReportController($pdo);

//$reports = $reportController->all();
//$user_reports = $reportController->userReports();

$result = $reportController->getReportsPage();

$reports = $result['reports'];
$filters = $result['filters'];
$page = $result['page'];
$totalPages = $result['total_pages'];
$totalReports = $result['total_reports'];
?>

<?php require __DIR__ . '/../../components/head.php'; ?>


<section class="dashboard">

    <!-- GENERATE: A simple staff reports page with a navigation header, page title, submit-report action, and reports table -->

    <nav class="nav dashboard_nav">
        <p>Date Harvest Reports</p>
        <ul>
            <li>
                <a href="./dashboard">Dashboard</a>
            </li>
            <li>
                <a href="./auth/logout.php">Logout</a>
            </li>
        </ul>
    </nav>

    <main class="dashboard_body centered">
        
    <header class="reports_header">
            <div>
                <h2>All Reports</h2>
            </div>
            <a href="./reports/create.php">
                Submit Report
            </a>
    </header>

    
<form method="GET" class="report-filters">
    <input
        type="search"
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

<p class="results-count">
    <?= number_format($totalReports) ?>
    <?= $totalReports === 1 ? 'report' : 'reports' ?> found
</p>

        
        <section class="reports_table">
        <?php if (empty($reports)): ?>

            <p>You haven't submitted any reports yet.</p>
            
            <a href="./create.php">
                Submit your first report
            </a>
            
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
                <td>
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
?>

<nav class="pagination" aria-label="Reports pagination">
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
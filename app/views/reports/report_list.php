<?php

session_start();

require_once __DIR__ . '/../../middleware/auth.php';
requireAuth();

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../models/report.model.php';
require_once __DIR__ . '/../../controllers/report.controller.php';

$reportController = new ReportController($pdo);


if ($_SESSION['role'] === 'admin') {
    $reports = $reportController->all();
} else {
    $reports = $reportController->index();
}

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
                <p>Reports</p>
                <h2>All Reports</h2>
            </div>
            <a href="./reports/create.php">
                Submit Report
            </a>
        </header>


        
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
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($reports as $report): ?>
            <tr>
                <td>
                    <?= htmlspecialchars($report['title']) ?>
                </td>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <td>
                        <?= htmlspecialchars($report['user_name']) ?>
                    </td>
                <?php endif; ?>
                <td>
                    <?= htmlspecialchars($report['report_date']) ?>
                </td>
                <td>
                    <?= htmlspecialchars($report['status']) ?>
                </td>
                <td>
                    <a href="./view.php?id=<?= urlencode($report['report_id']) ?>">
                        View
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
 <?php endif; ?>
</section>  







</main>
</section>

<?php require __DIR__ . '/../../components/bottom.php'; ?>
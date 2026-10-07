<?php
session_start();
require_once __DIR__ . '/../../controllers/report.controller.php';

$reportController = new ReportController($pdo);
$reportId = $_GET['id'] ?? null;

if (!$reportId) {
    header('Location: /dh_reports/dashboard/reports');
    exit;
}

$report = $reportController->show($reportId);

if (!$report) {
    http_response_code(404);
    die('Report not found.');
}

?>

<?php require __DIR__ . '/../../components/head.php'; ?>

<section class="dashboard">

    <!-- GENERATE: Simple report detail page showing the report title, date, status, description, and back button -->

    <nav class="nav dashboard_nav">
        <p>Date Harvest Reports</p>
    </nav>

    <main class="dashboard_body">
        <a href="/dh_reports/dashboard/reports">
             Back to reports
        </a>
        <article class="report_view">
            <header>
                <p><?= htmlspecialchars($report['system_id']) ?></p>

                <h2>
                    <?= htmlspecialchars($report['enrolment_officer_name']) ?>
                </h2>

                <p>
                    Report date:
                    <?= htmlspecialchars($report['updated_at']) ?>
                </p>

            </header>


            <div class="report_description">

                <?= nl2br(htmlspecialchars($report['remarks'])) ?>

            </div>


            <footer>

                <small>
                    Submitted:
                    <?= htmlspecialchars($report['created_at']) ?>
                </small>

            </footer>

        </article>

    </main>

</section>

<?php require __DIR__ . '/../../components/bottom.php'; ?>
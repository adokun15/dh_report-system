<?php
/*
session_start();

require_once __DIR__ . '/../../middleware/auth.php';
requireAuth();

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../models/report.model.php';
require_once __DIR__ . '/../../controllers/report.controller.php';

$reportController = new ReportController($pdo);

$reportId = $_GET['id'] ?? null;

if (!$reportId) {
    header('Location: ./index.php');
    exit;
}

$report = $reportController->show($reportId);

if (!$report) {
    http_response_code(404);
    die('Report not found.');
}
*/
?>

<?php require __DIR__ . '/../../components/head.php'; ?>

<section class="dashboard">

    <!-- GENERATE: Simple report detail page showing the report title, date, status, description, and back button -->

    <nav class="nav dashboard_nav">

        <p>Date Harvest Reports</p>

        <ul>
            <li>
                <a href="../dashboard">Dashboard</a>
            </li>

            <li>
                <a href="./index.php">My Reports</a>
            </li>

            <li>
                <a href="../auth/logout.php">Logout</a>
            </li>
        </ul>

    </nav>


    <main class="dashboard_body">

        <a href="./index.php">
            ← Back to reports
        </a>

        <article class="report_view">

            <header>

                <p>
                    <?= htmlspecialchars($report['status']) ?>
                </p>

                <h2>
                    <?= htmlspecialchars($report['title']) ?>
                </h2>

                <p>
                    Report date:
                    <?= htmlspecialchars($report['report_date']) ?>
                </p>

            </header>


            <div class="report_description">

                <?= nl2br(htmlspecialchars($report['description'])) ?>

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
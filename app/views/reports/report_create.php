<?php
//session_start();
//require_once __DIR__ . '/../../middleware/auth.php';
//requireAuth();
?>

<?php
/*
session_start();

require_once __DIR__ . '/../../middleware/auth.php';
requireAuth();

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../models/report.model.php';
require_once __DIR__ . '/../../controllers/report.controller.php';

$reportController = new ReportController($pdo);

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $success = $reportController->create($_POST);

    if ($success) {
        header('Location: ./index.php');
        exit;
    }

    $error = 'Failed to submit report.';
}
*/
?>

<?php require __DIR__ . '/../../components/head.php'; ?>
<section class="dashboard">
    <!-- GENERATE: Report creation page with a simple form for staff to submit a new report -->

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
        <header class="reports_header">
            <div>
                <p>Reports</p>
                <h2>Submit Report</h2>
            </div>
        </header>


        <form method="POST" class="report_form">
<?php if ($error): ?>
    <p class="error_text">
        <?= htmlspecialchars($error) ?>
    </p>
<?php endif; ?>
            <div>
                <label for="report_date">Report Date</label>

                <input
                    id="report_date"
                    type="date"
                    name="report_date"
                    required
                >
            </div>


            <div>
                <label for="title">Report Title</label>

                <input
                    id="title"
                    type="text"
                    name="title"
                    placeholder="Enter report title"
                    required
                >
            </div>


            <div>
                <label for="description">Report</label>

                <textarea
                    id="description"
                    name="description"
                    placeholder="Write your report..."
                    required
                ></textarea>
            </div>


            <div>

                <button type="submit">
                    Submit Report
                </button>
                <a href="./index.php">
                    Cancel
                </a>
            </div>
        </form>
    </main>

</section>

<?php require __DIR__ . '/../../components/bottom.php'; ?>
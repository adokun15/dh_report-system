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

    <?php require __DIR__ . '/../../components/private_nav.php'; ?>

    <main class="dashboard_body centered">
        <div class='reports_header'>
            <article>
                <h3>Single Report</h3>
               <p>View More information about a report</p>     
           </article>
           <p> 
               <a href="/dh_reports/dashboard/reports">
                   Back to reports
                </a>
</p> 
        </div>
        <article class="report_view">
            <header>
                <div>
                 <h4> System Details</h4>
                 <p><?= htmlspecialchars($report['system_id']) ?></p>
                 <p><?= htmlspecialchars($report['device']) ?></p>
                </div>

                <div>
                    <h4> Device Given to:</h4>
                    <p>Name: <?= htmlspecialchars($report['enrolment_officer_name']) ?></p>
                    <p>Phone: <?= htmlspecialchars($report['enrolment_officer_phone']) ?></p>
                </div>
                
                <div>
                    <h4> Division</h4>
                    <p><?= htmlspecialchars($report['division_place']) ?></p>
                </div>
                
            </header>


            <div class="report_record_view">
              <div class="report_record_before">
                <h4>Before Syncing</h4>
                <p>Duplicates Record: <?= nl2br((htmlspecialchars($report['before_duplicates_record'])))?></p>
                <p>Success Record: <?= nl2br((htmlspecialchars($report['before_success_record'])))?></p>
              </div>
              <div class="report_record_after">
                <h4>After Syncing</h4>
                <p>Duplicates Record: <?= nl2br((htmlspecialchars($report['after_duplicates_record'])))?></p>
                <p>Success Record: <?= nl2br((htmlspecialchars($report['after_success_record'])))?></p>
              </div>
              <div class="report_record_total">
                <h4>Total Records</h4>
                <p>Registration: <?= nl2br((htmlspecialchars($report['reg_record'])))?></p>
                <p>Sending: <?= nl2br((htmlspecialchars($report['sending_record'])))?></p>
                <p>Error: <?= nl2br((htmlspecialchars($report['error_record'])))?></p>
              </div>
              <div class="report_record_total">
                <h4>Record base on Category</h4>
                <p>Adult: <?= nl2br((htmlspecialchars($report['adult_record'])))?></p>
                <p>Recapture: <?= nl2br((htmlspecialchars($report['recap_record'])))?></p>
                <p>Child: <?= nl2br((htmlspecialchars($report['child_record'])))?></p>
                <p>Online: <?= nl2br((htmlspecialchars($report['online_record'])))?></p>
                <p>Child to Adult: <?= nl2br((htmlspecialchars($report['child_to_adult_record'])))?></p>
              </div>
            </div>

            <div class="report_description">
                <h4>Extra Comment</h4>
                <p><?= nl2br(htmlspecialchars($report['remarks'])) ?></p>
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
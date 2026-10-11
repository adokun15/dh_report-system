<?php
session_start();
require_once __DIR__ . '/../../controllers/report.controller.php';

$reportController = new ReportController($pdo);

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = $reportController->create($_POST);
    if ($success) {
      header('Location: /dh_reports/dashboard/reports');
        exit;
    }


    $error = 'Failed to submit report.';
}
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
                <h2>Data harvesting Report collection</h2>
            </div>
        </header>
        <form method="POST" class="report_form centered">
            <?php if ($error): ?>
            <p class="error_text">
                <?= htmlspecialchars($error) ?>
            </p>
            <?php endif; ?>
            <article>
                <div>
                    <label for="system_id">System ID</label>
                    <input id="system_id" type="text" placeholder="SYS_TESTING_DEMO" name="system_id" required>
                </div>
                <div class="division_device">

                    <div>
                        <label for="device">Device</label>
                        <select name="device">
                            <option value="cpu">Monitor/CPU</option>
                            <option value="laptop" selected>Laptop</option>
                            <option value="android">Android</option>
                        </select>
                    </div>
                    <div>
                        <label for="division">Division</label>
                        <select name="division">
                            <option value="hq" selected>Head Quarters</option>
                            <option value="ikeja">Ikeja</option>
                            <option value="lagos_island">Lagos Island</option>
                            <option value="badagry">Badagry</option>
                            <option value="epe">Epe</option>
                            <option value="ikorudu">Ikorudu</option>
                        </select>
                    </div>
                </div>
            </article>


            <article class="report_records">
                <h3>Records Information</h3>
                <div class='report_records_once'>
                    <div>
                        <label for="registration_count">Registration Count</label>
                        <input id="registration_count" type="number" placeholder='0' name="registration_count" required>
                    </div>
                    <div>
                        <label for="sending">Sending Count</label>
                        <input id="sending_count" type="number" placeholder='0' name="sending_count" required />
                    </div>
                    <div>
                        <label for="error_count">Error Count Record</label>
                        <input id="error_count" type="number" placeholder='0' name="error_count" required />
                    </div>
                </div>

                <div class="report_records_before">
                    <h4>Before Syncing</h4>
                    <article>
                        <div>
                            <label for="before_success_count">Success Count</label>
                            <input id="before_success_count" type="number" placeholder='0' name="before_success_count"
                                required />
                        </div>
                        <div>
                            <label for="before_duplicates_count">Duplicates Count Record</label>
                            <input id="before_duplicates_count" type="number" placeholder='0'
                                name="before_duplicates_count" required />
                        </div>
                    </article>
                </div>

                <div class="report_records_after">
                    <h4>After Syncing</h4>
                    <article>
                        <div>
                            <label for="after_success_count">Success Count</label>
                            <input id="after_success_count" type="number" placeholder='0' name="after_success_count"
                                required />
                        </div>
                        <div>
                            <label for="after_duplicates_count">Duplicates Count Record</label>
                            <input id="after_duplicates_count" type="number" placeholder='0'
                                name="after_duplicates_count" required />
                        </div>
                    </article>
                </div>

                <div class="report_records_category">
                    <h4>Category</h4>
                    <article>
                        <div>
                            <label for="adult">Adult</label>
                            <input id="adult" type="number" placeholder='0' name="adult" required />
                        </div>
                        <div>
                            <label for="child">child</label>
                            <input id="child" type="number" placeholder='0' name="child" required />
                        </div>
                        <div>
                            <label for="online">online</label>
                            <input id="online" type="number" placeholder='0' name="online" required />
                        </div>
                        <div>
                            <label for="recap">recapture</label>
                            <input id="recap" type="number" placeholder='0' name="recap" required />
                        </div>
                        <div>
                            <label for="child_to_adult">Child To Adult</label>
                            <input id="child_to_adult" type="number" placeholder='0' name="child_to_adult" required />
                        </div>
                    </article>
                </div>

            </article>

            <article>
                <h3> Enrollment Officer </h3>
                <div>
                    <label for="enrollment_name">Name</label>
                    <input id="enrollment_name" name="enrollment_name" placeholder="James Doe" required />
                </div>

                <div>
                    <label for="enrollment_phone">Phone</label>
                    <input id="enrollment_phone" name="enrollment_phone" placeholder="08012345678" required />
                </div>
            </article>
            
            <article>
                <label for="description">Remarks</label>
                <textarea id="description" name="description" placeholder="Write your report..." required></textarea>
            </article>


            <div>
                <button type="submit">
                    Submit Report
                </button>
                <a href="./">
                    Cancel
                </a>
            </div>
        </form>
    </main>

</section>

<?php require __DIR__ . '/../../components/bottom.php'; ?>
<?php
session_start();
require_once __DIR__ . '/../../controllers/report.controller.php';

$reportController = new ReportController($pdo);

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = $reportController->create($_POST);
    if ($success) {
    //    header('Location: ./index.php');
      //  exit;
    }


    $error = 'Failed to submit report.';
    var_dump($success);
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
        <form method="POST" class="report_form">
<?php if ($error): ?>
    <p class="error_text">
        <?= htmlspecialchars($error) ?>
    </p>
<?php endif; ?>
         <article>
             <h3>Device Information</h3> 
             <div>
                <label for="re">System ID</label>
                <input
                 id="system_id"
                 type="text"
                 placeholder="SYS_TESTING_DEMO"
                 name="system_id"
                 required>
                </div>
                <div>
                    <label for="device">Device</label>
                   <select name="device">
  <option value="cpu">Monitor/CPU</option>
  <option value="laptop" selected>Laptop</option>
  <option value="android">Android</option>
</select>
                </div>
            </article>

            <article>
                <h3>Division</h3> 
                <div>
                    <label for="division">Select the division</label>
                    <select name="division">
  <option value="hq" selected>Head Quarters</option>
  <option value="ikeja">Ikeja</option>
  <option value="lagos_island">Lagos Island</option>
  <option value="badagry">Badagry</option>
  <option value="epe">Epe</option>
  <option value="ikorudu">Ikorudu</option>
</select>
                </div>
            </article>
            
            <article>
                <h3>Records Information</h3> 
                <div>
                    <label for="registration_count">Registration Count Record</label>
                    <input
                    id="registration_count"
                    type="number"
                    placeholder='Enter a value > 0'
                    name="registration_count"
                    required
                    >
                </div>
                <div>
                    <label for="sending">Sending Count Record</label>
                    <input
                    id="sending_count"
                    type="number"
                    placeholder='Enter a value > 0'
                    name="sending_count"
                    required
                    />
                </div>

                <div>
                    <label for="duplicates">Duplicates Count Record</label>
                    <input
                    id="duplicates_count"
                    type="number"
                    placeholder='Enter a value > 0'
                    name="duplicates_count"
                    required
                    />
                </div>

                <div>
                    <label for="error_count">Error Count Record</label>
                    <input
                    id="error_count"
                    type="number"
                    placeholder='Enter a value > 0'
                    name="error_count"
                    required/>
                </div>
                <div>
                    <label for="success_count">Error Count Record</label>
                    <input
                    id="success_count"
                    type="number"
                    placeholder='Enter a value > 0'
                    name="success_count"
                    required/>
                </div>
            </article>
            <article>
                <h3> Enrollment Officer </h3>
                <div>
                <label for="enrollment_name">Name</label>
                <input
                    id="enrollment_name"
                    name="enrollment_name"
                    placeholder="James Doe"
                    required
                />
                </div>

                <div>
                <label for="">Phone</label>
                <input
                    id="enrollment_phone"
                    name="enrollment_phone"
                    placeholder="08012345678"
                    required
                />
            </div>
            </article>
            <div>
                <label for="description">Remarks</label>
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
                <a href="./">
                    Cancel
                </a>
            </div>
        </form>
    </main>

</section>

<?php require __DIR__ . '/../../components/bottom.php'; ?>
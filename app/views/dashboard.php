<?php 
  //if (!isset($_SESSION['user_id'])) {
    //     header('Location: ./login');
    //   exit;
    //}
    
    //Fetch User
    echo(isset($_SESSION['user_id']));
?>



<?php require __DIR__ . '/../components/head.php'; ?>

<section class='section dashboard'>


<!-- Navigate -->
<nav class='dashboard_nav nav'>
    <p>Date Harvest Reports</p>         
 <ul>
            <li>
                <a href="./dashboard">Dashboard</a>
            </li>

            <?php if ($role === 'staff'): ?>

                <li>
                    <a href="./reports/index.php">My Reports</a>
                </li>

            <?php elseif ($role === 'admin'): ?>

                <li>
                    <a href="./reports/index.php">All Reports</a>
                </li>

            <?php endif; ?>

            <li>
                <a href="./auth/logout.php">Logout</a>
            </li>
        </ul>


</nav>

    <!-- Main content -->
<main class='dashboard_body centered'>
        <!-- Quick action -->
        <section class="dashboard_intro">
            <div>
                <h2>Staff Dashboard</h2>
                <p>Submit and manage your reports.</p>
            </div>
            <a href="./reports/create.php">
                Submit Report
            </a>
        </section>


        <!-- Recent reports -->

        <section class="reports">
            <div class="section_header">
                <h2>My Reports</h2>

                <a href="./reports/index.php">
                    View all
                </a>
            </div>

            <div class="reports_empty">

                <p>You haven't submitted any reports yet.</p>

                <a href="./reports/create.php">
                    Submit your first report
                </a>

            </div>

        </section>

    </main>

</section>

<?php require __DIR__ . '/../components/bottom.php'; ?>

<?php 
  //Start Session
  session_start();
  require __DIR__ . '/../controllers/dashboard.controller.php';
?>


 
<?php require __DIR__ . '/../components/head.php'; ?>
<section class='section dashboard'>

<!-- Navigate -->
<nav class='nav'>
    <p class='nav_title'>Date Harvest Reports</p>         
 <ul>
            <li class="nav_link">
                <a href="./dashboard">Dashboard</a>
            </li>
            <?php if ($role === 'staff'): ?>
                <li class="nav_link">
                    <a href="dashboard/reports">My Reports</a>
                </li>
                <?php elseif ($role === 'admin'): ?>
                    <li class="nav_link">
                        <a href="dashboard/reports">All Reports</a>
                    </li>
                    <li class="nav_link">
                        <a href="dashboard/reports">All Staffs</a>
                    </li>
                    <?php endif; ?>
                    <li class="nav_link">
                <a class='logout' href="/logout">Logout</a>
            </li>
        </ul>
</nav>

    <!-- Main content -->
<main class='dashboard centered_layout'>
        <!-- Quick action -->
        <section class="dashboard_intro">
            <div>
                <h2>Welcome <?= htmlspecialchars($get_user['name'])?></h2>
                <p>View and analyse all submitted reports.</p>
            </div>

            <a href="dashboard/reports/create">
                View Reports
            </a>
        </section>
        
        <!-- Report Insight -->
        <section class="report_insight">
        <div class="report_insight_header">
            <h2>Report Summary</h2>  
            <p>All time</p>
        </div>
        <div class="report_insight_list">
            <article class="report_insight_card card">
                <h3>Total Registration</h3>
                <p><?= htmlspecialchars($get_insight['registrations']) ?></p>
            </article>   
            <article class="report_insight_card card">
                <h3>Successful</h3>
             <p><?= $get_insight['successful'] ?></p>
            </article>   
            <article class="report_insight_card card">
                <h3>Duplicate</h3>
                  <p><?= $get_insight['duplicates'] ?></p>
            </article>
            <article class="report_insight_card card">
                <h3>Error</h3>
             <p><?= $get_insight['errors'] ?></p>
            </article>   
        </div>  
    </section>

        <!-- Top Recent Report: Card styling -->
        <?php if($role === 'staff'): ?>
            <section class="recent_reports">
                <div class="recent_reports_header">
                <h2>Your Recent Reports</h2>
                <a href="dashboard/reports">
                    View all
                </a>
            </div>
            
            <?php if(!isset($get_recent_reports)): ?>
                <div class="recent_reports_empty card">
                    <p>You haven't submitted any reports yet.</p>
                <a href="dashboard/reports/create.php">
                    Submit your first report
                </a>
            </div>
            <?php else: ?>
                <div class="recent_reports_list card">
                    <?php foreach ($get_recent_reports as $r): ?>
                        <p><?= $r['receiver_phone'] ?></p> 
                        <?php endforeach ?>    
                    </div>
           <?php endif; ?>
        </section>
        <?php endif ?>
        
        <!--Division Grouping -->
        <?php if($role && $role === 'admin'): ?>
         <h2> Division Summary </h2>  
        <?php endif ?>


    </main>

</section>

<?php require __DIR__ . '/../components/bottom.php'; ?>

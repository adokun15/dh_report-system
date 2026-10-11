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


<form method="GET" class="dashboard-filter">
    <div>
        <label for="period">Reporting period</label>
        <select name="period" id="period" onchange="toggleCustomDates(this.value)">
            <option value="all_time" <?= $period === 'all_time' ? 'selected' : '' ?>>All time</option>
            <option value="today" <?= $period === 'today' ? 'selected' : '' ?>>Today</option>
            <option value="week" <?= $period === 'week' ? 'selected' : '' ?>>This week</option>
            <option value="month" <?= $period === 'month' ? 'selected' : '' ?>>This month</option>
            <option value="year" <?= $period === 'year' ? 'selected' : '' ?>>This year</option>
            <option value="custom" <?= $period === 'custom' ? 'selected' : '' ?>>Custom range</option>
        </select>
    </div>

    <div id="custom-dates" class="custom-dates"
         style="<?= $period === 'custom' ? '' : 'display:none' ?>">
        <div>
            <label for="start_date">From</label>
            <input type="date" id="start_date" name="start_date"
                   value="<?= htmlspecialchars($startDate ?? '') ?>">
        </div>

        <div>
            <label for="end_date">To</label>
            <input type="date" id="end_date" name="end_date"
                   value="<?= htmlspecialchars($endDate ?? '') ?>">
        </div>
    </div>

    <button type="submit" class="filter-button">Apply filter</button>
</form>

<section class="summary-grid">
    <article class="summary-card">
        <div class="card-icon icon-blue">▤</div>
        <p>Total Reports</p>
        <h2><?= number_format((int) $summary['total_reports']) ?></h2>
        <span>Reports submitted</span>
    </article>

    <article class="summary-card">
        <div class="card-icon icon-green">✓</div>
        <p>Successful Records</p>
        <h2><?= number_format((int) $summary['total_success']) ?></h2>
        <span>After-sync total</span>
    </article>

    <article class="summary-card">
        <div class="card-icon icon-orange">⧉</div>
        <p>Duplicate Records</p>
        <h2><?= number_format((int) $summary['total_duplicates']) ?></h2>
        <span>After-sync total</span>
    </article>

    <article class="summary-card">
        <div class="card-icon icon-red">!</div>
        <p>Error Records</p>
        <h2><?= number_format((int) $summary['total_errors']) ?></h2>
        <span>Reported errors</span>
    </article>

    <article class="summary-card">
        <div class="card-icon icon-purple">♙</div>
        <p>Active Reporting Staff</p>
        <h2><?= number_format((int) $summary['active_staff']) ?></h2>
        <span>Staff who submitted reports</span>
    </article>
</section>

</section>
<?php require __DIR__ . '/../components/bottom.php'; ?>

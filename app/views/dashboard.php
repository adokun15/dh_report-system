<?php 
  //Start Session
  session_start();
  require __DIR__ . '/../controllers/dashboard.controller.php';
  $report = new Report($pdo);
  
$period = $_GET['period'] ?? 'all_time';
$today = new DateTimeImmutable('today');

switch ($period) {
    case 'today':
        $startDate = $today->format('Y-m-d');
        $endDate = $startDate;
        break;

    case 'week':
        $startDate = $today->modify('monday this week')->format('Y-m-d');
        $endDate = $today->format('Y-m-d');
        break;

    case 'month':
        $startDate = $today->modify('first day of this month')->format('Y-m-d');
        $endDate = $today->format('Y-m-d');
        break;

    case 'year':
        $startDate = $today->format('Y-01-01');
        $endDate = $today->format('Y-m-d');
        break;

    case 'custom':
        $startDate = $_GET['start_date'] ?? '';
        $endDate = $_GET['end_date'] ?? '';

        if (
            !preg_match('/^\d{4}-\d{2}-\d{2}$/', $startDate) ||
            !preg_match('/^\d{4}-\d{2}-\d{2}$/', $endDate) ||
            $startDate > $endDate ||
            $endDate > $today->format('Y-m-d')
        ) {
            $period = 'all_time';
            $startDate = null;
            $endDate = null;
        }
        break;

    default:
        $period = 'all_time';
        $startDate = null;
        $endDate = null;
}

$summary = $report->getDashboardSummary(
    $startDate,
    $endDate
);

$divisionPerformance = $report->getDivisionPerformance(
    $startDate,
    $endDate,
);

$deviceUsage = $report->getDeviceUsage(
    $startDate,
    $endDate,
    
);
 

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


<form method="GET" class="dashboard-filter card">
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


<section class="analytics-grid">

    <!-- Division performance -->
    <article class="analytics-card division-card">
        <div class="analytics-heading">
            <div>
                <h3>Division Performance</h3>
                <p>Success rate by division</p>
            </div>
            <span class="analytics-icon">↗</span>
        </div>

        <div class="division-list">
            <?php if (empty($divisionPerformance)): ?>
                <p class="empty-state">No division data for this period.</p>
            <?php endif; ?>

            <?php foreach ($divisionPerformance as $division): ?>
                <?php
                    $rate = $division['success_rate'] !== null
                        ? (float) $division['success_rate']
                        : null;

                    $barWidth = $rate !== null
                        ? max(0, min(100, $rate))
                        : 0;
                ?>
                

                <div class="division-item">
                    <div class="division-meta">
                        <span class="division-name">
                            <?= htmlspecialchars(
                                ucwords(str_replace(
                                    '_',
                                    ' ',
                                    $division['division_place']
                                ))
                            ) ?>
                        </span>

                        <strong>
                            <?= $rate !== null
                                ? number_format($rate, 1) . '%'
                                : 'N/A' ?>
                        </strong>
                    </div>

                    <div class="progress-track">
                        <div
                            class="progress-fill"
                            style="width: <?= $barWidth ?>%"
                        ></div>
                    </div>

                    <span class="division-reports">
                        <?= number_format((int) $division['total_reports']) ?>
                        reports
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    </article>


    <!-- Device usage -->
    <article class="analytics-card device-card">
        <div class="analytics-heading">
            <div>
                <h3>Most Used Device</h3>
                <p>Devices reported by staff</p>
            </div>
            <span class="analytics-icon">▣</span>
        </div>

        <?php if (empty($deviceUsage)): ?>
            <p class="empty-state">No device data for this period.</p>
        <?php else: ?>
            <?php
                $topDevice = $deviceUsage[0];
            ?>

            <div class="top-device">
                <span class="top-device-label">MOST REPORTED</span>

                <h2>
                    <?= htmlspecialchars(
                        ucfirst($topDevice['device'])
                    ) ?>
                </h2>

                <p>
                    <?= number_format(
                        (int) $topDevice['total_reports']
                    ) ?>
                    reports ·
                    <?= number_format(
                        (float) $topDevice['usage_percentage'],
                        1
                    ) ?>%
                </p>
            </div>

            <div class="device-list">
                <?php foreach ($deviceUsage as $device): ?>
                    <div class="device-item">
                        <div class="device-meta">
                            <span>
                                <?= htmlspecialchars(
                                    ucfirst($device['device'])
                                ) ?>
                            </span>

                            <strong>
                                <?= number_format(
                                    (int) $device['total_reports']
                                ) ?>
                            </strong>
                        </div>

                        <div class="progress-track device-track">
                            <div
                                class="progress-fill device-fill"
                                style="width: <?= max(
                                    0,
                                    min(
                                        100,
                                        (float) $device['usage_percentage']
                                    )
                                ) ?>%"
                            ></div>
                        </div>

                        <span class="device-percentage">
                            <?= number_format(
                                (float) $device['usage_percentage'],
                                1
                            ) ?>% of reports
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </article>

</section>


</section>
<?php require __DIR__ . '/../components/bottom.php'; ?>

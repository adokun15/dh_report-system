<nav class="nav dashboard_nav">
    <p>Date Harvest Reports</p>
    <ul>
        <li>
            <a href="/dh_reports/dashboard">Dashboard</a>
        </li>
        
        <?php if(isset($role) && $role === 'staff'): ?> 
            <li>
                <a href='/dh_reports/dashboard/reports'>My Reports</a>
            </li>
        <?php endif; ?> 
        
        <?php if(isset($role) && $role === 'admin'): ?> 
            <li>
                <a href='/dh_reports/dashboard/reports'>All Reports</a>
            </li>
            <li>
                <a href='/dh_reports/dashboard/users'>Staffs</a>
            </li>
        <?php endif; ?> 

        <li>
            <a href="/dh_reports/logout">Logout</a>
        </li>
    </ul>
</nav>
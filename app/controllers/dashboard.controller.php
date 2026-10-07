<?php 
  //Database connection
  require_once __DIR__ . '/../config/db.php';
  //IS user authenticated!
  require_once __DIR__ . '/../config/middleware.php';
  //Pull user details
  require __DIR__ . '/../models/user.model.php';
  require __DIR__ . '/../models/report.model.php';
 
  requireAuth();
  
  $user = new User($pdo);
  $get_user =$user->findUserByUserId($user_id);
 
  //Summary of report
  $reports = new Report($pdo);
  if($role === 'staff'){
      $get_insight = $reports->getReportsStatForStaff($user_id);
      $get_recent_reports = $reports->getRecentReports($user_id);
   }else{
      $get_insight = $reports->getReportsStatForAdmin();
      $get_reports_by_division = $reports->getReportsByDivision($user_id);
}

?>



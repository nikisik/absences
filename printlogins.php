<?php 
require_once __DIR__ . '/src/helpers.php';
adminpage();
$teachers = $conn->query("SELECT `name`,`login` FROM `teachers` ORDER BY `name`")->fetch_all();
echo '<b>';
foreach ($teachers as $teacher) {
    echo "schl.orenzip.ru/ : " . ($teacher[0] == 'admin' ? '' : $teacher[0]." : ".$teacher[1]."<br>");
}
?> 

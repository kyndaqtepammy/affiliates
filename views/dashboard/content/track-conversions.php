<?php

    $data = array();
    global $wpdb;
    $tablename = $wpdb->prefix.'sdds_affiliates';
    global $current_user;
get_currentuserinfo();

$email = (string) $current_user->user_email;
    $email = $current_user->user_email; 
    $data = $wpdb->get_results("SELECT dated, COUNT(*) AS clicks FROM $tablename GROUP BY dated", ARRAY_N);
    //print_r($data) ; //die;
?>

<table style="width:100%">
  <tr>
    <th>Date</th>
    <th>Number of referals</th>
  </tr>
  <?php
  
  foreach ($data as $d ) {
       echo '<tr>';
       echo '<td>'. date('D M j', strtotime($d[0])).'</td>';
       echo '<td>'. $d[1].'</td>';
       echo '</tr>';
    } ?>
 
</table>
<?php

    $data = array();
    global $wpdb;
    $tablename = $wpdb->prefix.'sdds_affiliates';
    global $current_user;
    wp_get_current_user();

    $email = (string) $current_user->user_email; 
    $data = $wpdb->get_results("SELECT dated, COUNT(dated) AS clicks FROM $tablename WHERE email='$email' GROUP BY CAST(dated AS DATE)", ARRAY_N);
    //var_dump($data) ; //die;
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
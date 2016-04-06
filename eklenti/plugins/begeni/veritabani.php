<?php
global $table_version;
$table_version = "1.0";

function func_table_creat(){
    creat_table();
}
$table_name = $wpdb->prefix."begeniler";
function creat_table(){
    global $wpdb;
    global $table_version;
	global $table_name;
	
    $table_name = $wpdb->prefix."begeniler";
	
	$sql = "CREATE TABLE $table_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,  
  kul_id mediumint(9) NOT NULL,
  yazi_id mediumint(9) NOT NULL,
  durum mediumint(9) NOT NULL, 
  tarih TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY id (id)
);";

    require_once(ABSPATH.'wp-admin/includes/upgrade.php');
        dbDelta($sql);   

}

/*function veritabani_giris()
{
	/*if (isset($_POST['button-name']))
        if ($_POST['button-name'] == "submitted") {*/
            //global $wpdb;
			//$id_yazi = get_the_ID();
			//$id_kul = get_current_user_id();

			//$wpdb->insert( 
				/*wp_begeniler, 
				array( 
					'durum' => 5,
					'yazi_id' => $id_yazi,
					'kul_id' => $id_kul
				), 
				array( 
					'%d', 
					'%d',
					'%d'
				) 
			);
       /* }
    }*/
    


?>
<?php
/*
Plugin Name: Beğeni Eklentisi
Plugin URI: http://localhost/
Description:  Yazılar için beğenme eklentisi
Version: 1.0.0
Author: Faruk ÜNAL
Author URI: -

*/

require "veritabani.php";
add_action('plugins_loaded', 'func_table_creat');
//add_action('plugins_loaded', 'veritabani_giris');

//Include Javascript library
wp_enqueue_script('inkthemes', plugins_url( '/js/demo.js' , __FILE__ ) , array( 'jquery' ));
// including ajax script in the plugin Myajax.ajaxurl
wp_localize_script( 'inkthemes', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php')));

function calis2($content){
	
	$id = get_the_ID();
	$id_kul = get_current_user_id();	
	$tarih = date( 'd/m/Y');
	
	global $wpdb;
	$table_name = $wpdb->prefix."begeniler";
	$sorgula = $wpdb->get_row("SELECT durum FROM ".$table_name." WHERE kul_id=".$id_kul." and yazi_id=".$id."");
	if(is_single()){
		
		
		
		/*if ( $id_kul==0 ) {
			$son = "Beğeni yapmak için giriş yapınız.";
		}*/
		if ( !is_user_logged_in() ) {
			$son = "<br>Beğeni yapmak için giriş yapınız.";
			
		}else{			
		
			if($sorgula->durum == 0)
			{
				$son = '<hr>
						<div id="feedback">
						<form>
							
						<input type="hidden" id="yazi" name="yazi" value="'.$id.'">					
						<input type="hidden" id="durum" name="durum" value="'.$sorgula->durum.'">
						<input type="hidden" id="kullanici" name="kullanici" value="'.$id_kul.'">
												
						<input type="button"  id="submit2" name="'.$id.'" value="BEĞEN" />
						</form>
						</div>
						<br/><br/>
						';
			}else{
				$son = '<hr>
						<div id="feedback">
						<form>
							
						<input type="hidden" id="yazi" name="yazi" value="'.$id.'">					
						<input type="hidden" id="durum" name="durum" value="'.$sorgula->durum.'">
						<input type="hidden" id="kullanici" name="kullanici" value="'.$id_kul.'">
												
						<input type="button"  id="submit2" name="'.$id.'" value="BEĞENİYİ GERİ AL" />
						</form>
						</div>
						<br/><br/>
						';
			}
			
		}	
			
		return $content .= $son;	
	
	}else{
		return $content;
	}
	
	
}

add_action( 'the_content', 'calis2');
//apply_filters( 'the_content', 'calis2' );

function begeni_kontrol(){
	$id_user = $_POST['kullanici'];
	$id_post = $_POST['yazi'];
	$id_durum = $_POST['durum'];
	
	global $wpdb;
	$table_name = $wpdb->prefix."begeniler";
	$sorgula = $wpdb->get_row("SELECT durum FROM ".$table_name." WHERE kul_id=".$id_user." and yazi_id=".$id_post."");
	if($sorgula == null){
		
		$wpdb->insert( 
			$table_name, 
			array( 
				'kul_id' => $id_user,
				'yazi_id' => $id_post,
				'durum' => 1
			), 
			array( 
				'%d',
				'%d',
				'%d'
			) 
		);
		
	}else{
		$wpdb->delete( 
			$table_name, 
			array( 
				'kul_id' => $id_user,
				'yazi_id' => $id_post,				
			), 
			array( 
				'%d',
				'%d'
			) 
		);
		
	}
		
	
		
	//die();
	return true;
}

add_action('wp_ajax_begeni_kontrol', 'begeni_kontrol');
add_action('wp_ajax_nopriv_begeni_kontrol', 'begeni_kontrol');

//Eklenti Kodları Bitiş
//Widget Kodları Başlangıç
$anadizin = "";
function begeni_goster ($args) {
  extract($args);
  $secenekler = get_option('begeni_widget');
  $baslik = empty($secenekler['baslik']) ? "SON 10 BEĞENİNİZ" : $secenekler['baslik'];
  $url = empty($secenekler['url']) ? "begeni" : $secenekler['url'];
  
  
  global $wpdb;
  $id_kullanici = get_current_user_id();
  
echo $before_widget;
echo $before_title . $baslik . $after_title;
echo '<ul>';
  
	$sorgula = $wpdb->get_results("SELECT * FROM wp_begeniler WHERE kul_id=".$id_kullanici." order by tarih desc LIMIT 10");
	if($sorgula == null)
	{
		//$mesaj = empty($secenekler['mesaj']) ? "Henüz beğeniniz yok." : $secenekler['mesaj'];
		$mesaj = "Beğeniniz yok.";
		echo '<li>'.$mesaj.'</li>';
	}else{
		//$result = $wpdb->get_results($sorgula) or die(mysql_error());

		foreach( $sorgula as $deger ) {
		$url_yazi= esc_url( get_permalink($deger->yazi_id));
		$baslik_yazi= get_the_title($deger->yazi_id);
		
		
		$mesaj = "<a href='".$url_yazi."'>".$baslik_yazi."</a>";
		echo '<li>'.$mesaj.'</li>';
        //echo $deger->name;
		}
		global $anadizin;
		$anadizin = get_site_url();
		echo "<br><a href=".$anadizin."/".$url."/>Tüm beğeniler için tıklayın.</a>";
	}
  


   
echo '</ul>';
echo $after_widget;
}

function begeni_goster_init() {
	if (!function_exists('register_sidebar_widget')) {
		return;
	}

	register_sidebar_widget('Beğeni Listesi', 'begeni_goster');
	register_widget_control('Beğeni Listesi', 'begeni_goster_control', 400, 300);
}
add_action('plugins_loaded', 'begeni_goster_init');

function begeni_goster_control () {
 $secenekler = $yenisecenekler = get_option('begeni_widget');
 if ( $_POST["widget_buton_submit"] ) {
     $yenisecenekler['baslik'] = strip_tags(stripslashes($_POST["widget_begeni_baslik"]));
     $yenisecenekler['url'] = strip_tags(stripslashes($_POST["widget_begeni_url"]));
  }
if ( $secenekler != $yenisecenekler ){
     $secenekler = $yenisecenekler;
     update_option('begeni_widget', $secenekler);
  }
$baslik = htmlspecialchars($secenekler['baslik'], ENT_QUOTES);
$url = htmlspecialchars($secenekler['url'], ENT_QUOTES);
$anadizin = get_site_url();
?>
<div> 
        <label for="widget_begeni_baslik" style="line-height:35px;display:block;">Başlık: <input type="text" id="widget_begeni_baslik" name="widget_begeni_baslik" value="<?php echo $baslik; ?>" /></label> 
        <label for="widget_begeni_url-text" style="line-height:25px;display:block;">Şablon URL: <?php echo $anadizin."/"; ?><input type="text" id="widget_begeni_url" name="widget_begeni_url" value="<?php echo $url; ?>" />/</label>
        <input type="hidden" name="widget_buton_submit" id="widget_buton_submit " value="1" />
</div>
<?php
}
?>



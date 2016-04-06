<?php
/*
Template Name: Begeni
*/
?>
<!--<script>
jQuery(document).ready(function(){

	jQuery("#sil").click(function(){
		var nu_kul = jQuery("#kullanici_id").val();
		var nu_yazi = jQuery("#yazi_id").val();
		//var nu_durum = jQuery("#durum").val();
		jQuery.ajax({
		type: 'POST',
		url: MyAjax.ajaxurl,
		data: {"action": "post_word_count", "kullanici":nu_kul, "yazi":nu_yazi},
			success: function(data){
				//if(nu_durum==0){
					//jQuery("#durum").val("1");
					//jQuery("#submit").val("BEĞENİYİ GERİ AL");
					
				//}else{
					//jQuery("#durum").val("0");
					jQuery("#submit").val("tamm");
				//}

			}
		});
	});


});
</script>-->

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
<script type="text/javascript">

function gonder(){
    //Verileri gönderme işlemi
    $.ajax({
		var id = jQuery("#yazi_id").val();
		
        type:'POST',
        url:'begeni.php',
        data:$('#'.id.'').serialize(),
        success: function (msg) {
            //Dönen sonucu ekranda gösterme
            alert(msg);
        }
    });
}

</script>
<?php get_header(); ?>
 
<?php

if(isset($_POST["yazi_id"])){
	global $wpdb;
	$id_kullanici = get_current_user_id();
	$table_name = $wpdb->prefix."begeniler";
    //$isim=$_POST["ad"];
    $yazi=$_POST["yazi_id"];
	
	
	$islem = $wpdb->delete( 
			$table_name, 
			array( 
				'kul_id' => $id_kullanici,
				'yazi_id' => $yazi,				
			), 
			array( 
				'%d',
				'%d'
			) 
		);
		
	if($islem){
		//echo "Başarılı!";
	}else{
		echo "Veritabanı hatası.";
	}
    
    //echo "Veriler başarıyla geldi.";
}
?>
 
<?php while (have_posts()) : the_post(); ?>
<div id="begeni-icerik" align="center" width="%80" bg="white">
<h2 id="begeni-baslik"><?php the_title(); ?></h2>
 
<?php the_content(); ?>

<?php 

global $wpdb;
$id_kullanici = get_current_user_id();


	$sorgula = $wpdb->get_results("SELECT * FROM wp_begeniler WHERE kul_id=".$id_kullanici." order by tarih desc");
	if($sorgula == null)
	{
		echo "Henüz beğeniniz yok.";
	}else{
		//$result = $wpdb->get_results($sorgula) or die(mysql_error());

		foreach( $sorgula as $deger ) {
		
		$url= esc_url( get_permalink($deger->yazi_id));
		$baslik= get_the_title($deger->yazi_id);
		$id_yazi = $deger->yazi_id;
		$tarih_begen= $deger->tarih;
		$mesaj = "<form id='".$id_yazi."' method='POST' action=''>
					<a href='".$url."'>".$baslik."</a>  ".$tarih_begen."
					
					<input type='hidden' id='kullanici_id' name='kullanici_id' value='".$id_kullanici."'>
					<input type='hidden' id='yazi_id' name='yazi_id' value='".$id_yazi."'>
					<input type='submit' id='sil' name='sil' onclick='gonder()' value='Geri Al'>
					</form><br>";
		echo '<li>'.$mesaj.'</li>';
        //echo $deger->name;
    }
	}
?>
</div>
<?php endwhile; ?>
 
<style>
#begeni-icerik {
    background-color: white;
	padding:7px;
}
	
#begeni-baslik{
	display: block;
    font-size: 2em;
	margin-bottom: 0.67em;
	font-weight: bold;
}


</style>
 
<?php get_footer(); ?>
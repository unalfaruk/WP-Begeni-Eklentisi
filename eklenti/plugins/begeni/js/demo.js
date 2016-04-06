jQuery(document).ready(function(){

jQuery("#submit2").click(function(){
    var nu_kul = jQuery("#kullanici").val();
	var nu_yazi = jQuery("#yazi").val();
	var nu_durum = jQuery("#durum").val();
jQuery.ajax({
type: 'POST',
url: MyAjax.ajaxurl,
data: {"action": "begeni_kontrol", "kullanici":nu_kul, "yazi":nu_yazi, "durum":nu_durum},
success: function(data){
	if(nu_durum==0){
		jQuery("#durum").val("1");
		jQuery("#submit2").val("BEĞENİYİ GERİ AL");
		
	}else{
		jQuery("#durum").val("0");
		jQuery("#submit2").val("BEĞEN");
	}

}
});
});


});
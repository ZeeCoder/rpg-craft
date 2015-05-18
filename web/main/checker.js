var ready;
$.cookie("ready", null);
$.cookie("ready", 0, { path: '/' });

function openWindow( url ) {
  window.open(url, '_blank');
  window.focus();
}

function cleanup() {
	/*
	Egyenlőre felesleges ilyesmivel foglalkozni
	+ túl gyorsan hívódik meg sokszor.
	$.ajax({
		url: "validate_cleanup.php",
		data: {
			"game_type": $("#game_type").val()
		}
	});
	*/
}

function validator_uploaded(){
	if (typeof $("#install_game_name") != 'undefined' && $("#install_game_name").length!=0 && $("#install_game_name").val()!='')
		var game_type = $("#install_game_name").val();
	else
		var game_type = $("#game_type").val();
	
	ready = $.cookie("ready");
	if (ready == "1") {
		$.cookie("ready", 0, { path: '/' });
		$("#xml_to_check").val("");
		$("#lang_ini_to_check").val("");
		$("#conf_ini").val("");
		$("#ini_download_link").css("display", "none");
		
		$.ajax({
			url: "../charsheet_xml/sql_from_xml.php",
			data: {
				"game_type": game_type,
				"generate_ini": $("#generate_ini").attr("checked"),
				"install_game_name": $("#install_game_name").val(),
				"install_game_full_name": $("#install_game_full_name").val(),
				"forced_install": (($("#forced_install").attr("checked")==true)?$("#forced_install").val():null),
				"install_pass": (($("#install_mode").attr("checked")==true)?$("#install_mode").val():null)
			},
			success: function(data){
				var valdata = data;
				$("#pass_validated").html(valdata);
				$("#install_game_name").val("");
				$("#install_game_full_name").val("");
				$("#forced_install").removeAttr("checked");
				$("#install_mode").removeAttr("checked");
				if ($("#install_mode").attr("checked")!=true && valdata.search("No errors.")!=-1 && valdata.search("No warnings.")!=-1) {
					$.ajax({
						url: "../charsheet_xml/css_from_xml.php",
						data: {
							"game_type": game_type
						},
						success: function(data){
							$.ajax({
								url: "../charsheet_xml/index.php",
								data: {
									"game_type": game_type,
									"viewer_mode": ""
								},
								success: function(data){
									if ($("#generate_ini").attr("checked"))
										$("#ini_download_link").unbind("click").click(function(){
											openWindow("../charsheet_xml/"+game_type+"/lang_"+game_type+"_"+$("#language").html()+".ini");
										}).css("display", "inline-block");
									$("#generate_ini").removeAttr("checked");
									window.open("../charsheet_xml/?game_type="+game_type+"&viewer_mode");
								},
								error: function(){
									cleanup();
									alert(l["err_loading_charsheet"]);
								},
								complete: function(){
									cleanup();
									$("#validation_upload_form").hv_ajax_loader("toggle_loading", "off");
								}
							});
						},
						error: function(){
							cleanup();
							alert(l["err_gen_css"]);
							$("#validation_upload_form").hv_ajax_loader("toggle_loading", "off");
						}
					});
				} else
					$("#validation_upload_form").hv_ajax_loader("toggle_loading", "off");
				
			},
			error: function(){
				cleanup();
				alert(l["err_load_val"]);
				$("#validation_upload_form").hv_ajax_loader("toggle_loading", "off");
			}
		});
	} else
		setTimeout("validator_uploaded();", 100);
}

$(function(){
	$("#validation_upload_form")
		.hv_ajax_loader({"cover_on": true});
	$("#upload_xml").submit(function(e){
		if ($("#xml_to_check").val()=="") {
			alert(l["err_no_file"]);
			e.preventDefault();
		} else {
			$("#validation_upload_form").hv_ajax_loader("toggle_loading", "on");
			validator_uploaded();
		}
	});
});
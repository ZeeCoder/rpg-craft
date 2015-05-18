/*
	Űrlap tisztítása id alapján.
*/
function clear_form(id) {
	var frm_elements = $("#"+id)[0].elements;
	var field_type;
	if (typeof frm_elements != 'undefined')
		for(i = 0; i < frm_elements.length; i++) {
			field_type = frm_elements[i].type.toLowerCase();
			switch (field_type) {
				case "text":
				case "password":
				case "textarea":
				case "hidden":
					frm_elements[i].value = "";
					break;
				case "radio":
				case "checkbox":
					if (frm_elements[i].checked) frm_elements[i].checked = false;
					break;
				case "select-one":
				case "select-multi":
					frm_elements[i].selectedIndex = -1;
					break;
				default:
					break;
			}
		}
}

/*
	Általános törlés az adatbázisból az id, specID és dbname alapján.
*/
function ajax_delete(id) {
	var aggreed = confirm("Biztosan törlöd?");
	if (aggreed) {
		$("#ajax_loader").attr("src", "../img/ajax_loader_uploading.gif");
		$.ajax({
			type: "POST",
			url: "../charsheet_xml/ajax_delete.php",
			data: {
				"specID": $("#"+id+" input[name=specID]").val(),
				"dbname": $("#"+id+" input[name=dbname]").val()
			},
			success: function(data){
				if (data != 0) {
					alert("hiba: "+data);
				} else {
					$("#"+id).fadeTo(100, 0).animate({"height": 0}, 100, function(){
						$("#"+id+"_br").remove();
					});
					$("#ajax_loader").css("display", "none").attr("src", "../img/ajax_loader_waiting.gif");
				}
			}
		});
	}
}

/*
	Általános insert.
*/

function ajax_insert(id, new_id) {
	$("#ajax_loader").attr("src", "../img/ajax_loader_uploading.gif");
	var cloned;
	$.ajax({
		type: "POST",
		url: "../charsheet_xml/ajax_insert.php",
		data: $("#"+id).serialize(),
		success: function(data){
			if (data <= 2) alert("hiba: "+data);
			else {
				var new_add_id = new_id+"_"+data;
				cloned = $("#"+id).parent().children("form.to_copy").clone(true);
				cloned.css({"visibility": "hidden", "display": "inline-block", "height": "auto"});
				cloned.attr("id", new_add_id).insertAfter("#"+id);//.before("<br class='"+new_add_id+"_br' clear='all' />")
				cloned.children("input[name=specID]").val(data);
				add_update_checker("#"+new_add_id);
				
				var insert_name; var insert_value;
				$("#"+id+" div.char_container input").each(function() {
					if ($(this).attr("type") == "checkbox") {
						insert_name = $(this).attr("name"); insert_value = $(this).attr("checked");
						$("#"+new_id+"_"+data+" div.char_container input[name="+insert_name+"]").attr("checked", insert_value);
					} else {
						insert_name = $(this).attr("name"); insert_value = $(this).val();
						$("#"+new_id+"_"+data+" div.char_container input[name="+insert_name+"]").val(insert_value);
					}
				});
				var to_height = cloned.height();
				cloned.css("height", "0px").removeClass("to_copy");
				cloned.animate({"height": to_height}, 200, function(){
					cloned.css({"visibility": "visible", "display": "none"});
					$(this).fadeIn(200);
				});
				$("#"+id)[0].reset();
			}
			
			$("#ajax_loader").css("display", "none").attr("src", "../img/ajax_loader_waiting.gif");
		}
	});
}

function toggle_visibility(id) {
	$("#"+id).val( (($("#"+id).val() == 0)?1:0) );
}

$(function(e) {
	/*BUG talált, csekkelni hogy működnek e még a dolgok így is.*/
	$("#character_sheet form").submit(function(e){
		console.log( 'preventing' );
		e.preventDefault();
	});
	
	$("div.record_delete").live("click", function(){
		var id = $(this).parent().attr("id");
		ajax_delete(id);
	});
	$("div.record_to_drag").draggable();
	
	$("#character_sheet")
		.hv_ajax_loader({
			"cover": true,
			"align": "right",
			"valign": "top"
		});
});


var global_update_counter = 0;

var glob_timeouts = new Array();
var checking_what_to_update;

function upload_glob_timeout(){
	$("#character_sheet").hv_ajax_loader("toggle_loading", "on");
	var continue_updater=false;
	for (key in glob_timeouts) {
		if (glob_timeouts[key]>0) {
			glob_timeouts[key]--;
			if (glob_timeouts[key]==0) {
				$.ajax({
					type: "POST",
					url: "../charsheet_xml/ajax_upload.php",
					data: $("#"+key).serialize(),
					success: function(data){
						if (data != 0) alert("hiba: "+data);
					}
				});
			} else continue_updater=true;
		}
	}
	if (continue_updater) setTimeout(function(){upload_glob_timeout();}, 500);
	else $("#character_sheet").hv_ajax_loader("toggle_loading", "off");
}

function add_update_checker(selector) {
	$(selector+" input").bind("keyup", function(){
		var zee_form = $(this).closest("form");
		var id = zee_form.attr("id");
		if (zee_form.valid()) {
			glob_timeouts[id] = 10;
			upload_glob_timeout();
		} else glob_timeouts[id] = 0;
	});
}
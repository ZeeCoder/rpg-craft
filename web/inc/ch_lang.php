<?php
	
	$lang_links = $hvt->get_lang_change_links();
	
	foreach ( $lang_links as $lang_label => $link ) {
		echo "<a href='$link'><img ".(($sess_lang!=$lang_label)?"onclick=\"\"":"")." src='".PAGE_ROOT."img/flag_".$lang_label.".png' alt=\"".@$l_config["lang_chooser_".$lang_label]."\" /></a>";
	}
	
?>

<h2><?php echo $l_config["menu_developers"]; ?></h2>

<div id="developers_container">
	<dl>
		<dt><a href="<?php echo PAGE_ROOT; ?>char_doc_v0.7.zip"><?php echo $l_config["devs_doc"]; ?></a></dt>
			<dd><?php echo $l_config["devs_doc_desc"]; ?></dd>
		<dt><a href="<?php echo PAGE_ROOT; ?>XML_docs.zip"><?php echo $l_config["devs_doc_old"]; ?></a></dt>
			<dd><?php echo $l_config["devs_doc_desc_old"]; ?></dd>
		
		<?php if ( isset( $sess_user_id ) ) { ?>
			<dt><a href="<?php echo PAGE_ROOT; ?>main/validate_tool.php"><?php echo $l_config["devs_val_tool"]; ?></a></dt>
				<dd><?php echo $l_config["devs_val_tool_desc"]; ?></dd>
		<?php } ?>
	</dl>
</div>
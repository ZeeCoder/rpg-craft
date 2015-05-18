<?php $charsheet_name = "example_10"; include_once("../xml_char_setup.php"); ?>

<charsheet>

	<description>
		<charsheet_full_name>Example Character Sheet 1.0 version</charsheet_full_name>
		<charsheet_author>Hubert Viktor</charsheet_author>
		<charsheet_desc><?php echo $config["charsheet_desc"]; ?></charsheet_desc>
	</description>
	
	<base limit="1">
		<record left_title="<?php echo $config["base_title"]; ?>">
			<field name="name"><?php echo $config["name"]; ?></field>
			<field name="xp"><?php echo $config["xp"]; ?></field>
			<field name="hit_points"><?php echo $config["hit_points"]; ?></field>
			<field name="defense"><?php echo $config["defense"]; ?></field>
			<field name="damage"><?php echo $config["damage"]; ?></field>
		</record>
	</base>
	<break></break>
	
	<inventory limit="0" top_title="<?php echo $config["inventory_title"]; ?>">
		<record>
			<field name="name" css="width: 300px !important;"><?php echo $config["name"]; ?></field>
			<field name="quantity"><?php echo $config["quantity"]; ?></field>
		</record>
	</inventory>
	
</charsheet>
<?php

add_action('admin_init', 'portfolio_register_settings');
add_action('admin_menu', 'portfolio_add_option_page');

register_activation_hook( WP_PLUGIN_DIR . '/simple-portfolio/simple-portfolio.php', 'enable_option_show_credits');

function portfolio_register_settings() {
	register_setting('portfolio-options', 'info-fields', '');
	register_setting('portfolio-options', 'slug', '');
	register_setting('portfolio-options', 'use-xml', '');
	register_setting('portfolio-options', 'use-slug-rewrite', '');
	register_setting('portfolio-options', 'show-credits', '');
}

function enable_option_show_credits() {
	update_option('show-credits', '1');
}

function portfolio_add_option_page() {
	add_options_page('Portfolio Settings', 'Portfolio', 'administrator', 'portfolio-settings', 'portfolio_options');
}

/**
 * Get the preformatted common options as array
 *
 * @see Settings Panel
 * @return array
 */
function get_option_preformatted() {
	$options = array();
	
	$fields = explode(",", get_option('info-fields'));
	foreach ($fields as $field):
		if (trim($field) != ''):
			$options[strtolower(str_replace(' ', '_', $field))] = $field;
		endif;
	endforeach;

	return $options;
}

/**
 * Create a validation block
 * 
 * @param $file
 * @param $id
 */
function create_validation_block($file, $id) {
	
?>
	<div style="display:none; background:#ffb995;border-top:1px dashed #999; padding: 10px;" id="<?php echo $id; ?>">
		<?php _e('Theme file missing', 'simple-portfolio'); ?> <code><?php echo get_stylesheet() . '/' . $file; ?></code> <input type="button" onclick="help_toggle(this);" value="Help ?">
		<div id="help" style="margin-top: 10px; display: none;">
			<?php _e('Do not forget to create this file.', 'simple-portfolio'); ?>
			<em><?php _e('Some references you might need for creating this file:', 'simple-portfolio'); ?></em>
			<ul style="padding:10px">
				<li>&gt; <em><?php _e('Plugin wordpress directory:', 'simple-portfolio'); ?> <a href="http://wordpress.org/extend/plugins/simple-portfolio">http://wordpress.org/extend/plugins/simple-portfolio/</a></em></li>
				<li>&gt; <em><?php _e('Plugin website:', 'simple-portfolio'); ?> <a href="http://projects.inlet.nl/simple-portfolio-wordpress3">http://projects.inlet.nl/simple-portfolio-wordpress3/</a></em></li>
			</ul>
		</div>
		<div id="file_exists" style="display:none;"><?php echo (file_exists(get_stylesheet_directory() . '/' . $file)) ? 'true' : 'false'; ?></div>
	</div>
<?php
}

/**
 * The settings HTML
 */
function portfolio_options() {
?>

<div class="wrap">
	
	<div id="icon-options-general" class="icon32"><br /></div>
	<h2><?php _e('Portfolio Options', 'simple-portfolio'); ?></h2>

	<form action="options.php" method="post">

		<!-- NOTIFICATION DIALOG FOR PERMALINKS -->
		<?php settings_fields('portfolio-options'); ?>
		<?php if (get_option('permalink_structure') == ''): ?>
			<div id='editable-post-name' style='padding:10px;margin:10px;'>
				<p><?php _e("Permalinks are disabled. In order to make this plugin work correctly you'll need to change the permalink link structure", 'simple-portfolio'); ?></p>
				<p><a class='button' href='options-permalink.php'><?php _e("Change Permalinks", 'simple-portfolio'); ?></a></p>
			</div>
		<?php else: ?>

		<!-- SLUG SETTINGS -->
		<?php $slug = (trim(get_option('slug')) != '' ? get_option('slug') : 'portfolio'); ?>
		<h3><?php _e('Slug', 'simple-portfolio'); ?></h3>
		<p><?php
			_e('The slug is one of the most important setting!', 'simple-portfolio');
			echo '<br />';
			_e('This enables you to change the permalink structure for the portfolio and the related projects.', 'simple-portfolio');
		?></p>

		<!-- permalink box -->
		<div id="slugblock" style="padding:10px;background-color:#FFFBCC;">
			<strong><?php _e('Permalink:', 'simple-portfolio'); ?> </strong><code><?php echo get_site_url() . '/'?></code>
			<input type="text" name="slug" class="regular-text code" value="<?php echo $slug; ?>">
			<code>/name-of-project</code>
		</div>

		<!-- validation files in theme -->
		<div id="validation-slug">
			<?php create_validation_block('portfolio.php', 'portfolio_validation'); ?>
			<?php create_validation_block('single-portfolio.php', 'portfolio_single_validation'); ?>
		</div>

		<div style="margin-top:15px"><?php _e('Rewrite slug automatically?', 'simple-portfolio'); ?></div>
		
		<table class="form-table">
			<tr>
				<th><label><input name="use-slug-rewrite" type="radio" value="0" class="tog" <?php checked('0', get_option('use-slug-rewrite')); ?> /> <?php _e('Disable', 'simple-portfolio'); ?></label></th>
				<td><?php _e('List your portfolio projects in <code>a page template</code>.', 'simple-portfolio'); ?> </em><a target="_blank" href="http://codex.wordpress.org/Pages#Creating_Your_Own_Page_Templates" title="<?php _e('How to create a Page Template', 'simple-portfolio'); ?>">codex.wordpress.org/Pages</a></em></td>
			</tr>
			<tr>
				<th><label><input name="use-slug-rewrite" type="radio" value="1" class="tog" <?php checked('', get_option('use-slug-rewrite')); checked('1', get_option('use-slug-rewrite')); ?> /> <?php _e('Enable', 'simple-portfolio'); ?></label></th>
				<td><?php _e('List your portfolio projects in <code>portfolio.php</code>', 'simple-portfolio'); ?></td>
			</tr>
		</table>
		
		<div style="display:block;height:20px;">&nbsp;</div>

		<!-- XML OUTPUT -->
		<?php $xml_url = get_site_url() . '/' . (trim(get_option('slug')) != '' ? get_option('slug') : 'portfolio') . '.xml'; ?>
		<h3><?php _e('XML output', 'simple-portfolio'); ?></h3>
		<p><?php _e('When enabled', 'simple-portfolio'); ?>, <?php echo "<a href=\"$xml_url\" target=\"_blank\">" . $xml_url . "</a>"; ?> <?php _e('generates the xml', 'simple-portfolio'); ?></p>
		<p>
			<label title="<?php _e('dont use xml', 'simple-portfolio'); ?>">
				<input type="radio" value="0" name="use-xml" <?php checked('', get_option('use-xml')); checked('0', get_option('use-xml')); ?> />
				<span><?php _e('Disabled', 'simple-portfolio'); ?></span>
			</label>
		</p>
		<p>
			<label title="<?php _e('generate only portfolio data', 'simple-portfolio'); ?>">
				<input type="radio" value="1" name="use-xml" <?php checked('1', get_option('use-xml')); ?> />
				<span><?php _e('Enabled &gt; Portfolio Data', 'simple-portfolio'); ?></span>
			</label>
		</p>
		<p>
			<label title="<?php _e('generate portfolio and all wordpress data', 'simple-portfolio'); ?>">
				<input type="radio" value="2" name="use-xml"  <?php checked('2', get_option('use-xml')); ?> />
				<span><?php _e("Enabled &gt; All Data (including WP data such as pages, posts, custom menu's, categories and links)", 'simple-portfolio'); ?> </span>
			</label>
		</p>

		<div style="display:block;height:20px;">&nbsp;</div>

		<!-- COMMON INFORMATION FIELDS -->
		<h3><?php _e('Common Information Fields', 'simple-portfolio'); ?></h3>
		<p><?php _e('Create the fields that are mostly common for each project. These fields will automatically be added to each project.', 'simple-portfolio'); ?></p>
		<p><?php _e("Note: When deleting a field that's in use, it does effect the relating field in the project. When deleting make sure it's not in use by any project", 'simple-portfolio'); ?></p>

		<div style="padding:10px;">
			<input type="button" value="Add field" class="button tagadd" onclick="add_field();" style="margin-top:20px;margin-bottom:20px;"/>
			<div id="info_fields">
				<ul>
					<?php foreach (get_option_preformatted() as $field): if (trim($field) != '') : ?>
						<li>
							<span class="drag-handle"></span>
							<input type="text" class="regular-text code" value="<?php echo htmlentities($field); ?>" style="width:500px;" />
							<input type="button" value="Delete" onclick="delete_field(this);"  />
						</li>
					<?php endif; endforeach; ?>
				</ul>
			</div>
		</div>

		<input type="hidden" name="info-fields" value=""/>


		<div style="display:block;height:20px;">&nbsp;</div>


		<!-- SHOW CREDITS IN FRONTEND -->
		<h3><?php _e('Show some ♥', 'simple-portfolio'); ?></h3>
		<p><?php _e("Give plugin developer credits", 'simple-portfolio'); ?></p>
		<p>
			<label for="show-credits" title="<?php echo _e('Show some ♥ :)', 'simple-portfolio'); ?>">
				<input type="checkbox" name="show-credits" id="show-credits" value="1" <?php checked('1', get_option('show-credits')); ?> />
				<span><?php _e("Yes, I'm glad with this plugin and I'd like to output the plugin credits in my HTML source", 'simple-portfolio'); ?></span>
			</label>
		</p>

		<p class="submit"><input type="button" class="button-primary" value="<?php _e("Save changes", "simple-portfolio");?>" onclick="save_form();"/></p>

		<?php endif; ?>
	</form>

	<?php include(WP_PLUGIN_DIR . '/simple-portfolio/extends/credits.php'); ?>
</div>

<script type="text/javascript">

	function add_field() {
		var field_html = "<span class=\"drag-handle\"></span>";
		field_html += "<input type=\"text\" class=\"regular-text code\" value=\"\" style=\"width:500px;\" />";
		field_html += "<input type=\"button\" value=\"Delete\" onclick=\"delete_field(this);\"  />";

		jQuery('#info_fields ul').prepend('<li>' + field_html + '</li>');
		jQuery('#info_fields ul li').first().hide();
		jQuery('#info_fields ul li').first().slideDown('fast');
	}

	function delete_field(field) {
		jQuery(field).parent().slideUp('fast', function(e) { jQuery(this).html('');	});
	}

	function save_form() {
		var fields = [];
		jQuery('#info_fields input[type="text"]').each(function(index, value) {
			var v = jQuery(value).attr('value');
			if (jQuery.trim(v) != '')
				fields.push(v);
		});

		jQuery('input[type="hidden"][name="info-fields"]').attr('value', fields.join(','));
		jQuery('form[action="options.php"]').submit();
	}

	function help_toggle($el) {
		var helpdiv = jQuery($el).parent().find('#help');
		(helpdiv.css('display') == 'none') ? helpdiv.slideDown() : helpdiv.slideUp();
	}

	function update_validation_blocks($direct) {
		var portfolio = jQuery('#portfolio_validation');
		var single = jQuery('#portfolio_single_validation');

		var portfolio_vis = jQuery('input[name="use-slug-rewrite"]:checked').val() == '1' && portfolio.find('#file_exists').text() == 'false';
		var single_vis = single.find('#file_exists').text() == 'false';

		var time_animation = $direct ? 0 : 'fast';
		portfolio_vis ? portfolio.slideDown(time_animation) : portfolio.slideUp(time_animation);
		single_vis ? single.slideDown(time_animation) : single.slideUp(time_animation);
	}

	jQuery(document).ready(function() {
		// sortable info fields
		jQuery('div#info_fields ul').sortable({
			containment: 'parent',
			tolerance: 'pointer',
			handle: '.drag-handle',
			opacity: 0.6
		});

		update_validation_blocks( true );
		jQuery('input[name="use-slug-rewrite"]').change(function(){
			update_validation_blocks( false );
		});
	});

</script>

<script type='text/javascript' src='<?php echo WP_PLUGIN_URL; ?>/simple-portfolio/js/jquery-ui-1.8.4.custom.min.js'></script>

<?php
}


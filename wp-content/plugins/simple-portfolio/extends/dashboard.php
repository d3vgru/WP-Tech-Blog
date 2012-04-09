<?php

add_action('wp_dashboard_setup', 'portfolio_dashboard_box');

function portfolio_dashboard_box() {
	wp_add_dashboard_widget( 'portfolio_dashboard', 'Portfolio', 'portfolio_dashboard_html' );
}

function portfolio_dashboard_html() {	
?>
	<?php if (get_option('permalink_structure') == ''): ?>
		<p><?php _e('Notice: Permalinks disabled. In order to make this plugin work correctly, you need to enable the permalink.', 'simple-portfolio'); ?></p>
		<a href="options-permalink.php" class="button"><?php _e('Change Permalinks', 'simple-portfolio'); ?></a>
	<?php endif; ?>
	
	<p style="font-family:Georgia,'Times New Roman','Bitstream Charter',Times,serif;color:#777777;font-size:13px;font-style:italic;"><?php _e('Last added projects', 'simple-portfolio'); ?></p>
	<?php
		$projects = simple_portfolio_get_projects();
		
		$count = 10;
		foreach ($projects as $project):
			if ($count == 0) break;
			edit_post_link($project->post_title, '<p>', '</p>', $project->ID);
			$count--;
		endforeach;
		
	?>
	
	<div style="border-top:1px solid #ECECEC;">
		<p>&gt; <?php printf(__('Portfolio contains %d projects', 'simple-portfolio'), count($projects)); ?></p>
	</div>
<?php
}



add_action('right_now_content_table_end', 'portfolio_dashboard_right_now');

function portfolio_dashboard_right_now() {
	if (!post_type_exists('portfolio'))
		return;
	
	$num_posts = wp_count_posts( 'portfolio' );
	$num = number_format_i18n( $num_posts->publish );
	
	$text = _n( 'Portfolio project', 'Portfolio Projects', intval($num_posts->publish), 'simple-portfolio');

	if ( current_user_can( 'edit_posts' ) ) {
		$num = "<a href='edit.php?post_type=portfolio'>$num</a>";
		$text = "<a href='edit.php?post_type=portfolio'>$text</a>";
	}
	
	echo '<td class="first b b-portfolio">' . $num . '</td>';
	echo '<td class="t portfolio">' . $text . '</td>';

	echo '</tr>';

	if ($num_posts->pending > 0) {
		$num = number_format_i18n( $num_posts->pending );
		$text = _n( 'Portfolio projects Pending', 'Portfolio projects Pending', intval($num_posts->pending), 'simple-portfolio');
		if ( current_user_can( 'edit_posts' ) ) {
			$num = "<a href='edit.php?post_status=pending&post_type=portfolio'>$num</a>";
			$text = "<a href='edit.php?post_status=pending&post_type=portfolio'>$text</a>";
		}
		echo '<td class="first b b-portfolio">' . $num . '</td>';
		echo '<td class="t portfolio">' . $text . '</td>';
		
		echo '</tr>';
	}	
}


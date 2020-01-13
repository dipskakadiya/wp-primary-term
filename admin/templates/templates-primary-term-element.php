<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
?>
<script type="text/html" id="tmpl-wppt-primary-term-element">
	<?php
	printf(
		'<button type="button" class="wppt-primary-term-button">%1$s</button>',
		esc_html__( 'Make Primary', 'wp-primary-term' )
	);
	?>
</script>

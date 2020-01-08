<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
?>
<script type="text/html" id="tmpl-wppt-primary-term-render">
    <span class="wppt-primary-term-render-label"><?php
        echo esc_html(
            sprintf(
            /* translators: %s is the taxonomy title. This will be shown to screenreaders */
                '' . __( 'Primary %s', 'wp-primary-term' ) . '',
                '{{data.taxonomy.name}}'
            )
        );
    ?></span>
</script>
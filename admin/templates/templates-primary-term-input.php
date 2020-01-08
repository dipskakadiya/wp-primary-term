<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
?>
<script type="text/html" id="tmpl-wppt-primary-term-input">
    <input id="wppt-primary-{{data.taxonomy.name}}" class="wppt-primary-{{data.taxonomy.name}}"
           name="wppt-primary-{{data.taxonomy.name}}" value="{{data.taxonomy.primary}}" type="hidden">

	<?php wp_nonce_field( 'save-primary-term', 'wppt_primary_{{data.taxonomy.name}}_nonce' ); ?>
</script>

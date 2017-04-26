<?php
/**
 * 一般設定
 */
?>
<div class="wrap">
<h1><?=esc_html( __('General Settings') )?></h1>

<form method="post" action="options.php">
<?php settings_fields('site_settings'); ?>

<table class="form-table">

	<?php do_settings_sections('site_settings'); ?>

		<?php do_settings_fields('site_settings', 'default'); ?>


</table>

<?php submit_button(); ?>
</form>

</div>

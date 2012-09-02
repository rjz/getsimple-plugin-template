<?php
/**
 *	View for administration
 *	@author @rjzaworski
 */
?>
<h3>Update Contact Link</h3>

<p>Add <code>(% contact %)</code> to your template files to include a link to your contact e-mail.</p>

<?php if (isset($error)): ?>
<div class="error">
	<p><?php echo $error; ?></p>
</div>
<?php elseif (isset($updated)): ?>
<div class="updated">
	<p><?php echo $updated; ?></p>
</div>
<?php endif; ?>

<form method="post">

	<input type="hidden" name="_action" value="<?php echo $action; ?>" />
	<input type="hidden" name="_nonce" value="<?php echo get_nonce($action, $info['id']); ?>" />

	<p>
		<label for="email">Your E-mail</label>
		<input type="email" name="email" id="email" value="<?php echo $settings['email']; ?>" />
	</p>

	<div class="form-actions">
		<input class="button" type="submit" value="Save" />
	</div>
</form>

<?php
/**
 *	View for admin form
 */
?>
<h3 class="floated">Settings for Google Calendar</h3>

<div class="edit-nav">
	<a href="#details" role="expander">More?</a>
	<div class="clear"></div>
</div>

<div id="details">
Tell me more.
</div>

<?php if (isset($error)): ?>
<div class="error">
	<p><?php echo $error; ?></p>
</div>
<?php elseif (isset($updated)): ?>
<div class="updated">
	<p><?php echo $updated; ?></p>
</div>
<?php endif; ?>

<form method="post" action="?id=google_calendar">

	<input type="hidden" name="_action" value="save" />
	<input type="hidden" name="_nonce" value="<?php echo get_nonce('save', $info['id']); ?>" />

	<p>
		<label for="private_url" class="control-label">Private Calendar URL: (<a class="" href="http://support.google.com/calendar/bin/answer.py?hl=en&answer=37648">Where can I find this?</a>)</label>
		<input class="text" type="text" name="private_url" id="private_url" value="<?php echo $settings['private_url'] ?>" />
	</p>

	<p>
		<label for="timeout" class="control-label">Timeout (seconds):</label>
		<input class="text" type="text" name="timeout" id="timeout" value="<?php echo $settings['timeout'] ?>" />
	</p>

	<div class="form-actions">
		<input class="submit" type="submit" value="Save" />
	</div>
</form>

<hr />

<h3>Cache</h3>
<a class="submit" href="?id=google_calendar&_action=refresh&_nonce=<?php echo get_nonce('refresh', $info['id']); ?>">Refresh</a>

<form action="<?php echo admin_url('admin-post.php'); ?>" method="POST">

	<input type="hidden" name="action" value="send_form" />

	<div>
		<label for="mp_name">Restaurant Name</label>
		<input type="text" id="mp_name" name="mp_name" placeholder="Enter the restaurant's name" />
	</div>

	<div>
		<label for="mp_address">Restaurant Address</label>
		<input type="text" id="mp_address" name="mp_address" placeholder="Enter the restaurant's address" />
	</div>

	<div>
		<label for="mp_city">Restaurant City</label>
		<input type="text" id="mp_city" name="mp_city" placeholder="Enter the restaurant's city" />
	</div>

	<div>
		<input type="submit" value="Send suggestion!" />
	</div>

</form>

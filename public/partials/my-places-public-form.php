<form action="<?php echo admin_url('admin-post.php'); ?>" method="POST">

	<?php
		$name = "";
		$address = "";
		$city = "";

		if (isset($_REQUEST['mp_form_submit_success'])) {

			if ($_REQUEST['mp_form_submit_success']) {

				?>
					<div id="mp_form_response" style="background-color: #cfc; margin-bottom: 1rem; padding: 0.5rem 1rem;">
						Thanks for submitting a place suggestion!
					</div>
				<?php

			} else {

				if (isset($_REQUEST['mp_form_data'])) {
					$name = $_REQUEST['mp_form_data']['name'];
					$address = $_REQUEST['mp_form_data']['address'];
					$city = $_REQUEST['mp_form_data']['city'];
				}

				?>
					<div id="mp_form_response" style="background-color: #fcc; margin-bottom: 1rem; padding: 0.5rem 1rem;">
						Oooops, something went wrong. Please check that you submitted all required fields.
					</div>
				<?php
			}
		}

	?>

	<input type="hidden" name="action" value="send_form" />

	<div>
		<label for="mp_name">Restaurant Name</label>
		<input type="text" id="mp_name" name="mp_name" placeholder="Enter the restaurant's name" value="<?php echo $name; ?>" required="required" />
	</div>

	<div>
		<label for="mp_address">Restaurant Address</label>
		<input type="text" id="mp_address" name="mp_address" placeholder="Enter the restaurant's address" value="<?php echo $address; ?>" required="required" />
	</div>

	<div>
		<label for="mp_city">Restaurant City</label>
		<input type="text" id="mp_city" name="mp_city" placeholder="Enter the restaurant's city" value="<?php echo $city; ?>" required="required" />
	</div>

	<div>
		<input type="submit" value="Send suggestion!" />
	</div>

</form>

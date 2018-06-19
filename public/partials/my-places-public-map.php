<div id="my-places-map-wrapper">
	<div id="my-placetypes">
		<ul>
			<?php
				// loop over all terms in taxonomy my_placetype and echo out a checkbox for each term
				$placetypes = get_terms(['taxonomy' => 'my_placetype']);
				foreach($placetypes as $placetype) {
					?>
						<li>
							<input
								type="checkbox"
								id="placetype_<?php echo $placetype->term_id; ?>"
								data-id="<?php echo $placetype->term_id; ?>"
								checked="checked">
							<label
								for="placetype_<?php echo $placetype->term_id; ?>">
								<?php echo $placetype->name; ?>
							</label>
						</li>
					<?php
				}
			?>
		</ul>
	</div>
	<div id="my-places-map"><i>Loading map...</i></div>
</div>

<?php

$terms = get_terms( 'color' );
if ( $terms ) {
	?>
<form class="form-filter">
	<div class="form-check">
		<input class="form-check-input" type="radio" name="color" id="all" value="all" checked>
		<label class="form-check-label" for="all">
			All
		</label>
	</div>
		<?php foreach ( $terms  as $term ) { ?>
			<div class="form-check">
				<input class="form-check-input" type="radio" name="color" id="<?php echo $term->term_id; ?>" value="<?php echo $term->name; ?>">
				<label class="form-check-label" for="<?php echo $term->term_id; ?>">
					<?php echo $term->name; ?>
				</label>
			</div>
			<?php } ?>
</form>

	<?php
}

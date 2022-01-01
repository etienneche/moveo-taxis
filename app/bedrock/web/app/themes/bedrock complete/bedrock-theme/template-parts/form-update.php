<?php

$args = array(
	'post_type'      => 'taxi',
	'posts_per_page' => -1,
);

$terms = get_terms( 'color' );


$query = new WP_Query( $args );

if ( $query->have_posts() ) {



	?>
<form class="form-update mb-3">
	<select class="taxi-select form-select" name="">
		<option value="">Select A Taxi</option>
		<?php
		while ( $query->have_posts() ) {
			$query->the_post();
			$post_terms = get_the_terms( get_the_id(), 'color');
			?>
				<option value="<?php echo get_the_ID(); ?>" data-color="<?php echo $post_terms[0]->name; ?>">
					<?php the_title(); ?>
				</option>
			<?php

		}

		wp_reset_query();
		?>
	</select>
</form>
<form class="taxi-update" style="display:none" >
	<input type="hidden" name="taxi_id" value="">
	<input type="text" name="title" value="" placeholder="Title"  class="form-control mb-3">
	<input type="text" name="model" value="" placeholder="Model" class="form-control mb-3">
	<input type="text" name="year" value="" placeholder="Year" class="form-control mb-3">

	<select  name="color" class="form-control form-select mb-3" required>
		<option value="">
			Color
		</option>
		<?php foreach ( $terms  as $term ) : ?>
				<option value="<?php echo $term->name; ?>">
					<?php echo $term->name; ?>
				</option>
		<?php endforeach; ?>
	</select>

	<input type="text" name="lat" value="" placeholder="Lat" class="form-control mb-3">
	<input type="text" name="lng" value="" placeholder="Lng" class="form-control mb-3">
	<input type="submit" name="submit" value="Update" class="btn btn-primary">

	<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Remove Taxi</button>

</form>

<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">
			Are You Sure you want to remove this taxi?
		</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        <button type="button" class="btn btn-primary remove-taxi-btn">Yes</button>
      </div>
    </div>
  </div>
</div>
	<?php
}

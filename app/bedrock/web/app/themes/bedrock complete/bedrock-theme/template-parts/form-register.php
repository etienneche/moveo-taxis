<?php
$terms = get_terms( 'color' );

?>
<form class="taxi-register">
	<input type="text" name="title" value="" placeholder="Title"  class="form-control mb-3" required>
	<input type="text" name="id" value="" placeholder="Taxi Id"  class="form-control mb-3" required>
	<input type="text" name="model" value="" placeholder="Model" class="form-control mb-3" required>
	<input type="text" name="year" value="" placeholder="Year" class="form-control mb-3" required>
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
	<input type="text" name="lat" value="" placeholder="Lat" class="form-control mb-3" required>
	<input type="text" name="lng" value="" placeholder="Lng" class="form-control mb-3" required>
	<input type="submit" name="submit" value="Submit" class="btn btn-primary">

</form>

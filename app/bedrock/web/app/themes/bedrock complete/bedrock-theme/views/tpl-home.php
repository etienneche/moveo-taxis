<?php
/**
 * Template Name: HomePage
 *
 * @package WordPress
 */

get_header();
?>

<section class="grid-header">
	<div class="row justify-content-end">
		<div class="col-4">
			<h1>MOVEO TAXI'S</h1>
		</div>
		<div class="col-4" id="middle-col">
			<div class="d-flex justify-content-center form-check form-switch">
				<input class="form-check-input toggle-btn" name="" type="checkbox" role="switch" id="flexSwitchCheckChecked">
				<label class="form-check-label" for="flexSwitchCheckChecked">2km From Station</label>
			</div>
	</div>
</section>

<section class="map-section">
	<div class="container-fluid">
		<div class="row">
			<div class="col-lg-12">

			</div>
		</div>
		<div class="row align-items-center">
			<div id="col-map" class="col-lg-8" >
				<div id="map" class="map"></div>
			</div>
			<div class="form-section col-lg-4">
				
				<div class="accordion" id="accordionExample">
					<div class="accordion-item">
						<h2 class="accordion-header">
						<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne1" aria-expanded="false" aria-controls="collapseOne">
								Filter by Color
						</button>
						</h2>
							<div id="collapseOne1" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
								<div class="accordion-body">
								<?php get_template_part( 'template-parts/form', 'filter' ); ?>

								</div>
							</div>
						</div>
						<div class="accordion-item">
							<h2 class="accordion-header">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
									Update Taxi
							</button>
							</h2>
							<div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
								<div class="accordion-body">
									<?php get_template_part( 'template-parts/form', 'update' ); ?>
								</div>
							</div>
						</div>
						<div class="accordion-item">
							<h2 class="accordion-header">
							<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
								Register Taxi
							</button>
							</h2>
							<div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
								<div class="accordion-body">
									<?php get_template_part( 'template-parts/form', 'register' ); ?>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="update-alert" class="toast text-white bg-primary" role="alert" aria-live="assertive" aria-atomic="true">
		<div class="toast-header">
			<strong class="me-auto">Taxi Updated</strong>
			<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>

		</div>
	</div>

</section>

<?
get_footer();

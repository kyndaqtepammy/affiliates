<?php
//button to show popup and generate link
function generate_affiliate() { ?>
    <!-- DASHBOARD TABS -->
    <div class="tabset">
		<input
			type="radio"
			name="tabset_1"
			id="tabset_1_description"
			hidden
			aria-hidden="true"
			checked
		>
		<input
			type="radio"
			name="tabset_1"
			id="tabset_1_statistics"
			hidden
			aria-hidden="true"
		>
		<input
			type="radio"
			name="tabset_1"
			id="tabset_1_reviews"
			hidden
			aria-hidden="true"
		>
		<input
			type="radio"
			name="tabset_1"
			id="tabset_1_contact"
			hidden
			aria-hidden="true"
		>
		<ul hidden aria-hidden="true">
			<li><label for="tabset_1_description">Share</label></li>
			<li><label for="tabset_1_statistics">Track Conversions</label></li>
			<li><label for="tabset_1_reviews">View Earnings</label></li>
			<li><label for="tabset_1_contact">Settings</label></li>
		</ul>
		<div>
			<section>
				<h2>Share</h2>
				<?php  require_once 'views/dashboard/content/aff-generate-button.php'; ?>
			</section><section>
				<h2>Track Conversions</h2>
				<?php  require_once 'views/dashboard/content/track-conversions.php'; ?>
			</section><section>
				<h2>Profile</h2>
				<p>
					Donec non nunc ac augue ornare aliquam. Aenean sed volutpat arcu. Sed molestie lacus placerat nisl gravida condimentum.
				</p>
			</section><section>
				<h2>Settings</h2>
				<?php  require_once 'views/dashboard/content/profile-settings.php'; ?>
			</section>
		</div>
	<!-- .tabset --></div>
<?php
}
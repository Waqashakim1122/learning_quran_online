<?php $this->load->view('parts/header');?>

<div class="container">
	<div class="row">
		<div class="col-lg-12"><br><br>
			<h1 class="text-center">Welcome to the Home</h1>
			<?php print_r($this->session->userdata('user')); ?>
		</div>
	</div>
</div>



<?php $this->load->view('parts/footer');?>
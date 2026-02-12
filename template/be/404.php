<div class="main-content">
	<div class="row">
		<div class="col-xxl-4">
			<div class="card stretch stretch-full">
				<div class="card-body">
					<h5>404 - halaman tidak ditemukan</h5>
					<?php
					echo $_SESSION['404'];
					if(!empty($this->pageBase)) {
						echo '<br/>';
						echo 'navigasi pageBase: <b>'.$this->pageBase.'</b> sudah diatur?';
					}
					if(!empty($this->pageLevel1)) {
						echo '<br/>';
						echo 'navigasi pageLevel1: <b>'.$this->pageLevel1.'</b> sudah diatur?';
					}
					if(!empty($this->pageLevel2)) {
						echo '<br/>';
						echo 'navigasi pageLevel1: <b>'.$this->pageLevel2.'</b> sudah diatur?';
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
unset($_SESSION['404']);
?>
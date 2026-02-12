<form action="" method="post">
<div class="main-content">
	<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
		<div class="breadcrumb-title pe-3"><?=$this->pageTitle?></div>
	</div>
	
	<div class="card stretch stretch-full">
		<div class="card-body">
			<?=$umum->sessionInfo()?>
			
			<div class="form-group row mb-3">
				<label class="col-sm-2 col-form-label">Password Lama</label>
				<div class="col-sm-7">
					<input name="OldPass" type="text" class="form-control" placeholder="" autocomplete="off">
					<small class="form-text text-white">password saat ini</small>
				</div>
			</div>
			<div class="form-group row mb-3">
				<label class="col-sm-2 col-form-label">Password Baru</label>
				<div class="col-sm-7">
					<input name="Pass1" type="text" class="form-control" placeholder="" autocomplete="off">
				</div>
			</div>
			<div class="form-group row mb-3">
				<label class="col-sm-2 col-form-label">Ulangi Password Baru</label>
				<div class="col-sm-7">
					<input name="Pass2" type="text" class="form-control" placeholder="" autocomplete="off">
				</div>
			</div>
		</div>
		<div class="card-footer">
			<button type="submit" name="changePwd" class="btn btn-primary float-right">Submit</button>
		</div>
	</div>
</div>
</form>
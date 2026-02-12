<div class="page-header">
	<div class="page-header-left d-flex align-items-center">
		<div class="page-header-title">
			<h5 class="m-b-10"><?=$this->pageTitle?></h5>
		</div>
	</div>
	<div class="page-header-right ms-auto">
		<div class="page-header-right-items">
			<div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
				<a href="<?=BE_MAIN_HOST?>/dashboard/review_lap_learning" class="btn btn-primary"><i class="feather-chevron-left me-2"></i> Kembali</a>
			</div>
		</div>
	</div>
</div>

<form>
<div class="main-content">
	<div class="card stretch stretch-full">
		<div class="card-header">
			<h5 class="card-title">Laporan yang Perlu Diperiksa</h5>
		</div>
		<div class="card-body">
			<table class="table table-sm">
				<tr>
					<td style="width:20%">Nama</td>
					<td>: Ryan Aprianto</td>
				</tr>
				<tr>
					<td>NIK</td>
					<td>: 654321</td>
				</tr>
				<tr>
					<td>Entitas</td>
					<td>: PT Perkebunan Nusantara I</td>
				</tr>
				<tr>
					<td>Jenis Laporan</td>
					<td>: Social Learning</td>
				</tr>
				<tr>
					<td>Tanggal Pelaksanaan</td>
					<td>: 8 Oktober 2025</td>
				</tr>
				<tr>
					<td>JPL</td>
					<td>: 8</td>
				</tr>
				<tr>
					<td>Summary</td>
					<td>: Melakukan sharing session terkait transformasi administrasi menggunakan SAP </td>
				</tr>
				<tr>
					<td>Evidence</td>
					<td>
						<div><a href="https://www.youtube.com/watch?v=xcDduxg_l1I" target="_blank" class="btn btn-sm btn-primary mb-1"><i class="feather-link-2"></i>&nbsp;youtube</a></div>
						<div><a href="https://img.youtube.com/vi/xcDduxg_l1I/maxresdefault.jpg" target="_blank" class="btn btn-sm btn-primary mb-1"><i class="feather-link-2"></i>&nbsp;dokumentasi</a></div>
					</td>
				</tr>
			</table>
			
			<div class="form-group row mb-3">
				<label class="col-sm-2 col-form-label">Status Review</label>
				<div class="col-sm-5">
					<select class="form-select p-1">
					<option selected></option>
					<option value="1">Terima Laporan</option>
					<option value="2">Tolak Laporan</option>
					<option value="3">Kembalikan Laporan kepada Pembuat</option>
					</select>
				</div>
			</div>
			<div class="mb-3">
				<label for="alasan" class="form-label">Alasan</label>
				<textarea class="form-control" id="alasan" rows="3"></textarea>
			</div>
		</div>
		<div class="card-footer">
			<button type="submit" name="changePwd" class="btn btn-primary float-right">Submit</button>
		</div>
	</div>
</div>
</form>
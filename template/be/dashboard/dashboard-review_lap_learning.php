<div class="page-header">
	<div class="page-header-left d-flex align-items-center">
		<div class="page-header-title">
			<h5 class="m-b-10"><?=$this->pageTitle?></h5>
		</div>
	</div>
</div>

<div class="main-content">
	<div class="card stretch stretch-full">
		<div class="card-header"><h4 class="card-title">Pencarian</h4></div>
		<div class="card-body">
			<form method="get" action="<?=$targetpage?>">
				<div class="form-group row mb-2">
					<label class="col-sm-2 col-form-label" for="label">Tahun</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" id="label" name="label" value="<?=$label?>" />
					</div>
				</div>
				
				<div class="form-group row mb-2">
					<label class="col-sm-2 col-form-label" for="entitas">Entitas</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" id="entitas" name="entitas" value="<?=$entitas?>" />
					</div>
				</div>
				
				<input class="btn btn-primary" type="submit" value="cari"/>
			</form>
		</div>
	</div>
	
	<div class="card stretch stretch-full">
		<div class="card-header">
			<h5 class="card-title">Laporan yang Perlu Diperiksa</h5>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-hover mb-0">
					<thead>
						<tr class="border-b">
							<th scope="row">No</th>
							<th>Karyawan</th>
							<th>Entitas</th>
							<th>Jenis Laporan</th>
							<th>Status</th>
							<th class="text-center">Aksi</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>1</td>
							<td>
								<div class="d-flex align-items-center gap-3">
									<div>
									<span class="d-block">Ryan Aprianto</span>
									<span class="fs-12 d-block fw-normal text-muted">654321</span>
									</div>
								</div>
							</td>
							<td>PT Perkebunan Nusantara I</td>
							<td>Social Learning</td>
							<td>
								<span class="badge bg-soft-primary text-primary">Sedang Direview</span>
							</td>
							<td class="text-end">
								<a href="<?=BE_MAIN_HOST?>/dashboard/review_lap_learning/periksa?id=1" class="btn btn-sm btn-primary"><i class="feather-edit-2"></i>&nbsp;Review</a>
							</td>
						</tr>
						<tr>
							<td>2</td>
							<td>
								<div class="d-flex align-items-center gap-3">
									<div>
									<span class="d-block">Ryan Aprianto</span>
									<span class="fs-12 d-block fw-normal text-muted">654321</span>
									</div>
								</div>
							</td>
							<td>PT Perkebunan Nusantara I</td>
							<td>Coaching</td>
							<td>
								<span class="badge bg-soft-success text-success">Disetujui</span>
							</td>
							<td class="text-end">
								<a href="<?=BE_MAIN_HOST?>/dashboard/review_lap_learning/detail?id=1" class="btn btn-sm btn-success"><i class="feather-eye"></i>&nbsp;Detail</a>
							</td>
						</tr>
						<tr>
							<td>3</td>
							<<td>
								<div class="d-flex align-items-center gap-3">
									<div>
									<span class="d-block">Ryan Aprianto</span>
									<span class="fs-12 d-block fw-normal text-muted">654321</span>
									</div>
								</div>
							</td>
							<td>PT Perkebunan Nusantara I</td>
							<td>Sharing Session</td>
							<td>
								<span class="badge bg-soft-warning text-warning">Butuh Perbaikan</span>
							</td>
							<td class="text-end">
								<a href="<?=BE_MAIN_HOST?>/dashboard/review_lap_learning/detail?id=2" class="btn btn-sm btn-warning"><i class="feather-eye"></i>&nbsp;Detail</a>
							</td>
						</tr>
						<tr>
							<td>4</td>
							<td>
								<div class="d-flex align-items-center gap-3">
									<div>
									<span class="d-block">Ryan Aprianto</span>
									<span class="fs-12 d-block fw-normal text-muted">654321</span>
									</div>
								</div>
							</td>
							<td>PT Perkebunan Nusantara I</td>
							<td>Benchmark</td>
							<td>
								<span class="badge bg-soft-danger text-danger">Ditolak</span>
							</td>
							<td class="text-end">
								<a href="<?=BE_MAIN_HOST?>/dashboard/review_lap_learning/detail?id=3" class="btn btn-sm btn-danger"><i class="feather-eye"></i>&nbsp;Detail</a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
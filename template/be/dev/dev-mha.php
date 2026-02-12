<div class="main-content">
	<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
		<div class="breadcrumb-title pe-3"><?=$this->pageTitle?></div>
	</div>
	
	<div class="card stretch stretch-full">
		<div class="card-header"><h4 class="card-title">Pencarian</h4></div>
		<div class="card-body">
			<form method="get" action="<?=$targetpage?>">
				<div class="form-group row mb-2">
					<label class="col-sm-2 col-form-label" for="label">Label</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" id="label" name="label" value="<?=$label?>" />
					</div>
				</div>
				
				<input class="btn btn-primary" type="submit" value="cari"/>
			</form>
		</div>
	</div>
</div>

<div class="main-content">
	<div class="card stretch stretch-full">
		<div class="card-header">
		   <h5 class="card-title">Data</h5>
		</div>
		<div class="card-body">
		   <div class="table-responsive">
			  <table class="table table-hover mb-0">
				 <thead>
					<tr class="border-b">
					   <th style="width:1%"><b>ID</b></th>
						<th><b>Kategori</b></th>
						<th><b>Label</b></th>
						<th style="width:1%"><b>Status</b></th>
					</tr>
				 </thead>
				 <tbody>
					<?
					$i = $arrPage['num'];
					foreach($data as $row) { 
						$i++;
					?>
					<tr>
						<td class="align-top"><?=$row->id?></td>
						<td class="align-top"><?=$row->kategori?></td>
						<td class="align-top"><?=$row->label?></td>
						<td class="align-top"><?=$row->status?></td>
					 </tr>
					<? } ?>
				 </tbody>
			  </table>
			  <?=$arrPage['bar']?>
		   </div>
		</div>
	</div>
</div>
<div class="main-content">
	<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
		<div class="breadcrumb-title pe-3"><?=$this->pageTitle?></div>
	</div>
	
	<div class="card stretch stretch-full">
		<div class="card-header"><h4 class="card-title">Pencarian</h4></div>
		<div class="card-body">
			<form method="get" action="<?=$targetpage?>">
				<div class="form-group row mb-2">
					<label class="col-sm-2 col-form-label" for="nama">Karyawan</label>
					<div class="col-sm-7">
						<textarea class="form-control border border-primary" id="nk" name="nk" rows="1" onfocus="textareaOneLiner(this)"><?=$nk?></textarea>
						<input type="hidden" name="idk" value="<?=$idk?>"/>
					</div>
					<div class="col-sm-1">
						<span id="help_karyawan" class="text-info" data-bs-toggle="tooltip" data-bs-placement="top" title="Masukkan nik/nama karyawan untuk mengambil data"><i class="feather-alert-octagon"></i></span>
					</div>
				</div>
				
				<div class="form-group row mb-2">
					<label class="col-sm-2 col-form-label" for="kategori">Aktivitas</label>
					<div class="col-sm-5">
						<input type="text" class="form-control" id="kategori" name="kategori" value="<?=$kategori?>" />
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
					   <th style="width:1%"><b>No</b></th>
						<th><b>Tanggal/ID</b></th>
						<th><b>NIK/Nama</b></th>
						<th><b>Kategori</b></th>
						<th style="width:1%"><b>IP</b></th>
					</tr>
				 </thead>
				 <tbody>
					<?
					$i = $arrPage['num'];
					foreach($data as $row) { 
						$i++;
						
						$nama_karyawan = $sdm->getData("nik_nama_karyawan_by_id",array("id_user"=>$row->id_user,"all_level"=>"1"));
						
						$catatan = '';
						if(!empty($row->query_error)) $catatan .= '<span class="text-danger">'.$row->query_error.'</span>';
					?>
					<tr>
						<td class="align-top"><?=$i?>.</td>
						<td class="align-top"><?=$umum->date_indo($row->tanggal,'datetime').'<br/>'.$row->id?></td>
						<td class="align-top"><?=$nama_karyawan?></td>
						<td class="align-top"><?=$row->kategori?></td>
						<td class="align-top"><?=$row->ip?></td>
					 </tr>
					 <tr>
						<td colspan="5" class="align-top">catatan: <?=$catatan?></td>
					 </tr>	
					<? } ?>
				 </tbody>
			  </table>
			  <?=$arrPage['bar']?>
		   </div>
		</div>
	</div>
</div>

<script>
$(document).ready(function(){
	$('#nk').autocomplete({
		source:'<?=BE_MAIN_HOST?>/sdm/ajax?act=karyawan&s=all',
		minLength:1,
		change:function(event,ui) { if($(this).val().length==0) $('input[name=idk]').val(''); },
		select:function(event,ui) { $('input[name=idk]').val(ui.item.id); }
	});
});
</script>
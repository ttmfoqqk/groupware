<!-- .page-content -->
<div class="page-content sidebar-page clearfix">
	<!-- .page-content-wrapper -->
	<div class="page-content-wrapper">
		<div class="page-content-inner">
			<!-- .page-content-inner -->
			<div id="page-header" class="clearfix">
				<div class="page-header">
					<h2>&nbsp;</h2>
					<span>-parameters 점검,upload/download 관련 작업 요망</span>
				</div>
			</div>
			<div class="row">
				<!-- col-lg-12 end here -->
				<div class="col-lg-12">
					<!-- col-lg-12 start here -->
					<div class="panel panel-primary">
						<!-- Start .panel -->
						<div class="panel-heading">
							<h4 class="panel-title"><i class="fa fa-circle"></i> <?echo $board_name;?></h4>
						</div>
						<div class="panel-body">


							<form id="board-form-list" action="<?echo $action_url;?>" method="post" class="form-horizontal group-border stripped" role="form">
							<input type="hidden" name="action_type" id="action_type" value="">
							<input type="hidden" name="parameters" id="parameters" value="<?echo $parameters;?>">

							<table id="tabletools" class="table table-bordered" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th style="width:50px;">
											<div class="checkbox-custom">
												<input class="check-all" type="checkbox" id="masterCheck" value="option1">
												<label for="masterCheck"></label>
											</div>
										</th>
										<th>제목</th>
										<th class="per10">작성자</th>
										<th class="per10">등록일</th>
										<th class="per5">조회</th>
									</tr>
								</thead>
								<tbody>
								<?foreach($notice as $lt){
									$anchor = $anchor_url .'&no='.$lt['no'];
								?>
									<tr>
										<td>
											<div class="checkbox-custom text-danger">공지</div>
										</td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $lt['subject'];?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $lt['user_name'];?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $lt['created'];?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $lt['count_hit'];?></a></td>
									</tr>
								<?}?>

								<?foreach($list as $lt){
									$anchor = $anchor_url .'&no='.$lt['no'];
									
									$depth = '';
									if( $lt['no'] != $lt['parent_no'] ){
										$nbsp = '';
										for($i=0;$i<$lt['depth'];$i++){
											$nbsp .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
										}
										$depth = $nbsp.'[RE]:';
									}
									?>
									<tr>
										<td>
											<div class="checkbox-custom">
												<input id="check<?$lt['no'];?>" name="contents_no[]" class="check" type="checkbox" value="<?echo $lt['no'];?>">
												<label for="check<?$lt['no'];?>"></label>
											</div>
										</td>
										<td><?echo $depth;?><a href="<?echo $anchor;?>" class="text-normal"><?echo $lt['subject'];?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $lt['user_name'];?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $lt['created'];?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $lt['count_hit'];?></a></td>
									</tr>
								<?}
								if( count($list) <= 0 ){?>
									<tr>
										<td colspan="5">등록된 내용이 없습니다.</td>
									</tr>
								<?}?>
								</tbody>
							</table>
							<div class="panel-body" style="text-align:center;"><?echo $pagination;?></div>
							<div class="panel-body pull-right">
								<button id="btn_list_delete" type="button" class="btn btn-danger btn-alt mr5 mb10">삭제</button>
								<button type="button" class="btn btn-primary btn-alt mr5 mb10" onclick="location.href='<?echo $write_url;?>';">등록</button>
							</div>

							</form>
						</div>
					</div>
					<!-- End .panel -->
				</div>
				<!-- col-lg-12 end here -->
			</div>
			
			<!-- End .row -->
		</div>
		<!-- / .page-content-inner -->
	</div>
	<!-- / page-content-wrapper -->
</div>
<!-- / page-content -->

<script src="<?echo $this->config->base_url()?>html/js/sw/sw_board.js"></script>
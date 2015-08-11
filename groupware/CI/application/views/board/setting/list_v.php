<!-- .page-content -->
<div class="page-content sidebar-page clearfix">
	<!-- .page-content-wrapper -->
	<div class="page-content-wrapper">
		<div class="page-content-inner">
			<!-- .page-content-inner -->
			<div id="page-header" class="clearfix">
				<div class="page-header">
					<h2>게시판 설정</h2>
				</div>
			</div>
			<div class="row">
				<!-- col-lg-12 end here -->
				<div class="col-lg-12">
					<!-- col-lg-12 start here -->
					<div class="panel panel-default">
						<!-- Start .panel -->
						<div class="panel-heading">
							<h4 class="panel-title">게시판 목록</h4>
						</div>
						<div class="panel-body">
							<form id="board-form-list" action="<?echo site_url('board_setting/proc');?>" method="post" class="form-horizontal group-border stripped" role="form">
							<input type="hidden" name="action_type" id="action_type" value="delete">

							<table id="tabletools" class="table table-bordered" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th style="width:50px;">
											<div class="checkbox-custom">
												<input class="check-all" type="checkbox" id="masterCheck" value="option1">
												<label for="masterCheck"></label>
											</div>
										</th>
										<th class="per10">코드</th>
										<th class="per10">타입</th>
										<th>이름</th>
										<th class="per5">답글</th>
										<th class="per5">댓글</th>
										<th class="per5">순서</th>
									</tr>
								</thead>
								<tbody>
								<?php foreach($list as $lt){
									$anchor = $anchor_url.'?no='.$lt['no'];
									?>
									<tr>
										<td>
											<div class="checkbox-custom">
												<input id="check<?$lt['no'];?>" name="board_no[]" class="check" type="checkbox" value="<?echo $lt['no'];?>">
												<label for="check<?$lt['no'];?>"></label>
											</div>
										</td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?php echo $lt['code'];?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?php echo $lt['type'];?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?php echo $lt['name'];?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?php echo ($lt['reply'] == 0 ? 'Y' : 'N'); ?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?php echo ($lt['comment'] == 0 ? 'Y' : 'N'); ?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?php echo $lt['order'];?></a></td>
									</tr>
								<?php }?>
								<?
								if( count($list) <= 0 ){?>
									<tr>
										<td colspan="7">등록된 내용이 없습니다.</td>
									</tr>
								<?}?>
								</tbody>
							</table>
							<div class="panel-body" style="text-align:center;"><?echo $pagination;?></div>
							<div class="panel-body pull-right">
								<button id="btn_list_delete" type="button" class="btn btn-danger btn-alt mr5 mb10">삭제</button>
								<button type="button" class="btn btn-primary btn-alt mr5 mb10" onclick="location.href='<?echo $anchor_url;?>';">등록</button>
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



<script src="<?echo $this->config->base_url()?>html/plugins/tables/datatables/jquery.dataTables.js"></script>
<script src="<?echo $this->config->base_url()?>html/plugins/tables/datatables/dataTables.tableTools.js"></script>
<script src="<?echo $this->config->base_url()?>html/plugins/tables/datatables/dataTables.bootstrap.js"></script>
<script src="<?echo $this->config->base_url()?>html/plugins/tables/datatables/dataTables.responsive.js"></script>

<script src="<?echo $this->config->base_url()?>html/plugins/forms/bootstrap-filestyle/bootstrap-filestyle.js"></script>
<script src="<?echo $this->config->base_url()?>html/plugins/forms/select2/select2.js"></script>
<script src="<?echo $this->config->base_url()?>html/plugins/forms/validation/jquery.validate.js"></script>
<script src="<?echo $this->config->base_url()?>html/plugins/forms/validation/additional-methods.min.js"></script>
<script src="<?echo $this->config->base_url()?>html/plugins/charts/sparklines/jquery.sparkline.js"></script>

<script src="<?echo $this->config->base_url()?>html/plugins/forms/checkall/jquery.checkAll.js"></script>

<script src="<?echo $this->config->base_url()?>html/js/sw/sw_board.js"></script>
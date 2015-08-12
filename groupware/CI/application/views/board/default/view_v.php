<!-- .page-content -->
<div class="page-content sidebar-page clearfix">
	<!-- .page-content-wrapper -->
	<div class="page-content-wrapper">
		<div class="page-content-inner">
			<!-- .page-content-inner -->
			<div id="page-header" class="clearfix">
				<div class="page-header">
					<h2>&nbsp;</h2>
				</div>
			</div>
			<div class="row">
				<!-- col-lg-12 end here -->
				<div class="col-lg-12">
					<!-- col-lg-12 start here -->
					<div class="panel panel-primary">
						<!-- Start .panel -->
						<div class="panel-heading">
							<h4 class="panel-title"><i class="fa fa-circle"></i> <?echo BOARD_TITLE;?></h4>
						</div>
						<div class="panel-body">
							<form id="board-form-write-board" method="post" class="form-horizontal group-border stripped" role="form" action="<?echo BOARD_FORM;?>">
							<input type="hidden" name="action_type" id="action_type" value="">
							<input type="hidden" name="contents_no" id="contents_no" value="<?echo $data['no'];?>">
							<input type="hidden" name="parameters" id="parameters" value="<?echo urlencode($parameters);?>">
								
								<table id="tabletools" class="table table-bordered" cellspacing="0" width="100%">
									<tbody>
										<tr>
											<th class="per10">작성자</th>
											<td class="per40"><?echo $data['user_name'];?></td>
											<th class="per10">작성일</th>
											<td class="per40"><?echo $data['created'];?></td>
										</tr>
										<tr>
											<th>제목</th>
											<td colspan="5"><?echo $data['subject'];?></td>
										</tr>
										<tr>
											<th>첨부파일</th>
											<td colspan="5"></td>
										</tr>
										<tr>
											<th>내용</th>
											<td colspan="5" style="height:400px;"><?echo $data['contents'];?></td>
										</tr>
									</tbody>
								</table>


								<!-- End .form-group  -->
								<div class="panel-body pull-left">
									<button type="button" class="btn btn-info btn-alt mr5 mb10" onclick="location.href='<?echo $list_url;?>'">리스트</button>
								</div>
								<div class="panel-body pull-right">
									<?if($data['user_id']==$this->session->userdata('id')){?>
									<button id="contents_board_delete" type="button" class="btn btn-danger btn-alt mr5 mb10">삭제</button>
									<button type="button" class="btn btn-primary btn-alt mr5 mb10" onclick="location.href='<?echo $edit_url?>'">수정</button>
									<?}?>
									<?if(BOARD_REPLY == 0){?>
									<button type="button" class="btn btn-primary btn-alt mr5 mb10" onclick="location.href='<?echo $reply_url?>'">답글</button>
									<?}?>
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
<script src="<?echo $this->config->base_url()?>html/plugins/forms/summernote/summernote.js"></script>
<script src="<?echo $this->config->base_url()?>html/js/sw/sw_board.js"></script>
<!-- .page-content -->
<div class="page-content sidebar-page clearfix">
	<!-- .page-content-wrapper -->
	<div class="page-content-wrapper">
		<div class="page-content-inner">
			<!-- .page-content-inner -->
			<div id="page-header" class="clearfix">
				<div class="page-header">
					<h2>게시판 - <?echo BOARD_TITLE;?></h2>
				</div>
			</div>
			<div class="row">
				<!-- col-lg-12 end here -->
				<div class="col-lg-12">
					<!-- col-lg-12 start here -->
					<div class="panel panel-default">
						<!-- Start .panel -->
						<div class="panel-heading">
							<h4 class="panel-title"><?echo BOARD_TITLE;?> - 수정</h4>
						</div>
						<div class="panel-body">
							
							<form id="board-form-write-board" action="<?echo BOARD_FORM;?>" method="post" class="form-horizontal group-border stripped" role="form">
							<input type="hidden" name="action_type" id="action_type" value="edit">
							<input type="hidden" name="contents_no" id="contents_no" value="<?echo $data['no'];?>">
							<input type="hidden" name="parameters" id="parameters" value="<?echo urlencode($parameters);?>">
								
								<div class="form-group">
									<label for="subject" class="col-lg-2 col-md-3 control-label">제목</label>
									<div class="col-lg-10 col-md-9">
										<input id="subject" name="subject" type="text" class="form-control required" value="<?echo $data['subject'];?>">
									</div>
								</div>
								<div class="form-group">
									<label for="contents" class="col-lg-2 col-md-3 control-label">내용</label>
									<div class="col-lg-10 col-md-9">
										<textarea id="contents" name="contents" class="form-control" rows="20"><?echo $data['contents'];?></textarea>
									</div>
								</div>
								<div class="form-group">
									<label for="" class="col-lg-2 col-md-3 control-label"> </label>
									<div class="col-lg-10 col-md-9">
										<div class="checkbox-custom checkbox-inline">
											<input type="checkbox" id="is_notice" name="is_notice" <?echo $data['is_notice']==0?'checked':''; ?>>
											<label for="is_notice">공지</label>
										</div>
									</div>
								</div>
								<!-- End .form-group  -->
								
								<div class="panel-body pull-left">
									<button type="button" class="btn btn-info btn-alt mr5 mb10" onclick="location.href='<?echo $list_url?>';">리스트</button>
								</div>
								<div class="panel-body pull-right">
									<button type="submit" class="btn btn-primary btn-alt mr5 mb10">수정</button>
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
<script src="<?echo $this->config->base_url()?>daumeditor/js/editor_loader.js" type="text/javascript" charset="utf-8"></script>
<script src="<?echo $this->config->base_url()?>daumeditor/js/editor_creator.js" type="text/javascript" charset="utf-8"></script>
<script src="<?echo $this->config->base_url()?>html/js/sw/sw_board.js"></script>
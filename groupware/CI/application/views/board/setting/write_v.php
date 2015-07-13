<!-- .page-content -->
<div class="page-content sidebar-page clearfix">
	<!-- .page-content-wrapper -->
	<div class="page-content-wrapper">
		<div class="page-content-inner">
			<!-- .page-content-inner -->
			<div id="page-header" class="clearfix">
				<div class="page-header">
					<h2>게시판 설정</h2>
					<span class="txt">게시판 설정</span>
				</div>
			</div>
			<div class="row">
				<!-- col-lg-12 end here -->
				<div class="col-lg-12">
					<!-- col-lg-12 start here -->
					<div class="panel panel-default">
						<!-- Start .panel -->
						<div class="panel-heading">
							<h4 class="panel-title"><?echo ($data['name'] ? $data['name']:'게시판 생성');?></h4>
						</div>
						<div class="panel-body">

							<form id="board-form-write-setting" action="<?echo $action_url;?>" method="post" class="form-horizontal group-border stripped" role="form">
							<input type="hidden" name="action_type" id="action_type" value="<?echo $action_type;?>">
							<input type="hidden" name="board_no" id="board_no" value="<?echo $data['no'];?>">

								<div class="form-group">
									<label for="board_code" class="col-lg-2 col-md-3 control-label">코드</label>
									<div class="col-lg-10 col-md-9">
										<input id="board_code" name="board_code" type="text" class="form-control" placeholder="게시판 코드 (영문)" value="<?echo $data['code'];?>" maxlength="20" <?echo $action_type=='edit'?'readonly':''; ?>>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 col-md-3 control-label" for="board_type">타입</label>
									<div class="col-lg-10 col-md-9">
										<select id="board_type" name="board_type" class="fancy-select form-control">
											<option value="default" <?echo $data['type']=='default'?'selected':''; ?>>default</option>
											<option value="gallery" <?echo $data['type']=='gallery'?'selected':''; ?>>gallery</option>
										</select>
									</div>
								</div>
								<!-- End .form-group  -->
								<div class="form-group">
									<label for="board_name" class="col-lg-2 col-md-3 control-label">이름</label>
									<div class="col-lg-10 col-md-9">
										<input id="board_name" name="board_name" type="text" class="form-control"  placeholder="게시판 이름" value="<?echo $data['name'];?>" maxlength="20">
									</div>
								</div>
								<!-- End .form-group  -->
								<div class="form-group">
									<label for="" class="col-lg-2 col-md-3 control-label">설정</label>
									<div class="col-lg-10 col-md-9">
										<div class="checkbox-custom checkbox-inline">
											<input type="checkbox" id="board_reply" name="board_reply" <?echo ($data['reply'] == 0 ? 'checked':'')?>>
											<label for="board_reply">답변 사용</label>
										</div>
										<div class="checkbox-custom checkbox-inline">
											<input type="checkbox" id="board_comment" name="board_comment" <?echo ($data['comment'] == 0 ? 'checked':'')?>>
											<label for="board_comment">댓글 사용</label>
										</div>
									</div>
								</div>
								<!-- End .form-group  -->
								<div class="form-group">
									<label for="board_order" class="col-lg-2 col-md-3 control-label">순서</label>
									<div class="col-lg-10 col-md-9">
										<input id="board_order" name="board_order" type="text" class="form-control input-mini"  placeholder="순서" value="<?echo $data['order'];?>" maxlength="3">
									</div>
								</div>
								<!-- End .form-group  -->
								
								
								<!-- End .form-group  -->
							
								<div class="panel-body pull-left">
									<button type="button" class="btn btn-info btn-alt mr5 mb10" onclick="location.href='<?echo site_url('board_setting/lists')?>';">리스트</button>
								</div>
								<div class="panel-body pull-right">
									<button id="contents_setting_delete" type="button" class="btn btn-danger btn-alt mr5 mb10">삭제</button>
									<button type="submit" class="btn btn-primary btn-alt mr5 mb10">등록</button>
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



<script src="<?echo $this->config->base_url()?>html/plugins/forms/bootstrap-filestyle/bootstrap-filestyle.js"></script>
<script src="<?echo $this->config->base_url()?>html/plugins/forms/select2/select2.js"></script>
<script src="<?echo $this->config->base_url()?>html/plugins/forms/validation/jquery.validate.js"></script>
<script src="<?echo $this->config->base_url()?>html/plugins/forms/validation/additional-methods.min.js"></script>
<script src="<?echo $this->config->base_url()?>html/plugins/charts/sparklines/jquery.sparkline.js"></script>

<script src="<?echo $this->config->base_url()?>html/plugins/forms/checkall/jquery.checkAll.js"></script>

<script src="<?echo $this->config->base_url()?>html/js/sw/sw_board.js"></script>
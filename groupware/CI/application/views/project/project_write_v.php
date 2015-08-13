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
							<h4 class="panel-title"><i class="fa fa-circle"></i> 업무등록</h4>
						</div>
						<div class="panel-body">

							<form id="board-form-write-setting" action="<?echo site_url('project/proc');?>" method="post" class="form-horizontal group-border stripped" role="form">
							<input type="hidden" name="action_type" id="action_type" value="<?echo $action_type;?>">
							<input type="hidden" name="no" id="no" value="<?echo $data['no'];?>">

								<div class="form-group">
									<label for="board_code" class="col-lg-2 col-md-3 control-label">기안부서</label>
									<div class="col-lg-10 col-md-9">
										<select id="board_type" name="board_type" class="fancy-select form-control">
											<option value="default" <?echo $data['menu_part_no']=='default'?'selected':''; ?>>기안부서</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 col-md-3 control-label">기안자</label>
									<div class="col-lg-10 col-md-9" style="line-height:250%;">
										<?php echo $this->session->userdata('name');?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 col-md-3 control-label">기안일자</label>
									<div class="col-lg-10 col-md-9" style="line-height:250%;">
										<?php echo date('Y-m-d');?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 col-md-3 control-label" for="board_type">분류</label>
									<div class="col-lg-10 col-md-9">
										<select id="board_type" name="board_type" class="fancy-select form-control">
											<option value="default" <?echo $data['menu_no']=='default'?'selected':''; ?>>분류</option>
										</select>
									</div>
								</div>
								<!-- End .form-group  -->
								<div class="form-group">
									<label for="board_name" class="col-lg-2 col-md-3 control-label">제목</label>
									<div class="col-lg-10 col-md-9">
										<input id="board_name" name="board_name" type="text" class="form-control"  placeholder="제목" value="<?echo $data['title'];?>" maxlength="20">
									</div>
								</div>
								<!-- End .form-group  -->
								<div class="form-group">
									<label for="" class="col-lg-2 col-md-3 control-label">진행기간</label>
									<div class="col-lg-10 col-md-9">
										<div class="col-lg-8 col-md-8 row">
											<div class="input-group col-xs-11">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												<input type="text" class="form-control" name="start" />
												<span class="input-group-addon">to</span>
												<input type="text" class="form-control" name="end" />
											</div>
										</div>
										
										<div class="col-lg-4 col-md-4 row">
											<button type="button" class="btn btn-sm btn-primary btn-alt">오늘</button> 
											<button type="button" class="btn btn-sm btn-primary btn-alt">7일</button> 
											<button type="button" class="btn btn-sm btn-primary btn-alt">30일</button> 
											<button type="button" class="btn btn-sm btn-primary btn-alt">날짜초기화</button> 
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="board_order" class="col-lg-2 col-md-3 control-label">+결재점수</label>
									<div class="col-lg-10 col-md-9">
										<input id="board_order" name="board_order" type="text" class="form-control input-mini"  placeholder="결재점수" value="<?echo $data['pPoint'];?>" maxlength="3">
									</div>
								</div>
								<div class="form-group">
									<label for="board_order" class="col-lg-2 col-md-3 control-label">-누락점수</label>
									<div class="col-lg-10 col-md-9">
										<input id="board_order" name="board_order" type="text" class="form-control input-mini"  placeholder="누락점수" value="<?echo $data['mPoint'];?>" maxlength="3">
									</div>
								</div>
								<div class="form-group">
									<label for="board_name" class="col-lg-2 col-md-3 control-label">서식선택</label>
									<div class="col-lg-10 col-md-9">
										
										<div class="col-lg-10 col-md-10 row">
											<div class="input-group col-xs-11">
												<input id="board_name" name="board_name" type="text" class="form-control"  placeholder="제목" value="" maxlength="20">
											</div>
										</div>
										
										<div class="col-lg-2 col-md-2 row">
											<button type="button" class="btn btn-sm btn-primary btn-alt">선택</button> 
										</div>

									</div>
								</div>
								<div class="form-group">
									<div class="col-xs-12">
										<textarea class="form-control" rows="20"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 col-md-3 control-label" for="">첨부파일</label>
									<div class="col-lg-10 col-md-9">
										<input type="file" id="userfile" name="userfile" class="filestyle" data-buttonText="Find file" data-buttonName="btn-danger" data-iconName="fa fa-plus">
									</div>
								</div>
								<div class="form-group">
									<label for="board_order" class="col-lg-2 col-md-3 control-label">순서</label>
									<div class="col-lg-10 col-md-9">
										<input id="board_order" name="board_order" type="text" class="form-control input-mini"  placeholder="순서" value="<?echo $data['order'];?>" maxlength="3">
									</div>
								</div>
								<!-- End .form-group  -->
								
								
								<!-- End .form-group  -->
							
								<div class="panel-body pull-left">
									<button type="button" class="btn btn-info btn-alt mr5 mb10" onclick="location.href='<?echo $list_url?>';">리스트</button>
								</div>
								<div class="panel-body pull-right">
									<?if( $action_type == 'edit' ) {?>
									<button id="contents_setting_delete" type="button" class="btn btn-danger btn-alt mr5 mb10">삭제</button>
									<?}?>
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
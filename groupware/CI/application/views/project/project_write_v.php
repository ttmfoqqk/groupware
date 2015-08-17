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

							<form id="project-form-write-setting" action="<?echo $action_url;?>" method="post" class="form-horizontal group-border stripped" role="form">
							<input type="hidden" name="action_type" id="action_type" value="<?echo $action_type;?>">
							<input type="hidden" name="no" id="no" value="<?echo $data['no'];?>">
							<input type="hidden" name="parameters" id="parameters" value="<?echo $parameters;?>">

								<div class="form-group">
									<label for="menu_part_no" class="col-lg-2 col-md-3 control-label">기안부서</label>
									<div class="col-lg-10 col-md-9">
										<select id="menu_part_no" name="menu_part_no" data-method="department" data-value="<?echo $data['menu_part_no'];?>" class="fancy-select form-control">
											<option value="">담당부서</option>
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
									<label class="col-lg-2 col-md-3 control-label" for="menu_no">분류</label>
									<div class="col-lg-10 col-md-9">
										<select id="menu_no" name="menu_no" data-method="project" data-value="<?echo $this->input->get('menu_no');?>" class="fancy-select form-control">
											<option value="">분류</option>
										</select>
									</div>
								</div>
								<!-- End .form-group  -->
								<div class="form-group">
									<label for="title" class="col-lg-2 col-md-3 control-label">제목</label>
									<div class="col-lg-10 col-md-9">
										<input id="title" name="title" type="text" class="form-control"  placeholder="제목" value="<?echo $data['title'];?>" maxlength="20">
									</div>
								</div>
								<!-- End .form-group  -->
								<div class="form-group">
									<label for="" class="col-lg-2 col-md-3 control-label">진행기간</label>
									<div class="col-lg-10 col-md-9">
										<div class="col-lg-8 col-md-8 row">
											<div class="input-group col-xs-11 input-daterange">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
												<input type="text" class="form-control" id="sData" name="sData" value="<?echo $data['sData'];?>"/>
												<span class="input-group-addon">to</span>
												<input type="text" class="form-control" id="eData" name="eData" value="<?echo $data['eData'];?>"/>
											</div>
										</div>
										
										<div class="col-lg-4 col-md-4 row">
											<button type="button" class="btn btn-sm btn-primary btn-alt" id="WsToday">오늘</button>
											<button type="button" class="btn btn-sm btn-primary btn-alt" id="WsWeek">7일</button>
											<button type="button" class="btn btn-sm btn-primary btn-alt" id="WsMonth">30일</button>
											<button type="button" class="btn btn-sm btn-primary btn-alt" id="WsReset">날짜초기화</button>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="pPoint" class="col-lg-2 col-md-3 control-label">+결재점수</label>
									<div class="col-lg-10 col-md-9">
										<input id="pPoint" name="pPoint" type="text" class="form-control input-mini"  placeholder="결재점수" value="<?echo $data['pPoint'];?>" maxlength="3">
									</div>
								</div>
								<div class="form-group">
									<label for="mPoint" class="col-lg-2 col-md-3 control-label">-누락점수</label>
									<div class="col-lg-10 col-md-9">
										<input id="mPoint" name="mPoint" type="text" class="form-control input-mini"  placeholder="누락점수" value="<?echo $data['mPoint'];?>" maxlength="3">
									</div>
								</div>
								<div class="form-group">
									<label for="board_name" class="col-lg-2 col-md-3 control-label">서식선택</label>
									<div class="col-lg-10 col-md-9">
										
										<div class="col-lg-10 col-md-10 row">
											<div class="input-group col-xs-11">
												<input id="document_input" name="document_input" type="text" class="form-control"  placeholder="서식선택" value="" readonly>
											</div>
										</div>
										
										<div class="col-lg-2 col-md-2 row">
											<button type="button" class="btn btn-sm btn-primary btn-alt">선택</button> 
										</div>

									</div>
								</div>
								<div class="form-group">
									<div class="col-xs-12">
										<textarea id="contents" name="contents" class="form-control" rows="20"><?echo $data['contents']?></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 col-md-3 control-label" for="userfile">첨부파일</label>
									<div class="col-lg-10 col-md-9">
										<input type="file" id="userfile" name="userfile" class="filestyle" data-buttonText="Find file" data-buttonName="btn-danger" data-iconName="fa fa-plus">
									</div>
								</div>
								<div class="form-group">
									<label for="order" class="col-lg-2 col-md-3 control-label">순서</label>
									<div class="col-lg-10 col-md-9">
										<input id="order" name="order" type="text" class="form-control input-mini"  placeholder="순서" value="<?echo $data['order'];?>" maxlength="3">
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


<script type="text/javascript">
$(function(){
    $('#menu_part_no').create_menu({
		method : 'department',
		value  : '<?echo $data["menu_part_no"]?>'
	});
	$('#menu_no').create_menu({
		method : 'project',
		value  : '<?echo $data["menu_no"]?>'
	});
});
</script>
<script src="<?echo $this->config->base_url()?>html/plugins/forms/bootstrap-filestyle/bootstrap-filestyle.js"></script>
<script src="<?echo $this->config->base_url()?>html/plugins/forms/select2/select2.js"></script>
<script src="<?echo $this->config->base_url()?>html/plugins/forms/validation/jquery.validate.js"></script>
<script src="<?echo $this->config->base_url()?>html/plugins/forms/validation/additional-methods.min.js"></script>
<script src="<?echo $this->config->base_url()?>html/plugins/charts/sparklines/jquery.sparkline.js"></script>
<script src="<?echo $this->config->base_url()?>html/js/sw/sw_project.js"></script>
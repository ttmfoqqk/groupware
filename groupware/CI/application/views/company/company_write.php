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
							<h4 class="panel-title">회사 관리</h4>
						</div>
						<div class="panel-body">

							<form id="company-form-write-setting" action="<?echo $action_url;?>" method="post" class="form-horizontal group-border stripped" role="form">
							<input type="hidden" name="action_type" id="action_type" value="<?echo $action_type;?>">
							<input type="hidden" name="company_no" id="company_no" value="<?echo $data['no'];?>">

								<div class="form-group">
									<label for="biz_name" class="col-lg-2 col-md-3 control-label"><?php echo $this->lang->line("bizName");?></label>
									<div class="col-lg-10 col-md-9">
										<input id="biz_name" name="biz_name" type="text" class="form-control" placeholder="" value="<?echo $data['bizName'];?>" maxlength="20" <?echo $action_type=='edit'?'readonly':''; ?>>
									</div>
								</div>
								<!-- End .form-group  -->
								<div class="form-group">
									<label for="ceo_name" class="col-lg-2 col-md-3 control-label"><?php echo $this->lang->line("ceoName");?></label>
									<div class="col-lg-10 col-md-9">
										<input id="ceo_name" name="ceo_name" type="text" class="form-control" placeholder="" value="<?echo $data['ceoName'];?>" maxlength="20" <?echo $action_type=='edit'?'readonly':''; ?>>
									</div>
								</div>
								<!-- End .form-group  -->
								<div class="form-group">
									<label for="bizNumber" class="col-lg-2 col-md-3 control-label"><?php echo $this->lang->line("bizNumber");?></label>
									<div class="col-lg-10 col-md-9">
										<input id="bizNumber" name="bizNumber" type="text" class="form-control"  placeholder="" value="<?echo $data['bizNumber'];?>" maxlength="20">
									</div>
								</div>
								<!-- End .form-group  -->
								<div class="form-group">
									<label for="classify" class="col-lg-2 col-md-3 control-label"><?php echo $this->lang->line("classify");?></label>
									<div class="col-lg-10 col-md-9">
										<input id="classify" name="classify" type="text" class="form-control"  placeholder="" value="<?echo $data['gubun'];?>" maxlength="20">
									</div>
								</div>
								<!-- End .form-group  -->
								<div class="form-group">
									<label for="bizType" class="col-lg-2 col-md-3 control-label"><?php echo $this->lang->line("bizType");?></label>
									<div class="col-lg-10 col-md-9">
										<input id="bizType" name="bizType" type="text" class="form-control"  placeholder="" value="<?echo $data['bizType'];?>" maxlength="20">
									</div>
								</div>
								<!-- End .form-group  -->
								<div class="form-group">
									<label for="bizCondition" class="col-lg-2 col-md-3 control-label"><?php echo $this->lang->line("bizCondition");?></label>
									<div class="col-lg-10 col-md-9">
										<input id="bizCondition" name="bizCondition" type="text" class="form-control"  placeholder="" value="<?echo $data['bizCondition'];?>" maxlength="20">
									</div>
								</div>
								<!-- End .form-group  -->
								<div class="form-group">
									<label for="addr" class="col-lg-2 col-md-3 control-label"><?php echo $this->lang->line("addr");?></label>
									<div class="col-lg-10 col-md-9">
										<input id="addr" name="addr" type="text" class="form-control"  placeholder="" value="<?echo $data['addr'];?>" maxlength="20">
									</div>
								</div>
								<!-- End .form-group  -->
								<div class="form-group">
									<label for="phone" class="col-lg-2 col-md-3 control-label"><?php echo $this->lang->line("phone");?></label>
									<div class="col-lg-10 col-md-9">
										<input id="phone" name="phone" type="text" class="form-control"  placeholder="" value="<?echo $data['phone'];?>" maxlength="20">
									</div>
								</div>
								<!-- End .form-group  -->
								<div class="form-group">
									<label for="fax" class="col-lg-2 col-md-3 control-label"><?php echo $this->lang->line("fax");?></label>
									<div class="col-lg-10 col-md-9">
										<input id="fax" name="fax" type="text" class="form-control"  placeholder="" value="<?echo $data['fax'];?>" maxlength="20">
									</div>
								</div>
								<!-- End .form-group  -->
								<div class="form-group">
									<label for="note" class="col-lg-2 col-md-3 control-label"><?php echo $this->lang->line("note");?></label>
									<div class="col-lg-10 col-md-9">
										<input id="note" name="note" type="text" class="form-control"  placeholder="" value="<?echo $data['bigo'];?>" maxlength="20">
									</div>
								</div>
								<!-- End .form-group  -->
								<div class="form-group">
									<label for="order" class="col-lg-2 col-md-3 control-label"><?php echo $this->lang->line("order");?></label>
									<div class="col-lg-10 col-md-9">
										<input id="order" name="board_order" type="text" class="form-control input-mini valid"  placeholder="순서" value="<?echo $data['order'];?>" maxlength="3">
									</div>
								</div>
								<!-- End .form-group  -->
								
							
								<div class="panel-body pull-left">
									<button type="button" class="btn btn-info btn-alt mr5 mb10" onclick="location.href='<?echo site_url('company')?>';">리스트</button>
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

<script src="<?echo $this->config->base_url()?>html/js/sw/sw_company.js"></script>
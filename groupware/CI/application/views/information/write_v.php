<!-- .page-content -->
<div class="page-content sidebar-page clearfix">
	<!-- .page-content-wrapper -->
	<div class="page-content-wrapper">
		<div class="page-content-inner">
			<!-- .page-content-inner -->
			<div id="page-header" class="clearfix">
				<div class="page-header">
					<h2><?php echo PAGE_TITLE;?></h2>
					<span class="txt"><?php echo PAGE_TITLE?></span>
				</div>
			</div>
			<div class="row">
				<!-- col-lg-12 end here -->
				<div class="col-lg-12">
					<!-- col-lg-12 start here -->
					<div class="panel panel-default">
						<!-- Start .panel -->
						<div class="panel-heading">
							<h4 class="panel-title"><?php echo PAGE_TITLE;?></h4>
						</div>
						<div class="panel-body">

							<form id="company-form-write-setting" action="<?echo PAGE_FORM;?>" method="post" class="form-horizontal group-border stripped" role="form">
							<input type="hidden" name="action_type" id="action_type" value="<?echo $action_type;?>">
							<input type="hidden" name="no" id="no" value="<?echo $data['no'];?>">
							<input type="hidden" name="parameters" id="parameters" value="<?echo $parameters;?>">

								<div class="form-group">
									<label for="biz_name" class="col-lg-2 col-md-3 control-label"><font class="red">* </font> 상호명</label>
									<div class="col-lg-10 col-md-9">
										<input id="bizName" name="bizName" type="text" class="form-control" placeholder="" value="<?echo $data['bizName'];?>" maxlength="20" <?echo $action_type=='edit'?'readonly':''; ?>>
									</div>
								</div>
								<!-- End .form-group  -->
								<div class="form-group">
									<label for="ceo_name" class="col-lg-2 col-md-3 control-label">대표자명</label>
									<div class="col-lg-10 col-md-9">
										<input id="ceoName" name="ceoName" type="text" class="form-control" placeholder="" value="<?echo $data['ceoName'];?>" maxlength="20">
									</div>
								</div>
								<!-- End .form-group  -->
								<div class="form-group">
									<label for="bizNumber" class="col-lg-2 col-md-3 control-label">사업자 번호</label>
									<div class="col-lg-10 col-md-9">
										<input id="bizNumber" name="bizNumber" type="text" class="form-control"  placeholder="" value="<?echo $data['bizNumber'];?>" maxlength="20">
									</div>
								</div>
								<!-- End .form-group  -->
								<div class="form-group">
									<label for="classify" class="col-lg-2 col-md-3 control-label"><font class="red">* </font>구분</label>
									<div class="col-lg-10 col-md-9">
										<input id="gubun" name="gubun" type="text" class="form-control"  placeholder="" value="<?echo $data['gubun'];?>" maxlength="20">
									</div>
								</div>
								<!-- End .form-group  -->
								<div class="form-group">
									<label for="bizType" class="col-lg-2 col-md-3 control-label">업종</label>
									<div class="col-lg-10 col-md-9">
										<input id="bizType" name="bizType" type="text" class="form-control"  placeholder="" value="<?echo $data['bizType'];?>" maxlength="20">
									</div>
								</div>
								<!-- End .form-group  -->
								<div class="form-group">
									<label for="bizCondition" class="col-lg-2 col-md-3 control-label">업태</label>
									<div class="col-lg-10 col-md-9">
										<input id="bizCondition" name="bizCondition" type="text" class="form-control"  placeholder="" value="<?echo $data['bizCondition'];?>" maxlength="20">
									</div>
								</div>
								<!-- End .form-group  -->
								<div class="form-group">
									<label for="addr" class="col-lg-2 col-md-3 control-label">주소</label>
									<div class="col-lg-10 col-md-9">
										<input id="addr" name="addr" type="text" class="form-control"  placeholder="" value="<?echo $data['addr'];?>" maxlength="20">
									</div>
								</div>
								<!-- End .form-group  -->
								<div class="form-group">
									<label for="phone" class="col-lg-2 col-md-3 control-label"><font class="red">* </font>전화번호</label>
									<div class="col-lg-10 col-md-9">
										<input id="phone" name="phone" type="text" class="form-control"  placeholder="" value="<?echo $data['phone'];?>" maxlength="20">
									</div>
								</div>
								<!-- End .form-group  -->
								<div class="form-group">
									<label for="fax" class="col-lg-2 col-md-3 control-label">팩스번호</label>
									<div class="col-lg-10 col-md-9">
										<input id="fax" name="fax" type="text" class="form-control"  placeholder="" value="<?echo $data['fax'];?>" maxlength="20">
									</div>
								</div>
								<!-- End .form-group  -->
								<div class="form-group">
									<label for="note" class="col-lg-2 col-md-3 control-label">비고</label>
									<div class="col-lg-10 col-md-9">
										<input id="bigo" name="bigo" type="text" class="form-control"  placeholder="" value="<?echo $data['bigo'];?>" maxlength="20">
									</div>
								</div>
								<!-- End .form-group  -->
								<div class="form-group">
									<label for="order" class="col-lg-2 col-md-3 control-label">정렬</label>
									<div class="col-lg-10 col-md-9">
										<input id="order" name="order" type="text" class="form-control input-mini valid"  placeholder="순서" value="<?echo $data['order'];?>" maxlength="3">
									</div>
								</div>
								<!-- End .form-group  -->
								
							
								<div class="panel-body pull-left">
									<button type="button" class="btn btn-info btn-alt mr5 mb10" onclick="location.href='<?echo $list_url;?>';">리스트</button>
								</div>
								<div class="panel-body pull-right">
									<button type="submit" class="btn btn-primary btn-alt mr5 mb10">등록</button>
									<button id="contents_setting_delete" type="button" class="btn btn-danger btn-alt mr5 mb10">삭제</button>
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



<script src="<?echo $this->config->base_url()?>html/js/sw/sw_information.js"></script>
<!-- .page-content -->
<div class="page-content sidebar-page clearfix">
	<!-- .page-content-wrapper -->
	<div class="page-content-wrapper">
		<div class="page-content-inner">
			<!-- .page-content-inner -->
			<div id="page-header" class="clearfix">
				<div class="page-header">
					<h2><?php echo $head_name?></h2>
				</div>
			</div>
			<div class="row">
				<!-- col-lg-12 end here -->
				<div class="col-lg-12">
					<!-- col-lg-12 start here -->
					<div class="panel panel-default">
						<!-- Start .panel -->
						<div class="panel-heading">
							<h4 class="panel-title">서식 등록</h4>
						</div>
						
						<!-- Start .panel-body -->
						<div class="panel-body">
							<!-- top form(document-form-write-setting) start  -->
							<form id="document-form-write-setting" action="<?echo $action_url;?>" method="post" class="form-horizontal group-border stripped" role="form" enctype="multipart/form-data">
								<input type="hidden" name="action_type" id="action_type" value="<?echo $action_type;?>">
								<input type="hidden" name="no" id="no" value="<?echo $data['no'];?>">
								<div class="form-group">
									<label for="sel_phone" class="col-lg-2 col-md-2 control-label lb-left-align"> 등록자</label>
									<div class="col-lg-10 col-md-10">
										<label for="sel_phone" class="col-lg-2 col-md-2 control-label lb-left-align"> <?php echo $this->session->userdata('name');?></label>
									</div>
								</div>
								<div class="form-group">
									<label for="sel_phone" class="col-lg-2 col-md-2 control-label lb-left-align"> 등록일자</label>
									<div class="col-lg-10 col-md-10">
										<label for="sel_phone" class="col-lg-2 col-md-2 control-label lb-left-align"> <?php echo date("Y-m-d");?></label>
									</div>
								</div>
								<div class="form-group">
									<label for="document" class="col-lg-2 col-md-2 control-label lb-left-align"><font class="red">* </font> 분류</label>
									<div class="col-lg-10 col-md-10">
										<select class="fancy-select form-control" id="document" name="document" data-method="document" data-value="<?php echo $data['menu_no'];?>">
                                        	<option value="">분류</option>
                                        </select>
									</div>
								</div>
								<div class="form-group">
									<label for="name" class="col-lg-2 col-md-2 control-label lb-left-align"><font class="red">* </font> 서식명</label>
									<div class="col-lg-10 col-md-10">
										<input id="name" name="name" type="text" class="form-control" placeholder=""  maxlength="20" value=<?php echo $data['name']?>>
									</div>
								</div>
								<div class="form-group">
									<div class="col-xs-12">
										<textarea id="contents" name="contents" class="form-control" rows="20"> <?php echo $data['contents']?></textarea>
									</div>
								</div>
								<div class="form-group">
									<label for="adt_annual" class="col-lg-2 col-md-2 control-label lb-left-align">첨부파일</label>
									<div class="col-lg-10 col-md-10">
										<input type="file" id="userfile" name="userfile" class="filestyle" data-buttonText="찾기" data-buttonName="btn-danger" data-iconName="fa fa-plus">
										<a href=<?php echo site_url('/upload/document/') . '/' . $data['file']?>><?php echo $data['origin_file'];?></a>
									</div>
								</div>
								<div class="form-group">
									<label for="order" class="col-lg-2 col-md-2 control-label lb-left-align">순서</label>
									<div class="col-lg-10 col-md-10">
										<input id="order" name="order" type="text" class="form-control" placeholder=""  maxlength="20" value=<?php echo $data['order']?>>
									</div>
								</div>
								<div class="form-group">
									<label  class="col-lg-2 col-md-2 control-label lb-left-align">사용여부</label>
									<div class="col-lg-3 col-md-3">
										<div class="radio-custom radio-inline">
                                        	<input type="radio" name=is_active value=0 <?=$data['is_active'] == '0' ? ' checked="checked"' : '';?>  id="in_active" checked="checked">
                                        	<label for="in_active">사용</label>
                                        </div>
                                        <div class="radio-custom radio-inline">
                                        	<input type="radio" name="is_active" value=1 <?=$data['is_active'] == '1' ? ' checked="checked"' : '';?> id="out_active">
                                        	<label for="out_active">미사용</label>
                                        </div>
									</div>
								</div>
								
								<div class="panel-body pull-left">
									<button type="button" class="btn btn-info btn-alt mr5 mb10" onclick="location.href='<?echo site_url('document')?>';">리스트</button>
								</div>
								<div class="panel-body pull-right">
									<button type="submit" class="btn btn-primary btn-alt mr5 mb10">등록</button>
									<button id="contents_setting_delete" type="button" class="btn btn-danger btn-alt mr5 mb10">삭제</button>
								</div>
								
							</form>
							<!-- top form(member-form-write-setting) END  -->
							

						</div>
						<!-- End .panel-body -->
						

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
<script src="<?echo $this->config->base_url()?>html/js/sw/sw_document_write.js"></script>
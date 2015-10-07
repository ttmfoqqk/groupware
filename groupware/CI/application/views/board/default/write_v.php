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
							
							<form id="board_form_write_board" name="board_form_write_board" action="<?echo BOARD_FORM;?>" method="post" class="form-horizontal group-border stripped" enctype="multipart/form-data">
							<input type="hidden" name="action_type" id="action_type" value="<?php echo $action_type;?>">
							<input type="hidden" name="contents_no" id="contents_no" value="<?echo $data['no'];?>">
							<input type="hidden" name="original_no" id="original_no" value="<?echo $data['original_no'];?>">
							<input type="hidden" name="depth" id="depth" value="<?echo $data['depth'];?>">
							<input type="hidden" name="order" id="order" value="<?echo $data['order'];?>">
							<?
							if($action_type=='edit'){
								foreach($files as $lt){
							?><input type="hidden" name="oldFile" id="oldFile" value="<?echo $lt['upload_name'];?>">
							<?
								}
							}
							?>
							
							<input type="hidden" name="parameters" id="parameters" value="<?echo urlencode($parameters);?>">

								<div class="form-group">
									<label for="subject" class="col-lg-2 col-md-3 control-label">분류</label>
									<div class="col-lg-10 col-md-9">
										<select id="menu_no" name="menu_no" data-method="board" data-value="<?echo $data['menu_no'];?>" class="fancy-select form-control">
											<option value="">분류</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label for="subject" class="col-lg-2 col-md-3 control-label">제목</label>
									<div class="col-lg-10 col-md-9">
										<input id="subject" name="subject" type="text" class="form-control" maxlength="200" placeholder="제목" value="<?echo $data['subject'];?>">
									</div>
								</div>
								<div class="form-group">
									<label for="contents" class="col-lg-2 col-md-3 control-label">내용</label>
									<div class="col-lg-10 col-md-9" id="tx_trex_container">
										<textarea id="contents" name="contents" style="width:100%;height:500px;"><?echo $data['contents'];?></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 col-md-3 control-label" for="">첨부파일</label>
									<div class="col-lg-10 col-md-9">
										<input type="file" id="userfile" name="userfile" class="filestyle" data-buttonText="찾기" data-buttonName="btn-danger" data-iconName="fa fa-plus">
										<div>
										
										<br>
										<?
										if($action_type=='edit'){
											foreach($files as $lt){?>
											<a href="<?php echo site_url('download?path=upload/board/&oname='.$lt['original_name'].'&uname='.$lt['upload_name'])?>"><?php echo $lt['original_name'];?></a>
										<?
											}
										}
										?>
										</div>
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
									<button type="button" id="board-submit-button" class="btn btn-primary btn-alt mr5 mb10" >등록</button>
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
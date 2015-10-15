<!-- .page-content -->
<div class="page-content sidebar-page clearfix">
	<!-- .page-content-wrapper -->
	<div class="page-content-wrapper">
		<div class="page-content-inner">
			<!-- .page-content-inner -->
			<div id="page-header" class="clearfix">
				<div class="page-header">
					<h2>사원 관리</h2>
				</div>
			</div>
			<div class="row">
				<!-- col-lg-12 end here -->
				<div class="col-lg-12">
					<!-- col-lg-12 start here -->
					<div class="panel panel-default">
						<!-- Start .panel -->
						<div class="panel-heading">
							<h4 class="panel-title">사원 등록</h4>
						</div>
						
						<!-- Start .panel-body -->
						<div class="panel-body">
							
							<!-- top form(member-form-write-setting) start  -->
							<form id="member-form-write-setting" action="<?echo $action_url;?>" method="post" class="form-horizontal group-border stripped" role="form" enctype="multipart/form-data">
							<input type="hidden" name="action_type" id="action_type" value="<?echo $action_type;?>">
							<input type="hidden" name="no" id="no" value="<?echo $data['no'];?>">
							<input type="hidden" name="parameters" id="parameters" value="<?echo $parameters;?>">
								
								<div class="form-group">
									<!-- col-lg-2 start (profile picture) -->
									<div class="col-lg-2">
										<div class="row">
											<div class="col-lg-1"></div>
											<div class="col-lg-10">
												<a href="#" class="thumbnail">		
													<img id="user_pic" alt="" src="<?php if($data['file'] != '') echo $this->config->base_url() . 'upload/member/' . $data['file']?>" style="height: 200px;">
												</a>
												<input type="file" id="userfile" name="userfile" class="filestyle" data-buttonText="찾기" data-buttonName="btn-danger" data-iconName="fa fa-plus">
											</div>
											<div class="col-lg-1"></div>
										</div>
									</div>
									<!-- col-lg-2 end -->
									<!-- col-lg-10 start (id, pass, name, position)-->
									<div class="col-lg-10">
	                                    <div class="form-group">
	                                        <label class="col-lg-2 col-md-2 control-label lb-left-align" for=""><font class="red">* </font>아이디</label>
	                                        <div class="col-lg-10 col-md-10">
	                                            <input type="text" class="form-control" name="id" id="id" value=<?php echo $data['id']?> <?php if($action_type!="create") echo " disabled='disabled'";?>>
	                                        </div>
	                                    </div>
	                                    <div class="form-group">
	                                        <label class="col-lg-2 col-md-2 control-label lb-left-align" for=""><font class="red">* </font>비밀번호</label>
	                                        <div class="col-lg-10 col-md-10">
	                                            <input type="password" class="form-control" name="pass" id="pass">
	                                        </div>
	                                    </div>
	                                    <div class="form-group">
	                                        <label class="col-lg-2 col-md-2 control-label lb-left-align" for=""><font class="red">* </font>이름</label>
	                                        <div class="col-lg-10 col-md-10">
	                                            <input type="text" class="form-control lb-left-align" name="name" id="name" value=<?php echo $data['name']?>>
	                                        </div>
	                                    </div>
	                                    <div class="form-group">
	                                        <label class="col-lg-2 col-md-2 control-label lb-left-align" for=""><font class="red">* </font>직급</label>
	                                        <div class="col-lg-10 col-md-10">
	                                            <input type="text" class="form-control" name="position" id="position" value=<?php echo $data['position']?>>
	                                        </div>
	                                    </div>
									</div>
									<!-- col-lg-10 end -->
								</div>
								
								<div class="form-group">
									<label for="phone" class="col-lg-2 col-md-2 control-label lb-left-align">전화번호</label>
									<div class="col-lg-10 col-md-10">
										<input id="phone" name="phone" type="text" class="form-control" placeholder="" maxlength="20" value=<?php echo $data['phone']?>>
									</div>
								</div>
								<div class="form-group">
									<label for="sel_phone" class="col-lg-2 col-md-2 control-label lb-left-align"><font class="red">* </font> 핸드폰 번호</label>
									<div class="col-lg-10 col-md-10">
										<input id="mobile" name="mobile" type="text" class="form-control" placeholder=""  maxlength="20" value=<?php echo $data['mobile']?>>
									</div>
								</div>
								<div class="form-group">
									<label for="email" class="col-lg-2 col-md-2 control-label lb-left-align"><font class="red">* </font> 이메일</label>
									<div class="col-lg-10 col-md-10">
										<input id="email" name="email" type="text" class="form-control" placeholder=""  maxlength="20" value=<?php echo $data['email']?>>
									</div>
								</div>
								<div class="form-group">
									<label for="addr" class="col-lg-2 col-md-2 control-label lb-left-align">주소</label>
									<div class="col-lg-10 col-md-10">
										<input id="addr" name="addr" type="text" class="form-control" placeholder=""  maxlength="20" value=<?php echo $data['addr']?>>
									</div>
								</div>
								<div class="form-group">
									<label for="annual" class="col-lg-2 col-md-2 control-label lb-left-align"><font class="red">* </font> 연차</label>
									<div class="col-lg-10 col-md-10">
										<input id="annual" name="annual" type="text" class="form-control" placeholder="" maxlength="20" value=<?php echo $data['annual']?>>
									</div>
								</div>
								<div class="form-group">
									<label for="adt_annual" class="col-lg-2 col-md-2 control-label lb-left-align"><font class="red">* </font> 연차적용일</label>
									<div class="col-lg-4 col-md-4">
										<div class="input-daterange input-group">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<input type="text" class="form-control" name="sDate" id="sDate" value="<?php echo $data['sDate'];?>"/>
											<span class="input-group-addon">to</span>
											<input type="text" class="form-control" name="eDate" id="eDate" value="<?php echo $data['eDate'];?>"/>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="birthday" class="col-lg-2 col-md-2 control-label lb-left-align"><font class="red">* </font> 생년월일</label>
									<div class="col-lg-2 col-md-2">
										<div class="input-daterange input-group">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<input type="text" class="form-control" name="birth" id="birth" value="<?php echo $data['birth'];?>"/>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="join_date" class="col-lg-2 col-md-2 control-label lb-left-align"><font class="red">* </font> 입사일</label>
									<div class="col-lg-2 col-md-2">
										<div class="input-daterange input-group">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<input type="text" class="form-control" name="inDate" id="inDate" value="<?php echo $data['inDate'];?>"/>
										</div>
									</div>
									<div class="col-lg-2 col-md-2">
										<div class="radio-custom radio-inline">
                                        	<input type="radio" name="gender" value="0" <?=$data['gender'] == '0' ? 'checked' : '';?> id="radio4" checked="checked">
                                        	<label for="radio4">남자</label>
                                        </div>
                                        <div class="radio-custom radio-inline">
                                        	<input type="radio" name="gender" value="1" <?=$data['gender'] == '1' ? 'checked' : '';?> id="radio5">
                                        	<label for="radio5">여자</label>
                                        </div>
									</div>
									
								</div>
								<div class="form-group">
									<label for="color" class="col-lg-2 col-md-2 control-label lb-left-align"><font class="red">* </font> 색상</label>
									<div class="col-lg-2 col-md-2">
										<div id="component-colorpicker" class="input-group colorpicker-element">
											<span class="input-group-addon"><i style="background-color: rgb(89, 161, 101);"></i></span>
											<input type="text"  class="form-control" id="color" name="color" value="<?php echo $data['color'];?>">
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="order" class="col-lg-2 col-md-2 control-label lb-left-align">순서</label>
									<div class="col-lg-10 col-md-10">
										<input id="order" name="order" type="text" class="form-control" maxlength="20" value=<?php echo $data['order']?>>
									</div>
								</div>
								<div class="form-group">
									<label for="in_office" class="col-lg-2 col-md-2 control-label lb-left-align">재직여부</label>
									<div class="col-lg-2 col-md-2">
										<div class="radio-custom radio-inline">
                                        	<input type="radio" name="is_active" value="0" <?=$data['is_active'] == '0' ? 'checked' : '';?> id="in_office" checked="checked">
                                        	<label for="in_office">재직</label>
                                        </div>
                                        <div class="radio-custom radio-inline">
                                        	<input type="radio" name="is_active" value="1" <?=$data['is_active'] == '1' ? 'checked' : '';?> id="out_office">
                                        	<label for="out_office">퇴사</label>
                                        </div>
									</div>
								</div>
								
								<div class="panel-body pull-left">
									<button type="button" class="btn btn-info btn-alt mr5 mb10" onclick="location.href='<?echo $list_url?>';">리스트</button>
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

<script src="<?echo $this->config->base_url()?>html/plugins/forms/bootstrap-colorpicker/bootstrap-colorpicker.js"></script>
<script src="<?echo $this->config->base_url()?>html/js/sw/sw_member_write.js"></script>
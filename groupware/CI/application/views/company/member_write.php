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
							<h4 class="panel-title">사원 등록</h4>
						</div>
						
						<!-- Start .panel-body -->
						<div class="panel-body">
							<!-- top form(member-form-write-setting) start  -->
							<form id="member-form-write-setting" action="<?echo $action_url;?>" method="post" class="form-horizontal group-border stripped" role="form">
								<div class="form-group">
									<!-- col-lg-2 start (profile picture) -->
									<div class="col-lg-2">
										<div class="row">
											<div class="col-lg-1"></div>
											<div class="col-lg-10">
												<a href="#" class="thumbnail">
													<img id="user_pic" alt="" src="" style="height: 200px;">
													<!--<img src="http://localhost:85/groupware/html/img/avatars/128.jpg" alt="avatar">-->
												</a>
												<input type="file" id="userfile" name="userfile" class="filestyle" data-buttonText="Find file" data-buttonName="btn-danger" data-iconName="fa fa-plus">
												<!--<div class="btn btn-info btn-alt" id="bt_add_image">등록</div>-->
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
	                                            <input type="text" class="form-control" name="id" id="id">
	                                        </div>
	                                    </div>
	                                    <div class="form-group">
	                                        <label class="col-lg-2 col-md-2 control-label lb-left-align" for=""><font class="red">* </font>비밀번호</label>
	                                        <div class="col-lg-10 col-md-10">
	                                            <input type="text" class="form-control" name="pass" id="pass">
	                                        </div>
	                                    </div>
	                                    <div class="form-group">
	                                        <label class="col-lg-2 col-md-2 control-label lb-left-align" for=""><font class="red">* </font>이름</label>
	                                        <div class="col-lg-10 col-md-10">
	                                            <input type="text" class="form-control lb-left-align" name="name" id="name">
	                                        </div>
	                                    </div>
	                                    <div class="form-group">
	                                        <label class="col-lg-2 col-md-2 control-label lb-left-align" for=""><font class="red">* </font>직급</label>
	                                        <div class="col-lg-10 col-md-10">
	                                            <input type="text" class="form-control" name="positon" id="positon">
	                                        </div>
	                                    </div>
									</div>
									<!-- col-lg-10 end -->
								</div>
								<div class="form-group">
									<label for="phone" class="col-lg-2 col-md-2 control-label lb-left-align">전화번호</label>
									<div class="col-lg-10 col-md-10">
										<input id="phone" name="phone" type="text" class="form-control" placeholder="" value="" maxlength="20">
									</div>
								</div>
								<div class="form-group">
									<label for="sel_phone" class="col-lg-2 col-md-2 control-label lb-left-align"><font class="red">* </font> 핸드폰 번호</label>
									<div class="col-lg-10 col-md-10">
										<input id=""sel_phone"" name=""sel_phone"" type="text" class="form-control" placeholder="" value="" maxlength="20">
									</div>
								</div>
								<div class="form-group">
									<label for="email" class="col-lg-2 col-md-2 control-label lb-left-align"><font class="red">* </font> 이메일</label>
									<div class="col-lg-10 col-md-10">
										<input id="email" name="email" type="text" class="form-control" placeholder="" value="" maxlength="20">
									</div>
								</div>
								<div class="form-group">
									<label for="addr" class="col-lg-2 col-md-2 control-label lb-left-align">주소</label>
									<div class="col-lg-10 col-md-10">
										<input id=""addr"" name=""addr"" type="text" class="form-control" placeholder="" value="" maxlength="20">
									</div>
								</div>
								<div class="form-group">
									<label for="annual" class="col-lg-2 col-md-2 control-label lb-left-align"><font class="red">* </font> 연차</label>
									<div class="col-lg-10 col-md-10">
										<input id="annual" name="annual" type="text" class="form-control" placeholder="" value="" maxlength="20">
									</div>
								</div>
								<div class="form-group">
									<label for="adt_annual" class="col-lg-2 col-md-2 control-label lb-left-align"><font class="red">* </font> 연차적용일</label>
									<div class="col-lg-4 col-md-4">
										<div class="input-daterange input-group">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<input type="text" class="form-control" name="anual_start" id="anual_start"/>
											<span class="input-group-addon">to</span> <input type="text" class="form-control" name="anual_end" id="anual_end"/>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="birthday" class="col-lg-2 col-md-2 control-label lb-left-align"><font class="red">* </font> 생년월일</label>
									<div class="col-lg-2 col-md-2">
										<div class="input-daterange input-group">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<input type="text" class="form-control" name="birthday" id="birthday"/>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="join_date" class="col-lg-2 col-md-2 control-label lb-left-align"><font class="red">* </font> 입사일</label>
									<div class="col-lg-2 col-md-2">
										<div class="input-daterange input-group">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<input type="text" class="form-control" name="join_date" id="join_date"/>
										</div>
									</div>
									<div class="col-lg-2 col-md-2">
										<div class="radio-custom radio-inline">
                                        	<input type="radio" name="radio1" value="option1" checked="checked"  id="radio4">
                                        	<label for="radio4">남자</label>
                                        </div>
                                        <div class="radio-custom radio-inline">
                                        	<input type="radio" name="radio1" value="option2" id="radio5">
                                        	<label for="radio5">여자</label>
                                        </div>
									</div>
									
								</div>
								<div class="form-group">
									<label for="color" class="col-lg-2 col-md-2 control-label lb-left-align"><font class="red">* </font> 색상</label>
									<div class="col-lg-2 col-md-2">
										<div id="component-colorpicker" class="input-group colorpicker-element">
											<span class="input-group-addon"><i style="background-color: rgb(89, 161, 101);"></i></span>
											<input type="text" value="" class="form-control">
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="order" class="col-lg-2 col-md-2 control-label lb-left-align">순서</label>
									<div class="col-lg-10 col-md-10">
										<input id="order" name="order" type="text" class="form-control" placeholder="" value="" maxlength="20">
									</div>
								</div>
								<div class="form-group">
									<label for="in_office" class="col-lg-2 col-md-2 control-label lb-left-align">재직여부</label>
									<div class="col-lg-2 col-md-2">
										<div class="radio-custom radio-inline">
                                        	<input type="radio" name="radio2" value="option1" checked="checked"  id="in_office">
                                        	<label for="in_office">재직</label>
                                        </div>
                                        <div class="radio-custom radio-inline">
                                        	<input type="radio" name="radio2" value="option2" id="out_office">
                                        	<label for="out_office">퇴사</label>
                                        </div>
									</div>
								</div>
								
								<div class="panel-body pull-left">
									<button type="button" class="btn btn-info btn-alt mr5 mb10" onclick="location.href='<?echo site_url('member')?>';">리스트</button>
								</div>
								<div class="panel-body pull-right">
									<button id="contents_setting_delete" type="button" class="btn btn-danger btn-alt mr5 mb10">삭제</button>
									<button type="submit" class="btn btn-primary btn-alt mr5 mb10">등록</button>
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
<script src="<?echo $this->config->base_url()?>html/js/sw/sw_member.js"></script>
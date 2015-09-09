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
							<h4 class="panel-title">계정 등록</h4>
						</div>
						
						<!-- Start .panel-body -->
						<div class="panel-body">
							<!-- top form(member-form-write-setting) start  -->
							<form id="account-form-write-setting" action="<?echo $action_url;?>" method="post" class="form-horizontal group-border stripped" role="form"">
								<input type="hidden" name="action_type" id="action_type" value="<?echo $action_type;?>">
								<input type="hidden" name="no" id="no" value="<?echo $data['no'];?>">
								<div class="form-group">
									<!-- col-lg-12 start -->
									<div class="col-lg-12">
	                                    <div class="form-group">
	                                        <label class="col-lg-2 col-md-2 control-label lb-left-align" for=""><font class="red">* </font>아이디</label>
	                                        <div class="col-lg-10 col-md-10">
	                                            <input type="text" class="form-control" name="id" id="id" value=<?php echo $data['id']?>>
	                                        </div>
	                                    </div>
	                                    <div class="form-group">
	                                        <label class="col-lg-2 col-md-2 control-label lb-left-align" for=""><font class="red">* </font>비밀번호</label>
	                                        <div class="col-lg-10 col-md-10">
	                                            <input type="text" class="form-control" name="pass" id="pass" value=<?php echo $data['pwd']?>>
	                                        </div>
	                                    </div>
	                                    <div class="form-group">
	                                        <label class="col-lg-2 col-md-2 control-label lb-left-align" for=""><font class="red">* </font>이름</label>
	                                        <div class="col-lg-10 col-md-10">
	                                            <input type="text" class="form-control lb-left-align" name="name" id="name" value=<?php echo $data['name']?>>
	                                        </div>
	                                    </div>
	                                    <div class="form-group">
											<label for="in_office" class="col-lg-2 col-md-2 control-label lb-left-align">등급</label>
											<div class="col-lg-2 col-md-2">
												<div class="radio-custom radio-inline">
		                                        	<input type="radio" name="grade" value=1 <?=$data['grade'] == '1' ? ' checked="checked"' : '';?> checked="checked" id="in_office">
		                                        	<label for="in_office">일반</label>
		                                        </div>
		                                        <div class="radio-custom radio-inline">
		                                        	<input type="radio" name="grade" value=2 <?=$data['grade'] == '2' ? ' checked="checked"' : '';?> id="out_office">
		                                        	<label for="out_office">장기</label>
		                                        </div>
		                                        <div class="radio-custom radio-inline">
		                                        	<input type="radio" name="grade" value=3 <?=$data['grade'] == '3' ? ' checked="checked"' : '';?> id="out_office">
		                                        	<label for="out_office">등급</label>
		                                        </div>
											</div>
										</div>
	                                    <div class="form-group">
											<label for="email" class="col-lg-2 col-md-2 control-label lb-left-align"> 이메일</label>
											<div class="col-lg-10 col-md-10">
												<input id="email" name="email" type="text" class="form-control" placeholder=""  maxlength="20" value=<?php echo $data['email']?>>
											</div>
										</div>
	                                    <div class="form-group">
											<label for="join_date" class="col-lg-2 col-md-2 control-label lb-left-align"><font class="red">* </font> 생년월일</label>
											<div class="col-lg-2 col-md-2">
												<div class="input-daterange input-group">
													<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
													<input type="text" class="form-control" name="birthday" id="birthday" value=<?php if($data['birth'] != '1') echo "'" .date("Y-m-d", strtotime($data['birth'])) . "'";?>/>
												</div>
											</div>
											<div class="col-lg-2 col-md-2">
												<div class="radio-custom radio-inline">
		                                        	<input type="radio" name="sex" value=0 <?=$data['gender'] == '0' ? ' checked="checked"' : '';?> checked="checked" id="radio4">
		                                        	<label for="radio4">남자</label>
		                                        </div>
		                                        <div class="radio-custom radio-inline">
		                                        	<input type="radio" name="sex" value=1 <?=$data['gender'] == '1' ? ' checked="checked"' : '';?> id="radio5">
		                                        	<label for="radio5">여자</label>
		                                        </div>
											</div>
										</div>
										
										<div class="form-group">
											<label for="in_office" class="col-lg-2 col-md-2 control-label lb-left-align">분류</label>
											<div class="col-lg-2 col-md-2">
												<div class="radio-custom radio-inline">
		                                        	<input type="radio" name="kind" value=1 <?=$data['type'] == '1' ? ' checked="checked"' : '';?> checked="checked" id="kind_kin">
		                                        	<label for="in_office">지식인</label>
		                                        </div>
		                                        <div class="radio-custom radio-inline">
		                                        	<input type="radio" name="kind" value=2 <?=$data['type'] == '2' ? ' checked="checked"' : '';?> id="kind_blog">
		                                        	<label for="out_office">블로그</label>
		                                        </div>
											</div>
										</div>
										<div class="form-group">
											<label for="in_office" class="col-lg-2 col-md-2 control-label lb-left-align">용도</label>
											<div class="col-lg-2 col-md-2">
												<div class="radio-custom radio-inline">
		                                        	<input type="radio" name="use" value=3 <?=$data['is_using_question'] == '3' ? ' checked="checked"' : '';?> checked="checked" id="use_not">
		                                        	<label for="in_office">미사용</label>
		                                        </div>
		                                        <div class="radio-custom radio-inline">
		                                        	<input type="radio" name="use" value=1 <?=$data['is_using_question'] == '1' ? ' checked="checked"' : '';?> id="use_req">
		                                        	<label for="out_office">질문</label>
		                                        </div>
		                                        <div class="radio-custom radio-inline">
		                                        	<input type="radio" name="use" value=1 <?=$data['is_using_question'] == '2' ? ' checked="checked"' : '';?> id="use_res">
		                                        	<label for="out_office">답변</label>
		                                        </div>
											</div>
										</div>
										<div class="form-group">
											<label for="in_office" class="col-lg-2 col-md-2 control-label lb-left-align">사용여부</label>
											<div class="col-lg-2 col-md-2">
												<div class="radio-custom radio-inline">
		                                        	<input type="radio" name="is_active" value=1 <?=$data['is_active'] == '1' ? ' checked="checked"' : '';?> checked="checked" id="is_active">
		                                        	<label for="in_office">사용</label>
		                                        </div>
		                                        <div class="radio-custom radio-inline">
		                                        	<input type="radio" name="is_active" value=0 <?=$data['is_active'] == '0' ? ' checked="checked"' : '';?> id="kind_blog">
		                                        	<label for="out_office">비사용</label>
		                                        </div>
											</div>
										</div>
										<div class="form-group">
											<label for="order" class="col-lg-2 col-md-2 control-label lb-left-align">순서</label>
											<div class="col-lg-10 col-md-10">
												<input id="order" name="order" type="text" class="form-control" placeholder=""  maxlength="20" value=<?php echo $data['order']?>>
											</div>
										</div>
										
									</div>
									<!-- col-lg-12 end -->
								</div>
								
								<div class="panel-body pull-left">
									<button type="button" class="btn btn-info btn-alt mr5 mb10" onclick="location.href='<?echo site_url('account')?>';">리스트</button>
								</div>
								<div class="panel-body pull-right">
									<button id="contents_setting_delete" type="button" class="btn btn-danger btn-alt mr5 mb10">삭제</button>
									<button type="submit" class="btn btn-primary btn-alt mr5 mb10">등록</button>
								</div>
								
							</form>
							<!-- top form(account-form-write-setting) END  -->
							

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

<script src="<?echo $this->config->base_url()?>html/js/sw/sw_account_write.js"></script>
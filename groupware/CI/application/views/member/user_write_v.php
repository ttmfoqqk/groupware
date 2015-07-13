<!-- .page-content -->
<div class="page-content sidebar-page clearfix">
	<!-- .page-content-wrapper -->
	<div class="page-content-wrapper">
		<div class="page-content-inner">
			<!-- .page-content-inner -->
			<div id="page-header" class="clearfix">
				<div class="page-header">
					<h2>사원 설정</h2>
					<span class="txt">사원 설정</span>
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

							<form id="validate" action="<?echo $action_url;?>" method="post" class="form-horizontal group-border stripped" role="form">
							<input type="hidden" name="action_type" id="action_type" value="<?echo $action_type;?>">
							<input type="hidden" name="user_no" id="user_no" value="<?echo $data['no'];?>">

								<div class="form-group">
									<label for="user_id" class="col-lg-2 col-md-3 control-label">아이디</label>
									<div class="col-lg-10 col-md-9">
										<input id="user_id" name="user_id" type="text" class="form-control" placeholder="아이디" value="<?echo $data['id'];?>" maxlength="20" <?echo $action_type=='edit'?'readonly':''; ?>>
									</div>
								</div>

								<div class="form-group">
								<?if($action_type=='edit'){?>
									<label for="new_user_password" class="col-lg-2 col-md-3 control-label">비밀번호 변경</label>
									<div class="col-lg-10 col-md-9">
										<input id="new_user_password" name="new_user_password" type="password" class="form-control" placeholder="비밀번호 변경시 작성" maxlength="20" >
									</div>
								<?}else{?>
									<label for="user_password" class="col-lg-2 col-md-3 control-label">비밀번호</label>
									<div class="col-lg-10 col-md-9">
										<input id="user_password" name="user_password" type="password" class="form-control" placeholder="비밀번호" maxlength="20" >
									</div>
								<?}?>
								</div>

								<div class="form-group">
									<label for="user_name" class="col-lg-2 col-md-3 control-label">이름</label>
									<div class="col-lg-10 col-md-9">
										<input id="user_name" name="user_name" type="text" class="form-control"  placeholder="이름" value="<?echo $data['name'];?>" maxlength="20">
									</div>
								</div>
								<div class="form-group">
									<label for="user_email" class="col-lg-2 col-md-3 control-label">이메일</label>
									<div class="col-lg-10 col-md-9">
										<input id="user_email" name="user_email" type="text" class="form-control"  placeholder="이메일" value="<?echo $data['email'];?>" maxlength="20">
									</div>
								</div>
								 <div class="form-group">
									<label class="col-lg-2 col-md-3 control-label">부서</label>
									<div class="col-lg-10 col-md-9">
										<select class="form-control" id="user_organization" name="user_organization">
											<option value="">선택</option>
											<?echo $option;?>
										</select>
									</div>
								</div>

								<div class="form-group">
									<label for="" class="col-lg-2 col-md-3 control-label">페이지 권한</label>
									<div class="col-lg-10 col-md-9">
										<div class="checkbox-custom checkbox-inline">
											<input type="checkbox" id="board_reply" name="board_reply" <?//echo ($data['reply'] == 0 ? 'checked':'')?>>
											<label for="board_reply">페이지 관리 - 준비중</label>
										</div>
									</div>
								</div>
							
								<div class="panel-body pull-left">
									<button type="button" class="btn btn-info btn-alt mr5 mb10" onclick="location.href='<?echo site_url('member/lists')?>';">리스트</button>
								</div>
								<div class="panel-body pull-right">
									<button id="contents_member_delete" type="button" class="btn btn-danger btn-alt mr5 mb10">삭제</button>
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



<script src="/html/plugins/forms/bootstrap-filestyle/bootstrap-filestyle.js"></script>
<script src="/html/plugins/forms/select2/select2.js"></script>
<script src="/html/plugins/forms/validation/jquery.validate.js"></script>
<script src="/html/plugins/forms/validation/additional-methods.min.js"></script>
<script src="/html/plugins/charts/sparklines/jquery.sparkline.js"></script>

<script src="/html/plugins/forms/checkall/jquery.checkAll.js"></script>

<script src="/html/js/sw/sw_user_list.js"></script>
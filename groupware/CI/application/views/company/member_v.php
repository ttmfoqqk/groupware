<!-- .page-content -->
<div class="page-content sidebar-page clearfix">
	<!-- .page-content-wrapper -->
	<div class="page-content-wrapper">
		<div class="page-content-inner">
			<!-- .page-content-inner -->
			<div id="page-header" class="clearfix">
				<div class="page-header">
					<h2><?php echo $head_name;?></h2>
				</div>
			</div>
			<div class="row">
			
			
			<div class="col-lg-12">
					<!-- col-lg-12 start here -->
					<div class="panel panel-primary toggle">
						<!-- Start .panel -->
						<div class="panel-heading">
							<h4 class="panel-title">
								<i class="fa fa-circle"></i> 사원목록
							</h4>
						</div>
						<div class="panel-body">
							<!-- 검색 -->
							<form id="qu" class="form-horizontal" method="get" role="form"
								>
								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">이름</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" id="ft_name"
											name="ft_name" placeholder="이름"
											>
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">휴대폰번호</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" id="ft_phone"
											name="ft_phone" placeholder="휴대폰번호"
											>
									</div>
								</div>

								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">이메일</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" id="ft_email"
											name="ft_email" placeholder="이메일"
											>
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">재직여부</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" id="ft_iswork"
											name="ft_iswork" placeholder="재직여부"
											>
									</div>

									<div class="col-lg-2 col-md-2">
										<button type="submit" class="btn btn-primary btn-alt mr5 mb10">검
											색</button>
									</div>
								</div>


							</form>
							<!-- 검색 -->

							<form id="company-form-list" action="<?echo $action_url;?>"
								method="post" class="form-horizontal group-border stripped"
								role="form">
								<input type="hidden" name="action_type" id="action_type"
									value="<?echo $action_type;?>">
								<input type="hidden" name="page_cate" id="page_cate"
									value="<?echo $page;?>">
								<table class="table table-bordered" id="tabletools">
									<thead>
										<tr>
											<th style="width: 45px;">
												<div class="checkbox-custom">
													<input class="check-all" type="checkbox" id="masterCheck"
														value="option1"> <label for="masterCheck"></label>
												</div>
											</th>
											<th style="width: 50px;">순서</th>
											<th class="per10">이름</th>
											<th>휴대폰번호</th>
											<th class="per15">이메일</th>
											<th class="per15">재직여부</th>
											<th class="per15">등록일자</th>
											<th style="width: 60px;">권한</th>
											<th style="width: 60px;">담당자</th>
											<th style="width: 60px;">사이트</th>
										</tr>
									</thead>
									<tbody>
										<!-- 리스트 -->
									<?php
										foreach ( $list as $lt ) {
										$anchor_url = site_url ( 'company_setting/write/' . $page . '/' . $lt ['no'] );
										?>
										<tr>
											<td>
												<div class="checkbox-custom">
													<input id="check<?$lt['no'];?>" name="company_no[]"
														class="check" type="checkbox"
														value=<?php echo $lt['no'];?>> <label
														for="check<?$lt['no'];?>"></label> <input type="hidden"
														value="<?php echo $lt['no'];?>">
												</div>
											</td>
											<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['order'];?></a></td>
											<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['name'];?></a></td>
											<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['phone'];?></a></td>
											<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['email'];?></a></td>
											<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['is_active'];?></a></td>
											<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['created'];?></a></td>
											<td class="text-center">
												<button type="button" class="btn btn-success btn-xs"
													id="view_auth" onclick="test();">
													<i class="glyphicon glyphicon-user"></i>
												</button>
											</td>
											<td class="text-center">
												<button type="button" class="btn btn-success btn-xs"
													id="view_depart" onclick="test();">
													<i class="glyphicon glyphicon-user"></i>
												</button>
											</td>
											<td class="text-center">
												<button type="button" class="btn btn-primary btn-xs"
													id="view_year" onclick="alert('담당자 팝업');">
													<i class="glyphicon glyphicon-globe"></i>
												</button>
											</td>
										</tr>
									<?php }?>
									<!-- 리스트 -->
									<?if (count ( $list ) <= 0) {?>
									<tr>
											<td colspan="10">등록된 내용이 없습니다.</td>
										</tr>
								<?}?>
									
								</tbody>
								</table>

								<div class="panel-body" style="text-align: center;">
									<?php echo $table_num?><br><?echo $pagination;?></div>
								<div class="panel-body pull-right">
									<button id="btn_list_delete" type="button"
										class="btn btn-danger btn-alt mr5 mb10">삭제</button>
									<button type="button" class="btn btn-primary btn-alt mr5 mb10"
										onclick="location.href='<?echo site_url('company_setting/write/') . '/' . $page;?>';">등록</button>
								</div>
							</form>
						</div>
					</div>
					<!-- End .panel -->
				</div>

			
			
			
			
			</div>
			
			<!-- End .row -->
		</div>
		<!-- / .page-content-inner -->
	</div>
	<!-- / page-content-wrapper -->
</div>
<!-- / page-content -->
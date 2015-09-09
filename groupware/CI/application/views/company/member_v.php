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
							<form id="qu" class="form-horizontal" method="get" role="form" action="<?echo site_url($page . '/lists/1')?>">
								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">이름</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" id="ft_name"
											name="ft_name" placeholder="이름" value=<?php echo $filter['name'] != NULL ? $filter['name'] : '';?>>
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">휴대폰번호</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" id="ft_phone"
											name="ft_phone" placeholder="휴대폰번호" value=<?php echo $filter['phone'] != NULL ? $filter['phone'] : '';?>>
									</div>
								</div>

								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">이메일</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" id="ft_email"
											name="ft_email" placeholder="이메일" value=<?php echo $filter['email'] != NULL ? $filter['email'] : '';?>>
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">재직여부</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" id="ft_iswork"
											name="ft_iswork" placeholder="재직여부" value=<?php echo $filter['is_active'] != NULL ? $filter['is_active'] : '';?>>
									</div>

									<div class="col-lg-2 col-md-2">
										<button type="submit" class="btn btn-primary btn-alt mr5 mb10">검
											색</button>
									</div>
								</div>
								
								<!-- 테이블 옵션  -->
								<div class="pull-left">
									<select class="fancy-select form-control tb_num" id="tb_num" name="tb_num" val=<?php echo $tb_num;?>>
										<option value="10" <?=$tb_num == '10' ? ' selected="selected"' : '';?>>10개</option>
										<option value="20" <?=$tb_num == '20' ? ' selected="selected"' : '';?>>20개</option>
									</select>
								</div>
								<div class="pull-right">
									<button type="submit" class="btn btn-alt mr5 mb10">엑셀</button>
								</div>
								<!-- END 테이블 옵션  -->


							</form>
							<!-- 검색 -->

							<form id="member-form-list" action="<?echo $action_url;?>"
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
											<th style="width: 80px;">부서/직급</th>
											<th style="width: 80px;">연차등록</th>
										</tr>
									</thead>
									<tbody>
										<!-- 리스트 -->
									<?php
										foreach ( $list as $lt ) {
										$anchor_url = site_url ( 'member/write/' . $lt ['no'] );
										?>
										<tr>
											<td>
												<div class="checkbox-custom">
													<input id="check<?$lt['no'];?>" name="no[]"
														class="check" type="checkbox"
														value=<?php echo $lt['no'];?>> <label
														for="check<?$lt['no'];?>"></label> <input type="hidden"
														value="<?php echo $lt['no'];?>">
												</div>
											</td>
											<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['order'];?></a></td>
											<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['name'];?></a></td>
											<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['mobile'];?></a></td>
											<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['email'];?></a></td>
											<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['is_active'] == 0 ? '재직' : '퇴사';?></a></td>
											<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['created'];?></a></td>
											<td class="text-center">
												<button type="button" class="btn btn-success btn-xs" onclick="call_staff('<?echo $lt['no'];?>','permission');">
													<i class="glyphicon glyphicon-lock"></i>
												</button>
											</td>
											<td class="text-center">
												<button type="button" class="btn btn-success btn-xs" onclick="call_staff('<?echo $lt['no'];?>','department');">
													<i class="glyphicon glyphicon-user"></i>
												</button>
											</td>
											<td class="text-center">
												<button type="button" class="btn btn-success btn-xs" onclick="call_staff('<?echo $lt['no'];?>','annual');">
													<i class="glyphicon glyphicon-calendar"></i>
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
										onclick="location.href='<?echo site_url($page . '/write/');?>';">등록</button>
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


<script src="<?echo $this->config->base_url()?>html/js/sw/sw_member.js"></script>
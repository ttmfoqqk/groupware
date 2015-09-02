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
				
				<div class="col-lg-12">
					<!-- col-lg-12 start here -->
					<div class="panel panel-primary toggle">
						<!-- Start .panel -->
						<div class="panel-heading">
							<h4 class="panel-title"><i class="fa fa-circle"></i> 계정목록</h4>
						</div>
						<div class="panel-body">
							
							<!-- 검색 -->
							<form id="qu" class="form-horizontal" method="get" role="form"
								action="<?echo site_url($page . '/lists/1')?>">
								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">분류</label>
									<div class="col-lg-3 col-md-3 col-sm-3 col-sm-3">
										<select id="ft_type" name="ft_type" class="fancy-select form-control" value=<?php echo $this->input->get('ft_type');?>>
											<option value="">전체</option>
											<option value=1>지식인</option>
											<option value=2>블로그</option>
										</select>
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">아이디</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" placeholder="아이디" name="ft_id" value=<?php echo $this->input->get('ft_id');?>>
									</div>
								</div>

								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">등급</label>
									<div class="col-lg-3 col-md-3 col-sm-3 col-sm-3">
										<select id="ft_grade" name="ft_grade" class="fancy-select form-control" value=<?php echo $this->input->get('ft_grade');?>>
											<option value="">전체</option>
											<option value=1>일반</option>
											<option value=2>장기</option>
											<option value=3>등급</option>
										</select>
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">이름</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" placeholder="이름" id="ft_name" value=<?php echo $this->input->get('ft_name');?>>
									</div>
								</div>

								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">용도</label>
									<div class="col-lg-3 col-md-3">
										<select id="ft_use" name="ft_use" class="fancy-select form-control" value=<?php echo $this->input->get('ft_use');?>>
											<option value="">전체</option>
											<option value=1>질문</option>
											<option value=2>답변</option>
											<option value=3>미사용</option>
										</select>
									</div>

									<label class="col-lg-2 col-md-2 control-label" for=""></label>
									<div class="col-lg-3 col-md-3">
									</div>

									<div class="col-lg-2 col-md-2">
										<button type="submit" class="btn btn-primary btn-alt mr5 mb10"> 검 색</button>
									</div>
								</div>

								<!-- 테이블 옵션  -->
								<div class="pull-left">
									<select class="fancy-select form-control tb_num" id="tb_num" name="tb_num" val=<?echo $this->input->get('tb_num');?>>
										<option value="10" <?=$this->input->get('tb_num') == '10' ? ' selected="selected"' : '';?>>10개</option>
										<option value="20" <?=$this->input->get('tb_num') == '20' ? ' selected="selected"' : '';?>>20개</option>
									</select>
								</div>
								<div class="pull-right">
									<button type="submit" class="btn btn-alt mr5 mb10">엑셀</button>
								</div>
								<!-- END 테이블 옵션  -->

							</form>
							<!-- 검색 -->
							
							<form id="account-form-list" action="<?echo $action_url;?>"
								method="post" class="form-horizontal group-border stripped"
								role="form">
								<input type="hidden" name="action_type" id="action_type"
									value="<?echo $action_type;?>">
								<input type="hidden" name="page_cate" id="page_cate"
									value="<?echo $page;?>">
									
									
								<table class="table table-bordered" id="tabletools">
									<thead>
										<tr>
											<th style="width:45px;">
												<div class="checkbox-custom">
													<input class="check-all" type="checkbox" id="masterCheck" value="option1">
													<label for="masterCheck"></label>
												</div>
											</th>
											<th style="width:60px;">순서</th>
											<th class="per8">분류</th>
											<th>아이디</th>
											<th class="per8">비밀번호</th>
											<th class="per8">등급</th>
											<th class="per8">이름</th>
											<th class="per8">생년월일</th>
											<th width="80px;">용도</th>
										</tr>
									</thead>
									<tbody>
										<!-- 리스트 -->
										<?php
											foreach ( $list as $lt ) {
											$anchor_url = site_url ( 'account/write/' . $lt ['no'] );
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
												<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php if($lt['type'] == 1) echo "지식인";else if($lt['type'] == 2) echo "블로그";?></a></td>
												<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['id'];?></a></td>
												<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['pwd'];?></a></td>
												<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php if($lt['grade'] == 1) echo "일반";else if($lt['grade'] == 2) echo "장기";else if($lt['grade'] == 3) echo "등급";?></a></td>
												<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['name'];?></a></td>
												<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo date("Y-m-d", strtotime($lt['birth']));?></a></td>
												<td>
													<a href="<?echo $anchor_url;?>" class="text-normal">
														<?php if($lt['is_using_question'] == 1) echo '질문';
															else if($lt['is_using_question'] == 2) echo '답변';
															else if($lt['is_using_question'] == 3) echo '미사용';
															?></a>
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
										onclick="location.href='<?echo site_url('account/write/');?>';">등록</button>
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

<!-- 폼 날짜 -->
<script src="<?echo $this->config->base_url()?>html/plugins/forms/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<script src="<?echo $this->config->base_url()?>html/plugins/forms/bootstrap-datepicker/bootstrap-datepicker.js"></script>

<script src="<?echo $this->config->base_url()?>html/js/sw/sw_account.js"></script>
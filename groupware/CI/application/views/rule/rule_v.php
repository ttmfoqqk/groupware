<!-- .page-content -->
<div class="page-content sidebar-page clearfix">
	<!-- .page-content-wrapper -->
	<div class="page-content-wrapper">
		<div class="page-content-inner">
			<!-- .page-content-inner -->
			<div id="page-header" class="clearfix">
				<div class="page-header">
					<h2>회사규정 정보</h2>
				</div>
			</div>
			<div class="row">
			
				<div class="col-lg-12">
					<!-- col-lg-12 start here -->
					<div class="panel panel-primary toggle">
						<!-- Start .panel -->
						<div class="panel-heading">
							<h4 class="panel-title">
								<i class="fa fa-circle"></i> 회사서식 정보
							</h4>
						</div>
						<div class="panel-body">
							<!-- 검색 -->
							<form id="qu" class="form-horizontal" method="get" role="form"
								action="<?echo site_url($page . '/lists/1')?>">
								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">등록일자</label>
									<div class="col-lg-6 col-md-6">
										<div class="input-daterange input-group">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<input type="text" class="form-control" name="ft_start"
												id="ft_start"
												value=<?php if($this->input->get('ft_start') != '1') echo "'" .$this->input->get('ft_start') . "'";?> />
											<span class="input-group-addon">to</span> <input type="text"
												class="form-control" name="ft_end" id="ft_end"
												value=<?php if($this->input->get('ft_end') != '1') echo "'" .$this->input->get('ft_end') . "'";?>/>
										</div>
									</div>
									<div class="col-lg-4 col-md-4">
										<button type="button"
											class="btn btn-sm btn-primary btn-alt init_today">오늘</button>
										<button type="button"
											class="btn btn-sm btn-primary btn-alt init_seven">7일</button>
										<button type="button"
											class="btn btn-sm btn-primary btn-alt init_thirty">30일</button>
										<button type="button"
											class="btn btn-sm btn-primary btn-alt init_date">날짜초기화</button>
									</div>
								</div>
								
								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">분류</label>
									<div class="col-lg-3 col-md-3">
										<select class="fancy-select form-control" id="ft_rule" name="ft_rule" data-method="rule" data-value="<?echo $this->input->get('ft_rule');?>">
                                        	<option value="">전체</option>
                                        </select>
									</div>
									<label class="col-lg-2 col-md-2 control-label" for="">점수</label>
									<div class="col-lg-3 col-md-3">
										<select class="fancy-select form-control" id="ft_point" name="ft_point" data-value="<?echo $this->input->get('ft_point');?>">
                                        	<option value="">전체</option>
                                        	<option value=0 <?=$this->input->get('ft_point') == '0' ? ' selected="selected"' : '';?>>+</option>
                                        	<option value=1 <?=$this->input->get('ft_point') == '1' ? ' selected="selected"' : '';?>>-</option>
                                        </select>
									</div>
								</div>

								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">제목</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" id="ft_title"
											name="ft_title" placeholder="제목"
											value="<?echo $this->input->get('ft_title');?>">
									</div>
									<label class="col-lg-2 col-md-2 control-label" for="">등록자</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" id="ft_userName"
											name="ft_userName" placeholder="등록자"
											value="<?echo $this->input->get('ft_userName');?>">
									</div>
									<div class="col-lg-2 col-md-2">
										<button type="submit" class="btn btn-primary btn-alt mr5 mb10">검
											색</button>
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

							
							<form id="rule-form-list" action="<?echo $action_url;?>"
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
											<th class="per15">분류</th>
											<th>제목</th>
											<th class="per10">점수</th>
											<th class="per15">사용여부</th>
											<th class="per15">등록일자</th>
											<th class="per10">등록자</th>
										</tr>
									</thead>
									<tbody>
										<!-- 리스트 -->
									<?php
										foreach ( $list as $lt ) {
										$anchor_url = site_url ( 'rule/write/' . $lt ['no'] );
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
											<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['menu_name'];?></a></td>
											<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['name'];?></a></td>
											<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['point'];?></a></td>
											<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['is_active'] ? "미사용" : "사용";?></a></td>
											<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['created'];?></a></td>
											<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['user_name'];?></a></td>
										</tr>
									<?php }?>
									<!-- 리스트 -->
									<?if (count ( $list ) <= 0) {?>
									<tr>
											<td colspan="8">등록된 내용이 없습니다.</td>
										</tr>
								<?}?>
									
								</tbody>
								</table>

								<div class="panel-body" style="text-align: center;">
									<?php echo $table_num?><br><?echo $pagination;?></div>
								<div class="panel-body pull-right">
									<button type="button" class="btn btn-primary btn-alt mr5 mb10"
										onclick="location.href='<?echo site_url('rule/write/');?>';">등록</button>
									<button id="btn_list_delete" type="button"
										class="btn btn-danger btn-alt mr5 mb10">삭제</button>
								</div>
							</form>
			
			
			</div>
			
			<!-- End .row -->
		</div>
		<!-- / .page-content-inner -->
	</div>
	<!-- / page-content-wrapper -->
</div>
<!-- / page-content -->

<script src="<?echo $this->config->base_url()?>html/js/sw/sw_rule.js"></script>
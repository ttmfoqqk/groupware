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
							<h4 class="panel-title">
								<i class="fa fa-circle"></i> CHC 목록
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
												value=<?php if($this->input->get('ft_end') != '1') echo "'" .$this->input->get('ft_end') . "'";?> />
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
									<label class="col-lg-2 col-md-2 control-label" for="">담당부서</label>
									<div class="col-lg-3 col-md-3 col-sm-3 col-sm-3">
										<select id="ft_department" name="ft_department" data-method="department" data-value="<?echo $this->input->get('ft_department');?>"
											class="fancy-select form-control">
											<option value="">전체</option>
										</select>
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">담당자</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" placeholder="담당자" id="ft_userName" name="ft_userName" value="<?echo $this->input->get('ft_userName');?>">
									</div>
								</div>

								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">고객사</label>
									<div class="col-lg-3 col-md-3 col-sm-3 col-sm-3">
										<input type="text" class="form-control" placeholder="고객사" id="ft_customer" name="ft_customer" value="<?echo $this->input->get('ft_customer');?>">
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">키워드</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" placeholder="키워드" id="ft_keyword" name="ft_keyword" value="<?echo $this->input->get('ft_keyword');?>">
									</div>
								</div>

								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">분류</label>
									<div class="col-lg-3 col-md-3">
										<select id="ft_title" name="ft_title"  data-value="<?echo $this->input->get('ft_title');?>"
											class="fancy-select form-control">
											<option value="">전체</option>
											<option >지식인</option>
											<option >블로그</option>
											<option >카페</option>
											<option >모바일</option>
										</select>
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">네이버순위</label>
									<div class="col-lg-3 col-md-3">
										<select id="ft_rank" name="ft_rank" data-value="<?echo $this->input->get('ft_rank');?>"
											class="fancy-select form-control">
											<option value="">전체</option>
											<option value=1>1위</option>
											<option value=2>2위</option>
											<option value=3>3위</option>
											<option value=4>4위</option>
											<option value=5>5위</option>
											<option value=7>6위 ~10위</option>
											<option value=6>6위 이상</option>
											<option value=8>10위 이상</option>
											<option value=-1>체크 실패</option>
										</select>
									</div>

									<div class="col-lg-2 col-md-2">
										<button type="submit" class="btn btn-primary btn-alt mr5 mb10">
											검 색</button>
									</div>
								</div>


							</form>
							<!-- 검색 -->
							
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
							
							<form id="chc-form-list" action="<?echo $action_url;?>"
								method="post" class="form-horizontal group-border stripped"
								role="form">
								<input type="hidden" name="action_type" id="action_type"
									value="<?echo $action_type;?>">
									
									
								<table class="table table-bordered" id="tabletools">
									<thead>
										<tr>
											<th style="width: 45px;">
												<div class="checkbox-custom">
													<input class="check-all" type="checkbox" id="masterCheck"
														value="option1"> <label for="masterCheck"></label>
												</div>
											</th>
											<th style="min-width: 60px;">순서</th>
											<th style="min-width: 100px; width:100px;">등록일자</th>
											<th style="min-width: 80px; width:80px;">분류</th>
											<th style="min-width: 150px;">제목</th>
											<th style="min-width: 100px;">고객사</th>
											<th style="min-width: 150px;">키워드</th>
											<th style="min-width: 250px;">URL</th>
											<th style="min-width: 70px;">답변수</th>
											<th style="min-width: 210px;">진행기간</th>
											<th style="min-width: 60px;">순위</th>
											<th style="min-width: 70px;">노출률</th>
											<th style="min-width: 70px;" >작업수</th>
											<th style="min-width: 70px;">그래프</th>
										</tr>
									</thead>
									<tbody>
									<!-- 리스트 -->
										<?php
											foreach ( $list as $lt ) {
											$anchor_url = site_url ( 'chc/write/'. $lt ['no'] );
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
												<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['created'];?></a></td>
												<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['menu_kind'];?></a></td>
												<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['title'];?></a></td>
												<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['bizName'];?></a></td>
												<td><a target="_blank" href="<?if($lt['kind'] == 'kin') echo "http://kin.search.naver.com/search.naver?where=kin&sm=tab_jum&ie=utf8&query=" . $lt['keyword'];
													elseif($lt['kind'] == 'blog') echo "http://cafeblog.search.naver.com/search.naver?where=post&sm=tab_jum&ie=utf8&query=" . $lt['keyword'];
													elseif($lt['kind'] == 'cafe') echo "http://cafeblog.search.naver.com/search.naver?where=article&sm=tab_jum&ie=utf8&query=" . $lt['keyword'];
													elseif($lt['kind'] == 'm') echo "http://m.search.naver.com/search.naver?query=" . $lt['keyword'];?>" 
													class=""><?php echo $lt['keyword'];?></a></td>
												<td><a target="_blank" style="display:block;width:300px;word-wrap: break-word;" href="<?echo $lt['url'];;?>" class=""><?php echo $lt['url'];?></a></td>
												<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo count($lt['response']);?></a></td>
												<td>
													<a href="<?echo $anchor_url;?>" class="text-normal">
														<?php echo date("Y-m-d", strtotime($lt['sData'])) . ' ~ ' . date("Y-m-d", strtotime($lt['eData']));?>
													</a>
												</td>
												<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['rank'];?></a></td>
												<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['exposeRate'] . '%' ;?></a></td>
												<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo count($lt['history']) ;?></a></td>
												<td class="text-center">
													<button type="button" class="btn btn-success btn-xs"
														id="view_staff" onclick="<?php echo 'expInfoKeyword(' . $lt['no'] . ')';?>">
														<i class="glyphicon glyphicon-signal"></i>
													</button>
												</td>
											</tr>
										<?php }?>
										<!-- 리스트 -->
										<?if (count ( $list ) <= 0) {?>
										<tr>
											<td colspan="14">등록된 내용이 없습니다.</td>
										</tr>
									<?}?>
									<!-- 리스트 -->
									</tbody>
								</table>
	
								<div class="panel-body" style="text-align: center;">
										<?php echo $table_num?><br><?echo $pagination;?></div>
								<div class="panel-body pull-right">
									<button id="btn_list_delete" type="button"
										class="btn btn-danger btn-alt mr5 mb10">삭제</button>
									<button type="button" class="btn btn-primary btn-alt mr5 mb10"
										onclick="location.href='<?echo site_url('chc/write/');?>';">등록</button>
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
<script
	src="<?echo $this->config->base_url()?>html/plugins/forms/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<script
	src="<?echo $this->config->base_url()?>html/plugins/forms/bootstrap-datepicker/bootstrap-datepicker.js"></script>

<script src="<?echo $this->config->base_url()?>html/js/sw/sw_chc.js"></script>



 <script src="<?echo $this->config->base_url()?>html/plugins/charts/flot/jquery.flot.custom.js"></script>
        <script src="<?echo $this->config->base_url()?>html/plugins/charts/flot/jquery.flot.pie.js"></script>
        <script src="<?echo $this->config->base_url()?>html/plugins/charts/flot/jquery.flot.resize.js"></script>
        <script src="<?echo $this->config->base_url()?>html/plugins/charts/flot/jquery.flot.time.js"></script>
        <script src="<?echo $this->config->base_url()?>html/plugins/charts/flot/jquery.flot.growraf.js"></script>
        <script src="<?echo $this->config->base_url()?>html/plugins/charts/flot/jquery.flot.categories.js"></script>
        <script src="<?echo $this->config->base_url()?>html/plugins/charts/flot/jquery.flot.stack.js"></script>
        <script src="<?echo $this->config->base_url()?>html/plugins/charts/flot/jquery.flot.orderBars.js"></script>
        <script src="<?echo $this->config->base_url()?>html/plugins/charts/flot/jquery.flot.tooltip.min.js"></script>
        <script src="<?echo $this->config->base_url()?>html/plugins/charts/flot/jquery.flot.curvedLines.js"></script>
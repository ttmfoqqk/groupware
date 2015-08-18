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
								<i class="fa fa-circle"></i> 물품목록
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
												value=<?php if($date['start'] != '1') echo "'" .$date['start'] . "'";?> />
											<span class="input-group-addon">to</span> <input type="text"
												class="form-control" name="ft_end" id="ft_end"
												value=<?php if($date['end'] != '1') echo "'" .$date['end'] . "'";?> />
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
										<select class="fancy-select form-control" id="ft_kind" name="ft_kind" data-method="object" data-value="<?echo $this->input->get('ft_kind');?>">
                                        	<option value="">전체</option>
                                        </select>
									</div>
									<label class="col-lg-2 col-md-2 control-label" for="">물품명</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" id="ft_itemName"
											name="ft_itemName" placeholder="물품명"
											value=<?php echo $filter['o.name'] != NULL ? $filter['o.name'] : '';?>>
									</div>
								</div>

								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">물품위치</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" id="ft_area"
											name="ft_area" placeholder="물품위치"
											value=<?php echo $filter['o.area'] != NULL ? $filter['o.area'] : '';?>>
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">관리자</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" id="ft_usrName"
											name="ft_usrName" placeholder="관리자"
											value=<?php echo $filter['u.name'] != NULL ? $filter['u.name'] : '';?>>
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
											<th>분류</th>
											<th class="per25">물품명</th>
											<th class="per15">물품위치</th>
											<th class="per15">등록일자</th>
											<th class="per15">관리자</th>
										</tr>
									</thead>
									<tbody>
										<!-- 리스트 -->
									<?php
										foreach ( $list as $lt ) {
										$anchor_url = site_url ( 'object/write/' . $page . '/' . $lt ['no'] );
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
											<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['menu_name'];?></a></td>
											<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['name'];?></a></td>
											<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['area'];?></a></td>
											<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['created'];?></a></td>
											<td><a href="<?echo $anchor_url;?>" class="text-normal"><?php echo $lt['user_name'];?></a></td>
										</tr>
									<?php }?>
									<!-- 리스트 -->
									<?if (count ( $list ) <= 0) {?>
									<tr>
											<td colspan="7">등록된 내용이 없습니다.</td>
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
										onclick="location.href='<?echo site_url('object/write/') . '/' . $page;?>';">등록</button>
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

<script src="<?echo $this->config->base_url()?>html/js/sw/sw_object.js"></script>
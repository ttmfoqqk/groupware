<!-- .page-content -->
<div class="page-content sidebar-page clearfix">
	<!-- .page-content-wrapper -->
	<div class="page-content-wrapper">
		<div class="page-content-inner">
			<!-- .page-content-inner -->
			<div id="page-header" class="clearfix">
				<div class="page-header">
					<h2>물품정보</h2>
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
							<form id="qu" class="form-horizontal" method="get" role="form" action="<?echo site_url('object/lists/')?>">
								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">등록일자</label>
									<div class="col-lg-6 col-md-6">
										<div class="input-daterange input-group">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<input type="text" class="form-control" name="sData" id="sData" value="<?php echo $this->input->get('sData');?>"/>
											<span class="input-group-addon">to</span>
											<input type="text" class="form-control" name="eData" id="eData" value="<?php echo $this->input->get('eData');?>"/>
										</div>
									</div>
									<div class="col-lg-4 col-md-4">
										<button type="button" class="btn btn-sm btn-primary btn-alt" id="sToday">오늘</button>
										<button type="button" class="btn btn-sm btn-primary btn-alt" id="sWeek">7일</button>
										<button type="button" class="btn btn-sm btn-primary btn-alt" id="sMonth">30일</button>
										<button type="button" class="btn btn-sm btn-primary btn-alt" id="sReset">날짜초기화</button>
									</div>
								</div>

								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">분류</label>
									<div class="col-lg-3 col-md-3">
										<select class="fancy-select form-control" id="menu_no" name="menu_no" data-method="object" data-value="<?echo $this->input->get('menu_no');?>">
                                        	<option value="">전체</option>
                                        </select>
									</div>
									<label class="col-lg-2 col-md-2 control-label" for="">물품명</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" id="name" name="name" placeholder="물품명" value="<?php echo $this->input->get('name');?>">
									</div>
								</div>

								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">물품위치</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" id="area" name="area" placeholder="물품위치" value="<?php echo $this->input->get('area');?>">
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">관리자</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" id="userName" name="userName" placeholder="관리자" value="<?php echo $this->input->get('userName');?>">
									</div>

									<div class="col-lg-2 col-md-2">
										<button type="submit" class="btn btn-primary btn-alt mr5 mb10">검 색</button>
									</div>
								</div>


								<!-- 테이블 옵션  -->
								<div class="pull-left">
									<select class="fancy-select form-control tb_num" id="tb_num" name="tb_num">
										<option value="10" <?=$this->input->get('tb_num') == '10' ? 'selected' : '';?>>10개</option>
										<option value="20" <?=$this->input->get('tb_num') == '20' ? 'selected' : '';?>>20개</option>
									</select>
								</div>
								<div class="pull-right">
									<button type="button" class="btn btn-alt mr5 mb10" onclick="location.href='<?php echo $excel_url;?>'">엑셀</button>
								</div>
								<!-- END 테이블 옵션  -->
								
							</form>
							<!-- 검색 -->
							

							<form id="object-form-list" action="<?echo $action_url;?>" method="post" class="form-horizontal group-border stripped" role="form">
								<input type="hidden" name="action_type" id="action_type" value="">
								<input type="hidden" name="parameters" id="parameters" value="<?echo $parameters;?>">
								
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
										$anchor = $anchor_url.'&no='.$lt['no'];
										?>
										<tr>
											<td>
												<div class="checkbox-custom">
													<input id="no" name="no[]" class="check" type="checkbox" value="<?echo $lt['no'];?>">
													<label for="check"></label>
												</div>
											</td>
											<td><a href="<?echo $anchor;?>" class="text-normal"><?php echo $lt['order'];?></a></td>
											<td><a href="<?echo $anchor;?>" class="text-normal"><?php echo $lt['menu_name'];?></a></td>
											<td><a href="<?echo $anchor;?>" class="text-normal"><?php echo $lt['name'];?></a></td>
											<td><a href="<?echo $anchor;?>" class="text-normal"><?php echo $lt['area'];?></a></td>
											<td><a href="<?echo $anchor;?>" class="text-normal"><?php echo $lt['created'];?></a></td>
											<td><a href="<?echo $anchor;?>" class="text-normal"><?php echo $lt['user_name'];?></a></td>
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

								<div class="panel-body" style="text-align: center;"><?echo $pagination;?></div>
								<div class="panel-body pull-right">
									<button type="button" class="btn btn-primary btn-alt mr5 mb10" onclick="location.href='<?echo $write_url;?>';">등록</button>
									<button id="btn_list_delete" type="button" class="btn btn-danger btn-alt mr5 mb10">삭제</button>
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
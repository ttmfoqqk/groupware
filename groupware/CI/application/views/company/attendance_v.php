<!-- .page-content -->
<div class="page-content sidebar-page clearfix">
	<!-- .page-content-wrapper -->
	<div class="page-content-wrapper">
		<div class="page-content-inner">
			<!-- .page-content-inner -->
			<div id="page-header" class="clearfix">
				<div class="page-header">
					<h2>근태현황</h2>
				</div>
			</div>
			<div class="row">
			
				
				
				<div class="col-lg-12">
					<!-- col-lg-12 start here -->
					<div class="panel panel-primary toggle">
						<!-- Start .panel -->
						<div class="panel-heading">
							<h4 class="panel-title">
								<i class="fa fa-circle"></i> 회사목록
							</h4>
						</div>
						<div class="panel-body">
							<!-- 검색 -->
							<form id="qu" class="form-horizontal" method="get" role="form" action="<?echo $search_url?>">
								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">등록일자</label>
									<div class="col-lg-6 col-md-6">
										<div class="input-daterange input-group">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<input type="text" class="form-control" name="sData" id="sData" value="<?php echo $this->input->get('sData');?>" />
											<span class="input-group-addon">to</span>
											<input type="text" class="form-control" name="eData" id="eData" value="<?php echo $this->input->get('eData');?>" />
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
									<label class="col-lg-2 col-md-2 control-label" for="">부서</label>
									<div class="col-lg-3 col-md-3">
										<select class="fancy-select form-control" id="menu_no" name="menu_no" data-method="department" data-value="<?echo $this->input->get('menu');?>">
                                        	<option value="">전체</option>
                                        </select>
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">사원명</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" id="name" name="name" placeholder="사원명" value=<?php echo $this->input->get('name');?>>
									</div>
									
									<div class="col-lg-2 col-md-2">
										<button type="submit" class="btn btn-primary btn-alt mr5 mb10">검 색</button>
									</div>
								</div>
								
								<!-- 테이블 옵션  -->
								<div class="pull-left">
									<select class="fancy-select form-control tb_num" id="tb_num" name="tb_num">
										<option value="10" <?=$this->input->get('tb_num') == '10' ? ' selected="selected"' : '';?>>10개</option>
										<option value="20" <?=$this->input->get('tb_num') == '20' ? ' selected="selected"' : '';?>>20개</option>
									</select>
								</div>
								<div class="pull-right">
									<button type="button" class="btn btn-alt mr5 mb10" onclick="location.href='<?php echo $excel_url;?>'">엑셀</button>
								</div>
								<!-- END 테이블 옵션  -->


							</form>
							<!-- 검색 -->

							<form id="company-form-list" method="post" class="form-horizontal group-border stripped" role="form">
								<table class="table table-bordered" id="tabletools">
									<thead>
										<tr>
											<th class="per15">부서</th>
											<th class="per15">사원명</th>
											<th class="per10">출근</th>
											<th class="per10">퇴근</th>
											<th class="per10">지각</th>
											<th class="per15">지각점수</th>
											<th class="per15">근태누적</th>
											<th class="per15">등록일자</th>
										</tr>
									</thead>
									<tbody>
										<!-- 리스트 -->
									<?php
										foreach ( $list as $lt ) {
										$anchor_url = site_url ( 'attendance/write/' . $lt ['no'] );
										?>
										<tr>
											<td><?php echo $lt['menu_name'];?></td>
											<td><?php echo $lt['name'];?></td>
											<td><?php echo $lt['sData'];?></td>
											<td><?php echo $lt['eData'];?></td>
											<td><?php echo $lt['oData'];?></td>
											<td><?php echo $lt['point'];?></td>
											<td></a></td>
											<td><?php echo $lt['created'];?></td>
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

								<div class="panel-body" style="text-align: center;"><?echo $pagination;?></div>
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

<script	src="<?echo $this->config->base_url()?>html/js/sw/sw_attendance_v.js"></script>
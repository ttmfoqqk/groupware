<!-- .page-content -->
<div class="page-content sidebar-page clearfix">
	<!-- .page-content-wrapper -->
	<div class="page-content-wrapper">
		<div class="page-content-inner">
			<!-- .page-content-inner -->
			<div id="page-header" class="clearfix">
				<div class="page-header">
					<h2>근태설정</h2>
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

							<form id="attendance-set-form-list" action="<?echo $action_url;?>"
								method="post" class="form-horizontal group-border stripped"
								role="form">

								<div class="form-group">
									<label class="col-lg-2 col-md-3  text-center">구분</label>
									<div class="col-lg-10 col-md-9">
										<label class="col-lg-3 col-md-3  text-center">출근시간</label>
										<label class="col-lg-3 col-md-3  text-center">퇴근시간</label>
										<label class="col-lg-2 col-md-2  text-center">지각(1분)</label>
										<label class="col-lg-2 col-md-2  text-center">사용여부</label>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-lg-2 col-md-3 text-center" style="font-weight:normal;">주중</label>
									<div class="col-lg-10 col-md-9">
										<div class="row">
											<div class="col-lg-3 col-md-3">
												<div class="input-group bootstrap-timepicker">
                                                	<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                	<input id="start-time1" name="start-time1" type="text" class="form-control" value="<?php echo empty($list[0]) ?  '' : $list[0]['sDate']?>">
                                                </div>
											</div>
											<div class="col-lg-3 col-md-3">
												<div class="input-group bootstrap-timepicker">
                                                	<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                	<input id="end-time1" name="end-time1" type="text" class="form-control" value="<?php echo empty($list[0]) ?  '' : $list[0]['eDate']?>">
                                                </div>
											</div>
											<div class="col-lg-2 col-md-2">
												<input type="text" class="form-control" id="late_1" name="late_1" value=<?php echo empty($list[0]) ?  '' : $list[0]['point']?>>
											</div>
											<div class="col-lg-2 col-md-2">
												<select class="fancy-select form-control" id="isUsed_1" name="isUsed_1">
                                                	<option value=0 <?php if(!empty($list[0]) && $list[0]['is_active'] == 0) echo ' selected="selected"';?>>사용</option>
													<option value=1 <?php if(!empty($list[0]) && $list[0]['is_active'] == 1) echo ' selected="selected"';?>>비사용</option>
                                                </select>
											</div>
										</div>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-lg-2 col-md-3 text-center" style="font-weight:normal;">토요일</label>
									<div class="col-lg-10 col-md-9">
										<div class="row">
											<div class="col-lg-3 col-md-3">
												<div class="input-group bootstrap-timepicker">
                                                	<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                	<input id="start-time2" name="start-time2" type="text" class="form-control" value=<?php echo empty($list[1]) ?  '' : $list[1]['sDate']?>>
                                                </div>
											</div>
											<div class="col-lg-3 col-md-3">
												<div class="input-group bootstrap-timepicker">
                                                	<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                	<input id="end-time2" name="end-time2" type="text" class="form-control" value=<?php echo empty($list[1]) ?  '' : $list[1]['eDate']?>>
                                                </div>
											</div>
											<div class="col-lg-2 col-md-2">
												<input type="text" class="form-control" id="late_2" name="late_2" value=<?php echo empty($list[1]) ?  '' : $list[1]['point']?>>
											</div>
											<div class="col-lg-2 col-md-2">
												<select class="fancy-select form-control" id="isUsed_2" name="isUsed_2">
                                                	<option value=0 <?php if(!empty($list[1]) && $list[1]['is_active'] == 0) echo ' selected="selected"';?>>사용</option>
													<option value=1 <?php if(!empty($list[1]) && $list[1]['is_active'] == 1) echo ' selected="selected"';?>>비사용</option>
                                                </select>
											</div>
										</div>
									</div>
								</div>
								
								<div class="form-group">
									<label class="col-lg-2 col-md-3 text-center" style="font-weight:normal;">공휴일</label>
									<div class="col-lg-10 col-md-9">
										<div class="row">
											<div class="col-lg-3 col-md-3">
												<div class="input-group bootstrap-timepicker">
                                                	<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                	<input id="start-time3" name="start-time3" type="text" class="form-control" value=<?php echo empty($list[2]) ?  '' : $list[2]['sDate']?>>
                                                </div>
											</div>
											<div class="col-lg-3 col-md-3">
												<div class="input-group bootstrap-timepicker">
                                                	<span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                                                	<input id="end-time3" name="end-time3" type="text" class="form-control" value=<?php echo empty($list[2]) ?  '' : $list[2]['eDate']?>>
                                                </div>
											</div>
											<div class="col-lg-2 col-md-2">
												<input type="text" class="form-control" id="late_3" name="late_3" value=<?php echo empty($list[2]) ?  '' : $list[2]['point']?>>
											</div>
											<div class="col-lg-2 col-md-2">
												<select class="fancy-select form-control" id="isUsed_3" name="isUsed_3">
                                                	<option value=0 <?php if(!empty($list[2]) && $list[2]['is_active'] == 0) echo ' selected="selected"';?>>사용</option>
													<option value=1 <?php if(!empty($list[2]) && $list[2]['is_active'] == 1) echo ' selected="selected"';?>>비사용</option>
                                                </select>
											</div>
										</div>
									</div>
								</div>
								

								<div class="panel-body" style="text-align: left;">
									<div class="row">
									<label >지각 또는 조퇴  </label> &nbsp;<input type="text" value="8" size="1"></input> &nbsp; <label>시간 누적 시 연차 1일 공제</label>
									</div>
									<div class="row">
									<label >현재 나의 누적 지각시간 :</label> <label  style="color: red">3시간 22분 </label> &nbsp;&nbsp; <label > 현재 나의 누적 근무시간 : </label> <label style="color: blue">2,112시간 22분</label>
									</div>
									
									
								</div>
								<div class="panel-body pull-right">
									<button type="submit" class="btn btn-primary btn-alt mr5 mb10">등록</button>
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


<script
	src="<?echo $this->config->base_url()?>html/plugins/forms/select2/select2.js"></script>
<script
	src="<?echo $this->config->base_url()?>html/plugins/forms/validation/jquery.validate.js"></script>
<script
	src="<?echo $this->config->base_url()?>html/plugins/forms/bootstrap-timepicker/bootstrap-timepicker.js"></script>
<script
	src="<?echo $this->config->base_url()?>html/js/sw/sw_attendance_setting.js"></script>
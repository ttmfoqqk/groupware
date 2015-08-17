<!-- .page-content -->
<div class="page-content sidebar-page clearfix">
	<!-- .page-content-wrapper -->
	<div class="page-content-wrapper">
		<div class="page-content-inner">
			<!-- .page-content-inner -->
			<div id="page-header" class="clearfix">
				<div class="page-header">
					<h2>&nbsp;</h2>
				</div>
			</div>
			<div class="row">
				
				<div class="col-lg-12">
					<!-- col-lg-12 start here -->
					<div class="panel panel-primary toggle">
						<!-- Start .panel -->
						<div class="panel-heading">
							<h4 class="panel-title"><i class="fa fa-circle"></i> 업무일정</h4>
						</div>
						<div class="panel-body">
							
							<!-- 검색 -->
							<form class="form-horizontal">
								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">진행일자</label>
									<div class="col-lg-6 col-md-6">
										<div class="input-daterange input-group">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<input type="text" class="form-control" name="sData" id="sData" value="<?echo $this->input->get('sData')?>" />
											<span class="input-group-addon">to</span>
											<input type="text" class="form-control" name="eData" id="eData" value="<?echo $this->input->get('eData')?>"/>
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
									<label class="col-lg-2 col-md-2 control-label" for="">담당부서</label>
									<div class="col-lg-3 col-md-3">
										<select id="menu_part_no" name="menu_part_no" data-method="department" data-value="<?echo $this->input->get('menu_part_no');?>" class="fancy-select form-control">
											<option value="">담당부서</option>
										</select>
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">담당자</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" name="userName" class="form-control" placeholder="담당자" value="<?echo $this->input->get('userName')?>">
									</div>
								</div>

								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">분류</label>
									<div class="col-lg-3 col-md-3">
										<select id="menu_no" name="menu_no" data-method="project" data-value="<?echo $this->input->get('menu_no');?>" class="fancy-select form-control">
											<option value="">분류</option>
										</select>
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">제목</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" name="title" class="form-control" placeholder="제목" value="<?echo $this->input->get('title')?>">
									</div>

									<div class="col-lg-2 col-md-2">
										<button type="submit" class="btn btn-primary btn-alt mr5 mb10"> 검 색</button>
									</div>
								</div>


							</form>
							<!-- 검색 -->


							<div class="row col-xs-12">gantt</div>

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

<script src="<?echo $this->config->base_url()?>html/js/sw/sw_project.js"></script>
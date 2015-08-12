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
							<h4 class="panel-title"><i class="fa fa-circle"></i> 추가평점목록</h4>
						</div>
						<div class="panel-body">
							
							<!-- 검색 -->
							<form class="form-horizontal">
								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">등록일자</label>
									<div class="col-lg-6 col-md-6">
										<div class="input-daterange input-group">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<input type="text" class="form-control" name="start" />
											<span class="input-group-addon">to</span>
											<input type="text" class="form-control" name="end" />
										</div>
									</div>
									<div class="col-lg-4 col-md-4">
										<button type="button" class="btn btn-sm btn-primary btn-alt">오늘</button>
										<button type="button" class="btn btn-sm btn-primary btn-alt">7일</button>
										<button type="button" class="btn btn-sm btn-primary btn-alt">30일</button>
										<button type="button" class="btn btn-sm btn-primary btn-alt">날짜초기화</button>
									</div>
								</div>

								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">담당부서</label>
									<div class="col-lg-3 col-md-3 col-sm-3 col-sm-3">
										<select id="board_type" name="board_type" class="fancy-select form-control">
											<option value="담당부서">담당부서</option>
										</select>
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">사원명</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" placeholder="사원명">
									</div>
								</div>

								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">사유</label>
									<div class="col-lg-3 col-md-3">
										<select id="board_type" name="board_type" class="fancy-select form-control">
											<option value="사유">사유</option>
										</select>
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">점수</label>
									<div class="col-lg-3 col-md-3">
										<select id="board_type" name="board_type" class="fancy-select form-control">
											<option value="점수">점수</option>
										</select>
									</div>

									<div class="col-lg-2 col-md-2">
										<button type="submit" class="btn btn-primary btn-alt mr5 mb10"> 검 색</button>
									</div>
								</div>


							</form>
							<!-- 검색 -->

							<table class="table table-bordered" id="tabletools">
								<thead>
									<tr>
										<th class="per15">부서</th>
										<th class="per15">사원명</th>
										<th>사유</th>
										<th class="per15">-점수</th>
										<th class="per15">-누적</th>
										<th class="per15">등록일자</th>
										<th class="per15">&nbsp;</th>
									</tr>
								</thead>
								<tbody>
									<!-- 리스트 -->
									<tr>
										<td>부서</td>
										<td>사원명</td>
										<td>사유</td>
										<td>점수</td>
										<td>누적</td>
										<td>등록일자</td>
										<td>&nbsp;</td>
									</tr>
									<!-- 리스트 -->
								</tbody>
							</table>

							<div class="panel-body" style="text-align:center;"><?echo $pagination;?></div>

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

<script src="<?echo $this->config->base_url()?>html/js/sw/sw_company.js"></script>
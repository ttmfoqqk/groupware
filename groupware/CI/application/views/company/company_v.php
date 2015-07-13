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
							<h4 class="panel-title"><i class="fa fa-circle"></i> 회사목록</h4>
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
									<label class="col-lg-2 col-md-2 control-label" for="">구분</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" placeholder="구분">
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">상호명</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" placeholder="상호명">
									</div>
								</div>

								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">사업자번호</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" placeholder="사업자번호">
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">전화번호</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" placeholder="전화번호">
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
										<th style="width:45px;">
											<div class="checkbox-custom">
												<input class="check-all" type="checkbox" id="masterCheck" value="option1">
												<label for="masterCheck"></label>
											</div>
										</th>
										<th style="width:50px;">순서</th>
										<th class="per10">구분</th>
										<th>상호명</th>
										<th class="per15">사업자번호</th>
										<th class="per15">전화번호</th>
										<th class="per15">팩스번호</th>
										<th class="per10">등록일자</th>
										<th style="width:60px;">담당자</th>
										<th style="width:60px;">사이트</th>
									</tr>
								</thead>
								<tbody>
									<!-- 리스트 -->
									<tr>
										<td>
											<div class="checkbox-custom">
												<input id="check" class="check" type="checkbox" value="option2">
												<label for="check"></label>
											</div>
										</td>
										<td>1</td>
										<td>마케팅</td>
										<td>유업비즈</td>
										<td>113-22-19694</td>
										<td>02-6455-5628</td>
										<td>02-6455-5628</td>
										<td>02-6455-5628</td>
										<td class="text-center">
											<button type="button" class="btn btn-success btn-xs" id="view_staff" onclick="alert('담당자 팝업');"><i class="glyphicon glyphicon-user"></i></button>
										</td>
										<td class="text-center">
											<button type="button" class="btn btn-primary btn-xs" id="view_staff" onclick="alert('담당자 팝업');"><i class="glyphicon glyphicon-globe"></i></button>
										</td>
									</tr>
									<!-- 리스트 -->
								</tbody>
							</table>

							<div class="panel-body" style="text-align:center;">test pagination 공통 함수 작성 요청<br><?echo $pagination;?></div>

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
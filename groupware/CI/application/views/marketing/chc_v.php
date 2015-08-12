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
							<h4 class="panel-title"><i class="fa fa-circle"></i> CHC목록</h4>
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

									<label class="col-lg-2 col-md-2 control-label" for="">담당자</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" placeholder="담당자">
									</div>
								</div>

								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">고객사</label>
									<div class="col-lg-3 col-md-3 col-sm-3 col-sm-3">
										<input type="text" class="form-control" placeholder="고객사">
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">키워드</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" placeholder="키워드">
									</div>
								</div>

								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">제목</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" placeholder="제목">
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">네이버순위</label>
									<div class="col-lg-3 col-md-3">
										<select id="board_type" name="board_type" class="fancy-select form-control">
											<option value="네이버순위">네이버순위</option>
										</select>
									</div>

									<div class="col-lg-2 col-md-2">
										<button type="submit" class="btn btn-primary btn-alt mr5 mb10"> 검 색</button>
									</div>
								</div>


							</form>
							<!-- 검색 -->
							<div class="pull-left">
								<select id="board_type" name="board_type" class="fancy-select form-control">
									<option value="10">10개</option>
								</select>
							</div>
							<div class="pull-right">
								<button type="submit" class="btn btn-alt mr5 mb10">엑셀</button>
							</div>

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
										<th class="per8">등록일자</th>
										<th class="per8">분류</th>
										<th class="per8">제목</th>
										<th class="per8">고객사</th>
										<th>키워드</th>
										<th class="per8">URL</th>
										<th class="per8">답변수</th>
										<th class="per8">진행기간</th>
										<th class="per8">네이버순위</th>
										<th class="per8">노출률</th>
										<th class="per8">작업수</th>
										<th style="width:60px;">그래프</th>
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
										<td>순서</td>
										<td>등록일자</td>
										<td>분류</td>
										<td>제목</td>
										<td>고객사</td>
										<td>키워드</td>
										<td>URL</td>
										<td>답변수</td>
										<td>진행기간</td>
										<td>네이버순위</td>
										<td>노출률</td>
										<td>작업수</td>
										<td class="text-center">
											<button type="button" class="btn btn-success btn-xs" id="view_staff" onclick="alert('그래프 팝업');"><i class="glyphicon glyphicon-signal"></i></button>
										</td>
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
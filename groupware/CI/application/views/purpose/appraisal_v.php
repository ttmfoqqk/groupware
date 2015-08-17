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
							<h4 class="panel-title"><i class="fa fa-circle"></i> 업무평가</h4>
						</div>
						<div class="panel-body">
							
							<!-- 검색 -->
							<form class="form-horizontal">
								<div class="form-group">
									
									<label class="col-lg-2 col-md-2 col-sm-2 control-label" for="">평가일자</label>

									<div class="col-lg-3 col-md-3 col-sm-3">
										<select id="board_type" name="board_type" class="fancy-select form-control">
											<option value="월별">월별</option>
											<option value="분기별" >분기별</option>
										</select>
									</div>
									
									<div class="col-lg-3 col-md-3 col-sm-3">
										<select id="board_type" name="board_type" class="fancy-select form-control">
											<option value="2015">2015</option>
										</select>
									</div>

									<div class="col-lg-3 col-md-3 col-sm-3">
										<select id="board_type" name="board_type" class="fancy-select form-control">
											<option value="1기">1기</option>
											<option value="2기">2기</option>
											<option value="3기">3기</option>
											<option value="4기">4기</option>
											<option value="1기+2기">1기+2기</option>
											<option value="1기+2기+3기">1기+2기+3기</option>
											<option value="1기+2기+3기+4기">1기+2기+3기+4기</option>
										</select>
									</div>

								</div>

								<div class="form-group">
									<label class="col-lg-2 col-md-2 col-sm-2 control-label" for="">담당부서</label>
									<div class="col-lg-3 col-md-3 col-sm-3 col-sm-3">
										<select id="board_type" name="board_type" class="fancy-select form-control">
											<option value="담당부서">담당부서</option>
										</select>
									</div>

									<label class="col-lg-2 col-md-2 col-sm-2 control-label" for="">담당자</label>
									<div class="col-lg-3 col-md-3 col-sm-3 col-sm-3">
										<select id="board_type" name="board_type" class="fancy-select form-control">
											<option value="담당자">담당자</option>
										</select>
									</div>
								</div>

								<div class="form-group">
									<label class="col-lg-2 col-md-2 col-sm-2 control-label" for="">분류</label>
									<div class="col-lg-3 col-md-3 col-sm-3">
										<select id="board_type" name="board_type" class="fancy-select form-control">
											<option value="분류">분류</option>
										</select>
									</div>

									<label class="col-lg-2 col-md-2 col-sm-2 control-label" for="">제목</label>
									<div class="col-lg-3 col-md-3 col-sm-3">
										<input type="text" class="form-control" placeholder="제목">
									</div>

									<div class="col-lg-2 col-md-2">
										<button type="submit" class="btn btn-primary btn-alt mr5 mb10"> 검 색</button>
									</div>
								</div>


							</form>
							<!-- 검색 -->

							<div class="panel hidden-sm hidden-xs">
								<!-- Start .panel -->
								<div class="panel-body text-center">
									<div class="col-lg-3 col-md-3">
										<b>등록점수</b>
									</div>
									<div class="col-lg-3 col-md-3">
										<b>+점수</b>
									</div>
									<div class="col-lg-3 col-md-3">
										<b>-점수</b>
									</div>
									<div class="col-lg-3 col-md-3">
										<b class="text-primary">전자결재평점</b>
									</div>
								</div>
							</div>
							<div class="panel-primary">
								<div class="panel-body text-center">
									
									<div class="panel hidden-lg hidden-md visible-sm visible-xs">
										<div class="panel-body text-center">
											<b>등록점수</b>
										</div>
									</div>
									<div class="col-lg-3 col-md-3">
										<div class="pie-charts green-pie">
											<div class="easy-pie-chart" data-percent="28">28%</div>
										</div>
									</div>

									<div class="panel hidden-lg hidden-md visible-sm visible-xs">
										<div class="panel-body text-center">
											<b>+점수</b>
										</div>
									</div>
									<div class="col-lg-3 col-md-3">
										<div class="pie-charts green-pie">
											<div class="easy-pie-chart-green" data-percent="28">28%</div>
										</div>
										<div style="position:absolute;top:33%;left:100%;font-size:30px;" class="hidden-sm hidden-xs">-</div>
									</div>

									<div class="panel hidden-lg hidden-md visible-sm visible-xs">
										<div class="panel-body text-center">
											<b>-점수</b>
										</div>
									</div>									
									<div class="col-lg-3 col-md-3">
										<div class="pie-charts green-pie">
											<div class="easy-pie-chart-green" data-percent="28">28%</div>
										</div>
										<div style="position:absolute;top:33%;left:100%;font-size:30px;" class="hidden-sm hidden-xs">=</div>
									</div>

									<div class="panel hidden-lg hidden-md visible-sm visible-xs">
										<div class="panel-body text-center">
											<b class="text-primary">전자결재평점</b>
										</div>
									</div>									
									<div class="col-lg-3 col-md-3">
										<div class="pie-charts green-pie">
											<div class="easy-pie-chart-blue" data-percent="28">28%</div>
										</div>
									</div>


								</div>
							</div>

							<div class="panel hidden-sm hidden-xs">
								<!-- Start .panel -->
								<div class="panel-body text-center">
									<div class="col-lg-3 col-md-3">
										<b>평균순위(점수)</b>
									</div>
									<div class="col-lg-3 col-md-3">
										<b>노출률</b>
									</div>
									<div class="col-lg-3 col-md-3">
										<b>문장평가</b>
									</div>
									<div class="col-lg-3 col-md-3">
										<b class="text-primary">CHC평점</b>
									</div>
								</div>
							</div>
							<div class="panel-primary">
								<div class="panel-body text-center">

									<div class="panel hidden-lg hidden-md visible-sm visible-xs">
										<div class="panel-body text-center">
											<b>평균순위(점수)</b>
										</div>
									</div>									
									<div class="col-lg-3 col-md-3">
										<div class="pie-charts green-pie">
											<div class="easy-pie-chart-green" data-percent="28">28%</div>
										</div>
									</div>

									<div class="panel hidden-lg hidden-md visible-sm visible-xs">
										<div class="panel-body text-center">
											<b>노출률</b>
										</div>
									</div>
									<div class="col-lg-3 col-md-3">
										<div class="pie-charts green-pie">
											<div class="easy-pie-chart-green" data-percent="28">28%</div>
										</div>
									</div>

									<div class="panel hidden-lg hidden-md visible-sm visible-xs">
										<div class="panel-body text-center">
											<b>문장평가</b>
										</div>
									</div>
									<div class="col-lg-3 col-md-3">
										<div class="pie-charts green-pie">
											<div class="easy-pie-chart-green" data-percent="28">28%</div>
										</div>
									</div>

									<div class="panel hidden-lg hidden-md visible-sm visible-xs">
										<div class="panel-body text-center">
											<b class="text-primary">CHC평점</b>
										</div>
									</div>
									<div class="col-lg-3 col-md-3">
										<div class="pie-charts blue-pie">
											<div class="easy-pie-chart-blue" data-percent="28">28%</div>
										</div>
									</div>
								</div>
							</div>

							

							<div class="panel hidden-sm hidden-xs">
								<!-- Start .panel -->
								<div class="panel-body text-center">
									<div class="col-lg-3 col-md-3">
										<b class="text-primary">전자결재평점</b>
									</div>
									<div class="col-lg-3 col-md-3">
										<b class="text-primary">CHC평점</b>
									</div>
									<div class="col-lg-3 col-md-3">
										<b class="text-primary">추가평점</b>
									</div>
									<div class="col-lg-3 col-md-3">
										<b class="text-danger">총점</b>
									</div>
								</div>
							</div>
							<div class="panel-primary">
								<div class="panel-body text-center">
									
									<div class="panel hidden-lg hidden-md visible-sm visible-xs">
										<div class="panel-body text-center">
											<b class="text-primary">전자결재평점</b>
										</div>
									</div>
									<div class="col-lg-3 col-md-3">
										<div class="pie-charts blue-pie">
											<div class="easy-pie-chart-blue" data-percent="65">65%</div>
										</div>
									</div>

									<div class="panel hidden-lg hidden-md visible-sm visible-xs">
										<div class="panel-body text-center">
											<b class="text-primary">CHC평점</b>
										</div>
									</div>
									<div class="col-lg-3 col-md-3">
										<div class="pie-charts blue-pie">
											<div class="easy-pie-chart-blue" data-percent="65">65%</div>
										</div>
									</div>

									<div class="panel hidden-lg hidden-md visible-sm visible-xs">
										<div class="panel-body text-center">
											<b class="text-primary">추가평점</b>
										</div>
									</div>
									<div class="col-lg-3 col-md-3">
										<div class="pie-charts blue-pie">
											<div class="easy-pie-chart-blue" data-percent="65">65%</div>
										</div>
									</div>

									<div class="panel hidden-lg hidden-md visible-sm visible-xs">
										<div class="panel-body text-center">
											<b class="text-danger">총점</b>
										</div>
									</div>
									<div class="col-lg-3 col-md-3">
										<div class="pie-charts red-pie">
											<div class="easy-pie-chart-red" data-percent="65">65%</div>
										</div>
									</div>

								</div>
							</div>


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
<script src="<?echo $this->config->base_url()?>html/plugins/core/pace/pace.min.js"></script>
<script src="<?echo $this->config->base_url()?>html/plugins/charts/sparklines/jquery.sparkline.js"></script>
<script src="<?echo $this->config->base_url()?>html/plugins/charts/pie-chart/jquery.easy-pie-chart.js"></script>
<script src="<?echo $this->config->base_url()?>html/js/sw/sw_purpose.js"></script>
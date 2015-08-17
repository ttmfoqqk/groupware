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

							<form id="company-form-list" action="<?echo $action_url;?>"
								method="post" class="form-horizontal group-border stripped"
								role="form">
								<input type="hidden" name="action_type" id="action_type"
									value="<?echo $action_type;?>">
								<table class="table table-bordered" id="tabletools">
									<thead>
										<tr>
											<th class="per5">구분</th>
											<th class="per15">출근시간</th>
											<th class="per15">퇴근시간</th>
											<th class="per15">지각(1분)</th>
											<th class="per5">사용여부</th>
										</tr>
									</thead>
									<tbody>
										<!-- 리스트 -->
										<tr>
											<td>주중</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<td>토요일</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<td>공휴일</td>
											<td></td>
											<td></td>
											<td></td>
											<td></td>
										</tr>
									<!-- 리스트 -->
									
									
								</tbody>
								</table>

								<div class="panel-body" style="text-align: center;">
									</div>
								<div class="panel-body pull-right">
									<button type="button" class="btn btn-primary btn-alt mr5 mb10"
										onclick="location.href='<?echo site_url('company_setting/write/') . '/';?>';">등록</button>
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
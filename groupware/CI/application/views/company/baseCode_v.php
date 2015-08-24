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
					<div class="panel panel-primary">
						
						<div class="panel-heading">
							<h4 class="panel-title"><i class="fa fa-circle"></i> 코드목록 </h4>
						</div>
						
						<!-- START .panel-body -->
						<div class="panel-body">
							<!-- START .col-lg-6 col-md-6 -->
							<div class="col-lg-6 col-md-6 ">
								<table class="table table-bordered" id="tbKey">
									<thead>
										<tr>
											<th style="min-width: 50px;">순서</th>
											<th style="min-width: 100px;" class="per15">KEY</th>
											<th style="min-width: 300px; ">내용</th>
											<th style="min-width: 60px;">수정</th>
										</tr>
									</thead>
									<tbody id="keyBody">
									</tbody>
								</table>
								<button id="btKeyAdd" type="button" class="btn btn-primary btn-alt mr5 mb10">등록</button>
							</div>
							<!-- END .col-lg-6 .col-md-6 -->
							<!-- START .col-lg-6 .col-md-6 -->
							<div class="col-lg-6 col-md-6 ">
								<table class="table table-bordered" id="tbCode">
									<thead>
										<tr>
											<th style="min-width: 45px; width: 45px;">
												<div class="checkbox-custom">
													<input class="check-all" type="checkbox" id="masterCheck"
														value="option1"> <label for="masterCheck"></label>
												</div>
											</th>
											<th style="min-width: 50px;">순서</th>
											<th style="min-width: 50px;">IDX</th>
											<th style="min-width: 200px;">내용</th>
											<th style="min-width: 80px;">사용 여부</th>
										</tr>
									</thead>
									<tbody  id="codeBody">
									</tbody>
								</table>
								<button id="btCodeAdd" type="button" class="btn btn-primary btn-alt mr5 mb10">등록</button>
								<button id="btCodeDel" type="button" class="btn btn-primary btn-alt mr5 mb10">삭제</button>
							</div>
							<!-- END .col-lg-6 .col-md-6 -->

						</div>
						<!-- END .panel-body -->


					</div>
				</div>
			
			</div>
			
			<!-- End .row -->
		</div>
		<!-- / .page-content-inner -->
	</div>
	<!-- / page-content-wrapper -->
</div>
<!-- / page-content -->

<script src="<?echo $this->config->base_url()?>html/js/sw/sw_basecode.js"></script>
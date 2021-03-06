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
							<h4 class="panel-title"><i class="fa fa-circle"></i> 금일현황</h4>
						</div>
						<div class="panel-body">
							<!-- 출/퇴근 버튼 -->
							<form method="post" role="form" action="<?echo site_url('main/start')?>">
								<div class="row text-center">
									<div class="col-lg-6 col-md-6 col-sm-6">
										<div class="col-lg-6 col-md-6 col-sm-6">
											<h4>출근 시간 : </h4>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-6">
											<h4><?php echo isset($atn['sData']) ? date('H시 i분 s초', strtotime($atn['sData'])) : ''?></h4>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6"><button type="submit" class="btn btn-primary btn-alt btn-lg mr5 mb10" id="btS">출근체크</button></div>
								</div>
							</form>
							<form method="post" role="form" action="<?echo site_url('main/end')?>">
								<div class="row text-center">
									<div class="col-lg-6 col-md-6 col-sm-6">
										<div class="col-lg-6 col-md-6 col-sm-6">
											<h4>퇴근 시간 : </h4>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-6">
											<h4 id="eDate"><?php echo isset($atn['eData']) ? date('H시 i분 s초', strtotime($atn['eData'])) : ''?></h4>
										</div>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6"><button type="submit" class="btn btn-primary btn-alt btn-lg mr5 mb10" id="btE">퇴근체크</button></div>
								</div>
							</form>
							<!-- 출/퇴근 버튼 -->

							<table class="table table-bordered table-striped text-center">
								<thead>
									<tr>
										<th class="per50 text-center">내가받은결재</th>
										<th class="per50 text-center">내가올린결재</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><a href="<?echo $anchor_s['a'];?>" class="text-normal">작업중 <span class="<?echo $class_s['a'];?>">( <?echo $sender['a'];?> )</span></a></td>
										<td><a href="<?echo $anchor_r['a'];?>" class="text-normal">작업중 <span class="<?echo $class_r['a'];?>">( <?echo $receiver['a'];?> )</span></a></td>
									</tr>
									<tr>
										<td><a href="<?echo $anchor_s['b'];?>" class="text-normal">결재대기 <span class="<?echo $class_s['b'];?>">( <?echo $sender['b'];?> )</span></a></td>
										<td><a href="<?echo $anchor_r['b'];?>" class="text-normal">결재대기 <span class="<?echo $class_r['b'];?>">( <?echo $receiver['b'];?> )</span></a></td>
									</tr>
									<tr>
										<td><a href="<?echo $anchor_s['c'];?>" class="text-normal">결재완료 <span class="<?echo $class_s['c'];?>">( <?echo $sender['c'];?> )</span></a></td>
										<td><a href="<?echo $anchor_r['c'];?>" class="text-normal">결재완료 <span class="<?echo $class_r['c'];?>">( <?echo $receiver['c'];?> )</span></a></td>
									</tr>
									<tr>
										<td><a href="<?echo $anchor_s['d'];?>" class="text-normal">반려 <span class="<?echo $class_s['d'];?>">( <?echo $sender['d'];?> )</span></a></td>
										<td><a href="<?echo $anchor_r['d'];?>" class="text-normal">반려 <span class="<?echo $class_r['d'];?>">( <?echo $receiver['d'];?> )</span></a></td>
									</tr>
									<tr>
										<td><a href="<?echo $anchor_s['ao'];?>" class="text-normal">미결재 <span class="<?echo $class_s['ao'];?>">( <?echo $sender['ao'];?> )</span></a></td>
										<td><a href="<?echo $anchor_r['ao'];?>" class="text-normal">미결재 <span class="<?echo $class_r['ao'];?>">( <?echo $receiver['ao'];?> )</span></a></td>
									</tr>
								</tbody>
							</table>
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


<script src="<?echo $this->config->base_url()?>html/js/sw/sw_main.js"></script>
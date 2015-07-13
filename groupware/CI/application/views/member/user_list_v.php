<!-- .page-content -->
<div class="page-content sidebar-page clearfix">
	<!-- .page-content-wrapper -->
	<div class="page-content-wrapper">
		<div class="page-content-inner">
			<!-- .page-content-inner -->
			<div id="page-header" class="clearfix">
				<div class="page-header">
					<h2>사원</h2>
					<span class="txt">사원</span>
				</div>
			</div>
			<div class="row">
				<!-- col-lg-12 end here -->
				<div class="col-lg-12">
					<!-- col-lg-12 start here -->
					<div class="panel panel-default">
						<!-- Start .panel -->
						<div class="panel-heading">
							<h4 class="panel-title">사원목록</h4>
						</div>
						<div class="panel-body">
							
							<table id="tabletools_" class="table table-striped table-bordered" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>아이디</th>
										<th>이름</th>
										<th>부서</th>
										<th>이메일</th>
										<th>생성일</th>
									</tr>
								</thead>
								<tbody>
								<?foreach($list as $lt){
									$anchor_url = site_url('member/write/'.$lt['no']);
									?>
									<tr>
										<td><a href="<?echo $anchor_url;?>" class="text-normal"><?echo $lt['id'];?></a></td>
										<td><a href="<?echo $anchor_url;?>" class="text-normal"><?echo $lt['name'];?></a></td>
										<td><a href="<?echo $anchor_url;?>" class="text-normal"><?echo $lt['organization_name'];?></a></td>
										<td><a href="<?echo $anchor_url;?>" class="text-normal"><?echo $lt['email'];?></a></td>
										<td><a href="<?echo $anchor_url;?>" class="text-normal"><?echo $lt['created'];?></a></td>
									</tr>
								<?}?>
								</tbody>
							</table>

							<div class="panel-body pull-right">
								<button type="button" class="btn btn-primary btn-alt mr5 mb10" onclick="location.href='<?echo site_url('member/write')?>';">입력</button>
							</div>
						</div>
					</div>
					<!-- End .panel -->
				</div>
				<!-- col-lg-12 end here -->
			</div>
			
			<!-- End .row -->
		</div>
		<!-- / .page-content-inner -->
	</div>
	<!-- / page-content-wrapper -->
</div>
<!-- / page-content -->


<script src="/html/plugins/tables/datatables/jquery.dataTables.js"></script>
<script src="/html/plugins/tables/datatables/dataTables.tableTools.js"></script>
<script src="/html/plugins/tables/datatables/dataTables.bootstrap.js"></script>
<script src="/html/plugins/tables/datatables/dataTables.responsive.js"></script>

<script src="/html/plugins/forms/bootstrap-filestyle/bootstrap-filestyle.js"></script>
<script src="/html/plugins/forms/select2/select2.js"></script>
<script src="/html/plugins/forms/validation/jquery.validate.js"></script>
<script src="/html/plugins/forms/validation/additional-methods.min.js"></script>
<script src="/html/plugins/charts/sparklines/jquery.sparkline.js"></script>

<script src="/html/plugins/forms/checkall/jquery.checkAll.js"></script>

<script src="/html/js/sw/sw_user_list.js"></script>
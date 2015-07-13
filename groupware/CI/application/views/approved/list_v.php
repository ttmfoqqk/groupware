<!-- .page-content -->
<div class="page-content sidebar-page clearfix">
	<!-- .page-content-wrapper -->
	<div class="page-content-wrapper">
		<div class="page-content-inner">
			<!-- .page-content-inner -->
			<div id="page-header" class="clearfix">
				<div class="page-header">
					<h2>결재 - <?echo PAGE_TITLE;?></h2>
					<span class="txt">결재 - <?echo PAGE_TITLE;?></span>
				</div>
			</div>
			<div class="row">
				<!-- col-lg-12 end here -->
				<div class="col-lg-12">
					<!-- col-lg-12 start here -->
					<div class="panel panel-default">
						<!-- Start .panel -->
						<div class="panel-heading">
							<h4 class="panel-title"><?echo PAGE_TITLE;?></h4>
						</div>
						<div class="panel-body">
							<table id="tabletools" class="table table-bordered" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th style="width:50px;">
											<div class="checkbox-custom">
												<input class="check-all" type="checkbox" id="masterCheck" value="option1">
												<label for="masterCheck"></label>
											</div>
										</th>
										<th>제목</th>
										<th class="per10">작성자</th>
										<th class="per10">등록일</th>
										<th class="per5">조회</th>
									</tr>
								</thead>
								<tbody>
								<?foreach($list as $lt){?>
									<tr>
										<td>
											<div class="checkbox-custom">
												<input id="check" class="check" type="checkbox" value="option2">
												<label for="check"></label>
											</div>
										</td>
										<td><?echo $lt['name'];?></td>
										<td>Developer</td>
										<td>2530$</td>
										<td>2530$</td>
									</tr>
								<?}
								if( count($list) <= 0 ){?>
									<tr>
										<td colspan="5">등록된 내용이 없습니다.</td>
									</tr>
								<?}?>
								</tbody>
							</table>
							<div class="panel-body pull-right">
								<button id="btn_list_delete" type="button" class="btn btn-danger btn-alt mr5 mb10" onclick="alert('준비중');">삭제</button>
								<button type="button" class="btn btn-primary btn-alt mr5 mb10" onclick="alert('준비중');">등록</button>
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
<!-- .page-content -->
<link href="/html/plugins/Nestable-master/nestable.css" rel="stylesheet" />
<div class="page-content sidebar-page clearfix">
	<!-- .page-content-wrapper -->
	<div class="page-content-wrapper">
		<div class="page-content-inner">
			<!-- .page-content-inner -->
			<div id="page-header" class="clearfix">
				<div class="page-header">
					<h2>조직도</h2>
					<span class="txt">조직도</span>
				</div>
			</div>
			<div class="row">

				<!-- col-lg-6 end here -->
				<div class="col-lg-12 col-md-12">
					<!-- col-lg-6 start here -->
					<div class="panel panel-default">
						<!-- Start .panel -->
						<div class="panel-heading">
							<h4 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> 조직도</h4>
						</div>
						<div class="panel-body">
							<div class="dd" id="nestable3" style="padding-bottom:100px;">
								<?echo $tree;?>
							</div>

						</div>
						<div class="panel-body">
							<button id="category_add" type="button" class="btn btn-primary btn-alt mr5 mb10">등록</button>
						</div>
					</div>
					<!-- End .panel -->
				</div>
				<!--textarea class="form-control" id="nestable-output"></textarea-->
				<!-- col-lg-6 end here -->


			</div>			
			<!-- End .row -->
		</div>
		<!-- / .page-content-inner -->
	</div>
	<!-- / page-content-wrapper -->
</div>
<!-- / page-content -->
<script src="/html/plugins/ui/nestable/jquery.nestable.js"></script>
<script src="/html/js/sw/sw_organization.js"></script>
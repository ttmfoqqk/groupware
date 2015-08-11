<!-- .page-content -->
<div class="page-content sidebar-page clearfix">
	<!-- .page-content-wrapper -->
	<div class="page-content-wrapper">
		<div class="page-content-inner">
			<!-- .page-content-inner -->
			<div id="page-header" class="clearfix">
				<div class="page-header">
					<h2><?echo MENU_NAME;?></h2>
					<span class="text">디자인 변경 체크 , 색상 적용 작업</span>
				</div>
			</div>
			<div class="row">
				<div class="panel-body">
					<div class="dd" id="nestable3" data-key="<?echo $key;?>" style="padding-bottom:100px;">
						<?echo $tree;?>
					</div>

				</div>
				<div class="panel-body">
					<button id="category_add" type="button" class="btn btn-primary btn-alt mr5 mb10">등록</button>
				</div>
			</div>
			
			<!-- End .row -->
		</div>
		<!-- / .page-content-inner -->
	</div>
	<!-- / page-content-wrapper -->
</div>
<!-- / page-content -->
<script src="<?echo $this->config->base_url()?>html/plugins/ui/nestable/jquery.nestable.js"></script>
<script src="<?echo $this->config->base_url()?>html/js/sw/sw_organization.js"></script>
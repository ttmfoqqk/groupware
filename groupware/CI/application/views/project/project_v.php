<!-- .page-content -->
<div class="page-content sidebar-page clearfix">
	<!-- .page-content-wrapper -->
	<div class="page-content-wrapper">
		<div class="page-content-inner">
			<!-- .page-content-inner -->
			<div id="page-header" class="clearfix">
				<div class="page-header">
					<h2>업무정보</h2>
				</div>
			</div>
			<div class="row">
				
				<div class="col-lg-12">
					<!-- col-lg-12 start here -->
					<div class="panel panel-primary toggle">
						<!-- Start .panel -->
						<div class="panel-heading">
							<h4 class="panel-title"><i class="fa fa-circle"></i> 업무목록</h4>
						</div>
						<div class="panel-body">
							
							<!-- 검색 -->
							<form class="form-horizontal" action="<?php echo site_url('project/lists/');?>">
								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">진행기간</label>
									<div class="col-lg-6 col-md-6">
										<div class="input-daterange input-group">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<input type="text" class="form-control" name="sData" id="sData" value="<?echo $this->input->get('sData')?>" />
											<span class="input-group-addon">to</span>
											<input type="text" class="form-control" name="eData" id="eData" value="<?echo $this->input->get('eData')?>"/>
										</div>
									</div>
									<div class="col-lg-4 col-md-4">
										<button type="button" class="btn btn-sm btn-primary btn-alt" id="sToday">오늘</button>
										<button type="button" class="btn btn-sm btn-primary btn-alt" id="sWeek">7일</button>
										<button type="button" class="btn btn-sm btn-primary btn-alt" id="sMonth">30일</button>
										<button type="button" class="btn btn-sm btn-primary btn-alt" id="sReset">날짜초기화</button>
									</div>
								</div>
								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">기안일자</label>
									<div class="col-lg-6 col-md-6">
										<div class="input-daterange input-group">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<input type="text" class="form-control" name="swData" id="swData" value="<?echo $this->input->get('swData')?>"/>
											<span class="input-group-addon">to</span>
											<input type="text" class="form-control" name="ewData" id="ewData" value="<?echo $this->input->get('ewData')?>"/>
										</div>
									</div>
									<div class="col-lg-4 col-md-4">
										<button type="button" class="btn btn-sm btn-primary btn-alt" id="swToday">오늘</button>
										<button type="button" class="btn btn-sm btn-primary btn-alt" id="swWeek">7일</button>
										<button type="button" class="btn btn-sm btn-primary btn-alt" id="swMonth">30일</button>
										<button type="button" class="btn btn-sm btn-primary btn-alt" id="swReset">날짜초기화</button>
									</div>
								</div>

								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">담당부서</label>
									<div class="col-lg-3 col-md-3">
										<select id="menu_part_no" name="menu_part_no" data-method="department" data-value="<?echo $this->input->get('menu_part_no');?>" class="fancy-select form-control">
											<option value="">담당부서</option>
										</select>
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">담당자</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" name="userName" class="form-control" placeholder="담당자" value="<?echo $this->input->get('userName')?>">
									</div>
								</div>

								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">분류</label>
									<div class="col-lg-3 col-md-3">
										<select id="menu_no" name="menu_no" data-method="project" data-value="<?echo $this->input->get('menu_no');?>" class="fancy-select form-control">
											<option value="">분류</option>
										</select>
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">제목</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" name="title" class="form-control" placeholder="제목" value="<?echo $this->input->get('title')?>">
									</div>

									<div class="col-lg-2 col-md-2">
										<button type="submit" class="btn btn-primary btn-alt mr5 mb10"> 검 색</button>
									</div>
								</div>
								
								<div class="pull-left">
									<select class="fancy-select form-control tb_num" id="tb_num" name="tb_num">
										<option value="10" <?=$this->input->get('tb_num') == '10' ? ' selected="selected"' : '';?>>10개</option>
										<option value="20" <?=$this->input->get('tb_num') == '20' ? ' selected="selected"' : '';?>>20개</option>
									</select>
								</div>
								<div class="pull-right">
									<button type="button" class="btn btn-alt mr5 mb10" onclick="location.href='<?php echo $excel_url;?>'">엑셀</button>
								</div>


							</form>
							<!-- 검색 -->

							<form id="project-form-list" action="<?echo $action_url;?>" method="post" class="form-horizontal group-border stripped" role="form">
							<input type="hidden" name="action_type" id="action_type" value="delete">
							<input type="hidden" name="parameters" id="parameters" value="<?echo $parameters;?>">
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
										<th class="per15">담당부서</th>
										<th class="per15">분류</th>
										<th >제목</th>
										<th class="per15">진행기간</th>
										<th class="per5">결재점수</th>
										<th class="per5">누락점수</th>
										<th class="per10">기안일자</th>
										<th class="per10">기안자</th>
										<th style="width:60px;">담당자</th>
									</tr>
								</thead>
								<tbody>
								<?php foreach($list as $lt){
									$anchor = $anchor_url.'&no='.$lt['no'];
									$part = search_node($lt['part_no'],'parent');
									$menu = search_node($lt['menu_no'],'parent');
									?>
									<tr <?if( $lt['cnt'] > 0 ){echo 'style="background-color:#f5f5f5;"';}?>>
										<td>
										<?if( $lt['cnt'] <= 0 ){?>
											<div class="checkbox-custom">
												<input id="no" name="no[]" class="check" type="checkbox" value="<?echo $lt['no'];?>">
												<label for="check"></label>
											</div>
										<?}else{?>
											X
										<?}?>
										</td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $lt['order'];?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $part['name'];?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $menu['name'];?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $lt['title'];?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $lt['sData'].' ~ '.$lt['eData'];?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $lt['pPoint'];?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $lt['mPoint'];?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $lt['created'];?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $lt['user_name'];?></a></td>
										<td class="text-center">
										<?if($lt['cnt'] > 0){?>
											<button type="button" class="btn btn-success btn-xs" id="view_staff" onclick="call_staff_view('<?echo $lt['no'];?>');"><i class="glyphicon glyphicon-user"></i></button>
										<?}else{?>
											<button type="button" class="btn btn-success btn-xs" id="view_staff" onclick="call_staff('<?echo $lt['no'];?>');"><i class="glyphicon glyphicon-user"></i></button>
										<?}?>
											
										</td>
									</tr>
								<?php }?>
								<?
								if( count($list) <= 0 ){?>
									<tr>
										<td colspan="11">등록된 내용이 없습니다.</td>
									</tr>
								<?}?>
								</tbody>
							</table>

							<div class="panel-body" style="text-align:center;"><?echo $pagination;?></div>
							<div class="panel-body pull-right">
								<button id="btn_list_delete" type="button" class="btn btn-danger btn-alt mr5 mb10">삭제</button>
								<button type="button" class="btn btn-primary btn-alt mr5 mb10" onclick="location.href='<?echo $write_url;?>';">등록</button>
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

<!-- 폼 날짜 -->
<script src="<?echo $this->config->base_url()?>html/plugins/forms/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<script src="<?echo $this->config->base_url()?>html/plugins/forms/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<script src="<?echo $this->config->base_url()?>html/js/sw/sw_project.js"></script>
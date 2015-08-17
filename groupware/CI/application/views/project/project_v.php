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
							<h4 class="panel-title"><i class="fa fa-circle"></i> 업무목록</h4>
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
									<label class="col-lg-2 col-md-2 control-label" for="">기안일자</label>
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
									<div class="col-lg-3 col-md-3">
										<select id="menu_department" name="menu_department" class="fancy-select form-control">
											<option value="담당부서">담당부서</option>
										</select>
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">상호명</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" placeholder="상호명">
									</div>
								</div>

								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">사업자번호</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" placeholder="사업자번호">
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">제목</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" placeholder="제목">
									</div>

									<div class="col-lg-2 col-md-2">
										<button type="submit" class="btn btn-primary btn-alt mr5 mb10"> 검 색</button>
									</div>
								</div>


							</form>
							<!-- 검색 -->

							<form id="board-form-list" action="<?echo site_url('project/proc');?>" method="post" class="form-horizontal group-border stripped" role="form">
							<input type="hidden" name="action_type" id="action_type" value="delete">
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
										<th class="per10">구분</th>
										<th class="per10">분류</th>
										<th >제목</th>
										<th class="per15">진행기간</th>
										<th class="per10">결재</th>
										<th class="per10">누락</th>
										<th class="per10">기안일자</th>
										<th class="per10">기안자</th>
										<th style="width:60px;">담당자</th>
									</tr>
								</thead>
								<tbody>
								<?php foreach($list as $lt){
									$anchor = $anchor_url.'?no='.$lt['no'];
									?>
									<tr>
										<td>
											<div class="checkbox-custom">
												<input id="check" class="check" type="checkbox" value="option2">
												<label for="check"></label>
											</div>
										</td>
										<td><?echo $lt['order'];?></td>
										<td><?echo $lt['menu_part_no'];?></td>
										<td><?echo $lt['menu_no'];?></td>
										<td><?echo $lt['title'];?></td>
										<td><?echo substr($lt['sData'],0,10).' ~ '.substr($lt['eData'],0,10);?></td>
										<td><?echo $lt['pPoint'];?></td>
										<td><?echo $lt['mPoint'];?></td>
										<td><?echo substr($lt['created'],0,10);?></td>
										<td><?echo $lt['user_no'];?></td>
										<td class="text-center">
											<button type="button" class="btn btn-success btn-xs" id="view_staff" onclick="alert('담당자 팝업');"><i class="glyphicon glyphicon-user"></i></button>
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
								<button type="button" class="btn btn-primary btn-alt mr5 mb10" onclick="location.href='<?echo $anchor_url;?>';">등록</button>
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

<script src="<?echo $this->config->base_url()?>html/js/sw/sw_company.js"></script>

<script type="text/javascript">
	$(function(){
		$('#menu_department').create_menu({
			method : 'department', /* 메뉴명 */
			value : '' /* selected 할 no 값 */
		});
	});
</script>
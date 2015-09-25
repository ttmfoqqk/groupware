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
							<h4 class="panel-title"><i class="fa fa-circle"></i> 결재목록</h4>
						</div>
						<div class="panel-body">
							
							<!-- 검색 -->
							<form class="form-horizontal" action="<?php echo site_url('approved_send/lists/');?>">

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
									<label class="col-lg-2 col-md-2 control-label" for="">등록일자</label>
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
									<label class="col-lg-2 col-md-2 control-label" for="">결재부서</label>
									<div class="col-lg-3 col-md-3 col-sm-3 col-sm-3">
										<select id="part_receiver" name="part_receiver" data-method="department" data-value="<?echo $this->input->get('part_receiver');?>" class="fancy-select form-control">
											<option value="">결재부서</option>
										</select>
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">결재자</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" name="name_receiver" id="name_receiver" class="form-control" placeholder="결재자" value="<?echo $this->input->get('name_receiver');?>">
									</div>
								</div>

								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">분류</label>
									<div class="col-lg-3 col-md-3">
										<select id="menu_no" name="menu_no" data-method="project,document" data-value="<?echo $this->input->get('menu_no');?>" class="fancy-select form-control">
											<option value="">분류</option>
										</select>
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">문서번호</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" name="doc_no" id="doc_no" class="form-control" placeholder="문서번호" value="<?echo $this->input->get('doc_no');?>">
									</div>

								</div>
								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">제목</label>
									<div class="col-lg-8 col-md-8">
										<input type="text" id="title" name="title" class="form-control" placeholder="제목" value="<?echo $this->input->get('title')?>">
									</div>

									<div class="col-lg-2 col-md-2">
										<button type="submit" class="btn btn-primary btn-alt mr5 mb10"> 검 색</button>
									</div>
								</div>


							</form>
							<!-- 검색 -->
							<div class="pull-left">
								<select id="board_type" name="board_type" class="fancy-select form-control">
									<option value="10">10개</option>
								</select>
							</div>
							<div class="pull-right">
								<button type="submit" class="btn btn-alt mr5 mb10">엑셀</button>
							</div>

							<form id="approved-form-list" action="<?echo $action_url;?>" method="post" class="form-horizontal group-border stripped" role="form">
							<input type="hidden" name="action_type" id="action_type" value="delete">
							<input type="hidden" name="parameters" id="parameters" value="<?echo $parameters;?>">

							<table class="table table-bordered" id="tabletools">
								<thead>
									<tr>
										<!--th style="width:45px;">
											<div class="checkbox-custom">
												<input class="check-all" type="checkbox" id="masterCheck" value="option1">
												<label for="masterCheck"></label>
											</div>
										</th-->
										<th style="width:60px;">순서</th>
										<th style="width:100px;">문서번호</th>
										<th class="per8">분류</th>
										<th>제목</th>
										<th class="per8">진행기간</th>
										<th class="per8">결재</th>
										<th class="per8">누락</th>
										<th class="per8">등록일자</th>
										<th class="text-center" width="80px;">결재자</th>
									</tr>
								</thead>
								<tbody>
								<?php foreach($list as $lt){
									$anchor = $anchor_url.'&no='.$lt['no'];
									?>
									<tr>
										<!--td>
											<div class="checkbox-custom">
												<input id="check" class="check" type="checkbox" value="option2">
												<label for="check"></label>
											</div>
										</td-->
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo !$lt['order']?0:$lt['order']; ?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $lt['no']; ?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $lt['menu_name'];?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $lt['title'];?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo substr($lt['sData'],0,10);?> ~ <?echo substr($lt['eData'],0,10);?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo ($lt['kind']=='0' ? '+'.$lt['pPoint'] : '');?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo ($lt['kind']=='0' ? '-'.$lt['mPoint'] : '');?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $lt['created'];?></a></td>
										<td class="text-center">
											<button type="button" class="btn btn-success btn-xs" onclick="alert('준비중');"><i class="glyphicon glyphicon-user"></i></button>
										</td>
									</tr>
									<?php }?>
								<?
								if( count($list) <= 0 ){?>
									<tr>
										<td colspan="8">등록된 내용이 없습니다.</td>
									</tr>
								<?}?>
								</tbody>
							</table>

							<div class="panel-body" style="text-align:center;"><?echo $pagination;?></div>

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

<script src="<?echo $this->config->base_url()?>html/js/sw/sw_approved.js"></script>
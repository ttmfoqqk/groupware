<!-- .page-content -->
<div class="page-content sidebar-page clearfix">
	<!-- .page-content-wrapper -->
	<div class="page-content-wrapper">
		<div class="page-content-inner">
			<!-- .page-content-inner -->
			<div id="page-header" class="clearfix">
				<div class="page-header">
					<h2>보관함</h2>
				</div>
			</div>
			<div class="row">
				
				<div class="col-lg-12">
					<!-- col-lg-12 start here -->
					<div class="panel panel-primary toggle">
						<!-- Start .panel -->
						<div class="panel-heading">
							<h4 class="panel-title"><i class="fa fa-circle"></i> 결재 보관함</h4>
						</div>
						<div class="panel-body">
							
							<!-- 검색 -->
							<form class="form-horizontal" action="<?php echo site_url('approved_archive/lists/');?>">

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
									<label class="col-lg-2 col-md-2 control-label" for="">담당부서</label>
									<div class="col-lg-3 col-md-3 col-sm-3 col-sm-3">
										<select id="part_sender" name="part_sender" data-method="department" data-value="<?echo $this->input->get('part_sender');?>" class="fancy-select form-control">
											<option value="">담당부서</option>
										</select>
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">담당자</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" name="name_sender" class="form-control" placeholder="담당자" value="<?echo $this->input->get('name_sender');?>">
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
										<input type="text" name="name_receiver" class="form-control" placeholder="결재자" value="<?echo $this->input->get('name_receiver');?>">
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
										<input type="text" name="doc_no" class="form-control" placeholder="문서번호" value="<?echo $this->input->get('doc_no');?>">
									</div>

									
								</div>
								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">제목</label>
									<div class="col-lg-8 col-md-8">
										<input type="text" name="title" class="form-control" placeholder="제목" value="<?echo $this->input->get('title');?>">
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
							
							
							<form id="approved-form-list" action="<?echo $action_url;?>" method="post" class="form-horizontal group-border stripped" role="form">
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
										<th style="width:60px;">순서</th>
										<th class="per15">분류</th>
										<th>제목</th>
										<th class="per8">진행기간</th>
										<th class="per8">결재</th>
										<th class="per8">누락</th>
										<th class="per8">등록일자</th>
										<th width="80px;">담당자</th>
										<th width="90px;">결재요청</th>
									</tr>
								</thead>
								<tbody>
								<?php foreach($list as $lt){
									$anchor = $anchor_url.'&no='.$lt['no'];
									$menu = search_node($lt['menu_no'],'parent');
									?>
									<tr>
										<td>
											<div class="checkbox-custom">
												<input id="no" name="no[]" class="check" type="checkbox" value="<?echo $lt['no'];?>">
												<label for="check"></label>
											</div>
										</td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo !$lt['order']?0:$lt['order']; ?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $menu['name'];?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $lt['title'];?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $lt['sData'].' ~ '.$lt['eData'];?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo ($lt['kind']=='0' ? '+'.$lt['pPoint'] : '');?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo ($lt['kind']=='0' ? '-'.$lt['mPoint'] : '');?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $lt['created'];?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $lt['user_name'];?></a></td>
										<td class="text-center">
											<button type="button" class="btn btn-danger btn-xs" onclick="call_project_staff('<?echo $lt['project_no'];?>','<?echo $lt['no'];?>','<?echo $lt['kind'];?>',true);"><i class="glyphicon glyphicon-ok"></i></button>
											
											<?if($lt['kind']=="1"){?>
											<button type="button" class="btn btn-success btn-xs" onclick="document_staff('<?echo $lt['no'];?>');"><i class="glyphicon glyphicon-user"></i></button>
											<?}?>
										</td>
									</tr>
									<?php }?>
								<?
								if( count($list) <= 0 ){?>
									<tr>
										<td colspan="10">등록된 내용이 없습니다.</td>
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

<script src="<?echo $this->config->base_url()?>html/js/sw/sw_approved.js"></script>
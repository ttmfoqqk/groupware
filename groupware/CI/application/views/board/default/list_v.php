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
				<!-- col-lg-12 end here -->
				<div class="col-lg-12">
					<!-- col-lg-12 start here -->
					<div class="panel panel-primary">
						<!-- Start .panel -->
						<div class="panel-heading">
							<h4 class="panel-title"><i class="fa fa-circle"></i> <?echo $board_name;?></h4>
						</div>
						<div class="panel-body">
							
							<form id="qu" class="form-horizontal" method="get" role="form" action="<?echo $action_url;?>">
								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">등록일자</label>
									<div class="col-lg-6 col-md-6">
										<div class="input-daterange input-group">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											<input type="text" class="form-control" name="sData" id="sData" value="<?php echo $this->input->get('sData');?>" />
											<span class="input-group-addon">to</span>
											<input type="text" class="form-control" name="eData" id="eData" value="<?php echo $this->input->get('eData');?>" />
										</div>
									</div>
									<div class="col-lg-4 col-md-4">
										<button type="button" id="sToday" class="btn btn-sm btn-primary btn-alt">오늘</button>
										<button type="button" id="sWeek"  class="btn btn-sm btn-primary btn-alt">7일</button>
										<button type="button" id="sMonth" class="btn btn-sm btn-primary btn-alt">30일</button>
										<button type="button" id="sReset" class="btn btn-sm btn-primary btn-alt">날짜초기화</button>
									</div>
								</div>

								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">분류</label>
									<div class="col-lg-3 col-md-3">
										<select id="menu_no" name="menu_no" data-method="board" data-value="<?echo $this->input->get('menu_no');?>" class="fancy-select form-control">
											<option value="">분류</option>
										</select>
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">작성자</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" id="user_name" name="user_name" placeholder="작성자" value="<?echo $this->input->get('user_name');?>">
									</div>
								</div>

								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">제목</label>
									<div class="col-lg-8 col-md-8">
										<input type="text" class="form-control" id="subject" name="subject" placeholder="제목" value="<?echo $this->input->get('subject');?>">
									</div>

									

									<div class="col-lg-2 col-md-2">
										<button type="submit" class="btn btn-primary btn-alt mr5 mb10">검 색</button>
									</div>
								</div>
							</form>


							<form id="board-form-list" action="<?echo BOARD_FORM;?>" method="post" class="form-horizontal group-border stripped" role="form">
							<input type="hidden" name="action_type" id="action_type" value="">
							<input type="hidden" name="parameters" id="parameters" value="<?echo $parameters;?>">

							<table id="tabletools" class="table table-bordered" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th style="width:50px;">
											<div class="checkbox-custom">
												<input class="check-all" type="checkbox" id="masterCheck" value="option1">
												<label for="masterCheck"></label>
											</div>
										</th>
										<th class="per10">분류</th>
										<th>제목</th>
										<th class="per10">작성자</th>
										<th class="per10">등록일</th>
										<th class="per5">조회</th>
									</tr>
								</thead>
								<tbody>
								<?foreach($notice as $lt){
									$anchor = $anchor_url .'&no='.$lt['no'];
									$menu   = search_node($lt['menu_no'],'parent');
								?>
									<tr>
										<td>
											<div class="checkbox-custom text-danger">공지</div>
										</td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $menu['name'];?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $lt['subject'];?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $lt['user_name'];?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $lt['created'];?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $lt['count_hit'];?></a></td>
									</tr>
								<?}?>

								<?foreach($list as $lt){
									$anchor = $anchor_url .'&no='.$lt['no'];
									$menu   = search_node($lt['menu_no'],'parent');
									
									$depth = '';
									if( $lt['no'] != $lt['parent_no'] ){
										$nbsp = '';
										for($i=0;$i<$lt['depth'];$i++){
											$nbsp .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
										}
										$depth = $nbsp.'[RE]:';
									}
									?>
									<tr>
										<td>
											<div class="checkbox-custom">
												<input id="check<?$lt['no'];?>" name="contents_no[]" class="check" type="checkbox" value="<?echo $lt['no'];?>">
												<label for="check<?$lt['no'];?>"></label>
											</div>
										</td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $menu['name'];?></a></td>
										<td><?echo $depth;?><a href="<?echo $anchor;?>" class="text-normal"><?echo $lt['subject'];?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $lt['user_name'];?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $lt['created'];?></a></td>
										<td><a href="<?echo $anchor;?>" class="text-normal"><?echo $lt['count_hit'];?></a></td>
									</tr>
								<?}
								if( count($list) <= 0 ){?>
									<tr>
										<td colspan="6">등록된 내용이 없습니다.</td>
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
				<!-- col-lg-12 end here -->
			</div>
			
			<!-- End .row -->
		</div>
		<!-- / .page-content-inner -->
	</div>
	<!-- / page-content-wrapper -->
</div>
<!-- / page-content -->

<script src="<?echo $this->config->base_url()?>html/js/sw/sw_board.js"></script>
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
							<form class="form-horizontal">
								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">분류</label>
									<div class="col-lg-3 col-md-3 col-sm-3 col-sm-3">
										<select id="board_type" name="board_type" class="fancy-select form-control">
											<option value="담당부서">담당부서</option>
										</select>
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">아이디</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" placeholder="아이디">
									</div>
								</div>

								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">등급</label>
									<div class="col-lg-3 col-md-3 col-sm-3 col-sm-3">
										<select id="board_type" name="board_type" class="fancy-select form-control">
											<option value="등급">등급</option>
										</select>
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">이름</label>
									<div class="col-lg-3 col-md-3">
										<input type="text" class="form-control" placeholder="이름">
									</div>
								</div>

								<div class="form-group col-lg-12 col-md-12">
									<label class="col-lg-2 col-md-2 control-label" for="">질문사용</label>
									<div class="col-lg-3 col-md-3">
										<select id="board_type" name="board_type" class="fancy-select form-control">
											<option value="질문사용">질문사용</option>
										</select>
									</div>

									<label class="col-lg-2 col-md-2 control-label" for="">답변사용</label>
									<div class="col-lg-3 col-md-3">
										<select id="board_type" name="board_type" class="fancy-select form-control">
											<option value="답변사용">답변사용</option>
										</select>
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
										<th class="per8">분류</th>
										<th>아이디</th>
										<th class="per8">비밀번호</th>
										<th class="per8">등급</th>
										<th class="per8">이름</th>
										<th class="per8">생년월일</th>
										<th width="80px;">질문사용</th>
										<th width="80px;">답변사용</th>
									</tr>
								</thead>
								<tbody>
									<!-- 리스트 -->
									<tr>
										<td>
											<div class="checkbox-custom">
												<input id="check" class="check" type="checkbox" value="option2">
												<label for="check"></label>
											</div>
										</td>
										<td>순서</td>
										<td>분류</td>
										<td>아이디</td>
										<td>비밀번호</td>
										<td>등급</td>
										<td>이름</td>
										<td>생년월일</td>
										<td>사용</td>
										<td>미사용</td>
									</tr>
									<!-- 리스트 -->
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

<script src="<?echo $this->config->base_url()?>html/js/sw/sw_company.js"></script>
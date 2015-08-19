<!-- .page-sidebar -->
<aside id="sidebar" class="page-sidebar hidden-md hidden-sm hidden-xs">
	<!-- Start .sidebar-inner -->
	<div class="sidebar-inner">
		<!-- Start .sidebar-scrollarea -->
		<div class="sidebar-scrollarea">
			<!--  .sidebar-panel -->
			<div class="sidebar-panel">
				<h5 class="sidebar-panel-title">Profile</h5>
			</div>
			<!-- / .sidebar-panel -->
			<div class="user-info clearfix">
				<img style="height: 64px; width: 64px;" src="<?php if($this->session->userdata('file') != NULL) echo $this->config->base_url() . 'upload/member/' . $this->session->userdata('file')?>" alt="avatar">
				
				<span class="name"><?php echo $this->session->userdata('name');?></span>
				<!--div class="btn-group">
					<button type="button" class="btn btn-default btn-xs"><i class="l-basic-gear"></i>
					</button>
					<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">settings <span class="caret"></span>
					</button>
					<ul class="dropdown-menu right" role="menu">
						<li><a href="profile.html"><i class="fa fa-edit"></i>Edit profile</a></li>
						<li class="divider"></li>
						<li><a href="<?php echo site_url('login/logout');?>"><i class="fa fa-power-off"></i>Logout</a></li>
					</ul>
				</div-->
			</div>
			<!--  .sidebar-panel -->
			<div class="sidebar-panel">
				<h5 class="sidebar-panel-title">SPIDERWEB 그룹웨어</h5>
			</div>
			<!-- / .sidebar-panel -->
			<!-- .side-nav -->
			<div class="side-nav">
				<ul class="nav">
					<!-- 회사관리 -->
					<li>
						<a href="#"><i class="l-basic-home"></i> <span class="txt">회사관리</span></a>
						<ul class="sub">
							<li><a href="<?php echo site_url('company');?>"><span class="txt">회사정보</span></a></li>
							<li><a href="<?php echo site_url('partner');?>"><span class="txt">거래처</span></a></li>
							<li><a href="<?php echo site_url('menu/lists/department');?>"><span class="txt">부서 분류</span></a></li>
							<li><a href="<?php echo site_url('member');?>"><span class="txt">사원관리</span></a></li>
							<li><a href="<?php echo site_url('holiday');?>"><span class="txt">휴일설정</span></a></li>
							<li><a href="<?php echo site_url('attendance/set');?>"><span class="txt">근태설정</span></a></li>
							<li><a href="<?php echo site_url('attendance');?>"><span class="txt">근태현황</span></a></li>
							<li><a href="<?php echo site_url('menu/lists/object');?>"><span class="txt">물품 분류</span></a></li>
							<li><a href="<?php echo site_url('object');?>"><span class="txt">물품 정보</span></a></li>
							<li><a href="<?php echo site_url('baseCode');?>"><span class="txt">기초코드</span></a></li>
						</ul>
					</li>
					<!-- 회사규정 -->
					<li>
						<a href="#"><i class="l-basic-sheet-txt"></i> <span class="txt">회사규정</span></a>
						<ul class="sub">
							<li><a href="<?php echo site_url('menu/lists/rule');?>"><span class="txt">규정 분류</span></a></li>
							<li><a href="<?php echo site_url('rule');?>"><span class="txt">규정 정보</span></a></li>
						</ul>
					</li>
					<!-- 회사서식 -->
					<li>
						<a href="#"><i class="l-basic-folder-multiple"></i> <span class="txt">회사서식</span></a>
						<ul class="sub">
							<li><a href="<?php echo site_url('menu/lists/document');?>"><span class="txt">서식 분류</span></a></li>
							<li><a href="<?php echo site_url('document');?>"><span class="txt">서식 정보</span></a></li>
						</ul>
					</li>
					<!-- 회의관리 -->
					<li>
						<a href="#"><i class="l-basic-share"></i> <span class="txt">회의관리</span></a>
						<ul class="sub">
							<li><a href="<?php echo site_url('menu/lists/meeting');?>"><span class="txt">회의 분류</span></a></li>
							<li><a href="<?php echo site_url('meeting');?>"><span class="txt">회의 정보</span></a></li>
						</ul>
					</li>
					<!-- 업무관리 -->
					<li>
						<a href="#"><i class="l-basic-server2"></i> <span class="txt">업무관리</span></a>
						<ul class="sub">
							<li><a href="<?php echo site_url('menu/lists/project');?>"><span class="txt">업무 분류</span></a></li>
							<li><a href="<?php echo site_url('project/');?>"><span class="txt">업무 정보</span></a></li>
							<li><a href="<?php echo site_url('schedule/');?>"><span class="txt">업무 일정</span></a></li>
						</ul>
					</li>
					<!-- 전자결재 -->
					<li>
						<a href="#"><i class="l-basic-todo-pencil"></i> <span class="txt">결재</span></a>
						<ul class="sub">
							<li><a href="<?php echo site_url('approved_archive/lists/');?>"><span class="txt">[등록/보관]</span></a></li>

							<li><a href="<?php echo site_url('approved_receive/lists/all/');?>"><span class="txt">[받은 결재]</span></a>
							<li><a href="<?php echo site_url('approved_receive/lists/a');?>"><span class="txt"> - 작업중(1)</span></a></li>
							<li><a href="<?php echo site_url('approved_receive/lists/b');?>"><span class="txt"> - 결재대기(1)</span></a></li>
							<li><a href="<?php echo site_url('approved_receive/lists/c');?>"><span class="txt"> - 결재완료(1)</span></a></li>
							<li><a href="<?php echo site_url('approved_receive/lists/d');?>"><span class="txt"> - 반려(1)</span></a></li>
							<li><a href="<?php echo site_url('approved_receive/lists/e');?>"><span class="txt"> - 미결재(1)</span></a></li>

							<li><a href="<?php echo site_url('approved_send/lists/all/');?>"><span class="txt">[보낸 결재]</span></a></li>
							<li><a href="<?php echo site_url('approved_send/lists/a');?>"><span class="txt"> - 작업중(1)</span></a></li>
							<li><a href="<?php echo site_url('approved_send/lists/b');?>"><span class="txt"> - 결재대기(1)</span></a></li>
							<li><a href="<?php echo site_url('approved_send/lists/c');?>"><span class="txt"> - 결재완료(1)</span></a></li>
							<li><a href="<?php echo site_url('approved_send/lists/d');?>"><span class="txt"> - 반려(1)</span></a></li>
							<li><a href="<?php echo site_url('approved_send/lists/e');?>"><span class="txt"> - 미결재(1)</span></a></li>

						</ul>
					</li>
					<!-- develop -->
					<li>
						<a href="#"><i class="l-basic-settings"></i> <span class="txt">DEVELOP</span></a>
						<ul class="sub">
							<li><a href="<?php echo site_url('develop');?>"><span class="txt">고객사 정보</span></a></li>
						</ul>
					</li>
					<!-- MARKETING -->
					<li>
						<a href="#"><i class="l-basic-world"></i> <span class="txt">MARKETING</span></a>
						<ul class="sub">
							<li><a href="<?php echo site_url('marketing');?>"><span class="txt">고객사 정보</span></a></li>
							<li><a href="<?php echo site_url('account');?>"><span class="txt">계정관리</span></a></li>
							<li><a href="<?php echo site_url('chc');?>"><span class="txt">CHC</span></a></li>
						</ul>
					</li>
					<!-- 목표관리 -->
					<li>
						<a href="#"><i class=" l-ecommerce-graph1"></i> <span class="txt">목표관리</span></a>
						<ul class="sub">
							<li><a href="<?php echo site_url('purpose/search');?>"><span class="txt">목표설정</span></a></li>
							<li><a href="<?php echo site_url('purpose/appraisal');?>"><span class="txt">업무평가</span></a></li>
							<li><a href="<?php echo site_url('purpose/add');?>"><span class="txt">추가평점</span></a></li>
						</ul>
					</li>
					<li>
						<a href="#"><i class="l-basic-elaboration-message-dots"></i> <span class="txt">게시판</span></a>
						<ul class="sub">
							<li><a href="<?php echo site_url('board_setting/lists');?>"><span class="txt">게시판 관리</span></a></li>
						<?php 
						// 게시판 목록셋팅 -> hooks/Common.php
						$board_menu_json = json_decode(BOARD_LIST_JSON);
						if($board_menu_json>0){
							foreach ($board_menu_json as $lt) {
						?>
							<li><a href="<?php echo site_url('board/lists/'.$lt->code);?>"><span class="txt"><?php echo $lt->name;?></span></a></li>
						<?	}
						}?>
						</ul>
					</li>
					
				</ul>
			</div>
			<!-- / side-nav -->
		</div>
		<!-- End .sidebar-scrollarea -->
	</div>
	<!-- End .sidebar-inner -->
</aside>
<!-- / page-sidebar -->
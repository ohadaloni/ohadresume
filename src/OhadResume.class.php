<?php
/*------------------------------------------------------------*/
class OhadResume extends Mcontroller {
	/*------------------------------------------------------------*/
	private $startTime;
	/*------------------------------------------------------------*/
	public function index() {
		$this->showTxt("summary", "Summary", "CV");
	}
	/*------------------------------------------------------------*/
	public function viewSource() {
		$file = $_REQUEST['file'];
		highlight_file($file);
	}
	/*------------------------------------------------------------*/
	public function showTxt($name = null, $title = null, $sectionName = null) {
		if ( ! $name ) {
			$name = @$_REQUEST['name'];
			$title = @$_REQUEST['title'];
			$sectionName = @$_REQUEST['sectionName'];
		}
		$tpl = "$name.tpl";
		$txt = $this->Mview->render($tpl);
		$txt = "<pre>\n$txt</pre>\n";
		$this->Mview->msg("$sectionName/$title");
		$this->Mview->pushOutput($txt);
	}
	/*------------------------------------------------------------*/
	public function run() {
		$class = $_REQUEST['class'];
		$classFile = "$class.class.php";
		require_once($classFile);
		$instance = new $class;
		$instance->index();
		echo "<pre>\n";
		highlight_file($classFile);
		echo "</pre>\n";
	}
	/*------------------------------------------------------------*/
	public function printVersion() {
		$menu = $this->menu();
		$CV = $menu['CV'];
		$summary = $CV[0];
		$summary['title'] = "Ohad Aloni's CV";
		$education = $CV[1];
		$printVersion = $menu['Experience'];
		array_unshift($printVersion, $education);
		array_unshift($printVersion, $summary);
		$txt = "";
		foreach ( $printVersion as $key => $item ) {
			$name = $item['name'];
			$title = $item['title'];
			$titleTxt = strtoupper($title);
			$titleTxt = "\n\n$titleTxt\n\n";
			if ( $key < 2 )
				$tpl = "$name.tpl";
			else
				$tpl = "experience/$name.tpl";
			if ( $key == 2 )
				$txt .= "\n\n\n\n\nEXPERIENCE\n\n\n";
			$rendered = $this->Mview->render($tpl);
			$postfix = "\n\n\n";
			$txt   .= "$titleTxt$rendered$postfix";
		}
		$txtFile = "../tmp/ohadResume.txt";
		$pdfFile = "../tmp/ohadResume.pdf";
		file_put_contents($txtFile, $txt);

		system("rm $pdfFile");
		system("pandoc $txtFile -o $pdfFile");
		$pdf = file_get_contents($pdfFile);

		$filesize = strlen($pdf);
		header("Content-type: application/pdf");
		header("Content-Disposition: attachment; filename=ohadResume.pdf");
		header("Content-Length: $filesize");
		echo $pdf;
	}
	/*------------------------------------------------------------*/
	public function experience() {
		$this->Mview->msg("CV/Experience");
		$menu = $this->menu();
		$Experience = $menu['Experience'];
		$txt = "<pre>\n";
		foreach ( $Experience as $item ) {
			$name = $item['name'];
			$title = $item['title'];
			$titleTxt = "<h4 style=\"color:blue\">$title</h4>\n";
			$tpl = "experience/$name.tpl";
			$rendered = $this->Mview->render($tpl);
			$postfix = "\n\n\n";
			$txt   .= "$titleTxt$rendered$postfix";
		}
		$txt .= "</pre>\n";
		$this->Mview->pushOutput($txt);
	}
	/*------------------------------------------------------------*/
	protected function before() {
		ini_set('max_execution_time', 10);
		ini_set("memory_limit", "5M");

		$this->startTime = microtime(true);
		$this->Mview->assign(array(
			'controller' => $this->controller,
			'action' => $this->action,
		));
		if ( $this->showMargins()) {
			$this->Mview->showTpl("head.tpl");
			$this->Mview->showTpl("header.tpl");
			$this->Mview->showTpl("menuDriver.tpl", array(
				'menu' => $this->menu(),
			));
		}
	}
	/*------------------------------*/
	protected function after() {
		if ( ! $this->showMargins())
			return;
		$this->Mview->runningTime($this->startTime);
		$this->Mview->showTpl("footer.tpl");
		$this->Mview->showTpl("foot.tpl");
	}
	/*------------------------------------------------------------*/
	/*------------------------------------------------------------*/
	private function isAjax() {
		$http_x_requested_with = @$_SERVER['HTTP_X_REQUESTED_WITH'];
		$isAjax =
			$http_x_requested_with &&
			strtolower($http_x_requested_with) == "xmlhttprequest" ;
		return($isAjax);
	}
	/*------------------------------*/
	private function showMargins() {
		if ( $this->isAjax() ) {
			return(false);
		}
		if( in_array($this->action, array(
					'phpinfo',
					'viewsource',
					'printversion',
				))) {
			return(false);
		}
		return(true);
	}
	/*------------------------------------------------------------*/
	private function menu() {
		$menu = array(
			 'CV' => array(
				array(
					'name' => 'summary',
					'title' => 'Summary',
					// target, if any
					'target' => null,
					// tpl to show, source file to highlight, func to call, or url to visit
					'txt' => 'summary',
					'source' => null,
					'func' => null,
					'url' => null,
				),
				array(
					'name' => 'education',
					'title' => 'Education',
					'txt' => 'education',
				),
				array(
					'name' => 'Experience',
					'title' => 'Experience',
					'func' => 'experience',
				),
				array(
					'name' => 'printVersion',
					'title' => 'Print Version',
					/*	'target' => 'printVersion',	*/
					'func' => 'printVersion',
				),
			 ),
			 'Experience' => array(
				array(
					'name' => 'cBidder',
					'title' => '2019-2020 - Clicxy RTB Bidder Development',
					'txt' => 'experience/cBidder',
				),
				array(
					'name' => 'bidder',
					'title' => '2013-2017 - Bidder (KeypointMedia/PLYmedia)',
					'txt' => 'experience/bidder',
				),
				array(
					'name' => 'ironsource',
					'title' => '2011-2012 - Ironsource',
					'txt' => 'experience/ironsource',
				),
				array(
					'name' => 'whitesmoke',
					'title' => '2009-2011 - Whitesmoke',
					'txt' => 'experience/whitesmoke',
				),
				array(
					'name' => 'freelance',
					'title' => '2001-2009 - Freelance',
					'txt' => 'experience/freelance',
				),
				array(
					'name' => 'theora.com',
					'title' => '1999-2001 - Theora.com',
					'txt' => 'experience/theora.com',
				),
				/*
				array(
					'name' => 'softwareEngines',
					'title' => '1990-1999 - Sotware Engines',
					'txt' => 'experience/softwareEngines',
				),
				array(
					'name' => 'objectiveTech',
					'title' => '1990-1990 - Objective Technologies',
					'txt' => 'experience/objectiveTech',
				),
				array(
					'name' => 'wallStreet',
					'title' => '1987-1990 - Wall Street',
					'txt' => 'experience/wallStreet',
				),
			 	*/
			 ),
			 /*
			 'Software Engines' => array(
				array(
					'name' => 'softwareEngine',
					'title' => 'The Software Engine Product',
					'txt' => 'softwareEngines/softwareEngine',
				),
				array(
					'name' => 'wallStreet',
					'title' => 'Wall Street',
					'txt' => 'softwareEngines/wallStreet',
				),
				array(
					'name' => 'ericsson',
					'title' => 'Ericsson Telecommunications',
					'txt' => 'softwareEngines/ericsson',
				),
				array(
					'name' => 'jCrew',
					'title' => 'J.Crew',
					'txt' => 'softwareEngines/jCrew',
				),
				array(
					'name' => 'catalogFactory',
					'title' => 'Catalog Factory',
					'txt' => 'softwareEngines/catalogFactory',
				),
			 ),
			 */
			 'Open Source RTB Infrastructure' => array(
				array(
					'name' => 'overview',
					'title' => 'Overview',
					'url' => "http://bidderui.theora.com/docs/showText?doc=architecture.txt",
					'target' => "bidderUIoverview",
				),
				array(
					'name' => 'demoBidderUI',
					'title' => 'Demo',
					'url' => "http://bidderui.theora.com",
					'target' => "bidderUI",
				),
				array(
					'name' => 'cloneB',
					'title' => 'Download',
					'url' => "https://github.com/ohadaloni/bidder",
					'target' => "cloneB",
				),
			 ),
			 'MVC - Developing a framework' => array(
				array(
					'name' => 'concept',
					'title' => 'Concept',
					'txt' => 'm/concept',
				),
				array(
					'name' => 'examples',
					'title' => 'Examples',
					'txt' => 'm/examples',
				),
				array(
					'name' => 'demo',
					'title' => 'Demo',
					'url' => "http://theora.com/Mdemo",
					'target' => "mDemo",
				),
				array(
					'name' => 'cloneM',
					'title' => 'Download',
					'url' => "https://github.com/ohadaloni/m",
					'target' => "cloneM",
				),
			 ),
			 'Code Samples' => array(
				array(
					'name' => 'Mcal',
					'title' => 'Wow Calendar',
					'url' => "https://github.com/ohadaloni/M/blob/master/Mcal.class.php",
					'target' => "gitHub",
				),
				array(
					'name' => 'corona',
					'title' => 'Corona Tracker',
					'url' => "http://corona.theora.com#viewSource",
					'target' => "viewSource",
				),
				array(
					'name' => 'OpenSourceBidder',
					'title' => 'Real Time Bidder',
					'url' => "http://bidderui.theora.com/showSource?topDir=bidder&file=src/Bidder.class.php",
					'target' => "viewSource",
				),
			 ),
			 'Eve (C++)' => array(
				array(
					'name' => 'concept',
					'title' => 'The EKG Visualization Environment',
					'txt' => 'eve/concept',
				),
				array(
					'name' => 'systemOverview',
					'title' => 'System Overview',
					'txt' => 'eve/systemOverview',
				),
				array(
					'name' => 'arduino',
					'title' => 'Arduino Parallelism Micro Kernel',
					'source' => 'tpl/eve/kernel.cpp',
				),
				array(
					'name' => 'prototype',
					'title' => 'Prototype',
					'target' => 'evePrototype',
					'url' => '/images/eve.jpg',
				),
				array(
					'name' => 'cloneEve',
					'title' => 'Download',
					'url' => "https://github.com/ohadaloni/eve",
					'target' => "gitHub",
				),
			 ),
			 'Misc' => array(
				array(
					'name' => 'articles',
					'title' => 'Articles',
					'txt' => 'softwareEngines/articles',
				),
				array(
					'name' => 'wawi',
					'title' => 'A Guitar Thing',
					'url' => "https://www.youtube.com/watch?v=ZM6ZIBXU07Q",
					'target' => "wawi",
				 ),
			 ),
		);
		return($menu);
	}
	/*------------------------------------------------------------*/
	private function errorLog($msg, $file = null, $line = null) {
		$pfx = $line ? "$file:$line: " : "" ;
		error_log("$pfx$msg");
	}
	/*------------------------------------------------------------*/
}

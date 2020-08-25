	// get rows from cache (memcache), or from database (and cache the results for 7 seconds)
	$phones = $this->Mmodel->getRows("select * from phones order by lastName", 7);
	// show the phones template, with the data from the phones table.
	$this->Mview->showTpl("phones.tpl", array('phones' => $phones));

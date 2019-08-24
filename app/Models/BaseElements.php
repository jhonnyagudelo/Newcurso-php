<?php

namespace App\Models;


class BaseElements implements Printable
{
	protected $title;
	public $description;
	public $visible = true;
	public $months;

	public function __construct($title, $description)
	{
		$this->setTitle($title);
		// $this->title = $title;
		$this->description = $description;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function setTitle($t)
	{
		if ($t == '') {
			$this->title = 'N/A';
		} else {
			$this->title = $t;
		}
	}


	public function getDurationAsString()
	{
		$years = floor($this->months / 12);
		$extraMonths = $this->months % 12;

		if ($years == 0) {
			return "$extraMonths months";
		} else {
			return " $years years, $extraMonths months";
		}
	}

	public function getDescription()
	{
		return $this->description;
	}
}

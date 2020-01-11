<?php

class Pagination {
	public $current_page;
	public $per_page;
	public $total_count;
	public $url;


	public function __construct ($page=1 , $per_page=20 , $total_count=0, $url='') {

		$this->current_page = +$page;
		$this->per_page = +$per_page;
		$this->total_count = +$total_count;
		$this->url = $url;

	}

	public function offset () {
		return $this->per_page * ($this->current_page - 1);
	}

	public function total_pages () {
		return ceil($this->total_count / $this->per_page) ;
	}

	public function next_page () {
		$next =  $this->current_page + 1 ;

		if ( $next > $this->total_pages()) {
			return false;
		} else {
			return $next;
		}
	}

	public function previous_page () {
		$previous = $this->current_page - 1 ;
		if ($previous) {
			return $previous;
		} else {
			return false;
		}
	}

	public function previous_link () {

		if ($this->previous_page() AND !empty($this->url)){

	      $previous =  "<a href=\"{$this->url}?page={$this->previous_page()}\">"; 
	      $previous .= "&laquo; Previous </a>"; 
	      return $previous;
	    }
	}

	public function next_link () {

	    if ( $this->next_page() AND !empty($this->url)){
	      $next = "<a href=\"{$this->url}?page={$this->next_page()}\">"; 
	      $next .= " Next &raquo;</a>"; 
	      return $next;
	    }
	}

	public function pages_link () {
		$pages = '';
		for ($i=1; $i <= $this->total_pages(); $i++) {
			if ($i == $this->current_page ) {
			$pages .= $this->current_page;
			} else {
			$pages .= "<a href=\"{$this->url}?page={$i}\">"; 
			$pages .= " {$i} </a>"; 
			}
		}
		return $pages;
	}

	public function pagination_interface () {
		$interface = null;
		if ($this->total_pages() > 1) {
			$interface = "<div class=\"pagination\">";
			$interface .= $this->previous_link();
			$interface .= $this->pages_link();
			$interface .= $this->next_link();
			$interface .= '</div>';
		}

		return $interface;
	}
}

?>
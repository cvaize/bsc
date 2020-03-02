<?php


namespace BSC\App\Http\Controllers;


class StaticController
{
	public function welcome(){
		return view('theme::pages.welcome.index');
	}
}

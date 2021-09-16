<?php

namespace App\Controllers;

use App\Controllers\BaseControllers;

class PostController extends BaseController
{
	public function index()
	{
        $data = [
            'title' => "Blog - Posts", 
        ];
        echo view('layouts/header', $data);
        echo view('layouts/navbar');
		echo view('v_post');
        echo view('layouts/footer');
	}
}

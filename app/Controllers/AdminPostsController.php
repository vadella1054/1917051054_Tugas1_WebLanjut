<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PostModel;

class AdminPostsController extends BaseController
{
	public function __construct() {
 
        // Mendeklarasikan class BukuModel menggunakan $this->tokobuku
        $this->posts = new PostModel();
        /* Catatan:
        Apa yang ada di dalam function construct ini nantinya bisa digunakan
        pada function di dalam class Tokobuku
        */
    }
	public function index()
	{
		$PostModel = model("PostModel");
		$data = [
			'posts' => $PostModel->findAll()
		];
        return view("posts/index", $data);
	}

	public function create()
	{
        session();
		$data = [
			'validation' => \Config\Services::validation(),
		];
		return view("posts/create", $data);
	}

	public function store()
	{
		$valid = $this->validate([
			"judul" => [
				"label" => "Judul",
				"rules" => "required",
				"error" => [
					"required" => "{field} Harus diisi!",
				]
			],
			"slug" => [
				"label" => "Slug",
				"rules" => "required|is_unique[posts.slug]",
				"error" => [
					"required" => "{field} Harus diisi!",
					"is_unique" => "{field} Sudah ada!"
				]
			],
			"kategori" => [
				"label" => "Kategori",
				"rules" => "required",
				"error" => [
					"required" => "{field} Harus diisi!",
				]
			],
			"author" => [
				"label" => "Author",
				"rules" => "required",
				"error" => [
					"required" => "{field} Harus diisi!",
				]
			],
			"deskripsi" => [
				"label" => "Deskripsi",
				"rules" => "required",
				"error" => [
					"required" => "{field} Harus diisi!",
				]
			],
		]);
		# dd($valid);

		if ($valid){
			$data = [
				'judul' => $this->request->getVar('judul'),
				'slug' => $this->request->getVar("slug"),
				'kategori' => $this->request->getVar("kategori"),
				'author' => $this->request->getVar("author"),
				'deskripsi' => $this->request->getVar("deskripsi"),
			];
			# dd($data);

			$PostModel = model("PostModel");
			$PostModel->insert($data);
			session()->setFlashdata('success', 'Data berhasil ditambahkan');
			return redirect()->to(base_url('admin/posts'));
	
		} else {
			return redirect()->to(base_url('admin/posts/create'))->withInput()->with('validation', $this->validator);
		}
	}

	public function edit($post_id){
		$data = [
			'title' => 'Form Edit Data',
			'validation' => \Config\Services::validation(),
			'post' => $this->posts->getPosts($post_id)
		];
		return view("posts/edit", $data);
	}

	public function update($post_id){
		$valid = $this->validate([
			"judul" => [
				"label" => "Judul",
				"rules" => "required",
				"errors" => [
					"required" => "{field} Harus Diisi!",
				]
			],
			"kategori" => [
				"label" => "Kategori",
				"rules" => "required",
				"errors" => [
					"required" => "{field} Harus Diisi!",
				]
			],
			"author" => [
				"label" => "Author",
				"rules" => "required",
				"errors" => [
					"required" => "{field} Harus Diisi!",
				]
			],
		]);

		if ($valid) {
		$this->posts->save([
			'post_id' => $post_id,
			'judul' => $this->request->getVar('judul'),
			'kategori' => $this->request->getVar("kategori"),
			'author' => $this->request->getVar("author"),
			'deskripsi' => $this->request->getVar("deskripsi")
		]);

		session()->setFlashdata('success', 'Data berhasil diubah');
		return redirect()->to(base_url('admin/posts'));
		
		}
		else {
			return 'Semua data harus diisi !';

		}
	}

	
	public function delete($post_id) {
		$this->posts->delete($post_id);
		session()->setFlashdata('success', 'Data berhasil dihapus');
		return redirect()->to(base_url('admin/posts'));
	}
	
}
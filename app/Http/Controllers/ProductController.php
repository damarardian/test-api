<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ProductController extends Controller
{
    public function index(){
        if ($data = Product::all()){
            $data->each(function($item) {
                $item->image_url;
            });
            return response()->json($data);
        } else {
            return response()->json(['status' => 'Gagal Menyimpan Data'], 500);            
        }
        
    }
        
    public function store(Request $request){                               

        $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath());
        $url = $uploadedFileUrl->getSecurePath();
        $publicId = $uploadedFileUrl->getPublicId();


        $input = new Product;
        $input->nama = $request->nama;
        $input->jenis = $request->jenis;
        $input->harga = $request->harga;
        $input->image_url = $url;
        $input->image_public_id = $publicId;

        if ($input->save()){
            return ["status" => "Berhasi Menyimpan Barang dengan Image",$input, 200];
        } else {
            return ["status" => "Gagal Menyimpan Barang"];
        }

    }

    public function uploadImage(Request $request){        

        // $response = cloudinary()->upload($request->file('image')->getRealPath())->getSecurePath();
        // $cloudinaryImage =  cloudinary()->upload($request->file('image')->getRealPath());

        // $url = $cloudinaryImage->getSecurePath();
        // $publicId = $cloudinaryImage->getPublicId();

        $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath());
        \Log::info('Cloudinary Response: ', (array) $uploadedFileUrl);

        $url = $uploadedFileUrl->getSecurePath();
        $publicId = $uploadedFileUrl->getPublicId();

        \Log::info('Secure URL: ' . $url);
        \Log::info('Public ID: ' . $publicId);

        // dd($cloudinaryImage);
        // dd($url);
        // dd($publicId);

        return ["status" => "Berhasi Menyimpan Barang dengan Image",$url, $publicId, 200];
    }

    public function getImage($publicId){                
        $url = cloudinary()->getUrl($publicId);
        // $response = cloudinary()->upload($request->file('image')->getRealPath())->getSecurePath();

        dd($url);

        return back()
            ->with('success', 'ppppp');
    }
}

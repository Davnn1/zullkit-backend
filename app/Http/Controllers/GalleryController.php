<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\GalleryRequest;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        if (request()->ajax()) {
            $query = Gallery::where('products_id', $product->id);

            return DataTables::of($query)
                ->addColumn('action', function ($item) {
                    return '
                        <form class="inline-block" action="' . route('dashboard.gallery.destroy', $item->id) . '" method="POST">
                        <button class="px-2 py-1 m-2 text-white transition duration-500 bg-red-500 border border-red-500 rounded-md select-none ease hover:bg-red-600 focus:outline-none focus:shadow-outline" >
                            Hapus
                        </button>
                            ' . method_field('delete') . csrf_field() . '
                        </form>';
                })
                ->editColumn('url', function ($item) {
                    return '<img style="max-width: 150px;" src="'. $item->url .'"/>';
                })
                ->editColumn('is_featured', function ($item) {
                    return $item->is_featured ? 'Yes' : 'No';
                })
                ->rawColumns(['action', 'url'])
                ->make();
        }

        return view('pages.dashboard.gallery.index', compact('product'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Product $product)
    {
        return view('pages.dashboard.gallery.create', compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GalleryRequest $request, Product $product)
    {
        $files = $request->file('files');

        if($request->hasFile('files'))
        {
            foreach ($files as $file) {
                $path = $file->storePublicly('public/gallery');

                Gallery::create([
                    'products_id' => $product->id,
                    'url' => $path
                ]);
            }
        }

        return redirect()->route('dashboard.product.gallery.index', $product->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function show(Gallery $gallery)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function edit(Gallery $gallery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function update(GalleryRequest $request, Gallery $gallery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Gallery  $Gallery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gallery $gallery)
    {
        $gallery->delete();

        return redirect()->route('dashboard.product.gallery.index', $gallery->products_id);
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CatalogController extends Controller
{
    public function index()
    {
        return view('admin.catalog.index');
    }

    /**
     * Stream the generated PDF for the given catalog.
     */
    public function download(Catalog $catalog): BinaryFileResponse
    {
        abort_unless($catalog->isCompleted() && $catalog->file_path, 404, 'Catalog file is not available.');
        abort_unless(Storage::disk('local')->exists($catalog->file_path), 404, 'Catalog file is missing.');

        $filename = ($catalog->name ? \Illuminate\Support\Str::slug($catalog->name) : 'catalog-' . $catalog->id) . '.pdf';
        $absolute = Storage::disk('local')->path($catalog->file_path);

        return response()->download($absolute, $filename, [
            'Content-Type' => 'application/pdf',
        ]);
    }
}

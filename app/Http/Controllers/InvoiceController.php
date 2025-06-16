<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Support\Arr;
use App\Providers\WebHelper;
use App\Rules\dataValidator;
use Illuminate\Http\Request;
use App\Providers\CrudHelper;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoiceArray = Invoice::orderByDesc('updated_at')->get()->toArray();
        $invoiceArray = WebHelper::jsonToArray($invoiceArray);

        $page = request()->get('page', 1);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $paginatedData = array_slice($invoiceArray, $offset, $perPage);

        $invoice = new \Illuminate\Pagination\LengthAwarePaginator(
            $paginatedData,
            count($invoiceArray),
            $perPage,
            $page,
            ['path' => request()->url()]
        );

        return view('pages.invoice.view', compact('invoice'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($type)
    {
        $result = Product::orderBy('name')->get();
        return view('pages.invoice.add', compact('result', 'type'));
    }

    /*
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // pengecualian input dari $request
        $input = Arr::except($request->all(), ['_token', 'photo']);

        // proses penambahan pembelian
        $table = 'invoices';
        $config = CrudHelper::table($table);
        $result = CrudHelper::masterInsertData($table, $config, $input);

        // jika terdapat error
        if (isset($result['error'])) {
            return redirect('/pembelian/tambah')->withErrors($result['error']);
        }

        // Validasi stock produk
        $product = Product::get()->toArray();
        $isValid = dataValidator::stockValidator($input['product'], $product, 'add');
        if(!$isValid){
            return redirect()->back()->withErrors('Stock tidak cukup!');
        }

        // jika berhasil
        return redirect('/pembelian')->with('success', 'Berhasil Menyimpan Data');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $invoice = Invoice::findOrFail($id);
        $products = Product::orderBy('name')->get();

        // mengambil tanggal untuk breadcrumbs
        $invoice->date = WebHelper::dateIndonesia($invoice->tanggal);

        $invoice->product = $invoice->product ?? '{}';
        $type = $invoice->type;

        // dd($type);
        return view('pages.invoice.edit', compact('invoice', 'products', 'type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi stock produk (1)
        $product = Product::get()->toArray();
        $isValid = $request['updated_stocks'];

        // pengecualian input dari $request
        $input = Arr::except($request->all(), ['_token', 'photo','_method', 'updated_stocks']);

        $productJson = $request->input('product');
        if (dataValidator::isEmptyJson($productJson)) {
            return back()->withErrors(['product' => 'Pilih minimal 1 produk!'])->withInput();
        }

        $table = 'invoices';
        $data = CrudHelper::table($table);

        // proses update
        $result = CrudHelper::masterUpdateData($table, $data, $input, $id);

        // Validasi stock produk (2)
        $isValid = dataValidator::stockValidator($request['updated_stocks'], $product, 'edit');
        if(!$isValid){
            return redirect()->back()->withErrors('Stock tidak cukup!');
        }

        // jika gagal
        if (isset($result['error'])) {
            return back()->withErrors($result['error']);
        }

        // jika berhasil
        return redirect('/pembelian')->with('success', 'Berhasil Mengubah Data');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Kiriman done tapi ingin dihapus
        $transaction = Invoice::findOrFail($id);
        if ($transaction->status === 'done') {
            return redirect()->back()->withErrors('Tidak dapat menghapus Kiriman yang sudah selesai.');
        }

        // Validasi stock produk (2)
        $product = Product::get()->toArray();
        $isValid = dataValidator::stockValidator($id, $product, 'delete');
        if(!$isValid){
            return redirect()->back()->withErrors('Stock tidak cukup!');
        }

        $table = 'invoices';
        $result = CrudHelper::masterDeleteData($table, $id);
        return  redirect('/pembelian')->with('success', 'Berhasil Menghapus Data');
    }
}

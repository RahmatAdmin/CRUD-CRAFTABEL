<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Siswa\BulkDestroySiswa;
use App\Http\Requests\Admin\Siswa\DestroySiswa;
use App\Http\Requests\Admin\Siswa\IndexSiswa;
use App\Http\Requests\Admin\Siswa\StoreSiswa;
use App\Http\Requests\Admin\Siswa\UpdateSiswa;
use App\Models\Siswa;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SiswaController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexSiswa $request
     * @return array|Factory|View
     */
    public function index(IndexSiswa $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Siswa::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'name', 'email', 'birthdate'],

            // set columns to searchIn
            ['id', 'name', 'email']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.siswa.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.siswa.create');

        return view('admin.siswa.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSiswa $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreSiswa $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the Siswa
        $siswa = Siswa::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/siswas'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/siswas');
    }

    /**
     * Display the specified resource.
     *
     * @param Siswa $siswa
     * @throws AuthorizationException
     * @return void
     */
    public function show(Siswa $siswa)
    {
        $this->authorize('admin.siswa.show', $siswa);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Siswa $siswa
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(Siswa $siswa)
    {
        $this->authorize('admin.siswa.edit', $siswa);


        return view('admin.siswa.edit', [
            'siswa' => $siswa,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSiswa $request
     * @param Siswa $siswa
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateSiswa $request, Siswa $siswa)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values Siswa
        $siswa->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/siswas'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/siswas');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroySiswa $request
     * @param Siswa $siswa
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroySiswa $request, Siswa $siswa)
    {
        $siswa->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroySiswa $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroySiswa $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Siswa::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}

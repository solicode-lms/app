<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Requests\SysColorRequest;
use Modules\Core\Services\SysColorService;


use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Exports\SysColorExport;
use Modules\Core\App\Imports\SysColorImport;
use Modules\Core\Services\ContextState;

class SysColorController extends AdminController
{
    protected $sysColorService;

    public function __construct(SysColorService $sysColorService)
    {
        parent::__construct();
        $this->sysColorService = $sysColorService;

    }


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $sysColor_searchQuery = str_replace(' ', '%', $request->get('q', ''));
        $sysColors_data = $this->sysColorService->paginate($sysColor_searchQuery);

        if ($request->ajax()) {
            return view('Core::sysColor._table', compact('sysColors_data'))->render();
        }

        return view('Core::sysColor.index', compact('sysColors_data','sysColor_searchQuery'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemSysColor = $this->sysColorService->createInstance();


        if (request()->ajax()) {
            return view('Core::sysColor._fields', compact('itemSysColor'));
        }
        return view('Core::sysColor.create', compact('itemSysColor'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(SysColorRequest $request)
    {
        $validatedData = $request->validated();
        $sysColor = $this->sysColorService->create($validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $sysColor,
                'modelName' => __('Core::sysColor.singular')])
            ]);
        }

        return redirect()->route('sysColors.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $sysColor,
                'modelName' => __('Core::sysColor.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemSysColor = $this->sysColorService->find($id);


        if (request()->ajax()) {
            return view('Core::sysColor._fields', compact('itemSysColor'));
        }

        return view('Core::sysColor.show', compact('itemSysColor'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemSysColor = $this->sysColorService->find($id);

        // Utilisé dans l'édition des relation HasMany
        $this->contextState->set('sysColor_id', $id);


        if (request()->ajax()) {
            return view('Core::sysColor._fields', compact('itemSysColor'));
        }

        return view('Core::sysColor.edit', compact('itemSysColor'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(SysColorRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $sysColor = $this->sysColorService->update($id, $validatedData);




        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $sysColor,
                'modelName' =>  __('Core::sysColor.singular')])
            ]);
        }

        return redirect()->route('sysColors.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $sysColor,
                'modelName' =>  __('Core::sysColor.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $sysColor = $this->sysColorService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $sysColor,
                'modelName' =>  __('Core::sysColor.singular')])
            ]);
        }

        return redirect()->route('sysColors.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $sysColor,
                'modelName' =>  __('Core::sysColor.singular')
                ])
        );
    }

    public function export()
    {
        $sysColors_data = $this->sysColorService->all();
        return Excel::download(new SysColorExport($sysColors_data), 'sysColor_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new SysColorImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('sysColors.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('sysColors.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('Core::sysColor.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getSysColors()
    {
        $sysColors = $this->sysColorService->all();
        return response()->json($sysColors);
    }
}

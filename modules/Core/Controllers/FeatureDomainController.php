<?php
// Ce fichier est maintenu par ESSARRAJ Fouad


namespace Modules\Core\Controllers;

use Modules\Core\Controllers\Base\AdminController;
use Modules\Core\App\Requests\FeatureDomainRequest;
use Modules\Core\Services\FeatureDomainService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Core\App\Exports\FeatureDomainExport;
use Modules\Core\App\Imports\FeatureDomainImport;

class FeatureDomainController extends AdminController
{
    protected $featureDomainService;

    public function __construct(FeatureDomainService $featureDomainService)
    {
        parent::__construct();
        $this->featureDomainService = $featureDomainService;
    }


    /**
     * Affiche la liste des filières ou retourne le HTML pour une requête AJAX.
     */
    public function index(Request $request)
    {
        $searchQuery = str_replace(' ', '%', $request->get('searchValue', ''));
        $data = $this->featureDomainService->paginate($searchQuery);

        if ($request->ajax()) {
            return view('Core::featureDomain._table', compact('data'))->render();
        }

        return view('Core::featureDomain.index', compact('data'));
    }

    /**
     * Retourne le formulaire de création.
     */
    public function create()
    {
        $itemFeatureDomain = $this->featureDomainService->createInstance();

        if (request()->ajax()) {
            return view('Core::featureDomain._fields', compact('itemFeatureDomain'));
        }
        return view('Core::featureDomain.create', compact('itemFeatureDomain'));
    }

    /**
     * Stocke une nouvelle filière.
     */
    public function store(FeatureDomainRequest $request)
    {
        $validatedData = $request->validated();
        $featureDomain = $this->featureDomainService->create($validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
             __('Core::msg.addSuccess', [
                'entityToString' => $featureDomain,
                'modelName' => __('Core::featureDomain.singular')])
            ]);
        }

        return redirect()->route('featureDomains.index')->with(
            'success',
            __('Core::msg.addSuccess', [
                'entityToString' => $featureDomain,
                'modelName' => __('Core::featureDomain.singular')
            ])
        );
    }

    /**
     * Affiche les détails d'une filière.
     */
    public function show(string $id)
    {
        $itemFeatureDomain = $this->featureDomainService->find($id);

        if (request()->ajax()) {
            return view('Core::featuredomain._fields', compact('itemFeatureDomain'));
        }

        return view('Core::featuredomain.show', compact('itemFeatureDomain'));
    }

    /**
     * Retourne le formulaire d'édition d'une filière.
     */
    public function edit(string $id)
    {
        $itemFeatureDomain = $this->featureDomainService->find($id);

        if (request()->ajax()) {
            return view('Core::featureDomain._fields', compact('itemFeatureDomain'));
        }

        return view('Core::featureDomain.edit', compact('itemFeatureDomain'));
    }

    /**
     * Met à jour une filière existante.
     */
    public function update(FeatureDomainRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $featuredomain = $this->featureDomainService->update($id, $validatedData);


        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.updateSuccess', [
                'entityToString' => $featuredomain,
                'modelName' =>  __('Core::featuredomain.singular')])
            ]);
        }

        return redirect()->route('featureDomains.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $featuredomain,
                'modelName' =>  __('Core::featuredomain.singular')
                ])
        );
    }

    /**
     * Supprime une filière.
     */
    public function destroy(Request $request, string $id)
    {
        $featuredomain = $this->featureDomainService->destroy($id);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 
            __('Core::msg.deleteSuccess', [
                'entityToString' => $featuredomain,
                'modelName' =>  __('Core::featuredomain.singular')])
            ]);
        }

        return redirect()->route('featureDomains.index')->with(
            'success',
            __('Core::msg.deleteSuccess', [
                'entityToString' => $featuredomain,
                'modelName' =>  __('Core::featuredomain.singular')
                ])
        );
    }

    public function export()
    {
        $data = $this->featureDomainService->all();
        return Excel::download(new FeatureDomainExport($data), 'featureDomain_export.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        try {
            Excel::import(new FeatureDomainImport, $request->file('file'));
        } catch (\InvalidArgumentException $e) {
            return redirect()->route('featureDomains.index')->withError('Invalid format or missing data.');
        }

        return redirect()->route('featureDomains.index')->with(
            'success', __('Core::msg.importSuccess', [
            'modelNames' =>  __('Core::featuredomain.plural')
            ]));



    }

    // Il permet d'afficher les information en format JSON pour une utilisation avec Ajax
    public function getFeatureDomains()
    {
        $featureDomains = $this->featureDomainService->all();
        return response()->json($featureDomains);
    }
}

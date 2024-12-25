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

    public function index(Request $request)
    {
        // Récupérer la valeur de recherche et paginer
        $searchValue = $request->get('searchValue', '');
        $searchQuery = str_replace(' ', '%', $searchValue);
    
        // Appel de la méthode paginate avec ou sans recherche
        $data = $this->featureDomainService->paginate($searchQuery);
    
        // Gestion AJAX
        if ($request->ajax()) {
            return response()->json([
                'html' => view('Core::featureDomain._table', compact('data'))->render()
            ]);
        }
    
        // Vue principale pour le chargement initial
        return view('Core::featureDomain.index', compact('data'));
    }

    public function create()
    {
        $item = $this->featureDomainService->createInstance();
        return view('Core::featureDomain.create', compact('item'));
    }

    public function store(FeatureDomainRequest $request)
    {
        $validatedData = $request->validated();
        $featureDomain = $this->featureDomainService->create($validatedData);


        return redirect()->route('featureDomains.index')->with('success', __('Core::msg.addSuccess', [
            'entityToString' => $featureDomain,
            'modelName' => __('Core::featureDomain.singular')
        ]));
    }
    public function show(string $id)
    {
        $item = $this->featureDomainService->find($id);
        return view('Core::featuredomain.show', compact('item'));
    }

    public function edit(string $id)
    {
        $item = $this->featureDomainService->find($id);
        return view('Core::featureDomain.edit', compact('item'));
    }

    public function update(FeatureDomainRequest $request, string $id)
    {
        $validatedData = $request->validated();
        $featuredomain = $this->featureDomainService->update($id, $validatedData);



        return redirect()->route('featureDomains.index')->with(
            'success',
            __('Core::msg.updateSuccess', [
                'entityToString' => $featuredomain,
                'modelName' =>  __('Core::featuredomain.singular')
                ])
        );
    }

    public function destroy(string $id)
    {
        $featuredomain = $this->featureDomainService->destroy($id);
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

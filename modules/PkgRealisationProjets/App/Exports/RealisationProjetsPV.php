<?php
 namespace Modules\PkgRealisationProjets\App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RealisationProjetsPV implements FromArray, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $data;
    protected $format;
    protected $taches;
    protected $evaluateurs;
    protected $groupe;

    public function __construct($data, $format = 'xlsx')
    {
        $this->data = $data;
        $this->format = $format;

        // Déduire les tâches depuis tous les projets
        $this->taches = collect($this->data)
            ->flatMap(fn($rp) => $rp->realisationTaches)
            ->pluck('tache')
            ->unique('id')
            ->values();

        // Déduire les évaluateurs depuis toutes les évaluations
        $this->evaluateurs = collect($this->data)
            ->flatMap(fn($rp) => $rp->evaluationRealisationProjets)
            ->pluck('evaluateur')
            ->unique('id')
            ->values();

        // Récupérer le groupe depuis le premier projet
        $this->groupe = optional($this->data->first()?->affectationProjet?->groupe);
    }

    public function headings(): array
    {
        $base = ['Nom', 'Prénom'];
        $questions = [];

        $number = 1;
        foreach ($this->taches as $tache) {
            $questions[] = $tache->code ?? ('Q' . $number); // Utilise le code si disponible, sinon fallback
            $number++;
        }

        return array_merge($base, $questions);
    }

    public function array(): array
    {
        $rows = [];


        // Ajout des évaluateurs
        $rows[] = ['']; // Ligne vide 


         // Ajout du groupe avec style
        $rows[] = ['Groupe :', $this->groupe->code ?? ''];

        foreach ($this->data as $realisationProjet) {

            $row = [];
            $row[] = $realisationProjet->apprenant->nom ?? '';
            $row[] = $realisationProjet->apprenant->prenom ?? '';

            foreach ($realisationProjet->realisationTaches as $rt) {
              

               
                foreach ($this->taches as $tache) {
                    if($rt->tache_id === $tache->id ){
                        $note =  $rt->note ?? '';
                        $row[] = number_format($rt->note, 2, '.', '');
                    }
                }
            }

            $rows[] = $row;
        }


        $rows[] = ['']; // Ligne sna style
        $rows[] = ['']; // Ligne sna style

        // Ligne sna style avec style 
        $rows[] = ['Évaluateurs :'];
        foreach ($this->evaluateurs as $e) {
            $rows[] = [($e->nom ?? '') . ' ' . ($e->prenom ?? '')];
        }
       

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();

        // Appliquer les styles uniquement sur la zone contenant des notes et en-têtes
        $styleRangeEndRow = $this->getDataSectionEndRow($sheet);

        $sheet->getStyle("A1:{$lastColumn}{$styleRangeEndRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        $sheet->getStyle("A1:{$lastColumn}1")->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => '4F81BD']],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);
    }

    private function getDataSectionEndRow(Worksheet $sheet)
    {
        // Trouve la première ligne vide après les notes (pour ne pas appliquer de style sur les évaluateurs/groupe)
        $highestRow = $sheet->getHighestRow();
        for ($row = 2; $row <= $highestRow; $row++) {
            $value = $sheet->getCell("A$row")->getValue();
            if ($value === null || trim($value) === '') {
                return $row - 1;
            }
        }
        return $highestRow;
    }
}

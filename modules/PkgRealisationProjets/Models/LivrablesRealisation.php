<?php


namespace Modules\PkgRealisationProjets\Models;
use Modules\PkgRealisationProjets\Models\Base\BaseLivrablesRealisation;

class LivrablesRealisation extends BaseLivrablesRealisation
{
    protected $with = [
        'livrable',
    ];

    /**
     * Vérifie si le lien du livrable est un lien GitHub.
     */
    public function isGithubLink(): bool
    {
        if (empty($this->lien)) {
            return false;
        }
        return (bool) preg_match('#^(https?://)?(www\.)?github\.com(/.*)?$#i', trim($this->lien));
    }

    /**
     * Retourne l'URL équivalente pour l'éditeur web GitHub (.dev).
     */
    public function getGithubDevUrl(): ?string
    {
        if (!$this->isGithubLink()) {
            return null;
        }
        $url = trim($this->lien);
        if (!preg_match('#^https?://#i', $url)) {
            $url = 'https://' . $url;
        }
        return preg_replace('#^https?://(www\.)?github\.com#i', 'https://github.dev', $url);
    }

    // public function generateReference(): string
    // {
    //     return $this->realisationProjet->reference . "-" . $this->livrable->reference ;
    // }
}

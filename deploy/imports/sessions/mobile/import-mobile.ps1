<#
.SYNOPSIS
    Guide interactif pas-à-pas pour l'importation et la synchronisation des sessions Mobile dans Solicode LMS.

.DESCRIPTION
    Ce script automatise et sécurise les 5 étapes d'importation depuis l'autoformation mobile vers le LMS :
    1. Diagnostic des fichiers CSV et détection automatique du niveau (N2 / N3)
    2. Analyse différentielle et suppression sécurisée des orphelins en BDD
    3. Copie vers les DataSeeders officiels du projet
    4. Génération de la fiche de déploiement deploy/updates/update_X.md
    5. Exécution ordonnée des 6 seeders Laravel en local

.PARAMETER Niveau
    Force un palier spécifique (ex: -Niveau 3 ou -Niveau 2). Si omis, le niveau est détecté automatiquement.

.PARAMETER Auto
    Mode semi-automatique validant par défaut chaque étape confirmable (utile pour exécution rapide).

.EXAMPLE
    ./import-mobile.ps1
    ./import-mobile.ps1 -Niveau 3
#>

param (
    [string]$Niveau = "",
    [switch]$Auto
)

# Configuration de l'encodage console pour les caractères accentués et emojis
try {
    [Console]::OutputEncoding = [System.Text.Encoding]::UTF8
} catch {}

# -----------------------------------------------------------------------------
# 0. Initialisation des Répertoires et Vérification de l'Environnement
# -----------------------------------------------------------------------------
$ScriptDir = $PSScriptRoot
if (-not $ScriptDir) {
    $ScriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
}

# Détermination dynamique de la racine du projet Solicode LMS
$ProjectRoot = (Resolve-Path "$ScriptDir\..\..\..\..").Path
if (-not (Test-Path "$ProjectRoot\artisan")) {
    if (Test-Path ".\artisan") {
        $ProjectRoot = (Get-Item ".").FullName
    } else {
        Write-Host "`n[ERREUR] Impossible de localiser la racine du projet Solicode LMS (fichier artisan manquant)." -ForegroundColor Red
        exit 1
    }
}

Set-Location $ProjectRoot
$MobileDir = "$ProjectRoot\deploy\imports\sessions\mobile"

function Show-Header {
    param([string]$Title)
    Write-Host ""
    Write-Host ("=" * 72) -ForegroundColor Cyan
    Write-Host "  $Title" -ForegroundColor Yellow -NoNewline
    Write-Host ""
    Write-Host ("=" * 72) -ForegroundColor Cyan
}

function Show-StepHeader {
    param([int]$Number, [string]$Title)
    Write-Host ""
    Write-Host "------------------------------------------------------------------------" -ForegroundColor DarkGray
    Write-Host " ÉTAPE $Number : $Title" -ForegroundColor Cyan
    Write-Host "------------------------------------------------------------------------" -ForegroundColor DarkGray
}

function Ask-Confirm {
    param(
        [string]$Message,
        [bool]$DefaultYes = $true
    )
    if ($Auto) {
        Write-Host "$Message [Auto: Oui]" -ForegroundColor DarkGreen
        return $true
    }
    $choices = if ($DefaultYes) { "[O/n]" } else { "[o/N]" }
    Write-Host "$Message $choices : " -ForegroundColor Yellow -NoNewline
    $inputVal = Read-Host
    if ([string]::IsNullOrWhiteSpace($inputVal)) {
        return $DefaultYes
    }
    return ($inputVal.Trim().ToLower() -in @('o', 'oui', 'y', 'yes'))
}

Show-Header "🚀 ASSISTANT D'IMPORTATION DES SESSIONS MOBILE — SOLICODE LMS"
Write-Host "📁 Racine du projet : " -NoNewline; Write-Host $ProjectRoot -ForegroundColor Green
Write-Host "📂 Dossier source   : " -NoNewline; Write-Host $MobileDir -ForegroundColor Green

# -----------------------------------------------------------------------------
# ÉTAPE 1 : Diagnostic des 6 fichiers CSV & Détection du Niveau
# -----------------------------------------------------------------------------
Show-StepHeader 1 "Contrôle des fichiers CSV sources & Détection du Niveau"

$requiredFiles = @(
    @{ Name = "competences";         Candidates = @("competences.csv", "competence.csv") },
    @{ Name = "microCompetences";    Candidates = @("microCompetences.csv", "microCompetence.csv") },
    @{ Name = "uniteApprentissages"; Candidates = @("uniteApprentissages.csv", "uniteApprentissage.csv", "uas.csv") },
    @{ Name = "chapitres";           Candidates = @("chapitres.csv", "chapitre.csv") },
    @{ Name = "sessionFormation";    Candidates = @("sessionFormation.csv", "sessionFormations.csv", "sessions.csv") },
    @{ Name = "alignementUa";        Candidates = @("alignementUa.csv", "alignementUas.csv") }
)

$allFound = $true
$detectedNiveaux = @{}
$fileStatusList = @()

foreach ($req in $requiredFiles) {
    $foundPath = $null
    foreach ($cand in $req.Candidates) {
        $checkPath = Join-Path $MobileDir $cand
        if (Test-Path $checkPath) {
            $foundPath = $checkPath
            break
        }
    }

    if ($foundPath) {
        $lines = @(Get-Content -Path $foundPath -ErrorAction SilentlyContinue)
        $rowCount = [Math]::Max(0, $lines.Count - 1)
        $fileStatusList += [PSCustomObject]@{
            Fichier = (Split-Path -Leaf $foundPath)
            Lignes  = $rowCount
            Statut  = "OK"
        }

        # Détection du niveau à partir des codes présents dans les lignes
        foreach ($line in $lines) {
            if ($line -match '^(?:[^,]+,)*(?:"?)((?:TUTO-)?[A-Z]+\.([0-9])[^",]*)(?:"?)') {
                $digit = $Matches[2]
                if ($digit) {
                    if ($detectedNiveaux.ContainsKey($digit)) {
                        $detectedNiveaux[$digit]++
                    } else {
                        $detectedNiveaux[$digit] = 1
                    }
                }
            }
        }
    } else {
        $allFound = $false
        $fileStatusList += [PSCustomObject]@{
            Fichier = $req.Candidates[0]
            Lignes  = 0
            Statut  = "MANQUANT"
        }
    }
}

# Affichage du bilan des fichiers
Write-Host ""
foreach ($item in $fileStatusList) {
    if ($item.Statut -eq "OK") {
        Write-Host "  [OK] " -ForegroundColor Green -NoNewline
        Write-Host ("{0,-26}" -f $item.Fichier) -NoNewline
        Write-Host " : $($item.Lignes) ligne(s) de données" -ForegroundColor Gray
    } else {
        Write-Host "  [MANQUANT] " -ForegroundColor Red -NoNewline
        Write-Host ("{0,-20}" -f $item.Fichier) -ForegroundColor Red
    }
}

if (-not $allFound) {
    Write-Host "`n❌ Des fichiers CSV sont manquants dans : $MobileDir" -ForegroundColor Red
    Write-Host "Veuillez exporter les 6 fichiers depuis le dépôt autoformation-mobile et relancer ce script." -ForegroundColor Yellow
    exit 1
}

# Détermination du niveau
$targetNiveau = $Niveau
if (-not $targetNiveau) {
    if ($detectedNiveaux.Count -gt 0) {
        $sortedLevels = $detectedNiveaux.GetEnumerator() | Sort-Object Value -Descending
        $targetNiveau = $sortedLevels[0].Key
        Write-Host "`n🎯 Niveau détecté automatiquement depuis les CSV : " -NoNewline
        Write-Host "N$targetNiveau" -ForegroundColor Green -NoNewline
        Write-Host " (codes de type C.$targetNiveau..., D.$targetNiveau..., UA.$targetNiveau..., T.$targetNiveau...)"
    } else {
        $targetNiveau = "3"
        Write-Host "`nℹ️ Niveau non détectable avec certitude, valeur par défaut appliquée : " -NoNewline
        Write-Host "N$targetNiveau" -ForegroundColor Yellow
    }
} else {
    $targetNiveau = ($targetNiveau -replace '[^0-9]', '')
    Write-Host "`n🎯 Niveau forcé par argument : " -NoNewline
    Write-Host "N$targetNiveau" -ForegroundColor Cyan
}

if (-not (Ask-Confirm "`nPasser à l'Étape 2 (Analyse différentielle BDD)")) {
    Write-Host "Importation interrompue par l'utilisateur." -ForegroundColor DarkYellow
    exit 0
}

# -----------------------------------------------------------------------------
# ÉTAPE 2 : Détection Différentielle des Données Orphelines
# -----------------------------------------------------------------------------
Show-StepHeader 2 "Analyse Différentielle BDD vs CSV (Filière DMB - Niveau N$targetNiveau)"

$detectCmd = "php deploy/imports/sessions/detect_obsolete_data.php --filiere=DMB --niveau=$targetNiveau"
Write-Host "Exécution : $detectCmd`n" -ForegroundColor DarkGray
Invoke-Expression $detectCmd

$reportFile = "$ProjectRoot\deploy\imports\sessions\reports\DMB_N${targetNiveau}_elements_a_supprimer.csv"
$hasOrphans = $false
if (Test-Path $reportFile) {
    $reportLines = @(Get-Content -Path $reportFile -ErrorAction SilentlyContinue)
    if ($reportLines.Count -gt 1) {
        $hasOrphans = $true
    }
}

if ($hasOrphans) {
    Write-Host "`n⚠️ Des éléments orphelins ont été détectés en BDD pour le niveau N$targetNiveau." -ForegroundColor Yellow
    if (Ask-Confirm "Voulez-vous exécuter la SUPPRESSION en cascade de ces éléments en BDD (--delete) ?" $false) {
        $deleteCmd = "php deploy/imports/sessions/detect_obsolete_data.php --filiere=DMB --niveau=$targetNiveau --delete"
        Write-Host "Exécution de la suppression : $deleteCmd`n" -ForegroundColor Red
        Invoke-Expression $deleteCmd
        if ($LASTEXITCODE -ne 0) {
            Write-Host "`n❌ Erreur lors de la suppression en BDD." -ForegroundColor Red
            exit 1
        }
    } else {
        Write-Host "Suppression ignorée. Poursuite du workflow sans purge BDD." -ForegroundColor DarkYellow
    }
} else {
    Write-Host "`n✅ Aucun élément orphelin à supprimer pour le niveau N$targetNiveau. La BDD est saine." -ForegroundColor Green
}

if (-not (Ask-Confirm "`nPasser à l'Étape 3 (Mise à jour des DataSeeders)")) {
    Write-Host "Importation interrompue par l'utilisateur." -ForegroundColor DarkYellow
    exit 0
}

# -----------------------------------------------------------------------------
# ÉTAPE 3 : Copie vers les DataSeeders du Projet
# -----------------------------------------------------------------------------
Show-StepHeader 3 "Mise à jour des DataSeeders de Solicode LMS"

Write-Host "Les fichiers CSV du LMS sous modules/PkgCompetences/Database/data/ et modules/PkgSessions/Database/data/"
Write-Host "vont être remplacés par les données de deploy/imports/sessions/mobile/." -ForegroundColor DarkGray

if (Ask-Confirm "Confirmez-vous la copie vers les DataSeeders ?") {
    $copyCmd = "php deploy/imports/sessions/copy_to_dataseeders.php --filiere=DMB --niveau=$targetNiveau"
    Write-Host "Exécution : $copyCmd`n" -ForegroundColor DarkGray
    Invoke-Expression $copyCmd
    if ($LASTEXITCODE -ne 0) {
        Write-Host "`n❌ Échec de la copie vers les DataSeeders." -ForegroundColor Red
        exit 1
    }
} else {
    Write-Host "Copie annulée. Les DataSeeders n'ont pas été modifiés." -ForegroundColor DarkYellow
}

if (-not (Ask-Confirm "`nPasser à l'Étape 4 (Génération de la fiche de déploiement)")) {
    Write-Host "Importation interrompue par l'utilisateur." -ForegroundColor DarkYellow
    exit 0
}

# -----------------------------------------------------------------------------
# ÉTAPE 4 : Génération de la Fiche de Déploiement update_X.md
# -----------------------------------------------------------------------------
Show-StepHeader 4 "Génération de la Fiche de Déploiement deploy/updates/update_X.md"

$defaultTitle = "Ajout des sessions Mobile N$targetNiveau et synchronisation des compétences"
$chosenTitle = $defaultTitle

if (-not $Auto) {
    Write-Host "Titre de la mise à jour (Entrée pour valider par défaut) :" -ForegroundColor Yellow
    Write-Host "  Défaut : " -NoNewline; Write-Host "'$defaultTitle'" -ForegroundColor Cyan
    Write-Host "Titre personnalisé : " -NoNewline
    $inputTitle = Read-Host
    if (-not [string]::IsNullOrWhiteSpace($inputTitle)) {
        $chosenTitle = $inputTitle.Trim()
    }
}

$genDocCmd = "php deploy/imports/sessions/generate_update_doc.php --filiere=DMB --niveau=$targetNiveau --title=`"$chosenTitle`""
Write-Host "Exécution : $genDocCmd`n" -ForegroundColor DarkGray
Invoke-Expression $genDocCmd

if ($LASTEXITCODE -ne 0) {
    Write-Host "`n❌ Échec de la génération de la fiche de déploiement." -ForegroundColor Red
    exit 1
}

if (-not (Ask-Confirm "`nPasser à l'Étape 5 (Exécution des 6 seeders en local)")) {
    Write-Host "Workflow suspendu avant l'exécution des seeders." -ForegroundColor DarkYellow
    exit 0
}

# -----------------------------------------------------------------------------
# ÉTAPE 5 : Exécution Séquentielle des 6 Seeders en Local
# -----------------------------------------------------------------------------
Show-StepHeader 5 "Exécution Séquentielle des 6 Seeders Laravel"

$seeders = @(
    "Modules\PkgCompetences\Database\Seeders\CompetenceSeeder",
    "Modules\PkgCompetences\Database\Seeders\MicroCompetenceSeeder",
    "Modules\PkgCompetences\Database\Seeders\UniteApprentissageSeeder",
    "Modules\PkgCompetences\Database\Seeders\ChapitreSeeder",
    "Modules\PkgSessions\Database\Seeders\SessionFormationSeeder",
    "Modules\PkgSessions\Database\Seeders\AlignementUaSeeder"
)

$seederIndex = 1
foreach ($seederClass in $seeders) {
    $shortName = $seederClass.Substring($seederClass.LastIndexOf('\') + 1)
    Write-Host "`n[$seederIndex/6] Exécution de $shortName..." -ForegroundColor Cyan
    
    $seedCmd = "php artisan db:seed --class=`"$seederClass`""
    Invoke-Expression $seedCmd

    if ($LASTEXITCODE -ne 0) {
        Write-Host "`n❌ ERREUR BLOQUANTE lors de l'exécution de $shortName !" -ForegroundColor Red
        Write-Host "La séquence est immédiatement interrompue pour protéger la cohérence de la base de données." -ForegroundColor Yellow
        Write-Host "Consultez le message d'erreur ci-dessus pour isoler la ligne incriminée dans le CSV." -ForegroundColor DarkYellow
        exit 1
    }
    Write-Host "  ✅ $shortName exécuté avec succès." -ForegroundColor Green
    $seederIndex++
}

# -----------------------------------------------------------------------------
# BILAN FINAL
# -----------------------------------------------------------------------------
Show-Header "🎉 IMPORTATION ET SYNCHRONISATION TERMINÉES AVEC SUCCÈS !"
Write-Host "Filière            : " -NoNewline; Write-Host "DMB (Développement Mobile)" -ForegroundColor Green
Write-Host "Niveau importé     : " -NoNewline; Write-Host "N$targetNiveau" -ForegroundColor Green
Write-Host "DataSeeders LMS    : " -NoNewline; Write-Host "Mis à jour" -ForegroundColor Green
Write-Host "Seeders exécutés   : " -NoNewline; Write-Host "6/6 seeders validés en base locale" -ForegroundColor Green
Write-Host ""

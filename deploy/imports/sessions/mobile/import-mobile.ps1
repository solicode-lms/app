<#
.SYNOPSIS
    Guide interactif pas-a-pas pour l'importation et la synchronisation des sessions Mobile dans Solicode LMS.

.DESCRIPTION
    Ce script automatise et securise les 5 etapes d'importation depuis l'autoformation mobile vers le LMS :
    1. Diagnostic des fichiers CSV et detection automatique du niveau (N2 / N3)
    2. Analyse differentielle et suppression securisee des orphelins en BDD
    3. Copie vers les DataSeeders officiels du projet
    4. Generation de la fiche de deploiement deploy/updates/update_X_YYYY-MM-DD.md
    5. Execution ordonnee des 6 seeders Laravel en local (un par un ou tous d'un coup)

.PARAMETER Niveau
    Force un palier specifique (ex: -Niveau 3 ou -Niveau 2). Si omis, le niveau est detecte automatiquement.

.PARAMETER Auto
    Mode semi-automatique validant par defaut chaque etape confirmable (utile pour execution rapide).

.EXAMPLE
    ./import-mobile.ps1
    ./import-mobile.ps1 -Niveau 3
#>

param (
    [string]$Niveau = "",
    [switch]$Auto
)

# -----------------------------------------------------------------------------
# 0. Initialisation des Repertoires et Verification de l'Environnement
# -----------------------------------------------------------------------------
$ScriptDir = $PSScriptRoot
if (-not $ScriptDir) {
    $ScriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
}

# Determination dynamique de la racine du projet Solicode LMS
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
    Write-Host "  $Title" -ForegroundColor Yellow
    Write-Host ("=" * 72) -ForegroundColor Cyan
}

function Show-StepHeader {
    param([int]$Number, [string]$Title)
    Write-Host ""
    Write-Host ("-" * 72) -ForegroundColor DarkGray
    Write-Host " ETAPE $Number : $Title" -ForegroundColor Cyan
    Write-Host ("-" * 72) -ForegroundColor DarkGray
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

Show-Header "ASSISTANT D'IMPORTATION DES SESSIONS MOBILE - SOLICODE LMS"
Write-Host "Racine du projet : " -NoNewline; Write-Host $ProjectRoot -ForegroundColor Green
Write-Host "Dossier source   : " -NoNewline; Write-Host $MobileDir -ForegroundColor Green

# -----------------------------------------------------------------------------
# ETAPE 1 : Diagnostic des 6 fichiers CSV & Detection du Niveau
# -----------------------------------------------------------------------------
Show-StepHeader 1 "Controle des fichiers CSV sources & Detection du Niveau"

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

        # Detection du niveau a partir des codes dans les lignes
        foreach ($line in $lines) {
            if ($line -match '\b(?:TUTO-)?[A-Z]+\.([0-9])') {
                $digit = $Matches[1]
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
        Write-Host (" : {0} ligne(s) de donnees" -f $item.Lignes) -ForegroundColor Gray
    } else {
        Write-Host "  [MANQUANT] " -ForegroundColor Red -NoNewline
        Write-Host ("{0,-20}" -f $item.Fichier) -ForegroundColor Red
    }
}

if (-not $allFound) {
    Write-Host "`n[ERREUR] Des fichiers CSV sont manquants dans : $MobileDir" -ForegroundColor Red
    Write-Host "Veuillez exporter les 6 fichiers depuis le depot autoformation-mobile et relancer ce script." -ForegroundColor Yellow
    exit 1
}

# Determination du niveau
$targetNiveau = $Niveau
if (-not $targetNiveau) {
    if ($detectedNiveaux.Count -gt 0) {
        $sortedLevels = $detectedNiveaux.GetEnumerator() | Sort-Object Value -Descending
        $targetNiveau = $sortedLevels[0].Key
        Write-Host "`nNiveau detecte automatiquement depuis les CSV : " -NoNewline
        Write-Host "N$targetNiveau" -ForegroundColor Green
        Write-Host ("  (codes de type C.{0}..., D.{0}..., UA.{0}..., T.{0}...)" -f $targetNiveau) -ForegroundColor DarkGray
    } else {
        $targetNiveau = "3"
        Write-Host "`nNiveau non detectable avec certitude, valeur par defaut appliquee : " -NoNewline
        Write-Host "N$targetNiveau" -ForegroundColor Yellow
    }
} else {
    $targetNiveau = ($targetNiveau -replace '[^0-9]', '')
    Write-Host "`nNiveau force par argument : " -NoNewline
    Write-Host "N$targetNiveau" -ForegroundColor Cyan
}

if (-not (Ask-Confirm "`nPasser a l'Etape 2 (Analyse differentielle BDD)")) {
    Write-Host "Importation interrompue par l'utilisateur." -ForegroundColor DarkYellow
    exit 0
}

# -----------------------------------------------------------------------------
# ETAPE 2 : Detection Differentielle des Donnees Orphelines
# -----------------------------------------------------------------------------
Show-StepHeader 2 "Analyse Differentielle BDD vs CSV (Filiere DMB - Niveau N$targetNiveau)"

$detectCmd = "php deploy/imports/sessions/detect_obsolete_data.php --filiere=DMB --niveau=$targetNiveau"
Write-Host "Execution : $detectCmd`n" -ForegroundColor DarkGray
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
    Write-Host "`n[ATTENTION] Des elements orphelins ont ete detectes en BDD pour le niveau N$targetNiveau." -ForegroundColor Yellow
    if (Ask-Confirm "Voulez-vous executer la SUPPRESSION en cascade de ces elements en BDD (--delete) ?" $false) {
        $deleteCmd = "php deploy/imports/sessions/detect_obsolete_data.php --filiere=DMB --niveau=$targetNiveau --delete"
        Write-Host "Execution de la suppression : $deleteCmd`n" -ForegroundColor Red
        Invoke-Expression $deleteCmd
        if ($LASTEXITCODE -ne 0) {
            Write-Host "`n[ERREUR] Erreur lors de la suppression en BDD." -ForegroundColor Red
            exit 1
        }
    } else {
        Write-Host "Suppression ignoree. Poursuite du workflow sans purge BDD." -ForegroundColor DarkYellow
    }
} else {
    Write-Host "`n[OK] Aucun element orphelin a supprimer pour le niveau N$targetNiveau. La BDD est saine." -ForegroundColor Green
}

if (-not (Ask-Confirm "`nPasser a l'Etape 3 (Mise a jour des DataSeeders)")) {
    Write-Host "Importation interrompue par l'utilisateur." -ForegroundColor DarkYellow
    exit 0
}

# -----------------------------------------------------------------------------
# ETAPE 3 : Copie vers les DataSeeders du Projet
# -----------------------------------------------------------------------------
Show-StepHeader 3 "Mise a jour des DataSeeders de Solicode LMS"

Write-Host "Les fichiers CSV du LMS sous modules/PkgCompetences/Database/data/ et modules/PkgSessions/Database/data/"
Write-Host "vont etre remplaces par les donnees de deploy/imports/sessions/mobile/." -ForegroundColor DarkGray

if (Ask-Confirm "Confirmez-vous la copie vers les DataSeeders ?") {
    $copyCmd = "php deploy/imports/sessions/copy_to_dataseeders.php --filiere=DMB --niveau=$targetNiveau"
    Write-Host "Execution : $copyCmd`n" -ForegroundColor DarkGray
    Invoke-Expression $copyCmd
    if ($LASTEXITCODE -ne 0) {
        Write-Host "`n[ERREUR] Echec de la copie vers les DataSeeders." -ForegroundColor Red
        exit 1
    }
} else {
    Write-Host "Copie annulee. Les DataSeeders n'ont pas ete modifies." -ForegroundColor DarkYellow
}

# -----------------------------------------------------------------------------
# ETAPE 4 : Fiche de Deploiement deploy/updates/update_X_YYYY-MM-DD.md
# -----------------------------------------------------------------------------
Show-StepHeader 4 "Fiche de Deploiement deploy/updates/update_X_YYYY-MM-DD.md"

if (Ask-Confirm "Voulez-vous generer / actualiser la fiche de deploiement deploy/updates/update_X_YYYY-MM-DD.md ?") {
    $defaultTitle = "Ajout des sessions Mobile N$targetNiveau et synchronisation des competences"
    $chosenTitle = $defaultTitle

    if (-not $Auto) {
        Write-Host "Titre de la mise a jour (Entree pour valider par defaut) :" -ForegroundColor Yellow
        Write-Host "  Defaut : " -NoNewline; Write-Host "'$defaultTitle'" -ForegroundColor Cyan
        Write-Host "Titre personnalise : " -NoNewline
        $inputTitle = Read-Host
        if (-not [string]::IsNullOrWhiteSpace($inputTitle)) {
            $chosenTitle = $inputTitle.Trim()
        }
    }

    $genDocCmd = "php deploy/imports/sessions/generate_update_doc.php --filiere=DMB --niveau=$targetNiveau --title=`"$chosenTitle`""
    Write-Host "Execution : $genDocCmd`n" -ForegroundColor DarkGray
    Invoke-Expression $genDocCmd

    if ($LASTEXITCODE -ne 0) {
        Write-Host "`n[ATTENTION] Probleme lors de la generation de la fiche de deploiement." -ForegroundColor Yellow
    }
} else {
    Write-Host "Etape 4 ignoree par le concepteur. Poursuite directe vers l'execution des seeders." -ForegroundColor Yellow
}

# -----------------------------------------------------------------------------
# ETAPE 5 : Execution des Seeders Laravel en Local (un par un ou tous)
# -----------------------------------------------------------------------------
Show-StepHeader 5 "Execution des Seeders Laravel en Local"

$seeders = @(
    @{ Name = "CompetenceSeeder";         Class = "Modules\PkgCompetences\Database\Seeders\CompetenceSeeder";         Desc = "Competences" },
    @{ Name = "MicroCompetenceSeeder";    Class = "Modules\PkgCompetences\Database\Seeders\MicroCompetenceSeeder";    Desc = "Micro-Competences" },
    @{ Name = "UniteApprentissageSeeder"; Class = "Modules\PkgCompetences\Database\Seeders\UniteApprentissageSeeder"; Desc = "Unites d'Apprentissage (UA)" },
    @{ Name = "ChapitreSeeder";           Class = "Modules\PkgCompetences\Database\Seeders\ChapitreSeeder";           Desc = "Chapitres / Tutoriels" },
    @{ Name = "SessionFormationSeeder";   Class = "Modules\PkgSessions\Database\Seeders\SessionFormationSeeder";   Desc = "Sessions de Formation" },
    @{ Name = "AlignementUaSeeder";       Class = "Modules\PkgSessions\Database\Seeders\AlignementUaSeeder";       Desc = "Alignements Sessions - UAs" }
)

Write-Host "Mode d'execution des seeders :" -ForegroundColor Cyan
Write-Host "  [1] Un par un avec confirmation a chaque etape [Defaut - Recommande]" -ForegroundColor Yellow
Write-Host "  [2] Tous d'un coup (sequence complete automatique)" -ForegroundColor DarkYellow
Write-Host "  [3] Ignorer cette etape" -ForegroundColor DarkGray
Write-Host "Votre choix [1/2/3] (Defaut: 1) : " -NoNewline -ForegroundColor Yellow

$seederMode = "1"
if (-not $Auto) {
    $inputMode = Read-Host
    if (-not [string]::IsNullOrWhiteSpace($inputMode)) {
        $seederMode = $inputMode.Trim()
    }
} else {
    Write-Host "2 [Auto]" -ForegroundColor DarkGreen
    $seederMode = "2"
}

if ($seederMode -eq "3") {
    Write-Host "Execution des seeders ignoree par l'utilisateur." -ForegroundColor DarkYellow
} else {
    $isOneByOne = ($seederMode -ne "2")
    $seederIndex = 1
    $executedCount = 0

    foreach ($s in $seeders) {
        $shortName = $s.Name
        $seederClass = $s.Class
        $seederDesc = $s.Desc

        $runThis = $true
        if ($isOneByOne) {
            $runThis = Ask-Confirm "`n[$seederIndex/6] Executer $shortName ($seederDesc) ?" $true
        } else {
            Write-Host "`n[$seederIndex/6] Execution de $shortName ($seederDesc)..." -ForegroundColor Cyan
        }

        if ($runThis) {
            $seedCmd = "php artisan db:seed --class=`"$seederClass`""
            Invoke-Expression $seedCmd

            if ($LASTEXITCODE -ne 0) {
                Write-Host "`n[ERREUR BLOQUANTE] lors de l'execution de $shortName !" -ForegroundColor Red
                Write-Host "Consultez le message d'erreur SQL/Laravel ci-dessus pour identifier l'anomalie." -ForegroundColor Yellow
                if (-not (Ask-Confirm "Voulez-vous tout de meme continuer vers le seeder suivant ?" $false)) {
                    Write-Host "Arret de la sequence de seeders par l'utilisateur." -ForegroundColor DarkYellow
                    break
                }
            } else {
                Write-Host "  [OK] $shortName ($seederDesc) execute avec succes." -ForegroundColor Green
                $executedCount++
            }
        } else {
            Write-Host "  [IGNORE] $shortName ignore." -ForegroundColor DarkGray
        }

        $seederIndex++
    }

    Write-Host "`nBilan seeders : $executedCount / 6 execute(s) avec succes." -ForegroundColor Green
}

# -----------------------------------------------------------------------------
# BILAN FINAL
# -----------------------------------------------------------------------------
Show-Header "IMPORTATION ET SYNCHRONISATION TERMINEES AVEC SUCCES !"
Write-Host "Filiere            : " -NoNewline; Write-Host "DMB (Developpement Mobile)" -ForegroundColor Green
Write-Host "Niveau importe     : " -NoNewline; Write-Host "N$targetNiveau" -ForegroundColor Green
Write-Host "DataSeeders LMS    : " -NoNewline; Write-Host "Mis a jour" -ForegroundColor Green
Write-Host "Seeders executes   : " -NoNewline; Write-Host "$executedCount / 6 seeders executes" -ForegroundColor Green
Write-Host ""

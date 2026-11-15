<#
.SYNOPSIS
    Guide interactif pour l'initialisation et l'importation de la derniere base de donnees dans Solicode LMS.

.DESCRIPTION
    Ce script reproduit et automatise la procedure officielle de backup_db/backup.md :
    1. Initialise la base de donnees en supprimant toutes les tables existantes (php artisan db:wipe).
    2. Detecte automatiquement la sauvegarde .sql la plus recente presente dans backup_db/ selon sa date.
    3. Importe le fichier SQL avec l'encodage UTF-8 via le client mysql :
       Get-Content .\<fichier>.sql -Raw -Encoding UTF8 | mysql -u root -p <database>

.PARAMETER Database
    Nom de la base de donnees cible (par defaut : solicode_2026).

.PARAMETER File
    Chemin ou nom d'un fichier .sql specifique a importer (outrepasse la detection automatique).

.PARAMETER SkipWipe
    Ignore l'etape d'initialisation (php artisan db:wipe) et passe directement a l'importation.

.PARAMETER Force
    Execute les etapes sans demander de confirmation interactive.

.EXAMPLE
    .\import-database.ps1
    .\import-database.ps1 -Database solicode_2026
    .\import-database.ps1 -File .\solicode-2026_29_10_26.sql
#>

param (
    [string]$Database = "solicode_2026",
    [string]$File = "",
    [switch]$SkipWipe,
    [switch]$Force
)

# -----------------------------------------------------------------------------
# 0. Initialisation de l'Environnement et des Chemins
# -----------------------------------------------------------------------------
$ScriptDir = $PSScriptRoot
if (-not $ScriptDir) {
    $ScriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
}

# Localisation dynamique de la racine du projet Solicode LMS
$ProjectRoot = (Resolve-Path "$ScriptDir\..").Path
if (-not (Test-Path "$ProjectRoot\artisan")) {
    if (Test-Path ".\artisan") {
        $ProjectRoot = (Get-Item ".").FullName
    } else {
        Write-Host "`n[ERREUR] Impossible de localiser la racine du projet Solicode LMS (fichier artisan manquant)." -ForegroundColor Red
        exit 1
    }
}

Set-Location $ProjectRoot
$BackupDir = "$ProjectRoot\backup_db"

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
    if ($Force) {
        Write-Host "$Message [Force: Oui]" -ForegroundColor DarkGreen
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

Show-Header "ASSISTANT D'IMPORTATION DE BASE DE DONNEES - SOLICODE LMS"
Write-Host "Racine du projet : " -NoNewline; Write-Host $ProjectRoot -ForegroundColor Green
Write-Host "Dossier backups  : " -NoNewline; Write-Host $BackupDir -ForegroundColor Green
Write-Host "Base cible       : " -NoNewline; Write-Host $Database -ForegroundColor Green

# -----------------------------------------------------------------------------
# ETAPE 1 : Initialisation de la Base de Donnees (php artisan db:wipe)
# -----------------------------------------------------------------------------
Show-StepHeader 1 "Initialisation de la base de donnees (php artisan db:wipe)"

if ($SkipWipe) {
    Write-Host "Option -SkipWipe activee : initialisation ignoree." -ForegroundColor DarkYellow
} else {
    Write-Host "Cette commande supprime toutes les tables et vues existantes dans la base '$Database'." -ForegroundColor DarkGray
    if (Ask-Confirm "Voulez-vous executer l'initialisation (php artisan db:wipe) ?") {
        Write-Host "`nExecution : php artisan db:wipe`n" -ForegroundColor DarkGray
        php artisan db:wipe
        if ($LASTEXITCODE -ne 0) {
            Write-Host "`n[ERREUR] Echec lors de l'initialisation de la base de donnees." -ForegroundColor Red
            if (-not (Ask-Confirm "Voulez-vous tout de meme continuer vers l'importation SQL ?" $false)) {
                exit 1
            }
        } else {
            Write-Host "`n[OK] Base de donnees initialisee avec succes." -ForegroundColor Green
        }
    } else {
        Write-Host "Initialisation ignoree par l'utilisateur." -ForegroundColor DarkYellow
    }
}

# -----------------------------------------------------------------------------
# ETAPE 2 : Detection de la Derniere Sauvegarde SQL
# -----------------------------------------------------------------------------
Show-StepHeader 2 "Detection de la derniere sauvegarde SQL"

$targetSqlFile = $null

if (-not [string]::IsNullOrWhiteSpace($File)) {
    # Fichier explicite demande
    $candidatePath = $File
    if (-not (Test-Path $candidatePath)) {
        $candidatePath = Join-Path $BackupDir $File
    }
    if (Test-Path $candidatePath) {
        $targetSqlFile = Get-Item $candidatePath
        Write-Host "Fichier specifie par argument : " -NoNewline
        Write-Host $targetSqlFile.Name -ForegroundColor Cyan
    } else {
        Write-Host "`n[ERREUR] Le fichier specifie est introuvable : $File" -ForegroundColor Red
        exit 1
    }
} else {
    # Scan et tri chronologique de tous les .sql de backup_db
    $sqlFiles = Get-ChildItem -Path $BackupDir -Filter "*.sql" -File
    if (-not $sqlFiles -or $sqlFiles.Count -eq 0) {
        Write-Host "`n[ERREUR] Aucun fichier .sql trouve dans : $BackupDir" -ForegroundColor Red
        exit 1
    }

    $analyzedFiles = @()

    foreach ($f in $sqlFiles) {
        $parsedDate = $null

        # Recherche de date dans le nom : ex solicode-2026_29_10_26.sql ou sauvegarde_14_07_25.sql
        if ($f.Name -match '_(\d{1,2})_(\d{1,2})_(\d{2,4})\.sql$') {
            $day = [int]$Matches[1]
            $month = [int]$Matches[2]
            $yearRaw = [int]$Matches[3]
            $year = if ($yearRaw -lt 100) { 2000 + $yearRaw } else { $yearRaw }

            # Validation du jour et mois
            if ($month -ge 1 -and $month -le 12 -and $day -ge 1 -and $day -le 31) {
                try {
                    $parsedDate = [DateTime]::new($year, $month, $day)
                } catch {
                    $parsedDate = $null
                }
            }
        }

        # Fallback sur la date de derniere modification du fichier si pas de date valide dans le nom
        $refDate = if ($parsedDate) { $parsedDate } else { $f.LastWriteTime }

        $analyzedFiles += [PSCustomObject]@{
            File         = $f
            DateRef      = $refDate
            DateFromName = ($null -ne $parsedDate)
        }
    }

    # Tri par date la plus recente en premier
    $sortedFiles = $analyzedFiles | Sort-Object DateRef -Descending
    $targetSqlFile = $sortedFiles[0].File

    Write-Host "Sauvegardes detectees dans backup_db/ (top 3 les plus recentes) :" -ForegroundColor DarkGray
    $maxShow = [Math]::Min(3, $sortedFiles.Count)
    for ($i = 0; $i -lt $maxShow; $i++) {
        $entry = $sortedFiles[$i]
        $tag = if ($i -eq 0) { "[PLUS RECENTE]" } else { "              " }
        $sizeMB = "{0:N2} MB" -f ($entry.File.Length / 1MB)
        $dateStr = $entry.DateRef.ToString("yyyy-MM-dd")
        $sourceType = if ($entry.DateFromName) { "(date nom)" } else { "(date modif)" }
        Write-Host "  $tag $($entry.File.Name) - $dateStr $sourceType - $sizeMB" -ForegroundColor $(if ($i -eq 0) { "Green" } else { "Gray" })
    }
}

Write-Host "`nFichier retenu pour l'importation : " -NoNewline
Write-Host $targetSqlFile.Name -ForegroundColor Green
$fileSizeMB = "{0:N2} MB" -f ($targetSqlFile.Length / 1MB)
Write-Host "Taille du fichier                 : $fileSizeMB" -ForegroundColor Gray

if (-not (Ask-Confirm "`nConfirmez-vous l'importation de ce fichier dans la base '$Database' ?")) {
    Write-Host "Importation annulee par l'utilisateur." -ForegroundColor DarkYellow
    exit 0
}

# -----------------------------------------------------------------------------
# ETAPE 3 : Importation SQL vers MySQL
# -----------------------------------------------------------------------------
Show-StepHeader 3 "Importation SQL vers MySQL"

Write-Host "Commande executee (selon backup_db/backup.md) :" -ForegroundColor DarkGray
Write-Host "  Get-Content .\$($targetSqlFile.Name) -Raw -Encoding UTF8 | mysql -u root -p $Database`n" -ForegroundColor Cyan

# Configuration de l'encodage UTF-8 selon backup.md
try { [Console]::OutputEncoding = [System.Text.Encoding]::UTF8 } catch {}
$OutputEncoding = [System.Text.Encoding]::UTF8

Write-Host "Veuillez saisir votre mot de passe MySQL si demande :" -ForegroundColor Yellow
$stopwatch = [System.Diagnostics.Stopwatch]::StartNew()

Get-Content -Path $targetSqlFile.FullName -Raw -Encoding UTF8 | mysql -u root -p $Database
$exitCode = $LASTEXITCODE
$stopwatch.Stop()

if ($exitCode -ne 0) {
    Write-Host "`n[ERREUR] L'importation MySQL a echoue avec le code de sortie : $exitCode" -ForegroundColor Red
    exit $exitCode
}

# -----------------------------------------------------------------------------
# BILAN FINAL
# -----------------------------------------------------------------------------
Show-Header "IMPORTATION TERMINEE AVEC SUCCES !"
Write-Host "Fichier importe    : " -NoNewline; Write-Host $targetSqlFile.Name -ForegroundColor Green
Write-Host "Base de donnees    : " -NoNewline; Write-Host $Database -ForegroundColor Green
Write-Host "Taille             : " -NoNewline; Write-Host $fileSizeMB -ForegroundColor Green
Write-Host "Duree d'execution  : " -NoNewline; Write-Host ("{0:N1} secondes" -f $stopwatch.Elapsed.TotalSeconds) -ForegroundColor Green
Write-Host ""

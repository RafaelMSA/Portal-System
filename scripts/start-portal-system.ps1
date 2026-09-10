Set-StrictMode -Version Latest
$ErrorActionPreference = 'Stop'

$projectRoot = Split-Path -Parent $PSScriptRoot
$phpExe = 'C:\Users\Rafael\.config\herd\bin\php84\php.exe'
$npmExe = (Get-Command npm.cmd -ErrorAction Stop).Source

if (-not (Test-Path $phpExe)) {
    $phpExe = (Get-Command php -ErrorAction Stop).Source
}

function Test-PhpCommand {
    param([string] $Pattern)

    return [bool](Get-CimInstance Win32_Process | Where-Object {
        $_.Name -eq 'php.exe' -and $_.CommandLine -match $Pattern
    } | Select-Object -First 1)
}

function Start-BackgroundCommand {
    param(
        [string] $Name,
        [string] $Executable,
        [string[]] $Arguments,
        [string] $Pattern
    )

    if ($Name -eq 'Vite') {
        $running = Get-NetTCPConnection -LocalPort 5173 -State Listen -ErrorAction SilentlyContinue
    } else {
        $running = Test-PhpCommand -Pattern $Pattern
    }

    if ($running) {
        Write-Host "$Name already running."
        return
    }

    Start-Process -FilePath $Executable -ArgumentList $Arguments -WorkingDirectory $projectRoot -WindowStyle Hidden | Out-Null
    Write-Host "Started $Name."
}

Start-BackgroundCommand -Name 'Laravel server' -Executable $phpExe -Arguments @('artisan', 'serve', '--host=0.0.0.0', '--port=8000') -Pattern 'artisan serve'
Start-BackgroundCommand -Name 'Queue worker' -Executable $phpExe -Arguments @('artisan', 'queue:work', 'database', '--tries=3', '--timeout=60', '--sleep=3') -Pattern 'artisan queue:work'
Start-BackgroundCommand -Name 'Scheduler' -Executable $phpExe -Arguments @('artisan', 'schedule:work') -Pattern 'artisan schedule:work'
Start-BackgroundCommand -Name 'Vite' -Executable $npmExe -Arguments @('run', 'dev', '--', '--host=0.0.0.0') -Pattern ''

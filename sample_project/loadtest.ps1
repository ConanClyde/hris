param(
    [string]$BaseUrl = "http://127.0.0.1:8001",
    [int]$Requests = 200,
    [int]$Concurrency = 20,
    [string]$Path = "/login"
)

$ErrorActionPreference = 'Stop'

Write-Host "BaseUrl: $BaseUrl"
Write-Host "Path: $Path"
Write-Host "Requests: $Requests"
Write-Host "Concurrency: $Concurrency"

$start = Get-Date
$jobs = @()
$completed = 0
$failures = 0

function Start-One($url) {
    return Start-Job -ScriptBlock {
        param($u)
        try {
            $sw = [System.Diagnostics.Stopwatch]::StartNew()
            $res = Invoke-WebRequest -Uri $u -Method GET -MaximumRedirection 3 -UseBasicParsing
            $sw.Stop()
            [pscustomobject]@{ ok = $true; status = $res.StatusCode; ms = $sw.ElapsedMilliseconds }
        } catch {
            [pscustomobject]@{ ok = $false; status = 0; ms = 0 }
        }
    } -ArgumentList $url
}

$url = ($BaseUrl.TrimEnd('/')) + $Path

for ($i = 1; $i -le $Requests; $i++) {
    while ($jobs.Count -ge $Concurrency) {
        $done = Wait-Job -Job $jobs -Any -Timeout 2
        if ($done) {
            $result = Receive-Job -Job $done
            Remove-Job -Job $done
            $jobs = $jobs | Where-Object { $_.Id -ne $done.Id }
            $completed++
            if (-not $result.ok) { $failures++ }
        }
    }

    $jobs += Start-One $url
}

while ($jobs.Count -gt 0) {
    $done = Wait-Job -Job $jobs -Any
    $result = Receive-Job -Job $done
    Remove-Job -Job $done
    $jobs = $jobs | Where-Object { $_.Id -ne $done.Id }
    $completed++
    if (-not $result.ok) { $failures++ }
}

$elapsed = (Get-Date) - $start
$rate = if ($elapsed.TotalSeconds -gt 0) { [math]::Round($completed / $elapsed.TotalSeconds, 2) } else { 0 }

Write-Host "Completed: $completed"
Write-Host "Failures: $failures"
Write-Host "Elapsed: $($elapsed.TotalSeconds)s"
Write-Host "Req/sec: $rate"
Write-Host ""
Write-Host "Tip: while running, check storage/logs/laravel.log for 'PERF request' and the Server-Timing header in DevTools for DB/app timing."

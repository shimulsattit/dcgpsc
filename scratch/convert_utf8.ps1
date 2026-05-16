$path = 'resources/views/templates/homepage/template_5.blade.php'
$content = Get-Content $path -Encoding Unicode
[IO.File]::WriteAllText((Resolve-Path $path), ($content -join "`r`n"), (New-Object System.Text.UTF8Encoding($false)))

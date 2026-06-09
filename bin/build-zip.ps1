Set-StrictMode -Version Latest
$ErrorActionPreference = 'Stop'

$ThemeSlug = 'brunoanastassakis-codice'
$ThemeRoot = (Resolve-Path (Join-Path $PSScriptRoot '..')).Path

Add-Type -AssemblyName System.IO.Compression
Add-Type -AssemblyName System.IO.Compression.FileSystem

if ((Split-Path $ThemeRoot -Leaf) -ne $ThemeSlug) {
	throw "Run this script from inside the $ThemeSlug theme. Resolved root: $ThemeRoot"
}

$RequiredFiles = @(
	'style.css',
	'functions.php',
	'index.php',
	'assets/css/tokens.css',
	'.distignore'
)

$RequiredZipEntries = @(
	'style.css',
	'functions.php',
	'index.php',
	'header.php',
	'footer.php',
	'front-page.php',
	'home.php',
	'single.php',
	'category.php',
	'search.php',
	'404.php',
	'theme.json',
	'inc/',
	'template-parts/',
	'assets/',
	'screenshot.png'
)

$ForbiddenZipEntries = @(
	"$ThemeSlug/$ThemeSlug/style.css",
	'app/public/',
	'docs/',
	'.git/',
	'AGENTS.md',
	'CLAUDE.md',
	'GEMINI.md',
	'README.md',
	'ESTRUTURA-DO-PROJETO.md',
	'CHECKLIST-INSTALACAO.md',
	'projetos-ia/',
	'output/',
	'bin/',
	'node_modules/',
	'vendor/',
	'.distignore',
	'.editorconfig',
	'.gitignore'
)

foreach ($RequiredFile in $RequiredFiles) {
	$Path = Join-Path $ThemeRoot $RequiredFile
	if (-not (Test-Path -LiteralPath $Path -PathType Leaf)) {
		throw "Required file not found: $RequiredFile"
	}
}

$ZipPath = Join-Path $ThemeRoot "$ThemeSlug.zip"
$TempRoot = Join-Path ([System.IO.Path]::GetTempPath()) ("codice-build-" + [System.Guid]::NewGuid().ToString('N'))
$StageTheme = Join-Path $TempRoot $ThemeSlug

New-Item -ItemType Directory -Path $TempRoot | Out-Null

try {
	Copy-Item -LiteralPath $ThemeRoot -Destination $StageTheme -Recurse -Force

	$IgnorePatterns = Get-Content -LiteralPath (Join-Path $ThemeRoot '.distignore') |
		ForEach-Object { $_.Trim() } |
		Where-Object { $_ -and -not $_.StartsWith('#') }

	foreach ($Pattern in $IgnorePatterns) {
		$Normalized = $Pattern.Replace('/', [System.IO.Path]::DirectorySeparatorChar).TrimEnd([System.IO.Path]::DirectorySeparatorChar)
		$HasWildcard = $Normalized.IndexOfAny([char[]]'*?[]') -ge 0
		$HasPath = $Normalized.Contains([System.IO.Path]::DirectorySeparatorChar)

		if ($HasWildcard) {
			Get-ChildItem -LiteralPath $StageTheme -Recurse -Force |
				Where-Object { $_.Name -like $Normalized } |
				Remove-Item -Recurse -Force
			continue
		}

		if ($HasPath) {
			$Target = Join-Path $StageTheme $Normalized
			if (Test-Path -LiteralPath $Target) {
				Remove-Item -LiteralPath $Target -Recurse -Force
			}
			continue
		}

		$RootTarget = Join-Path $StageTheme $Normalized
		if (Test-Path -LiteralPath $RootTarget) {
			Remove-Item -LiteralPath $RootTarget -Recurse -Force
		}

		Get-ChildItem -LiteralPath $StageTheme -Recurse -Force |
			Where-Object { $_.Name -eq $Normalized } |
			Remove-Item -Recurse -Force
	}

	foreach ($RequiredFile in $RequiredFiles | Where-Object { $_ -ne '.distignore' }) {
		$Path = Join-Path $StageTheme $RequiredFile
		if (-not (Test-Path -LiteralPath $Path -PathType Leaf)) {
			throw "Build staging is missing required theme file: $RequiredFile"
		}
	}

	if (Test-Path -LiteralPath $ZipPath) {
		Remove-Item -LiteralPath $ZipPath -Force
	}

	# Criar arquivo ZIP usando System.IO.Compression.ZipArchive para forçar barras normais (/)
	$ZipStream = [System.IO.File]::Create($ZipPath)
	try {
		$Archive = New-Object System.IO.Compression.ZipArchive($ZipStream, [System.IO.Compression.ZipArchiveMode]::Create)
		try {
			Get-ChildItem -LiteralPath $StageTheme -Recurse -File | ForEach-Object {
				$FilePath = $_.FullName
				# O caminho relativo deve começar com $ThemeSlug/
				# $StageTheme é $TempRoot/$ThemeSlug, então calculamos relativo a $TempRoot
				$RelativePath = $FilePath.Substring($TempRoot.Length + 1)
				$ZipEntryName = $RelativePath.Replace('\', '/')
				
				# Criar entrada no zip e copiar dados
				$Entry = $Archive.CreateEntry($ZipEntryName, [System.IO.Compression.CompressionLevel]::Optimal)
				$SourceStream = [System.IO.File]::OpenRead($FilePath)
				try {
					$EntryStream = $Entry.Open()
					try {
						$SourceStream.CopyTo($EntryStream)
					} finally {
						$EntryStream.Dispose()
					}
				} finally {
					$SourceStream.Dispose()
				}
			}
		} finally {
			$Archive.Dispose()
		}
	} finally {
		$ZipStream.Dispose()
	}

	$Zip = [System.IO.Compression.ZipFile]::OpenRead($ZipPath)
	try {
		$RawEntries = @(
			$Zip.Entries |
				ForEach-Object { $_.FullName }
		)
		$Entries = @(
			$RawEntries |
				ForEach-Object { $_.Replace('\', '/') }
		)
	} finally {
		$Zip.Dispose()
	}

	# Validar se alguma entrada contém barras invertidas (que causam erro no Linux/WordPress)
	$InvalidBackslashEntries = @($RawEntries | Where-Object { $_.Contains('\') })
	if ($InvalidBackslashEntries.Count -gt 0) {
		throw "ZIP contains entries with backslashes (\) which are invalid in Linux/WordPress installations: $($InvalidBackslashEntries -join ', ')"
	}

	foreach ($RequiredEntry in $RequiredZipEntries) {
		$EntryPath = "$ThemeSlug/$RequiredEntry"
		if ($RequiredEntry.EndsWith('/')) {
			if (-not ($Entries | Where-Object { $_.StartsWith($EntryPath) })) {
				throw "ZIP is missing required theme directory: $EntryPath"
			}
		} elseif (-not ($Entries -contains $EntryPath)) {
			throw "ZIP is missing required theme file: $EntryPath"
		}
	}

	$FirstLevelEntries = @(
		$Entries |
			Where-Object { $_ -and -not $_.EndsWith('/') } |
			ForEach-Object { ($_ -split '/')[0] } |
			Sort-Object -Unique
	)
	if ($FirstLevelEntries.Count -ne 1 -or $FirstLevelEntries[0] -ne $ThemeSlug) {
		throw "ZIP must contain a single top-level theme directory named $ThemeSlug/. Found: $($FirstLevelEntries -join ', ')"
	}

	foreach ($ForbiddenEntry in $ForbiddenZipEntries) {
		if ($ForbiddenEntry.EndsWith('/')) {
			if ($Entries | Where-Object { $_.StartsWith($ForbiddenEntry) -or $_.StartsWith("$ThemeSlug/$ForbiddenEntry") }) {
				throw "ZIP contains forbidden directory: $ForbiddenEntry"
			}
		} elseif ($Entries -contains $ForbiddenEntry -or $Entries -contains "$ThemeSlug/$ForbiddenEntry") {
			throw "ZIP contains forbidden file: $ForbiddenEntry"
		}
	}

	$EntryCount = (Get-ChildItem -LiteralPath $StageTheme -Recurse -Force -File).Count
	Write-Host "Created $ZipPath"
	Write-Host "Packaged $EntryCount files under $ThemeSlug/"
} finally {
	if (Test-Path -LiteralPath $TempRoot) {
		Remove-Item -LiteralPath $TempRoot -Recurse -Force
	}
}

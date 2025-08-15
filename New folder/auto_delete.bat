@echo off
setlocal

set "TARGET_FOLDER=C:\xampp\htdocs\videos"
set "DAYS=3"
set "LOG_FILE=%TARGET_FOLDER%\delete_log.txt"

echo ===== %date% %time% ===== >> "%LOG_FILE%"

for %%x in (jpg mp4) do (
    forfiles /p "%TARGET_FOLDER%" /s /m *.%%x /d -%DAYS% /c "cmd /c echo Deleting @path >> \"%LOG_FILE%\" & del /q @path"
)

echo ✅ %DAYS% দিন পুরনো .jpg ও .mp4 ফাইল ডিলিট হয়েছে >> "%LOG_FILE%"

pause
endlocal

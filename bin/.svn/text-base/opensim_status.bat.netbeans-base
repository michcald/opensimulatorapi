:: OpenSimulator Status
:: v.0.1
:: (c) Benjamin Koehne


@Echo Off

tasklist /FI "IMAGENAME eq OpenSim.32BitLaunch.exe" 2>NUL | find /I /N "OpenSim.32BitLaunch.exe">NUL
if "%ERRORLEVEL%"=="0" echo true


ping -n 5 127.0.0.1 > nul
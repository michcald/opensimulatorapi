:: Restart OpenSimulator
:: v.0.1
:: (c) Benjamin Koehne


@Echo Off

For /F "Usebackq" %%i In (`Tasklist ^| Find /C "OpenSim"`) Do If /I %%i GTR 0 Echo false & Goto END


start "OpenSimulator Server" /D "C:\opensimdiva074\bin" OpenSim.32BitLaunch.exe

Echo true

:END
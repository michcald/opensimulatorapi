:: Restart OpenSimulator
:: v.0.1
:: (c) Benjamin Koehne


@Echo Off


:: 1) Kill OpenSimulator processes
::

Echo Shutting down OpenSimulator...

:: kill process nicely and wait 5 seconds to confirm
taskkill /FI "IMAGENAME eq OpenSim.*"
Echo Confirming, stand by...
ping -n 5 127.0.0.1 > nul

:: strike to kill the rest
taskkill /F /FI "IMAGENAME eq OpenSim.*"

Echo OpenSimulator successfully shut down.
Echo Restarting in 5 seconds...
ping -n 5 127.0.0.1 > nul

Echo Launchig OpenSimulator in new minimized window...
start "OpenSimulator Server" /D "C:\opensimdiva074\bin" OpenSim.32BitLaunch.exe

Echo Operations complete...
ping -n 5 127.0.0.1 > nul
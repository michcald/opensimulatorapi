:: Shutdown OpenSimulator
:: v.0.1
:: (c) Benjamin Koehne


@Echo Off


:: Kill OpenSimulator processes
::

:: kill process nicely and wait 5 seconds to confirm
taskkill /FI "IMAGENAME eq OpenSim.*"

ping -n 5 127.0.0.1 > nul

:: strike to kill the rest
taskkill /F /FI "IMAGENAME eq OpenSim.*"

Echo true

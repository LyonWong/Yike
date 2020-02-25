@echo off
%~d0
set path_this=%~dp0
set path_root=%path_this%..\
set /p space=Please input space name:
cd %path_root%%space%\public\
echo %path_root%%space%\public\
mklink /D assets ..\view\assets
cd assets
mklink /D resource ..\..\..\resource
cd %path_root%
pause


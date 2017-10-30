# A Guide to the provided utilities and how to use them

## PHP Code Style Fixer

### Use the Code Style Fixer from PHP Storm
Open Settings, find Tools -> External Tools

Add a new External Tool with the following values:

Name: PHP Code Style Fixer  
Synchronize files after execution: Checked  
Show In: All Checked  
Program: vendor/friendsofphp/php-cs-fixer/php-cs-fixer  
Parameters: --verbose --config-file=./.php_cs fix "$FileDir$"/"$FileName$"  
Working Directory: $ProjectFileDir$

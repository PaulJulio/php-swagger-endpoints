# php-swagger-endpoints
Parse a swagger spec file into a spreadsheet (CSV) for your manager and security representative.

## To install and use scotch/box with the inlcluded Vagrantfile

* Install Virtual Box from https://www.virtualbox.org/wiki/Downloads
* Install Vagrant from https://www.vagrantup.com/downloads.html
* From this directory, run: vagrant up

How I use it right now:
The public info is generated via
```$bash
./parse.php ~/Development/connect-api-specification/api.json -o public.csv
```

The private info is first compiled then generated with
```$bash
pushd .; cd ~/Development/api-spec-generator; script/api-parser-all; popd; ./parse.php ~/Development/api-spec-generator/api.json.d/api_internal.json -o internal.csv
```

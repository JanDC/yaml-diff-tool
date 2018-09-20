# yaml-diff-tool
Simple tool that extracts missing/superfluous keys from a yaml file


# installation
`composer global require jandc/yaml-diff-tool`
# usage
`yaml-diff-tool /path/to/source.yaml /path/to/compared.yaml` outputs a yaml structure to stdout

if you want to save it to a file:
`yaml-diff-tool /path/to/source.yaml /path/to/compared.yaml > output.yaml`

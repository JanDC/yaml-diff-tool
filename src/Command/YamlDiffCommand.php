<?php

namespace YamlDiffTool\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class YamlDiffCommand extends Command
{
    public function configure()
    {
        $this->setName('yaml-diff-tool:run')
            ->setDescription('Simple tool that extracts missing/superfluous keys from a yaml file')
            ->addArgument('source file')
            ->addArgument('comparison file');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        try {

            $firstFilePath = $input->getArgument('source file');
            $secondFilePath = $input->getArgument('comparison file');

            $this->validateFiles($firstFilePath, $secondFilePath);
            [$sourceFile, $comparisonFile] = $this->extractContents($firstFilePath, $secondFilePath);
            $diff = $this->compareArrays($sourceFile, $comparisonFile);
            $output->writeln(Yaml::dump($diff, 999));
        } catch (\Exception $exception) {
            $output->writeln("<error>{$exception->getMessage()}</error>");
            $output->writeln("<comment>{$exception->getTraceAsString()}</comment>");
        }
    }

    private function validateFiles($firstFile, $secondFile)
    {
        if (!file_exists($firstFile)) {
            throw new InvalidArgumentException("The source file '$firstFile' could not be found!");
        }
        if (is_dir($firstFile)) {
            throw new InvalidArgumentException("The source file '$firstFile' is a directory!");
        }
        if (!file_exists($secondFile)) {
            throw new InvalidArgumentException("The comparison file '$secondFile' could not be found!");
        }
        if (is_dir($secondFile)) {
            throw new InvalidArgumentException("The comparison file '$secondFile' is a directory!");
        }
    }

    private function extractContents($firstFilePath, $secondFilePath)
    {
        return [
            Yaml::parseFile($firstFilePath),
            Yaml::parseFile($secondFilePath),

        ];
    }

    private function compareArrays(array $source, array $comparison)
    {
        $newKeys = [];

        foreach ($source as $key => $line) {
            if (!isset($comparison[$key])) {
                $newKeys[$key] = $line;
                continue;
            }

            if (\is_array($line)) {
                $comparedLine = $this->compareArrays($line, $comparison[$key]);
                if (!empty($comparedLine)) {
                    $newKeys[$key] = $comparedLine;
                }
            }
        }

        return $newKeys;


    }
}
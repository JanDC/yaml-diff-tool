<?php


use YamlDiffTool\Command\YamlDiffCommand;

class YamlDiffCommandTest extends \PHPUnit\Framework\TestCase
{
    public function testExecute()
    {
        $input = new Symfony\Component\Console\Input\ArrayInput(['source file' => __DIR__.'/file1.yaml', 'comparison file' => __DIR__.'/file2.yaml']);
        $output = new Symfony\Component\Console\Output\BufferedOutput();

        $command = new YamlDiffCommand();
        $command->run($input, $output);

        $this->assertEquals($this->getExpectedStructure(), $output->fetch());
    }


    private function getExpectedStructure()
    {
        return "otherroot:\n{$this->pad(4)}element1: 'foo again'\n{$this->pad(4)}element2:\n{$this->pad(8)}test: test\n{$this->pad(8)}tester: tester\ntest:\n{$this->pad(4)}key:\n{$this->pad(8)}foo: baar\n{$this->pad(8)}schaap: be\n\n";
    }

    private function pad($int)
    {
        return str_pad(' ', $int);
    }
}

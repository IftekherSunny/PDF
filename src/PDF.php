<?php

namespace Sun;

use RuntimeException;
use Twig_Environment;
use Twig_Loader_Filesystem;
use Sun\Contract\PDF as PDFContract;
use Symfony\Component\Process\Process;
use Symfony\Component\HttpFoundation\Response;

class PDF implements PDFContract
{
    /**
     * PhantomJS config file path.
     *
     * @var string
     */
    protected $configPath;

    /**
     * View directory path.
     *
     * @var string
     */
    protected $viewPath;

    /**
     * Create a new pdf instance.
     *
     * @param string $configPath
     * @param string $viewPath
     */
    public function __construct($configPath = null, $viewPath = null)
    {
        $this->configPath = $configPath;

        $this->viewPath = $viewPath;
    }

    /**
     * Get pdf output for the view.
     *
     * @param string $view
     * @param array  $data
     *
     * @return string
     */
    public function output($view, $data = [])
    {
        $path = $this->processOutput($view, $data);

        $content = file_get_contents($path);

        @unlink($path);

        return $content;
    }

    /**
     * Process pdf output for the view.
     *
     * @param string $view
     * @param array  $data
     *
     * @return string
     */
    protected function processOutput($view, $data = [])
    {
        $name = $this->generateFileName();

        $path = __DIR__ . DIRECTORY_SEPARATOR . "{$name}";

        file_put_contents($path, $this->generateView($view, $data));

        $this->getPhantomProcess($path)->setTimeout(0)->mustRun();

        return $path;
    }

    /**
     * Download generated pdf file.
     *
     * @param string $view
     * @param array  $data
     * @param string $name
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function download($view, $data = [], $name = 'download')
    {
        $path = $this->processOutput($view, $data);

        return $this->responseDownload($path, $name);
    }

    /**
     * Http response to download the pdf.
     *
     * @param string $path
     * @param string $name
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function responseDownload($path, $name)
    {
        $response = new Response(file_get_contents($path), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => 'attachment; filename="' . $name . '.pdf"',
            'Content-Transfer-Encoding' => 'binary'
        ]);

        @unlink($path);

        return $response->send();
    }


    /**
     * View pdf in the browser.
     *
     * @param string $view
     * @param array  $data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function stream($view, $data = [])
    {
        $path = $this->processOutput($view, $data);

        return $this->responseStream($path);
    }

    /**
     * Http response to stream pdf in the browser.
     *
     * @param string $path
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function responseStream($path)
    {
        $response = new Response(file_get_contents($path), 200, [
            'Content-type' => 'application/pdf',
            'Content-Transfer-Encoding' => 'binary'
        ]);

        @unlink($path);

        return $response->send();
    }

    /**
     * Get the PhantomJS process instance.
     *
     * @param  string  $viewPath
     *
     * @return \Symfony\Component\Process\Process
     */
    protected function getPhantomProcess($viewPath)
    {
        $system = $this->getSystem();

        $phantom = realpath(__DIR__.'/../bin/'.$system.'/phantomjs'.$this->getExtension($system));

        return new Process($phantom.' '. $this->getConfigurationFile() .' ' .$viewPath);
    }

    /**
     * Get the operating system name for the current platform.
     *
     * @return string
     */
    protected function getSystem()
    {
        $osName = strtolower(php_uname());

        if ($this->contains($osName, 'darwin')) {
            return 'macosx';
        } elseif ($this->contains($osName, 'win')) {
            return 'windows';
        } elseif ($this->contains($osName, 'linux')) {
            return PHP_INT_SIZE === 4 ? 'linux-i686' : 'linux-x86_64';
        } else {
            throw new RuntimeException('Unknown operating system.');
        }
    }

    /**
     * Get the binary extension for the system.
     *
     * @param  string  $system
     *
     * @return string
     */
    protected function getExtension($system)
    {
        return $system == 'windows' ? '.exe' : '';
    }

    /**
     * Generate name for the pdf file.
     *
     * @return string
     */
    protected function generateFileName()
    {
        $name = md5(uniqid()) . '.pdf';

        return $name;
    }

    /**
     * Generate view for the pdf file.
     *
     * @param string $view
     * @param array  $data
     *
     * @return string
     */
    protected function generateView($view, $data = [])
    {
        if(is_null($this->viewPath)) {
            return $view;
        }

        $twig = new Twig_Environment(
            new Twig_Loader_Filesystem(realpath($this->viewPath))
        );

        return $twig->render($view, $data);
    }

    /**
     * Check a given string contains on the given string.
     *
     * @param string $haystack
     * @param string $needle
     *
     * @return bool
     */
    protected function contains($haystack, $needle)
    {
        if(strpos($haystack, $needle) === false) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get PhantomJS configuration file.
     *
     * @return string
     */
    protected function getConfigurationFile()
    {
        if(!is_null($this->configPath)) {
            return realpath($this->configPath);
        } elseif(function_exists('base_path') and file_exists($phantomConfiguration = base_path() . DIRECTORY_SEPARATOR . 'SunPdf.js')) {
            return realpath($phantomConfiguration);
        } else {
            return realpath(__DIR__.'/../phantom.js');
        }
    }
}

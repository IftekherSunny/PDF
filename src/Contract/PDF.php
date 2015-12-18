<?php

namespace Sun\Contract;

interface PDF
{
    /**
     * Get pdf output for the view.
     *
     * @param string $view
     * @param array  $data
     *
     * @return string
     */
    public function output($view, $data = []);

    /**
     * Download generated pdf file.
     *
     * @param string $view
     * @param array  $data
     * @param string $name
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function download($view, $data = [], $name = 'download');

    /**
     * View pdf in the browser.
     *
     * @param string $view
     * @param array  $data
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function stream($view, $data = []);
}
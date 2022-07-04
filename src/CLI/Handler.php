<?php

namespace GSpataro\CLI;

final class Handler
{
    /**
     * Initialize Handler object
     *
     * @param OptionsCollection $options
     * @param Request $request
     * @param Response $response
     */

    public function __construct(
        private OptionsCollection $options,
        private Request $request,
        private Response $response
    ) {
    }

    /**
     * Generate options manpage
     *
     * @return void
     */

    public function printManpage(): void
    {
        $this->response->print("Usage: {$this->request->getScriptName()} *_option_* <args>");
        $this->response->print("");
        $this->response->print("Available options:");

        foreach ($this->options->getAll() as $optionName => $option) {
            $this->response->print("*_{$optionName}_*\t\t_{$option['manpage']}_");

            foreach ($option['args'] as $argName => $arg) {
                $separator = is_null($arg['manpage']) ? null : " ";
                $this->response->print("<{$argName}>\t\t{$arg['manpage']}" . (
                    $arg['required'] ? "{$separator}(required)" : null
                ));
            }

            $this->response->print("");
        }

        $this->response->print("*help*\tList available options");
    }

    /**
     * Start the request handling process and execute requested option callback
     *
     * @return void
     */

    public function deploy(): void
    {
        if ($this->request->getOptionName() == "help" || !$this->options->has($this->request->getOptionName())) {
            $this->printManpage();
            return;
        }

        $optionName = $this->request->getOptionName();
        $option = $this->options->get($optionName);
        $args = [];
        $i = 0;

        foreach ($option['args'] as $argName => $params) {
            if (!isset($this->request->getArgs()[$i]) && $params['required']) {
                $this->printManpage();
                return;
            }

            $args[$argName] = $this->request->getArgs()[$i] ?? null;
            $i++;
        }

        call_user_func_array($option['callback'], [
            "request" => $this->request,
            "response" => $this->response,
            "args" => $args
        ]);
    }
}

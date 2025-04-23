<?php

class RouteLoader
{
    private $directory;
    private $mergedRoutes = [];

    public function __construct(string $directory)
    {
        $this->directory = rtrim($directory, '/');
    }

    public function load(): array
    {
        if (!is_dir($this->directory)) {
            throw new InvalidArgumentException("O diretório {$this->directory} não existe.");
        }

        // Percorre todos os arquivos PHP no diretório
        foreach (glob($this->directory . '/*.php') as $file) {
            $variableName = pathinfo($file, PATHINFO_FILENAME);

            // Inclui o arquivo e verifica se a variável correspondente existe
            include $file;
            if (isset($$variableName) && is_array($$variableName)) {
                // Mescla as rotas com o array principal
                $this->mergedRoutes = array_merge($this->mergedRoutes, $$variableName);
            }
        }

        return $this->mergedRoutes;
    }
}

<?php
namespace JoJoBizzareCoders\DigitalJournal\Infrastructure;

final class Autoloader
{
    /**
     * Registred namespace
     * @var array
     */
    private array $registerNamespaces = [];

    public function __construct(array $registerNamespaces)
    {
        foreach ($registerNamespaces as $nms => $src){
            $this->registerNamespaces[trim($nms, '\\') . '\\'] = $src;
        }
        $this->registerNamespaces= $registerNamespaces;
    }

    /**
     * @param string $className - имя загружаемого файла
     * @return string|null - путь до файла или нулл если файл не найден
     */
    private function classNameToPath(string $className):?string
    {
        $path = null;
        foreach ($this->registerNamespaces as $prefix => $sourcePath){
            if (0 === strpos($className, $prefix)){
                $classNameWithoutPrefix = substr($className, strlen($prefix));
                $path = $sourcePath
                    . str_replace('\\', DIRECTORY_SEPARATOR, $classNameWithoutPrefix)
                    . '.php';
                break;
            }
        }
        return $path;
    }
    /**
     *  Логика загрузки файлов
     * @param string $className - имя загружаемого класса
     */
    public function __invoke(string $className):void
    {
        $pathToFile = $this->classNameToPath($className);
        if(null !== $pathToFile && file_exists($pathToFile) && false === is_dir($pathToFile)){
            require_once $pathToFile;
        }



    }
}
<?php

namespace TaskForce\classes;

use SplFileObject;
use FilesystemIterator;
use TaskForce\classes\exceptions\FileConverterException;

class ConverterCsvToSql
{
    private $path;
    private $filePath;
    private $fileObject;
    private $csvData;
    private $tableName;

    private $enterNewLine = "\n";


    /**
     * @param string $path Путь к директории с файлами для конвертации
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * Считываем файл в директории, и отсеиваем все кроме CSV
     * @return array
     * @throws FileConverterException
     */
    private function getFilesList(): array
    {
        $filesList = [];

        if (!is_dir($this->path)) {
            throw new FileConverterException('Директория отсутствует');
        }

        $iterator = new FilesystemIterator($this->path, FilesystemIterator::KEY_AS_PATHNAME);

        foreach ($iterator as $file) {
            $currentFile = new SplFileObject($file);
            if ($currentFile->getExtension() !== 'csv') {
                continue;
            }

            $filesList[] = $currentFile;
        }

        if (empty($filesList)) {
            throw new FileConverterException('Нет подходящих файлов для преобразования');
        }

        return $filesList;
    }

    /**
     * Запускает конвертацию файлов
     * @return void
     * @throws FileConverterException
     */
    public function start() :void
    {
        foreach ($this->getFilesList() as $file) {
            $this->fileObject = $file;

            $this->csvData = null;
            $this->convertor();
        }
    }

    /**
     * Основной метод для конвертации каждого файла
     * @return void
     * @throws FileConverterException
     */
    private function convertor() :void
    {
        $this->filePath = $this->fileObject->getPath();
        $this->tableName = $this->fileObject->getBasename('.csv');

        $this->getData();
        $this->createSqlFile();
    }

    /**
     * Получаем заголовками ввиде массива из переданного файла
     * @return array|null
     */
    private function getHeaderData(): ?array
    {
        $this->fileObject->rewind();

        return $this->fileObject->fgetcsv();
    }

    /**
     * Возвращает массив из данных переданного файла
     * @return void После работы получаем заполненный массив из данных CSV файла
     */
    private function getData(): void
    {
        $this->fileObject->seek(1);

        foreach ($this->getNextLine() as $line) {
            $this->csvData[] = $line;
        }
    }

    /**
     * Преобразуем каждую строку данных в массив
     * @return iterable|null
     */
    private function getNextLine(): ?iterable
    {
        $result = null;

        while (!$this->fileObject->eof()) {
            yield $this->fileObject->fgetcsv();
        }

        return $result;
    }

    /**
     * Генерируем строки данных для sql запроса добавления в БД
     * @return string
     */
    private function getSqlInsertDataValue() :string
    {
        $values = '';
        foreach ($this->csvData as $row) {
            $value = implode("','", $row);
            $values = $values . "('$value'),$this->enterNewLine";
        }

        return substr_replace($values, ';', -2);
    }

    /**
     * Собирает воедино заголовок и строку данных в единный запрос
     * @return string SQL INSERT
     */
    private function getSqlInsert() :string
    {
        $sqlTableColumns = implode(",", $this->getHeaderData());
        $sqlHead = "INSERT INTO {$this->tableName} ({$sqlTableColumns}){$this->enterNewLine}VALUES";

        return $sqlHead . ' ' . $this->getSqlInsertDataValue();
    }

    /**
     * Создает SQL файл рядом с исходником и записывает сгенерированный запрос
     * @return void
     * @throws FileConverterException Возможные ошибки при создани, записи файла
     */
    private function createSqlFile() :void
    {
        $newFile = "{$this->filePath}/{$this->tableName}.sql";

        try {
            $newSql = new SplFileObject($newFile, 'w');
        } catch (FileConverterException) {
            throw new FileConverterException('Не удалось создать файл для записи');
        }

        if (!file_exists($newFile)) {
            throw new FileConverterException('Файл для записи не существует');
        }

        $newSql->fwrite($this->getSqlInsert());
    }
}

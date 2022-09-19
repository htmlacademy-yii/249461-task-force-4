<?php

namespace TaskForce\classes;

use SplFileObject;
use FilesystemIterator;
use TaskForce\classes\exceptions\FileConverterException;

class SqlDataConverter
{
    private $fileName;
    private $filePath;
    private $columns;
    private $fileObject;
    private $csvData;
    private $tableName;

    private $enterNewLine = "\n";

    public function __construct(string $fileName, string $filePath, array $columns, string $tableName)
    {
        $this->fileName = $fileName;
        $this->filePath = $filePath;
        $this->columns = $columns;
        $this->tableName = $tableName;
    }

    /**
     * Возвращает массив из данных переданного файла
     * @return void
     * @throws FileConverterException
     */
    private function import():void {
        if ($this->validateColumns($this->columns)) {
            throw new FileConverterException('Заданы неверные заголовки столбцов');
        }

        $file = $this->filePath . '/' . $this->fileName;


        if (!file_exists($file)) {
            throw new FileConverterException('Файл не существует');
        }


        try {
            $this->fileObject = new SplFileObject($file);
        } catch (FileConverterException) {
            throw new FileConverterException("Не удалось открыть файл на чтение");
        }

        /**
         * Получение массива с заголовками из файла
         */
        $header_data = $this->getHeaderData();

        if ($header_data !== $this->columns) {
            throw new FileConverterException("Исходный файл не содержит необходимых столбцов");
        }

        foreach ($this->getNextLine() as $line) {
            $this->csvData[] = $line;
        }
    }

    /**
     * Массив заголовков CSV файла
     * @return array|null
     */
    private function getHeaderData():?array {
        $this->fileObject->rewind();
        $data = $this->fileObject->fgetcsv();

        return $data;
    }

    /**
     * Перебирает строки с данными в переданном файле
     * @return iterable|null
     */
    private function getNextLine():?iterable {
        $result = null;

        while (!$this->fileObject->eof()) {
            yield $this->fileObject->fgetcsv();
        }

        return $result;
    }

    /**
     * Проверяет переданный массив колонок, что все значения являеются строкой
     * @param array $columns
     * @return bool
     */
    private function validateColumns(array $columns):bool
    {
        $result = true;

        if (count($columns)) {
            foreach ($columns as $column) {
                if (!!is_string($column)) {
                    $result = false;
                }
            }
        }
        else {
            $result = false;
        }

        return $result;
    }

    /**
     * Генерирует строки данных для sql запроса добавления в БД
     * @return array|string|string[]
     */
    private function getSqlInsertDataValue() {
        $values = '';
        foreach ($this->csvData as $row) {
            $value = implode("','", $row);
            $values = $values . "('{$value}')" . ',' . $this->enterNewLine;
        }

        $values = substr_replace($values,';',-1);

        return $values;
    }

    /**
     * Собирает воедино заголовок и строку данных в единный запрос
     * @return string
     */
    private function getSqlInsert() {
        $sqlTableColumns = implode(",",$this->columns);
        $sqlHead = "INSERT INTO {$this->tableName} ({$sqlTableColumns}){$this->enterNewLine}VALUES";

        $sqlInsert = $sqlHead . ' ' .  $this->getSqlInsertDataValue();

        return $sqlInsert;
    }

    /**
     * Создает файл в тойже директории с исходником и записывает сгенерированный запрос
     * @return void
     */
    private function createSqlFile() {
        $newFile = "{$this->filePath}/{$this->tableName}.sql";

        try {
            $newSql = new SplFileObject($newFile, 'w');
        } catch (FileConverterException $e) {
            throw new FileConverterException('Не удалось создать файл для записи');
        }

        if (!file_exists($newFile)) {
            throw new FileConverterException('Файл для записи не существует');
        }

        $newSql->fwrite($this->getSqlInsert());
    }

    public function convert() {
        $this->import();
        $this->createSqlFile();
    }

}
